<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Versioning;

use Autoframe\DesignPatterns\Singleton\AfrSingletonAbstractClass;

class AfrFileVersioningMtimeHashClass extends AfrSingletonAbstractClass implements AfrFileVersioningMtimeHashInterface
{
    use AfrFileVersioningMtimeHashTrait;
}