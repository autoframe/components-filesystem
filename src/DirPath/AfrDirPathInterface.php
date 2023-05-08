<?php
declare(strict_types=1);

namespace Autoframe\Components\FileSystem\DirPath;

use Autoframe\Components\FileSystem\DirPath\Exception\AfrFileSystemDirPathException;

interface AfrDirPathInterface
{
    const AfrDirPathInterface = 'AfrDirPathClass';
    /**
     * the call filetype()=="dir" is clearly faster than the is_dir() call
     * @param string $sDirPath
     * @return bool
     */
    public function isDir(string $sDirPath): bool;

    /**
     * @param string $sDirPath
     * @param $context
     * @return false|resource
     * @throws AfrFileSystemDirPathException
     */
    public function openDir(string $sDirPath, $context = null);

    /**
     * Detect path slash style: /
     * @param string $sDirPath
     * @return string
     */
    public function detectDirectorySeparatorFromPath(string $sDirPath): string;

    /**
     * Validate or detect a slash style from a dir path
     * @param string $sDirPath
     * @param string $sSlashStyleFormat
     * @return string
     */
    public function getApplicableSlashStyle(string $sDirPath, string $sSlashStyleFormat = ''): string;

    /**
     * Remove final slash from a directory path
     * @param string $sDirPath
     * @return string
     */
    public function removeFinalSlash(string $sDirPath): string;

    /**
     * Add a final slash to a directory path
     * @param string $sDirPath
     * @return string
     */
    public function addFinalSlash(string $sDirPath): string;

    /**
     * Make the dir path to a uniform path for cross system like windows to unix and keep existing slash format
     * @param string $sDirPath
     * @return string
     */
    public function makeUniformSlashStyle(string $sDirPath): string;

    /**
     * Make the dir path to a uniform path for cross system like windows to unix
     * Full fix for a full directory path
     * @param string $sDirPath
     * @param bool $bWithFinalSlash
     * @param bool $bCorrectSlashStyle
     * @param string $sSlashStyle
     * @return string
     */
    public function correctPathFormat(string $sDirPath, bool $bWithFinalSlash = false, bool $bCorrectSlashStyle = true, string &$sSlashStyle = DIRECTORY_SEPARATOR): string;

    /**
     * IN: 'this/is/../a/./test/.///is'
     * OUT: 'this/a/test/is'
     * This method does not check for the correctness of the path, just does some cleanup
     * @param string $sPath
     * @return string
     */
    public function simplifyAbsolutePath(string $sPath): string;
}