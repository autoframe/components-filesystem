<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Traversing;


interface AfrDirTraversingCollectionInterface extends
    AfrDirTraversingCountChildrenDirsInterface,
    AfrDirTraversingFileListInterface,
    AfrDirTraversingGetAllChildrenDirsInterface
{

}