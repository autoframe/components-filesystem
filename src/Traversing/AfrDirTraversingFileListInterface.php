<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Traversing;

use Autoframe\Components\FileSystem\Exception\AfrFileSystemException;
use Autoframe\Components\FileSystem\Traversing\Exception\AfrFileSystemTraversingException;

interface AfrDirTraversingFileListInterface
{
    /**
     * @param string $sDirPath
     * @param array $aFilterExtensions
     * @return array|false
     * @throws AfrFileSystemException
     * @throws AfrFileSystemTraversingException
     */
    public function getDirFileList(string $sDirPath, array $aFilterExtensions = []);
}