<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Versioning;

use Autoframe\Components\FileSystem\Versioning\Exception\AfrFileSystemVersioningException;

interface AfrFileVersioningMtimeHashInterface
{
    /**
     * @param string $sDirPath
     * @param bool $bCanThrowException
     * @return string
     * @throws AfrFileSystemVersioningException
     */
    public function fileVersioningMtimeHash(string $sDirPath, bool $bCanThrowException): string;
}