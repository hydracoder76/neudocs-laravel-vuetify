<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-13
 * Time: 10:58
 */

namespace NeubusSrm\Services;

use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Lib\Exceptions\NeuInvalidActivityException;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Org\BoxLocationHistory;
use NeubusSrm\Repositories\BoxLocationHistoryRepository;
use NeubusSrm\Repositories\BoxRepository;

/**
 * Class BoxService
 * @package NeubusSrm\Services
 */
class BoxService extends NeuSrmService
{
    /**
     * @var BoxLocationHistoryRepository
     */
    private $boxLocationHistoryRepository;

    /**
     * @var BoxRepository
     */
    private $boxRepository;

    /**
     * BoxService constructor.
     * @param BoxLocationHistoryRepository $boxLocationHistoryRepository
     * @param BoxRepository $boxRepository
     */
    public function __construct(BoxLocationHistoryRepository $boxLocationHistoryRepository, BoxRepository $boxRepository) {
        $this->boxLocationHistoryRepository = $boxLocationHistoryRepository;
        $this->boxRepository = $boxRepository;
    }

    /**
     * @param array $request
     * @return string
     * @throws \NeubusSrm\Lib\Exceptions\NeuDataStoreException
     * @throws \Throwable|NeuEntityNotFoundException
     */
    public function createBoxLocation(array $request): string {
        $boxId = $this->boxRepository->getBoxIdByBoxName($request['box_name']);
        $params = [
            'user_id' => \Auth::id(),
            'box_id' => $boxId,
            'activity' => $request['activity'],
            'location' => $request['location']
        ];

        $this->locationAction($boxId, $request['activity'], $request['location']);
        $boxHistory = $this->boxLocationHistoryRepository->addNewBoxLocationHistoryEntity($params);
        $this->boxLocationHistoryRepository->updatedBy($boxId);
        return $boxHistory;
    }

    /**
     * @param int $boxId
     * @param string $activity
     * @param string $location
     * @throws NeuInvalidActivityException|\Throwable
     */
    private function locationAction(int $boxId, string $activity, string $location) : void {
        $errorMsg = 'The specified box could not be updated';
        switch ($activity) {
            case BoxLocationHistory::CHECKOUT_ACTIVITY:
                $activityResult = $this->boxRepository->clearLocation($boxId);
                throw_unless($activityResult,
                    NeuInvalidActivityException::class, $errorMsg);
                break;
            case BoxLocationHistory::CHECKIN_ACTIVITY:
                $activityResult = $this->boxRepository->setLocation($boxId, $location);
                throw_unless($activityResult,
                    NeuInvalidActivityException::class, $errorMsg);
                break;
            default:
                throw new NeuInvalidActivityException($errorMsg);
        }
    }
}
