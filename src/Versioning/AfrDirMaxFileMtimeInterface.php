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
     * @param bool $bGetTsFromDirs
     * @param bool $bFollowSymlinks
     * @return int
     * @throws AfrFileSystemVersioningException
     * @throws AfrFileSystemDirPathException
     */
    public function getDirMaxFileMtime(
        $pathStringOrPathsArray,
        int $iMaxSubDirs = 1,
        bool $bFollowSymlinks = false,
        bool $bGetTsFromDirs = false
    ): int;
}