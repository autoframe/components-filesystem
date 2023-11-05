<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\SplitMerge;

use Autoframe\Components\FileSystem\SplitMerge\Exception\AfrFileSystemSplitMergeException;

interface AfrSplitMergeInterface
{
    /**
     * @param string $sSourceFullFilePath
     * @param int $iPartSize
     * @param bool $bOverwrite
     * @param string $sOtherDestinationDirectoryPath
     * @param bool $bUnlinkSource
     * @return int
     * @throws AfrFileSystemSplitMergeException
     */
    public function split(
        string $sSourceFullFilePath,
        int    $iPartSize,
        bool   $bOverwrite,
        string $sOtherDestinationDirectoryPath = '',
        bool   $bUnlinkSource = false
    ): int;

    /**
     * @param string $sFirstPartPath
     * @param string $sOtherDestinationDirectoryPath
     * @param bool $bOverWriteDestination
     * @param bool $bValidatePartList
     * @param bool $bUnlinkSourcePartsOnSuccess
     * @return bool
     * @throws AfrFileSystemSplitMergeException
     */
    public function merge(
        string $sFirstPartPath,
        string $sOtherDestinationDirectoryPath = '',
        bool   $bOverWriteDestination = true,
        bool   $bValidatePartList = true,
        bool   $bUnlinkSourcePartsOnSuccess = false
    ): bool;

    /**
     * @param string $sDestinationFilePath
     * @param array $aParts
     * @return void
     * @throws AfrFileSystemSplitMergeException
     */
    public function blindMerge(string $sDestinationFilePath, array $aParts): bool;

    /**
     * @param string $sFullFilePath
     * @return bool
     */
    public function isFileFirstOfMergeShards(string $sFullFilePath): bool;
}