<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\SplitMerge;

use Autoframe\Components\FileSystem\Exception\AfrFileSystemException;
use Autoframe\Components\FileSystem\SplitMerge\Exception\AfrFileSystemSplitMergeException;

trait AfrSplitMergeTrait
{

    /**
     * @param string $sFullFilePath
     * @param int $iPartSize
     * @param bool $bOverwrite
     * @return int
     * @throws AfrFileSystemSplitMergeException
     */
    public function split(string $sFullFilePath, int $iPartSize, bool $bOverwrite): int
    {

        if ($iPartSize < 1) {
            throw new AfrFileSystemSplitMergeException(
                'You can not split a file to zero or negative byte sizes!'
            );
        }
        if (!$this->isFilePathSplit($sFullFilePath)) {
            throw new AfrFileSystemSplitMergeException(
                'File can not be split because it is missing: ' . $sFullFilePath
            );
        }
        $iFileSize = filesize($sFullFilePath);
        $iTotalParts = max((int)ceil($iFileSize / $iPartSize), 1);
        $iExtLength = strlen((string)$iTotalParts); //pad with zero up to

        $sourceStream = fopen($sFullFilePath, 'r');
        for ($iCurrentPart = 1; $iCurrentPart <= $iTotalParts; $iCurrentPart++) {
            $sNewFilePath = $this->getPadName($sFullFilePath, $iCurrentPart, $iExtLength);


            $writeStream = fopen($sNewFilePath, $bOverwrite ? 'w' : 'x');
            if (!$writeStream) {
                throw new AfrFileSystemSplitMergeException(
                    'File already exists and can not be overwritten:  ' . $sNewFilePath
                );
            }

            $iOffset = ($iCurrentPart - 1) * $iPartSize;
            $iOffsetEnd = min($iOffset + $iPartSize, $iFileSize);
            $iRWBuffer = min($iOffsetEnd - $iOffset, 1024 * 64);

            fseek($sourceStream, $iOffset);
            while (($sData = fread($sourceStream, $iRWBuffer))) {
                fwrite($writeStream, $sData);
                $iOffset += $iRWBuffer;
                if ($iOffset >= $iOffsetEnd) {
                    break;
                }
                $iRWBuffer = min($iOffsetEnd - $iOffset, 1024 * 64);
            }
            fclose($writeStream);
        }
        fclose($sourceStream);
        if ($bOverwrite) {
            $sFilePathTail = $this->getPadName($sFullFilePath, $iCurrentPart, $iExtLength);
            if ($this->isFilePathSplit($sFilePathTail)) {
                rename($sFilePathTail, $sFilePathTail . '.tail' . time());
            }
        }
        return $iCurrentPart - 1;
    }

    protected function getPadName(string $sFullFilePath, int $iCurrentPart, int $iExtLength): string
    {
        if (strlen((string)$iCurrentPart) < $iExtLength) {
            return $sFullFilePath . '.' . str_pad(
                    (string)$iCurrentPart,
                    $iExtLength,
                    '0',
                    STR_PAD_LEFT
                );
        }
        return $sFullFilePath . '.' . $iCurrentPart;
    }

    /**
     * @param $sFullFilePath
     * @return bool
     */
    protected function isFilePathSplit($sFullFilePath): bool
    {
        return @filetype($sFullFilePath) == 'file';
    }

    /**
     * @param string $sFirstPartPath
     * @param string $sDestinationPath
     * @param bool $bOverWriteDestination
     * @return bool
     * @throws AfrFileSystemSplitMergeException|AfrFileSystemException
     */
    public function merge(
        string $sFirstPartPath,
        string $sDestinationPath,
        bool   $bOverWriteDestination
    ): bool
    {
        if (!$this->isFilePathSplit($sFirstPartPath)) {
            throw new AfrFileSystemSplitMergeException(
                'Merge failed because first segment is missing: ' . $sFirstPartPath
            );
        }
        $aPathInfo = pathinfo($sFirstPartPath);
        foreach (['dirname', 'basename', 'extension', 'filename'] as $sKey) {
            $aPathInfo[$sKey] = $aPathInfo[$sKey] ?? '';
        }
        $iExtensionLength = strlen($aPathInfo['extension']);

        if (ltrim($aPathInfo['extension'], '0') !== '1') {
            throw new AfrFileSystemSplitMergeException(
                'Merge failed because first segment has invalid extension: ' . $sFirstPartPath . ' [' . $aPathInfo['extension'] . ']'
            );
        }
        if (strlen($aPathInfo['filename']) < 1) {
            throw new AfrFileSystemSplitMergeException(
                'Merge failed because first segment has invalid filename: ' . $sFirstPartPath
            );
        }

        if (!$sDestinationPath) {
            $sDestinationPath = substr($sFirstPartPath, -1 - $iExtensionLength);
        }
        if (!$bOverWriteDestination && $this->isFilePathSplit($sDestinationPath)) {
            throw new AfrFileSystemSplitMergeException(
                'File can not be merge because first segment missing: ' . $sFirstPartPath
            );
        }

        $aParts = $this->getDirPartList($aPathInfo, $sFirstPartPath);
        if (!$this->validatePartNumberBeforeMerge($aParts, $iExtensionLength)) {
            return false;
        }

        $this->actualMerge($sDestinationPath, $aParts);

        return true;
    }

