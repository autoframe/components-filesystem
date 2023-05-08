<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\Traversing;

use Autoframe\Components\FileSystem\DirPath\AfrDirPathClass;
use Autoframe\Components\FileSystem\DirPath\AfrDirPathInterface;

trait AfrDirTraversingDependency
{
    /** @var AfrDirPathInterface  */
    public static AfrDirPathInterface $AfrDirPathInstance;

    public function __construct(?AfrDirPathInterface $AfrDirPathInstance = null)
    {
        if(!empty($AfrDirPathInstance)){
            self::$AfrDirPathInstance = $AfrDirPathInstance;
        }
        else{
            $this->fallbackDependencyAfrDirPathInstance();
        }
    }

    private function fallbackDependencyAfrDirPathInstance(){
        if (empty(self::$AfrDirPathInstance)) {
            self::$AfrDirPathInstance = new AfrDirPathClass();
        }
    }


}