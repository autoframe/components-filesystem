<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\SplitMergeCopyDir;

use Autoframe\Components\FileSystem\SplitMerge\Exception\AfrFileSystemSplitMergeException;
use Autoframe\Components\FileSystem\SplitMergeCopyDir\Exception\AfrFileSystemSplitMergeCopyDirException;

interface AfrSplitMergeCopyDirInterface
{

    /**
     * @param string $sSourceDir
     * @param string $sDestinationDir
     * @param int $iPartSize
     * @param bool $bOverwriteFiles
     * @return int
     * @throws AfrFileSystemSplitMergeCopyDirException
     * @throws AfrFileSystemSplitMergeException
     */
    public function splitCopyDir(
        string $sSourceDir,
        string $sDestinationDir,
        int    $iPartSize,
        bool   $bOverwriteFiles
    ): int;

    /**
     * @param string $sSourceDir
     * @param string $sDestinationDir
     * @param bool $bOverwriteFiles
     * @param bool $bUnlinkSourcePartsOnSuccess
     * @return int
     * @throws AfrFileSystemSplitMergeCopyDirException
     * @throws AfrFileSystemSplitMergeException
     */
    public function mergeCopyDir(
        string $sSourceDir,
        string $sDestinationDir = '',
        bool   $bOverwriteFiles = false,
        bool   $bUnlinkSourcePartsOnSuccess = false
    ): int;



}