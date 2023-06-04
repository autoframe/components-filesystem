<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Encode;

use Autoframe\DesignPatterns\Singleton\AfrSingletonAbstractClass;

class AfrBase64InlineDataClass extends AfrSingletonAbstractClass implements AfrBase64InlineDataInterface
{
    use AfrBase64InlineDataTrait;

}