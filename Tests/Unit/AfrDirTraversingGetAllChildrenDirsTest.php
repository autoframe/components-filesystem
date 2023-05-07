<?php
declare(strict_types=1);

namespace Unit;

use Autoframe\Components\FileSystem\Traversing\AfrDirTraversingGetAllChildrenDirsClass;
use PHPUnit\Framework\TestCase;

class AfrDirTraversingGetAllChildrenDirsTest extends TestCase
{
    function AfrDirTraversingGetAllChildrenDirsDataProvider(): array
    {
        echo __CLASS__ . '->' . __FUNCTION__ . PHP_EOL;
        $d1 = __DIR__ . DIRECTORY_SEPARATOR . '../../';
        $d2 = __DIR__ . DIRECTORY_SEPARATOR . '../../vendor/composer/'; //without any subdirs
        $d3 = __DIR__ . DIRECTORY_SEPARATOR . '../../vendor/';
        return [
            [$d1, 0, false, function ($aFiles) {
                return $aFiles === false;
            }],
            [$d1, 2, true, function ($aFiles) {
                return
                    isset($aFiles['vendor']) &&
                    isset($aFiles['Tests']) &&
                    !isset($aFiles['.']) &&
                    !isset($aFiles['..']);
            }],
            [$d2, 2, true, function ($aFiles) {
                return $aFiles === [];
            }],
            [$d3, 3, true, function ($aFiles) {
                foreach ($aFiles as $lok => $l1) {
                    foreach ($l1 as $l1k => $l2) {
                        foreach ($l2 as $l2k => $l3) {
                            return $l3 === false;
                        }
                    }
                }
                return false;
            }],
        ];
    }

    /**
     * @test
     * @dataProvider AfrDirTraversingGetAllChildrenDirsDataProvider
     */
    public function AfrDirTraversingGetAllChildrenDirsTest(string $sPath, int $iMaxLevels, bool $bFollowSymlinks, $Fx): void
    {
        $oClass = new AfrDirTraversingGetAllChildrenDirsClass();
        $aFiles = $oClass->getAllChildrenDirs($sPath, $iMaxLevels, $bFollowSymlinks);
        $this->assertEquals(true, $Fx($aFiles), print_r($aFiles, true));

    }


}