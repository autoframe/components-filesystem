<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Traversing;

use Autoframe\DesignPatterns\Singleton\AfrSingletonAbstractClass;

class AfrDirTraversingCollectionClass extends AfrSingletonAbstractClass implements AfrDirTraversingCollectionInterface
{
    use AfrDirTraversingCollectionTrait;
}