<?php

namespace NeubusSrm\Repositories;

use NeubusSrm\Lib\Wrappers\Collections\BoxTypeDefsCollection;
use NeubusSrm\Models\Indexing\BoxTypeDef;
use DB;
use NeubusSrm\Models\Indexing\FileType;

/**
 * Class BoxTypeDefRepository.
 */
class BoxTypeDefRepository implements NeuSrmRepository
{
    /**
     * @return string
     */
    public function getModelClass(): string {
        return BoxTypeDef::class;
    }

    /**
     * @return BoxTypeDefsCollection
     */
    public function getBoxTypeDefs() {

        return BoxTypeDef::all();
    }
}
