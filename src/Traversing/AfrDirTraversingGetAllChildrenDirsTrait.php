<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Traversing;

use Autoframe\Components\FileSystem\DirPath\AfrDirPathClass;
use Autoframe\Components\FileSystem\DirPath\AfrDirPathInterface;
use Autoframe\Components\FileSystem\DirPath\Exception\AfrFileSystemDirPathException;
use Autoframe\Components\FileSystem\Traversing\Exception\AfrFileSystemTraversingException;

use function readdir;
use function closedir;
use function filetype;


trait AfrDirTraversingGetAllChildrenDirsTrait
{
    /** @var AfrDirPathInterface  */
    public static AfrDirPathInterface $AfrDirPathInstance;

    /**
     * @param string $sDirPath
     * @param int $iMaxLevels
     * @param bool $bFollowSymlinks
     * @param int $iCurrentLevel
     * @return array|false
     * @throws AfrFileSystemTraversingException
     * @throws AfrFileSystemDirPathException
     */
    public function getAllChildrenDirs(
        string $sDirPath,
        int    $iMaxLevels = 1,
        bool   $bFollowSymlinks = false,
        int    $iCurrentLevel = 0
    )
    {

        if ($iCurrentLevel < 0) {
            $iCurrentLevel = 0;
        }
        if ($iMaxLevels <= $iCurrentLevel) {
            return false;
        }

        if(empty(self::$AfrDirPathInstance)){
            self::$AfrDirPathInstance = new AfrDirPathClass();
        }

        if ($iCurrentLevel === 0) {
            if (!self::$AfrDirPathInstance->isDir($sDirPath)) {
                throw new AfrFileSystemTraversingException(
                    'Invalid directory path provided in ' . __CLASS__ . '->' . __FUNCTION__ . ': "' . $sDirPath . '"'
                );
            }
            $sDirPath = self::$AfrDirPathInstance->correctPathFormat($sDirPath, true, true);
        }

        $aDirs = $aDirsLoop = $aDirsLoopSymlink = [];
        $rDir = self::$AfrDirPathInstance->openDir($sDirPath);  //opendir/readdir calls are much faster than the RecursiveDirectoryIterator
        while ($sEntryName = readdir($rDir)) {
            if ($sEntryName === '.' || $sEntryName === '..') {
                continue;
            }
            if (self::$AfrDirPathInstance->isDir($sDirPath . $sEntryName)) {
                $aDirsLoop[] = $sEntryName;
            } elseif ($bFollowSymlinks && @filetype($sDirPath . $sEntryName) === 'link') {
                $sSymLinkTarget = readlink($sDirPath . $sEntryName);//TODO test symlinks
                if ($sSymLinkTarget && self::$AfrDirPathInstance->isDir($sSymLinkTarget)) {
                    $aDirsLoopSymlink[$sEntryName] = $sSymLinkTarget;
                }
            }
        }
        closedir($rDir);    // close directory

        //if (empty($aDirsLoop) && empty($aDirsLoopSymlink)) {
        //    return [];
        //}

        $iCurrentLevel++;
        foreach ($aDirsLoop as $sEntryName) {
            $aDirs[$sEntryName] = $this->getAllChildrenDirs(
                self::$AfrDirPathInstance->addFinalSlash($sDirPath . $sEntryName),
                $iMaxLevels,
                $bFollowSymlinks,
                $iCurrentLevel
            );
        }
        if ($aDirsLoopSymlink) {
            foreach ($aDirsLoopSymlink as $sEntryName => $sSymLinkTarget) {
                $aDirs[$sEntryName] = $this->getAllChildrenDirs(
                    self::$AfrDirPathInstance->addFinalSlash($sSymLinkTarget),
                    $iMaxLevels,
                    $bFollowSymlinks,
                    $iCurrentLevel
                );
            }
        }
        //$this->applyAfrDirTraversingSortMethod($aDirs, true);
        ksort($aDirs,SORT_NATURAL);

        return $aDirs;
    }


}
