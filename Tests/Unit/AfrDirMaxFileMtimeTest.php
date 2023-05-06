<?php
declare(strict_types=1);

namespace Unit;

use Autoframe\Components\FileSystem\Versioning\AfrDirMaxFileMtimeClass;
use PHPUnit\Framework\TestCase;

class AfrDirMaxFileMtimeTest extends TestCase
{
    function getDirMaxFileMtimeProvider(): array
    {
        echo __CLASS__ . '->' . __FUNCTION__ . PHP_EOL;
        return [
            [
                __DIR__.'/../../',
                strtotime('2023-05-01 00:00:00'),
                4
            ],
            [
                [
                    __DIR__,
                    __DIR__.'/../../',
                    ],
                strtotime('2023-05-01 00:00:00'),
                2
            ],
        ];


    }

    /**
     * @test
     * @dataProvider getDirMaxFileMtimeProvider
     */
    public function getDirMaxFileMtimeTest($mDirPath, int $iLibWriteTime, int $iMaxSubDirs ): void
    {
        $oClass = new AfrDirMaxFileMtimeClass();
        $iMaxTs = $oClass->getDirMaxFileMtime($mDirPath, $iMaxSubDirs, false, (bool)rand(0, 1));
        $this->assertGreaterThan($iLibWriteTime,$iMaxTs, date('Y-m-d H:i:s',$iLibWriteTime).' GTE? '.date('Y-m-d H:i:s',$iMaxTs));
    }


}