<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\SplitMergeCopyDir;

use Autoframe\Components\FileSystem\Recursive\AfrRecursiveRmDir;
use Autoframe\Components\FileSystem\SplitMerge\AfrSplitMergeInterface;
use Autoframe\Components\FileSystem\SplitMerge\AfrSplitMergeClass;
use Autoframe\Components\FileSystem\SplitMerge\Exception\AfrFileSystemSplitMergeException;
use Autoframe\Components\FileSystem\SplitMergeCopyDir\Exception\AfrFileSystemSplitMergeCopyDirException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

trait AfrSplitMergeCopyDirTrait
{

    protected static AfrSplitMergeInterface $AfrSplitMergeInterface;

    /**
     * @param AfrSplitMergeInterface|null $AfrSplitMergeInterface
     * @return AfrSplitMergeInterface
     */
    public function xetAfrSplitMergeInterface(
        AfrSplitMergeInterface $AfrSplitMergeInterface = null
    ): AfrSplitMergeInterface
    {
        if ($AfrSplitMergeInterface) {
            self::$AfrSplitMergeInterface = $AfrSplitMergeInterface;
        } elseif (empty(self::$AfrSplitMergeInterface)) {
            self::$AfrSplitMergeInterface = AfrSplitMergeClass::getInstance();
        }
        return self::$AfrSplitMergeInterface;
    }

    /**
     * @throws AfrFileSystemSplitMergeCopyDirException|AfrFileSystemSplitMergeException
     */
    public function splitCopyDir(
        string $sSourceDir,
        string $sDestinationDir,
        int    $iPartSize,
        bool   $bOverwriteFiles
    ): int
    {
        $iTotalProcessed = 0;
        $sRealPathSourceDir = realpath($sSourceDir);
        if ($sRealPathSourceDir === false) {
            throw new AfrFileSystemSplitMergeCopyDirException(
                __FUNCTION__ . ' Invalid source path: ' . $sSourceDir
            );
        }
        $sRealPathDestinationDir = realpath($sDestinationDir);
        if ($sRealPathDestinationDir === false) {
            throw new AfrFileSystemSplitMergeCopyDirException(
                __FUNCTION__ . ' Invalid destination path: ' . $sDestinationDir
            );
        }

        if ($sRealPathSourceDir === $sRealPathDestinationDir) {
            throw new AfrFileSystemSplitMergeCopyDirException(
                __FUNCTION__ . ' The source and destination can\'t be identical: ' . $sRealPathSourceDir
            );
        }

        $aSources = $this->collectPathInfo($sRealPathSourceDir, __FUNCTION__);
        if (count($aSources['files']) < 1) {
            return $iTotalProcessed;
        }

        foreach ($aSources['dirs'] as $aDirInfo) {
            $sDestDPath = $sRealPathDestinationDir . $aDirInfo['sRelativePath'];
            if (!is_dir($sDestDPath) && !mkdir($sDestDPath, $aDirInfo['iPerms'], true)) {
                throw new AfrFileSystemSplitMergeCopyDirException(
                    __FUNCTION__ . ' Unable to create destination path: ' . $sDestDPath
                );
            }
        }
        foreach ($aSources['files'] as $sSourceFullPath => $aFileInfo) {
            if (@filetype($sSourceFullPath) !== 'file') {
                continue;
            }

            $iTotalProcessed++;
            $sDestFPath = $sRealPathDestinationDir . $aFileInfo['sRelativePath'];
            if ($aFileInfo['iSize'] <= $iPartSize) {//normal copy
                if (!copy($sSourceFullPath, $sDestFPath)) {
                    throw new AfrFileSystemSplitMergeCopyDirException(
                        __FUNCTION__ . " Unable to copy file from [$sSourceFullPath] to [$sDestFPath]"
                    );
                }
            } else {//split copy
                $oSplitCopy = $this->xetAfrSplitMergeInterface();
                $oSplitCopy->split(
                    $sSourceFullPath,
                    $iPartSize,
                    true,
                    substr($sDestFPath, 0, -strlen($aFileInfo['sName']) - 1)
                );
                //$oSplitCopy->split($sSourceFullPath, $iPartSize, true, $sRealPathDestinationDir);
            }
        }
        return $iTotalProcessed;
    }

    /**
     * @param string $sSourceDir
     * @param string $sPrentFn
     * @return array
     * @throws AfrFileSystemSplitMergeCopyDirException
     */
    protected function collectPathInfo(string $sSourceDir, string $sPrentFn): array
    {
        $aFound = ['dirs' => [], 'files' => []];
        $sRealPathSourceDir = realpath($sSourceDir);
        if ($sRealPathSourceDir === false) {
            throw new AfrFileSystemSplitMergeCopyDirException(
                $sPrentFn ?: __FUNCTION__ . ' Invalid path: ' . $sSourceDir
            );
        }
        $aFiles = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sRealPathSourceDir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        /** @var SplFileInfo $oSplFileInfo */
        foreach ($aFiles as $oSplFileInfo) {
            $sFullFilePath = $oSplFileInfo->getRealPath();
            $sRelativePath = substr($sFullFilePath, strlen($sRealPathSourceDir));
            if (strlen(trim($sRelativePath, '\/')) < 1) {
                continue;
            }
            $bIsDir = $oSplFileInfo->isDir();
            $type = $bIsDir ? 'dirs' : 'files';
            if (isset($aFound[$type][$sFullFilePath])) {
                continue;
            }
            $aFound[$type][$sFullFilePath] = [
                'sName' => $bIsDir ? basename($sFullFilePath) : $oSplFileInfo->getFilename(),
                'iSize' => $bIsDir ? 0 : $oSplFileInfo->getSize(),
                'bIdDir' => $bIsDir,
                'sRelativePath' => $sRelativePath,
                'sFullFilePath' => $sFullFilePath,
                'iPerms' => octdec(substr(decoct((int)$oSplFileInfo->getPerms()), -4)),
            ];
        }
        ksort($aFound['dirs'], SORT_NATURAL);
        ksort($aFound['files'], SORT_NATURAL);
        return $aFound;
    }

}