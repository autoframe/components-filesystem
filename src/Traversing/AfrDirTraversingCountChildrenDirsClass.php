<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Traversing;

use Autoframe\DesignPatterns\Singleton\AfrSingletonAbstractClass;

class AfrDirTraversingCountChildrenDirsClass extends AfrSingletonAbstractClass implements AfrDirTraversingCountChildrenDirsInterface
{
    use AfrDirTraversingCountChildrenDirsTrait;

}