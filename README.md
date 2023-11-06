# Autoframe is a low level framework that is oriented on SOLID flexibility

[![Build Status](https://github.com/autoframe/components-filesystem/workflows/PHPUnit-tests/badge.svg?branch=main)](https://github.com/autoframe/components-filesystem/actions?query=branch:main)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
![Packagist Version](https://img.shields.io/packagist/v/autoframe/components-filesystem?label=packagist%20stable)
[![Downloads](https://img.shields.io/packagist/dm/autoframe/components-filesystem.svg)](https://packagist.org/packages/autoframe/components-filesystem)

*PHP File System utilities like "Looping", "Traversing", "Versioning", "Encode", "Base64", "Mime", etc*

Namespace:
- Autoframe\\Component\\FileSystem

SINGLETON Classes:
- AfrFileSystemCollectionClass (contains all the methods from the next classes)
- AfrDirPathClass
  -  isDir
  -  openDir
  -  detectDirectorySeparatorFromPath
  -  getApplicableSlashStyle
  -  removeFinalSlash
  -  addFinalSlash
  -  makeUniformSlashStyle
  -  correctPathFormat
  -  simplifyAbsolutePath
  -  fixDs

- AfrBase64InlineDataClass
  - getBase64InlineData
  
- AfrOverWriteClass
  - overWriteFile
 
- AfrDirTraversingCollectionClass (all traversing methods)
- AfrDirTraversingCountChildrenDirsClass
  - countAllChildrenDirs
- AfrDirTraversingFileListClass
  - getDirFileList
- AfrDirTraversingGetAllChildrenDirsClass
  - getAllChildrenDirs

- AfrDirMaxFileMtimeClass
  - getDirMaxFileMtime
- AfrFileVersioningMtimeHashClass
  - fileVersioningMtimeHash
- AfrSplitMergeClass
  - AfrSplitMergeInterface
- AfrSplitMergeCopyDirClass
  - AfrSplitMergeCopyDirInterface

Includes:
- Traits (can be used for embedding into classes if the singleton is not good enough)
- Interfaces