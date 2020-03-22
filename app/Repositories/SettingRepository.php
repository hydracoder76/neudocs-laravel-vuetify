<?php

namespace NeubusSrm\Repositories;

use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Lib\Wrappers\Collections\SettingsCollection;
use NeubusSrm\Models\Org\Setting;
use Auth;


/**
 * Class SettingRepository
 * @package NeubusSrm\Repositories
 */
class SettingRepository implements NeuSrmRepository
{
    /**
     * @return string
     */
    public function getModelClass(): string {
        return Setting::class;
    }

    /**
     * @param string $projectId
     * @return SettingsCollection
     * @throws \Throwable|NeuEntityNotFoundException
     */
    public function getSettingsByProject(string $projectId) : SettingsCollection{
        $settings =  Setting::where('project_id', $projectId)->get();
        throw_if($settings == null || $settings->count() == 0,
            NeuEntityNotFoundException::class, 'No settings for this project');
        return $settings;
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $projectId
     * @throws \Throwable|NeuEntityNotFoundException
     */
    public function setSetting(string $key, string $value, string $projectId) : void{
        $updateCount = Setting::where('project_id', $projectId)->where('setting_key', $key)->update(['value'=>$value]);
        throw_if($updateCount == null || $updateCount == 0,
            NeuEntityNotFoundException::class, 'No setting with this key ' . $key . ' for this project');
    }

    /**
     * @param string $key
     * @param string $projectId
     * @return string
     * @throws \Throwable|NeuEntityNotFoundException
     */
    public function getSettingByKey(string $key, string $projectId) : string{
        $setting = Setting::where('project_id', $projectId)->where('setting_key', $key)->first();
        throw_if($setting == null || $setting->count() == 0,
            NeuEntityNotFoundException::class, 'No setting with this key ' . $key . ' for this project');
        return $setting->value;
    }


}