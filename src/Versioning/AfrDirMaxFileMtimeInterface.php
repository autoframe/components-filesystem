<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Versioning;

use Autoframe\Components\FileSystem\DirPath\Exception\AfrFileSystemDirPathException;
use Autoframe\Components\FileSystem\Versioning\Exception\AfrFileSystemVersioningException;

interface AfrDirMaxFileMtimeInterface
{
    /**
     * @param string|array $pathStringOrPathsArray
     * @param int $iMaxSubDirs
     * @param bool $bFollowSymlinks
     * @param bool $bGetTsFromDirs
     * @param array $aFilterExtensions
     * @param array $aSkipDirs
     * @throws AfrFileSystemVersioningException
     * @throws AfrFileSystemDirPathException
     * @return int
     */
    public function getDirMaxFileMtime(
        $pathStringOrPathsArray,
        int $iMaxSubDirs = 1,
        bool $bFollowSymlinks = false,
        bool $bGetTsFromDirs = false,
        array $aFilterExtensions = [],
        array $aSkipDirs = []
    ): int;
}