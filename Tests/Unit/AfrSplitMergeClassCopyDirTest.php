<?php
declare(strict_types=1);

namespace Unit;

use Autoframe\Components\FileSystem\Exception\AfrFileSystemException;
use Autoframe\Components\FileSystem\Recursive\AfrRecursiveRmDir;
use Autoframe\Components\FileSystem\SplitMergeCopyDir\AfrSplitMergeCopyDirClass;
use Autoframe\Components\FileSystem\SplitMergeCopyDir\AfrSplitMergeCopyDirInterface;
use Autoframe\Components\FileSystem\SplitMergeCopyDir\Exception\AfrFileSystemSplitMergeCopyDirException;
use PHPUnit\Framework\TestCase;

class AfrSplitMergeClassCopyDirTest extends TestCase
{
    use AfrRecursiveRmDir;

    public static function splitDataProvider(): array
    {
        echo __CLASS__ . '->' . __FUNCTION__ . PHP_EOL;

        $sDirBase = __DIR__ . DIRECTORY_SEPARATOR . 'splitTest' . DIRECTORY_SEPARATOR . 'CopyMerge' . DIRECTORY_SEPARATOR;
        $dirFromPath = $sDirBase . 'SplitCopyFrom';
        $dirFromPathSd = $dirFromPath . DIRECTORY_SEPARATOR . 'SubDirLvL2';
        $dirToPath = $sDirBase . 'SplitCopyTo';
        $dirToMergePath = $sDirBase . 'SplitMergeTo';

        foreach ([$dirFromPathSd, $dirToPath, $dirToMergePath] as $dp) {
            if (!is_dir($dp)) {
                mkdir($dp, 0777, true);
            }
        }

        $sContents = '';
        $iParts = 15;
        for ($i = 0; $i < $iParts; $i++) {
            $sContents .= (string)$i;
        }

        file_put_contents($dirFromPathSd . '/sCopyDirL2.txt', $sContents);
        file_put_contents($dirFromPath . '/sCopyDirL1.txt', substr($sContents, 0, 3));


        $aTests = [];

        $aTests [] = [
            $dirFromPath,
            $dirToPath,
            $dirToMergePath,
            8
        ];

        return $aTests;
    }

    /**
     * @test
     * @dataProvider splitDataProvider
     */
    public function splitTest(
        string $sourceDirPath,
        string $sSplitDestinationPath,
        string $sMergeDestinationPath,
        int    $sizeBytes
    ): void
    {
        $oSplit = AfrSplitMergeCopyDirClass::getInstance();
        $this->assertSame(true, $oSplit instanceof AfrSplitMergeCopyDirInterface);
        //$return = $oSplit->split($sourcePath, $sizeBytes, $bOverwrite);
        $this->recursiveRmDir($sSplitDestinationPath);
        mkdir($sSplitDestinationPath, 0777, true);
        $iFiles = $oSplit->splitCopyDir($sourceDirPath, $sSplitDestinationPath, $sizeBytes, false);
        $this->assertSame(2, $iFiles);
        //    $this->recursiveRmDir($sDestinationPath);
    }


}