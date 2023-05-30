<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\OverWrite;

/**
 * It tries several times to overwrite a file, using a timeout and a maximum retry time
 */
class AfrOverWriteClass implements AfrOverWriteInterface
{
    use AfrOverWriteTrait;
}