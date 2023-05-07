<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Traversing;

use Autoframe\Components\FileSystem\DirPath\Exception\AfrFileSystemDirPathException;
use Autoframe\Components\FileSystem\Traversing\Exception\AfrFileSystemTraversingException;

interface AfrDirTraversingGetAllChildrenDirsInterface
{
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
    );
}