    /**
     * @throws AfrFileSystemSplitMergeException
     */
    protected function validatePartNumberBeforeMerge(array $aParts, int $iExtensionLength): bool
    {
        $iTotalParts = count($aParts);
        if ($iTotalParts < 1) {
            return false;
        }
        $aExpectedExtAndSize = [];
        for ($i = 1; $i <= $iTotalParts; $i++) {
            $sExpected = substr($this->getPadName('', $i, $iExtensionLength), 1);
            $aExpectedExtAndSize[$sExpected] = 0;
        }
        $sLastExtInSeries = $sExpected;
        foreach ($aParts as $sPartPath) {
            $sExpectedPartToCheck = substr($sPartPath, -$iExtensionLength, $iExtensionLength);
            if (isset($aExpectedExtAndSize[$sExpectedPartToCheck])) {
                $aExpectedExtAndSize[$sExpectedPartToCheck] = filesize($sPartPath);
            }
        }
        $iSeriesPartSize = -1;
        foreach ($aExpectedExtAndSize as $sExt => $iFileSize) {
            if ($iSeriesPartSize < 0) {
                $iSeriesPartSize = $iFileSize;
            }
            if ((string)$sExt === $sLastExtInSeries && $iFileSize > 0 && $iFileSize <= $iSeriesPartSize) {
                continue; //last segment has a smaller size, but not zero
            }
            if ($iSeriesPartSize !== $iFileSize) {
                throw new AfrFileSystemSplitMergeException(
                    'Invalid file size in merging series: ' .
                    substr($sPartPath, 0, -$iExtensionLength) . $sExt . '; Expected ' .
                    "$iSeriesPartSize bytes but found $iFileSize bytes!"
                );
            }
        }
        foreach ($aParts as $sPartPath) {
            $sExpectedPartToCheck = substr($sPartPath, -$iExtensionLength, $iExtensionLength);
            if (!isset($aExpectedExtAndSize[$sExpectedPartToCheck])) {
                throw new AfrFileSystemSplitMergeException(
                    'Invalid file in merging series: ' . $sPartPath
                );
            }
        }
        return true;
    }


    /**
     * @param array $aPathInfo
     * @param string $sFirstPartPath
     * @return array
     * @throws AfrFileSystemException
     */
    protected function getDirPartList(array $aPathInfo, string $sFirstPartPath): array
    {
        $aParts = [];
        $iBaseNameLength = strlen($aPathInfo['basename']);
        $iFileNameLength = strlen($aPathInfo['filename']);
        $iDirNameLength = strlen($aPathInfo['dirname']);

        $rDir = opendir($aPathInfo['dirname']);
        if (empty($rDir)) {
            throw new AfrFileSystemSplitMergeException(
                'Unable to merge files from dir: ' . $aPathInfo['dirname']
            );
        }
        while ($sDirFile = readdir($rDir)) {
            $sFilePath = ($iDirNameLength ?
                    substr($sFirstPartPath, 0, $iDirNameLength + 1) :
                    ''
                ) . $sDirFile;
            if (
                strlen($sDirFile) === $iBaseNameLength &&
                substr($sDirFile, 0, $iFileNameLength) === $aPathInfo['filename'] &&
                substr($sDirFile, $iFileNameLength, 1) === '.' &&
                $this->isFilePathSplit($sFilePath)
            ) {
                $aParts[] = $sFilePath;
            }
        }
        closedir($rDir);
        sort($aParts, SORT_NATURAL);
        return $aParts;
    }

    /**
     * @param string $sDestinationPath
     * @param array $aParts
     * @return void
     * @throws AfrFileSystemSplitMergeException
     */
    protected function actualMerge(string $sDestinationPath, array $aParts): void
    {
        $destinationStream = fopen($sDestinationPath, 'w');
        if (!$destinationStream) {
            throw new AfrFileSystemSplitMergeException(
                'Unable to merge files into: ' . $sDestinationPath
            );
        }

        foreach ($aParts as $sPartToMergePath) {
            $readStream = fopen($sPartToMergePath, 'r');
            if (!$readStream) {
                throw new AfrFileSystemSplitMergeException(
                    'Unable to read file for merging: ' . $sPartToMergePath
                );
            }
            while (($sData = fread($readStream, 2048))) {
                if (fwrite($destinationStream, $sData) === false) {
                    throw new AfrFileSystemSplitMergeException(
                        'Unable to write data to merge file: ' . $sDestinationPath
                    );
                }
            }
            fclose($readStream);
        }

        if (fclose($destinationStream) === false) {
            throw new AfrFileSystemSplitMergeException(
                'Unable to close file: ' . $sDestinationPath
            );
        }
    }

}