<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Traversing;

use Autoframe\DesignPatterns\Singleton\AfrSingletonAbstractClass;

class AfrDirTraversingFileListClass extends AfrSingletonAbstractClass implements AfrDirTraversingFileListInterface
{
    use AfrDirTraversingFileListTrait;
}