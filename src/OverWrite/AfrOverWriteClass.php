<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\OverWrite;

use Autoframe\DesignPatterns\Singleton\AfrSingletonAbstractClass;

/**
 * It tries several times to overwrite a file, using a timeout and a maximum retry time
 */
class AfrOverWriteClass extends AfrSingletonAbstractClass implements AfrOverWriteInterface
{
    use AfrOverWriteTrait;
}