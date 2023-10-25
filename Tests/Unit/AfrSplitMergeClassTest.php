<?php
declare(strict_types=1);

namespace Unit;

use Autoframe\Components\FileSystem\Exception\AfrFileSystemException;
use Autoframe\Components\FileSystem\SplitMerge\AfrSplitMergeClass;
use Autoframe\Components\FileSystem\SplitMerge\AfrSplitMergeInterface;
use Autoframe\Components\FileSystem\SplitMerge\Exception\AfrFileSystemSplitMergeException;
use PHPUnit\Framework\TestCase;

class AfrSplitMergeClassTest extends TestCase
{

    public static function splitDataProvider(): array
    {
        echo __CLASS__ . '->' . __FUNCTION__ . PHP_EOL;

        $sContents1 = '';
        $iParts = 15;
        for ($i = 0; $i < $iParts; $i++) {
            $sContents1 .= (string)$i;
        }
        $iBytes = 2;
        $aTests = [];

        $aTests [] = [
            __DIR__ . DIRECTORY_SEPARATOR . 'splitTest' . DIRECTORY_SEPARATOR . 'sContents1.txt',
            $sContents1,
            $iBytes,
            [
                'return' => (int)ceil(strlen($sContents1) / $iBytes),
                'filesize' => ['.10|' . $iBytes],
                'contents' => ['.10|14'],
            ],

        ];

        $sContents2 = substr($sContents1, 0, 3);
        $aTests [] = [

            __DIR__ . DIRECTORY_SEPARATOR . 'splitTest' . DIRECTORY_SEPARATOR . 'sContents2.txt',
            $sContents2,
            $iBytes,
            [
                'return' => (int)ceil(strlen($sContents2) / $iBytes),
                'filesize' => ['.1|' . $iBytes, '.2|1'],
                'contents' => ['.2|2', '.1|01'],
            ],

        ];

        return $aTests;
    }

    /**
     * @test
     * @dataProvider splitDataProvider
     */
    public function splitTest(
        string $sourcePath,
        string $sTestContents,
        int    $sizeBytes,
        array  $aCheck,
        bool   $bOverwrite = true
    ): void
    {
        file_put_contents($sourcePath, $sTestContents);
        $oSplit = AfrSplitMergeClass::getInstance();
        $this->assertSame(true, $oSplit instanceof AfrSplitMergeInterface);
        $return = $oSplit->split($sourcePath, $sizeBytes, $bOverwrite);
        if (isset($aCheck['return'])) {
            $this->assertSame($aCheck['return'], $return, 'number of split parts is wrong!');
        }
        if (!empty($aCheck['filesize'])) {
            foreach ($aCheck['filesize'] as $sFilesize) {
                $aFilesize = explode('|', $sFilesize);
                $this->assertSame((int)$aFilesize[1], filesize($sourcePath . $aFilesize[0]), 'split size mismatch');
            }
        }
        if (!empty($aCheck['contents'])) {
            foreach ($aCheck['contents'] as $sContents) {
                $aContents = explode('|', $sContents);
                $this->assertSame((string)$aContents[1], file_get_contents($sourcePath . $aContents[0]), 'split contents mismatch');
            }
        }

        $bMerged = false;
        $sFirstExt = str_pad(
            '1',
            strlen($aFilesize[0]) - 1,
            '0',
            STR_PAD_LEFT
        );
        try {
            $bMerged = $oSplit->merge($sourcePath . '.' . $sFirstExt, $sourcePath, $bOverwrite);
        } catch (AfrFileSystemSplitMergeException|AfrFileSystemException $e) {
            $this->assertSame('No error', $e->getMessage(), $e->getMessage());
        }
        if ($bMerged) {
            $sMergedContents = file_get_contents($sourcePath);
            $this->assertSame($sTestContents, $sMergedContents, 'merged contents differ from original contents! ' . "\n$sTestContents\n$sMergedContents");
        }

    }


}