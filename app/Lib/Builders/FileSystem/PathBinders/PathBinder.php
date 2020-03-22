<?php
/**
 * User: mlawson
 * Date: 2019-01-28
 * Time: 09:35
 */

namespace NeubusSrm\Lib\Builders\FileSystem\PathBinders;

/**
 * Interface PathBinder
 * @package NeubusSrm\Lib\Builders\FileSystem\PathBinders
 */
interface PathBinder
{

    /**
     * @param array $bindingRuleParams
     * @return string
     */
    public function getStoragePath(array $bindingRuleParams = []) : string;
}