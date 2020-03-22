<?php
/**
 * User: mlawson
 * Date: 2019-01-28
 * Time: 09:21
 */

namespace NeubusSrm\Lib\Builders\FileSystem\Drivers\Impl;


use NeubusSrm\Lib\Builders\FileSystem\Drivers\FilePathDriver;
use NeubusSrm\Lib\Builders\FileSystem\PathBinders\PathBinder;

/**
 * Class DiskFilePathDriver
 * @package NeubusSrm\Lib\Builders\FileSystem\Drivers\Impl
 */
class DiskFilePathDriver implements FilePathDriver
{
    /**
     * @return string
     */
    public function getDriverName(): string {
        return 'srm';
    }

    /**
     * @return string
     */
    public function getDriverClass(): string {
        return self::class;
    }

    /**
     * @param array $pathParams
     * @param PathBinder $binderClass
     * @return string
     */
    public function createLocator(array $pathParams, PathBinder $binderClass): string {
        return $binderClass->getStoragePath($pathParams);
    }


}