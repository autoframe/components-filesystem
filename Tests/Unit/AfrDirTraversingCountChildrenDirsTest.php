<?php
declare(strict_types=1);

namespace Unit;

use Autoframe\Components\FileSystem\Traversing\AfrDirTraversingCollectionClass;
use Autoframe\Components\FileSystem\Traversing\AfrDirTraversingCountChildrenDirsClass;
use PHPUnit\Framework\TestCase;

class AfrDirTraversingCountChildrenDirsTest extends TestCase
{
    public static function countAllChildrenDirsDataProvider(): array
    {
        echo __CLASS__ . '->' . __FUNCTION__ . PHP_EOL;
        $d1 = __DIR__;
        $d2 = __DIR__ . DIRECTORY_SEPARATOR . '../';
        return [
            [$d1, 0],
            [$d2, 1],
        ];
    }

    /**
     * @test
     * @dataProvider countAllChildrenDirsDataProvider
     */
    public function countAllChildrenDirsTest(string $sPath, int $iExpected): void
    {
        $iFound = AfrDirTraversingCollectionClass::getInstance()->countAllChildrenDirs($sPath);
        $this->assertSame($iExpected, $iFound, print_r($iFound, true));
    }


}