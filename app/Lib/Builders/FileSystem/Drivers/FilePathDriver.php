<?php
/**
 * User: mlawson
 * Date: 2019-01-28
 * Time: 09:21
 */

namespace NeubusSrm\Lib\Builders\FileSystem\Drivers;

use NeubusSrm\Lib\Builders\FileSystem\PathBinders\PathBinder;

/**
 * Interface FilePathDriver
 * @package NeubusSrm\Lib\Builders\FileSystem\Drivers
 */
interface FilePathDriver
{
    /**
     * @return string
     */
    public function getDriverName() : string;

    /**
     * @return string
     */
    public function getDriverClass() : string;

    /**
     * @param array $pathParams
     * @param PathBinder $binderClassInstance
     * @return string
     */
    public function createLocator(array $pathParams, PathBinder $binderClassInstance) : string;


}