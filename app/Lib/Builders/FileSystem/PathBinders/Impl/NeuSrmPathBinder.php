<?php
/**
 * User: mlawson
 * Date: 2019-01-28
 * Time: 11:10
 */

namespace NeubusSrm\Lib\Builders\FileSystem\PathBinders\Impl;

use NeubusSrm\Lib\Builders\FileSystem\PathBinders\PathBinder;

/**
 * Class NeuSrmPathBinder
 * @package NeubusSrm\Lib\Builders\FileSystem\PathBinders\Impl
 */
class NeuSrmPathBinder implements PathBinder
{

    /**
     * @param array $bindingRuleParams
     * @return string
     */
    public function getStoragePath(array $bindingRuleParams = []): string {
        $baseDir = $bindingRuleParams['base_dir'] ?? '';
        $projectId = $bindingRuleParams['project_id'] ?? '';
        $fileId = $bindingRuleParams['file_id'] ?? '';
        $frag1 = substr($fileId, 0,2);
        $frag2 = substr($fileId, 3,5);
        $boundPath = $baseDir .
            $projectId .
            DIRECTORY_SEPARATOR .
            $frag1 .
            DIRECTORY_SEPARATOR .
            $frag2;

        return $boundPath;
    }



}