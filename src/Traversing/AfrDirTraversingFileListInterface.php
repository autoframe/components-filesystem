<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Traversing;

use Autoframe\Components\FileSystem\Exception\AfrFileSystemException;

interface AfrDirTraversingFileListInterface
{
    /**
     * @param string $sDirPath
     * @param array $aFilterExtensions
     * @return array|false
     * @throws AfrFileSystemException
     */
    public function getDirFileList(string $sDirPath, array $aFilterExtensions = []);
}