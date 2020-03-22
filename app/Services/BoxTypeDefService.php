<?php
namespace NeubusSrm\Services;

use NeubusSrm\Models\Indexing\BoxTypeDef;
use NeubusSrm\Repositories\BoxTypeDefRepository;


class BoxTypeDefService extends NeuSrmService
{
    private $boxtypedefRepository;

    public function __construct(BoxTypeDefRepository $boxtypedefRepository) {
        $this->boxtypedefRepository = $boxtypedefRepository;
    }


    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Company[]
     */
    public function getAllBoxtypedefs()
    {
        $data = $this->boxtypedefRepository->getBoxTypeDefs();
        return $data->map(function(BoxTypeDef $boxTypeDef){
            return ['value' => $boxTypeDef->type, 'text' => $boxTypeDef->description];
        })->toArray();;
    }

}
