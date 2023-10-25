<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem;

use Autoframe\Components\FileSystem\DirPath\AfrDirPathTrait;
use Autoframe\Components\FileSystem\DirPath\AfrDirPathInterface;
use Autoframe\Components\FileSystem\Encode\AfrBase64InlineDataTrait;
use Autoframe\Components\FileSystem\Encode\AfrBase64InlineDataInterface;
use Autoframe\Components\FileSystem\OverWrite\AfrOverWriteInterface;
use Autoframe\Components\FileSystem\OverWrite\AfrOverWriteTrait;
use Autoframe\Components\FileSystem\SplitMerge\AfrSplitMergeInterface;
use Autoframe\Components\FileSystem\SplitMerge\AfrSplitMergeTrait;
use Autoframe\Components\FileSystem\Traversing\AfrDirTraversingCollectionTrait;
use Autoframe\Components\FileSystem\Traversing\AfrDirTraversingCollectionInterface;
use Autoframe\Components\FileSystem\Versioning\AfrDirMaxFileMtimeInterface;
use Autoframe\Components\FileSystem\Versioning\AfrDirMaxFileMtimeTrait;
use Autoframe\Components\FileSystem\Versioning\AfrFileVersioningMtimeHashInterface;
use Autoframe\Components\FileSystem\Versioning\AfrFileVersioningMtimeHashTrait;
use Autoframe\DesignPatterns\Singleton\AfrSingletonAbstractClass;

class AfrFileSystemCollectionClass extends AfrSingletonAbstractClass implements
    AfrDirPathInterface,
    AfrBase64InlineDataInterface,
    AfrDirTraversingCollectionInterface,
    AfrDirMaxFileMtimeInterface,
    AfrFileVersioningMtimeHashInterface,
    AfrOverWriteInterface,
    AfrSplitMergeInterface
{
    use AfrDirPathTrait;
    use AfrBase64InlineDataTrait;
    use AfrDirTraversingCollectionTrait;
    use AfrDirMaxFileMtimeTrait;
    use AfrFileVersioningMtimeHashTrait;
    use AfrOverWriteTrait;
    use AfrSplitMergeTrait;
}
