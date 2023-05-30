<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\OverWrite;


/**
 * It tries several times to overwrite a file, using a timeout and a maximum retry time
 */
interface AfrOverWriteInterface
{
    /**
     * @param string $sFilePath
     * @param string $sData
     * @param int $iMaxRetryMs
     * @param float $fDeltaSleepMs
     * @return bool
     */
    public function overWriteFile(string $sFilePath, string $sData, int $iMaxRetryMs = 1500, float $fDeltaSleepMs = 6.5): bool;
}