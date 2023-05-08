<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Traversing;

trait AfrDirTraversingCollectionTrait
{
    use AfrDirTraversingDependency;
    use AfrDirTraversingCountChildrenDirsTrait;
    use AfrDirTraversingFileListTrait;
    use AfrDirTraversingGetAllChildrenDirsTrait;
}
