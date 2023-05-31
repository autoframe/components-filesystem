<?php
declare(strict_types=1);

namespace Unit;

use Autoframe\Components\FileSystem\OverWrite\AfrOverWriteClass;
use PHPUnit\Framework\TestCase;

class AfrOverWriteClassTest extends TestCase
{
    function overWriteFileDataProvider(): array
    {
        echo __CLASS__ . '->' . __FUNCTION__ . PHP_EOL;
        return [
            ['OVERWRITTEN!' . microtime(true) . rand(1001, 3000), 5, 500, 7.2],
            ['OVERWRITTEN!' . microtime(true) . rand(30001, 60000), 30, 500, 14.1],
            ['OVERWRITTEN!', 160, 500, 15],
        ];
    }

    /**
     * @test
     * @dataProvider overWriteFileDataProvider
     */
    public function overWriteFileTest(
        string $sData,
        int    $iTimeoutBeforeStart,
        int    $iMaxRetryMs,
        float  $fDeltaSleepMs): void
    {

        $execFileArgs = __DIR__ . DIRECTORY_SEPARATOR . 'AfrOverWrite.php ' . $iTimeoutBeforeStart . ' Arg2';
        if (DIRECTORY_SEPARATOR === '\\') {
            $php = (substr(php_ini_loaded_file(), 0, -3) . 'exe');
            pclose(popen("start /B $php $execFileArgs", 'r'));
        } else {
            exec('php ' . $execFileArgs . ' > /dev/null &');
        }
        usleep(min($iTimeoutBeforeStart, 40) * 1000); //sleep 40 ms and wait for the file to be created with other data

        $sOverwritePath = __DIR__ . DIRECTORY_SEPARATOR . 'AfrOverWriteClass.txt';
        $this->assertNotEquals($sData, file_get_contents($sOverwritePath));

        $oClass = new AfrOverWriteClass();
        $bResponse = $oClass->overWriteFile($sOverwritePath, $sData, $iMaxRetryMs, $fDeltaSleepMs);
        $this->assertEquals(true, $bResponse);
        $this->assertEquals($sData, file_get_contents($sOverwritePath));
        usleep(10 * 1000);
        //@unlink($sOverwritePath);
    }


}