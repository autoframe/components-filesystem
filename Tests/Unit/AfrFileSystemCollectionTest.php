<?php
declare(strict_types=1);

namespace Unit;


use Autoframe\Components\FileSystem\AfrFileSystemCollectionClass;
use Autoframe\Components\FileSystem\Versioning\Exception\AfrFileSystemVersioningException;
use PHPUnit\Framework\TestCase;

class AfrFileSystemCollectionTest extends TestCase
{
    function getBase64InlineDataDataProvider(): array
    {
        echo __CLASS__ . '->' . __FUNCTION__ . PHP_EOL;
        return [
            [__DIR__ . DIRECTORY_SEPARATOR . 'AfrBase64InlineDataTest.jpg', 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4QAiRXhpZgAATU0AKgAAAAgAAQESAAMAAAABAAEAAAAAAAD/2wBDAAIBAQIBAQICAgICAgICAwUDAwMDAwYEBAMFBwYHBwcGBwcICQsJCAgKCAcHCg0KCgsMDAwMBwkODw0MDgsMDAz/2wBDAQICAgMDAwYDAwYMCAcIDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAz/wAARCAALABADASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD5p/Zw/Z0+GfgD4jf8IJJbeG/GVv8AE62WzluvEnheG6uNMSR2WSW1lSGSW3McUUxEsDQOsskR3qACdr9jL9kP4b/tA/DBrxvGVx8LfFPgm5TTNbSHTRfaTqu9f3bRF7hZhMHjnaRmRI2SaBY1HlO8ngPhVW8V/GL7dfTXTXkkskbTRTvC7KsKxquUKnaFQYHQEuRy7E/TH7Jvww0P4dfEy3m0ax+xyTN5DHzpJBtUuOA7EBiGIZhy3G4nAx9tgsPR+vvH03JKcm+VNJatJLa7Sj1bbvtpofHZhleKp5b/AGdOqnOEVH2llzXW8rbK76JWt0vqf//Z'],
            [__DIR__ . DIRECTORY_SEPARATOR . 'AfrBase64InlineDataTest.png', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAALCAYAAAB24g05AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJuSURBVChTDZBLSFQBGIW/+5yXzowz4ziaaZqKj8IkVIgkDTUXPWijQa0satWioNazclVthDYRFNFCAosWBplkkgiFSg8b03wy6pimzst5eGdud/FzNj/nfOcIFNfraFlAgP20IXlImTCZWBDyixEUFVW1kWexotgdmHJdqFY7WUNNsoooFtaAIx80aDmew8GLL2jRLiLhpxA5QHCX4HG5KKqpZSOeJrG/Ryq2ix7e5iARRtKTmp+sQXAg01SpMRC8wMfZbvYWxqlr72EjEODllXl85Z1MLUWJJ1LGbxxdlNBlK6J8/R5KxyXoPE+BOU1wNcm10gGGPkVxbk+zsxWi/1szT56Psz7WgtntJe3ykbWoZGUNQR4M6Q57Lk63lZt9DuZS+eR6ilmb3aLh0TMen26iqkLk/p2LnOlsZuTIDV5pHkzGZBkDXJQ319CCq8QmfhHXFRoqNPRUhLu9ZTjLPJR39+IsaMDfv01D+xscC7NYw2DeAtU4WVhZJG12ck4bZnC+nGq8vB8JsU0ubncGlSQTyxrxokZKOq6yM/MTyVJOcN+GIGaRfNVtfiEl0S2+5l8kwdv1Vuy1jeS0PqRHuE18aoSQXkQwLGE1yVRVVhOZG2A2ZFj/XUE60dzlP+z1MjX8jrE/ZUaqjCKJpLM5bCxvMjn9mx/hCvLsVnRd5/OiHVdyifq6enwWwdggsUf94hALU/NcbjvKbmCUaOAD4vcHzHwdN5JVjpXK+EwxNkf7OOuaxJ5ThLyzhlPIINw6eUqPRfeZdPiM9llikoQkK3gLy1DMNgNbQUvGkVWz0dmYXlKw2uxYPG5s+Yf4D5TN7so45vxeAAAAAElFTkSuQmCC'],
        ];
    }

    /**
     * @test
     * @dataProvider getBase64InlineDataDataProvider
     */
    public function getBase64InlineDataTest(string $sFile, string $sExpected): void
    {
        $sInline = (new AfrFileSystemCollectionClass())->getBase64InlineData($sFile);
    //    echo "$sFile\n$sInline\n\n";
        $this->assertEquals($sExpected, $sInline);
    }

    function getBase64InlineOnePxDataProvider(): array
    {
        echo __CLASS__ . '->' . __FUNCTION__ . PHP_EOL;
        return [
            [
                (new AfrFileSystemCollectionClass())->getBase64InlineOnePx(),
                'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=='
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getBase64InlineOnePxDataProvider
     */
    public function getBase64InlineOnePxTest(string $sInlineData, string $sExpected): void
    {
        $this->assertEquals($sExpected, $sInlineData);
    }



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
        $oClass = new AfrFileSystemCollectionClass();
        $iMaxTs = $oClass->getDirMaxFileMtime($mDirPath, $iMaxSubDirs, false, (bool)rand(0, 1));
        $this->assertGreaterThan($iLibWriteTime,$iMaxTs, date('Y-m-d H:i:s',$iLibWriteTime).' GTE? '.date('Y-m-d H:i:s',$iMaxTs));
    }





    function getDirFileListDataProvider(): array
    {
        echo __CLASS__ . '->' . __FUNCTION__ . PHP_EOL;
        $d1 = __DIR__ . DIRECTORY_SEPARATOR . '../../';
        $d2 = __DIR__ . DIRECTORY_SEPARATOR . '../../vendor/composer/';
        return [
            [$d1, [], function ($aFiles) {
                return in_array('composer.json', $aFiles);
            }],
            [$d1, ['md'], function ($aFiles) {
                return in_array('README.md', $aFiles);
            }],
            [$d2, ['json', 'php'], function ($aFiles) {
                return in_array('autoload_classmap.php', $aFiles) && in_array('installed.json', $aFiles);
            }],
            [$d2, [''], function ($aFiles) {
                return in_array('LICENSE', $aFiles);
            }],
        ];
    }

    /**
     * @test
     * @dataProvider getDirFileListDataProvider
     */
    public function getDirFileListTest(string $sPath, array $aExtFilter, $Fx): void
    {
        $oClass = new AfrFileSystemCollectionClass();
        $aFiles = $oClass->getDirFileList($sPath, $aExtFilter);
        $this->assertEquals(true, $Fx($aFiles), print_r($aFiles, true));

    }





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
        $oClass = new AfrFileSystemCollectionClass();
        $aFiles = $oClass->getAllChildrenDirs($sPath, $iMaxLevels, $bFollowSymlinks);
        $this->assertEquals(true, $Fx($aFiles), print_r($aFiles, true));

    }





    function fileVersioningMtimeHashProvider(): array
    {
        echo __CLASS__ . '->' . __FUNCTION__ . PHP_EOL;
        return [
            [__FILE__, false], //file without error
            [__FILE__, true], //can throw error if file is missing, but it does
            ['x', false], //wrong file without error
            ['x', true],//wrong file that throw error
        ];
    }

    /**
     * @test
     * @dataProvider fileVersioningMtimeHashProvider
     */
    public function fileVersioningMtimeHashTest(string $sFile, bool $bCanThrow): void
    {
        $oClass = new AfrFileSystemCollectionClass();
        if ($bCanThrow) {
            if (!is_file($sFile)) {
                $this->expectException(AfrFileSystemVersioningException::class);
                $sHash = $oClass->fileVersioningMtimeHash($sFile, true);
            } else {
                $sHash = $oClass->fileVersioningMtimeHash($sFile, true);
                $this->assertEquals(true, strlen($sHash) > 7);
            }

        } else {
            $sHash = $oClass->fileVersioningMtimeHash($sFile, false);
            if ($sHash === '0') {
                //file was not found and zero was returned as hash without a fatal error
                $this->assertEquals(true, true);
            } else {
                $this->assertEquals(true, strlen($sHash) > 7);
            }
        }

    }






}