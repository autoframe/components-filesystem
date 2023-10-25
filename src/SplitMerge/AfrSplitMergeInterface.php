<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\SplitMerge;

use Autoframe\Components\FileSystem\Exception\AfrFileSystemException;
use Autoframe\Components\FileSystem\SplitMerge\Exception\AfrFileSystemSplitMergeException;

interface AfrSplitMergeInterface
{
    /**
     * @param string $sFullFilePath
     * @param int $iPartSize
     * @param bool $bOverwrite
     * @return int
     * @throws AfrFileSystemSplitMergeException
     */
    public function split(string $sFullFilePath, int $iPartSize, bool $bOverwrite): int;

    /**
     * @param string $sFirstPartPath
     * @param string $sDestinationPath
     * @param bool $bOverWriteDestination
     * @return bool
     * @throws AfrFileSystemSplitMergeException|AfrFileSystemException
     */
    public function merge(string $sFirstPartPath, string $sDestinationPath, bool $bOverWriteDestination): bool;
}