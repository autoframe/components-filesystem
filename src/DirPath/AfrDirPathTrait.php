<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\DirPath;

use Autoframe\Components\FileSystem\DirPath\Exception\AfrFileSystemDirPathException;

trait AfrDirPathTrait
{
    /**
     * the call filetype()=="dir" is clearly faster than the is_dir() call
     * @param string $sDirPath
     * @return bool
     */
    public function isDir(string $sDirPath): bool
    {
        //Possible values are fifo, char, dir, block, link, file, socket and unknown.
        return @filetype($sDirPath) === 'dir';
    }

    /**
     * @param string $sDirPath
     * @param $context
     * @return false|resource
     * @throws AfrFileSystemDirPathException
     */
    public function openDir(string $sDirPath, $context = null)
    {
        try {
            if ($context) {
                $resource = opendir($sDirPath, $context);
            } else {
                $resource = opendir($sDirPath);
            }
        } catch (\Exception $ex) {
            throw new AfrFileSystemDirPathException('Unable to open directory: ' . $sDirPath);
        }
        return $resource;
    }

    /**
     * Detect windows network path \\192.168.. or Drive path C:\Dir
     * @param string $sDirPath
     * @return string
     */
    private function detectWinPath(string $sDirPath): string
    {
        if (
            substr($sDirPath, 0, 2) === '\\\\' ||
            substr($sDirPath,1,2)==':\\'
        ) {
            return '\\'; //
        }
        return '';
    }

    /**
     * Detect path slash style: /
     * @param string $sDirPath
     * @return string
     */
    public function detectDirectorySeparatorFromPath(string $sDirPath): string
    {
        if($sWinPath = $this->detectWinPath($sDirPath)){
            return $sWinPath;
        }
        $iUnixFound = substr_count($sDirPath, '/');
        $iWindowsFound = substr_count($sDirPath, '\\');
        if ($iUnixFound > $iWindowsFound) {
            return '/';
        } elseif ($iWindowsFound > $iUnixFound) {
            return '\\';
        }
        return DIRECTORY_SEPARATOR;
    }

    /**
     * Validate or detect a slash style from a dir path
     * @param string $sDirPath
     * @param string $sSlashStyleFormat
     * @return string
     */
    public function getApplicableSlashStyle(string $sDirPath, string $sSlashStyleFormat = ''): string
    {
        if($sWinPath = $this->detectWinPath($sDirPath)){
            return $sWinPath;
        }
        if ($sSlashStyleFormat === DIRECTORY_SEPARATOR) {
            return DIRECTORY_SEPARATOR; //we want to convert to system format, so we can skip the autodetect
        }
        if ($sSlashStyleFormat && !in_array($sSlashStyleFormat, ['/', '\\'])) {
            $sSlashStyleFormat = ''; // drop wrong strings
        }
        if (!$sSlashStyleFormat) {
            $sSlashStyleFormat = $this->detectDirectorySeparatorFromPath($sDirPath);
        }
        return $sSlashStyleFormat;
    }


    /**
     * Remove final slash from a directory path
     * @param string $sDirPath
     * @return string
     */
    public function removeFinalSlash(string $sDirPath): string
    {
        return rtrim($sDirPath, '\/');
    }

    /**
     * Add a final slash to a directory path
     * @param string $sDirPath
     * @return string
     */
    public function addFinalSlash(string $sDirPath): string
    {
        return rtrim($sDirPath, '\/') . $this->detectDirectorySeparatorFromPath($sDirPath);
    }

    /**
     * @param string $sDirPath
     * @param string $sSlashFormat
     * @return string
     */
    private function correctSlashStyleMethod(string $sDirPath, string $sSlashFormat): string
    {
        $aSearch = array_diff(['/', '\\'], [$sSlashFormat]);
        $iTypes = count($aSearch);
        if ($iTypes) {
            foreach ($aSearch as $sDs) {
                if (strpos($sDirPath, $sDs) !== false) {
                    $aReplace = array_fill(0, $iTypes, $sSlashFormat);
                    return str_replace($aSearch, $aReplace, $sDirPath);
                }
            }
        }
        return $sDirPath;
    }

    /**
     * Make the dir path to a uniform path for cross system like windows to unix and keep existing slash format
     * @param string $sDirPath
     * @return string
     */
    public function makeUniformSlashStyle(string $sDirPath): string
    {
        return $this->correctSlashStyleMethod(
            $sDirPath,
            $this->detectDirectorySeparatorFromPath($sDirPath)
        );
    }

    /**
     * Make the dir path to a uniform path for cross system like windows to unix
     * Full fix for a full directory path
     * @param string $sDirPath
     * @param bool $bWithFinalSlash
     * @param bool $bCorrectSlashStyle
     * @param string $sSlashStyle
     * @return string
     */
    public function correctPathFormat(
        string $sDirPath,
        bool   $bWithFinalSlash = false,
        bool   $bCorrectSlashStyle = true,
        string &$sSlashStyle = DIRECTORY_SEPARATOR
    ): string
    {
        $sSlashStyle = $this->getApplicableSlashStyle($sDirPath, $sSlashStyle);
        $sDirPath = $bWithFinalSlash ?
            $this->addFinalSlash($sDirPath) :
            $this->removeFinalSlash($sDirPath);
        if ($bCorrectSlashStyle) {
            $sDirPath = $this->correctSlashStyleMethod($sDirPath, $sSlashStyle);
        }
        return $sDirPath;
    }

    /**
     * IN: 'this/is/../a/./test/.///is'
     * OUT: 'this/a/test/is'
     * This method does not check for the correctness of the path, just does some cleanup
     * @param string $sPath
     * @return string
     */
    public function simplifyAbsolutePath(string $sPath): string
    {
        $sDs = $this->detectDirectorySeparatorFromPath($sPath);
        $sPath = str_replace(['/', '\\'], $sDs, $sPath);
        $aAbsolutes = [];
        foreach (array_filter(explode($sDs, $sPath), 'strlen') as $sSegment) {
            if ('.' === $sSegment) {
                continue;
            }
            if ('..' === $sSegment && count($aAbsolutes)>0) {
                array_pop($aAbsolutes);
            } else {
                $aAbsolutes[] = $sSegment;
            }
        }
        return implode($sDs, $aAbsolutes);
    }


}
