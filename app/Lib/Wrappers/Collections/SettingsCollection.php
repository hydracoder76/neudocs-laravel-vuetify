<?php

namespace NeubusSrm\Lib\Wrappers\Collections;

use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Models\Org\Setting;

/**
 * Class SettingsCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class SettingsCollection extends Collection implements NeuTypedCollection
{
    /**
     * @return string
     */
    public function getCollectionType(): string {
        Setting::class;
    }

}