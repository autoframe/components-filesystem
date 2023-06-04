<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Traversing;

use Autoframe\Components\FileSystem\DirPath\Exception\AfrFileSystemDirPathException;

use function readdir;
use function closedir;
use function count;

trait AfrDirTraversingCountChildrenDirsTrait
{
    use AfrDirTraversingDependency;

    /**
     * @param string $sDirPath
     * @return int
     * @throws AfrFileSystemDirPathException
     */
    public function countAllChildrenDirs(string $sDirPath): int
    {
        $aDirs = [];
        $this->checkAfrDirPathInstance();
        $rDir = self::$AfrDirPathInstance->openDir($sDirPath);
        while ($sEntryName = readdir($rDir)) {
            if ($sEntryName === '.' || $sEntryName === '..') {
                continue;
            }
            if (self::$AfrDirPathInstance->isDir($sDirPath . $sEntryName)) {
                $aDirs[] = $sEntryName;
            }
        }
        closedir($rDir);

        return count($aDirs);
    }

}
