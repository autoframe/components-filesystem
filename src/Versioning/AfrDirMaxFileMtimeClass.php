<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Versioning;

use Autoframe\DesignPatterns\Singleton\AfrSingletonAbstractClass;

class AfrDirMaxFileMtimeClass extends AfrSingletonAbstractClass implements AfrDirMaxFileMtimeInterface
{
    use AfrDirMaxFileMtimeTrait;
}