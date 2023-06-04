<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Traversing;

use Autoframe\Components\FileSystem\DirPath\AfrDirPathClass;
use Autoframe\Components\FileSystem\DirPath\AfrDirPathInterface;

trait AfrDirTraversingDependency /// TO REWRITE!!!!
{
    /** @var AfrDirPathInterface */
    protected static AfrDirPathInterface $AfrDirPathInstance;

    /**
     * @param AfrDirPathInterface $AfrDirPathInstance
     * @return void
     */
    public function setAfrDirPathInterface(AfrDirPathInterface $AfrDirPathInstance): void
    {
        self::$AfrDirPathInstance = $AfrDirPathInstance;
    }

    /**
     * @return void
     */
    protected function checkAfrDirPathInstance():void
    {
        if (empty(self::$AfrDirPathInstance)) {
            self::$AfrDirPathInstance = AfrDirPathClass::getInstance();
        }
    }


}