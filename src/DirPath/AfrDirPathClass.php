<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\DirPath;

use Autoframe\DesignPatterns\Singleton\AfrSingletonAbstractClass;

class AfrDirPathClass extends AfrSingletonAbstractClass implements AfrDirPathInterface
{
    use AfrDirPathTrait;
}