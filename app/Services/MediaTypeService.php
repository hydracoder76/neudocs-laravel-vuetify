<?php


namespace NeubusSrm\Services;

use NeubusSrm\Lib\Wrappers\Collections\MediaTypesCollection;
use NeubusSrm\Repositories\MediaTypeRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class MediaTypeService
 * @package NeubusSrm\Services
 */
class MediaTypeService extends NeuSrmService
{

	/**
	 * @var MediaTypeRepository
	 */
	private $mediaTypeRepository;


	/**
	 * CompanyService constructor.
	 * @param MediaTypeRepository $mediaTypeRepository
	 */
	public function __construct(MediaTypeRepository $mediaTypeRepository) {
		$this->mediaTypeRepository = $mediaTypeRepository;
	}


    /**
     * @return mixed
     */
    public function allMediaType() : MediaTypesCollection
    {
        return $this->mediaTypeRepository->getAllMediaTypes();
    }    

}
