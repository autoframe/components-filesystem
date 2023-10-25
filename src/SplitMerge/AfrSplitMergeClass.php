<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\SplitMerge;

use Autoframe\DesignPatterns\Singleton\AfrSingletonAbstractClass;

class AfrSplitMergeClass extends AfrSingletonAbstractClass implements AfrSplitMergeInterface
{
    use AfrSplitMergeTrait;
}