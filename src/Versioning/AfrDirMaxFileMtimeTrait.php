<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Versioning;

use Autoframe\Components\FileSystem\DirPath\AfrDirPathTrait;
use Autoframe\Components\FileSystem\DirPath\Exception\AfrFileSystemDirPathException;
use Autoframe\Components\FileSystem\Versioning\Exception\AfrFileSystemVersioningException;

use function is_array;
use function is_string;
use function strlen;
use function max;
use function filemtime;
use function filetype;
use function readdir;
use function readlink;
use function closedir;
use function gettype;

trait AfrDirMaxFileMtimeTrait
{
    use AfrDirPathTrait;

    /**
     * @param string|array $pathStringOrPathsArray
     * @param int $iMaxSubDirs
     * @param bool $bFollowSymlinks
     * @param bool $bGetTsFromDirs
     * @return int
     * @throws AfrFileSystemVersioningException
     * @throws AfrFileSystemDirPathException
     */
    public function getDirMaxFileMtime(
        $pathStringOrPathsArray,
        int $iMaxSubDirs = 1,
        bool $bFollowSymlinks = false,
        bool $bGetTsFromDirs = false
    ): int
    {
        if ($iMaxSubDirs < 0) {
            return 0;
        }
        $iMaxTimestamp = 0;

        if (is_array($pathStringOrPathsArray)) {
            foreach ($pathStringOrPathsArray as $sDirPath) {
                $iMaxTimestamp = max(
                    $iMaxTimestamp,
                    $this->getDirMaxFileMtime($sDirPath, $iMaxSubDirs, $bGetTsFromDirs, $bFollowSymlinks)
                );
            }
        } elseif (is_string($pathStringOrPathsArray)) {
            if (strlen($pathStringOrPathsArray) < 1 || $pathStringOrPathsArray === '.' || $pathStringOrPathsArray === '..') {
                throw new AfrFileSystemVersioningException(
                    'Invalid string "' . $pathStringOrPathsArray . '" provided for ' .
                    __CLASS__ . '->' . __FUNCTION__
                );
            }
            $sPath = $pathStringOrPathsArray;
            $aDirs = [];

            $sPathType = $this->getDirMaxFileMtimeProcess(
                $sPath,
                $iMaxTimestamp,
                $aDirs,
                $bFollowSymlinks,
                $bGetTsFromDirs
            );

            if ($sPathType === 'dir') {
                $sPath = $this->correctPathFormat($sPath, true, true);
                $rDir = $this->openDir($sPath);
                while ($sEntryName = readdir($rDir)) {
                    if ($sEntryName === '.' || $sEntryName === '..') {
                        continue;
                    }
                    $this->getDirMaxFileMtimeProcess(
                        $sPath . $sEntryName,
                        $iMaxTimestamp,
                        $aDirs,
                        $bFollowSymlinks,
                        $bGetTsFromDirs
                    );
                }
                closedir($rDir);
                if ($bGetTsFromDirs) {
                    $iMaxTimestamp = max(
                        $iMaxTimestamp,
                        filemtime($sPath)
                    );
                }
            }
            foreach ($aDirs as $sTargetDir) {
                $iMaxTimestamp = max(
                    $iMaxTimestamp,
                    $this->getDirMaxFileMtime($sTargetDir, $iMaxSubDirs - 1, $bGetTsFromDirs)
                );
            }

        } else {
            throw new AfrFileSystemVersioningException(
                'Expected string|array as parameter 1 but you have provided"' .
                gettype($pathStringOrPathsArray) . '" in ' .
                __CLASS__ . '->' . __FUNCTION__
            );
        }

        return $iMaxTimestamp;
    }

    /**
     * @param string $sTargetDir
     * @param int $iMaxTimestamp
     * @param array $aDirs
     * @param bool $bFollowSymlinks
     * @param bool $bGetTsFromDirs
     * @return string
     */
    private function getDirMaxFileMtimeProcess(
        string $sTargetDir,
        int    &$iMaxTimestamp,
        array  &$aDirs,
        bool   $bFollowSymlinks,
        bool   $bGetTsFromDirs
    ): string
    {
        $sType = @filetype($sTargetDir); //check if file exists and ignore warning. This is the fastest way
        $sType = (string)$sType;
        if (!$sType) {
            return '';
        }
        if ($sType === 'file') {
            $iMaxTimestamp = max($iMaxTimestamp, (int)filemtime($sTargetDir));
        } elseif ($sType === 'dir') {
            $aDirs[] = $sTargetDir; //keep low the open dir resource count
            if ($bGetTsFromDirs) {
                $iMaxTimestamp = max(
                    $iMaxTimestamp,
                    filemtime($sTargetDir)
                );
            }
        } elseif ($bFollowSymlinks && $sType === 'link') {
            $sSymLinkTarget = readlink($sTargetDir);//TODO test symlinks
            if ($sSymLinkTarget) {
                $sType = $this->getDirMaxFileMtimeProcess(
                    $sSymLinkTarget,
                    $iMaxTimestamp,
                    $aDirs,
                    true,
                    $bGetTsFromDirs
                );
            }
        }
        return $sType;
    }

}
