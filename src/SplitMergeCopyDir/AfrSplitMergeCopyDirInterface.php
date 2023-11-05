<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\SplitMergeCopyDir;

use Autoframe\Components\FileSystem\Exception\AfrFileSystemException;
use Autoframe\Components\FileSystem\SplitMerge\Exception\AfrFileSystemSplitMergeException;

interface AfrSplitMergeCopyDirInterface
{

    public function splitCopyDir(string $sSourceDir,
                                 string $sDestinationDir,
                                 int    $iPartSize,
                                 bool   $bOverwriteFiles
    ): int;

}