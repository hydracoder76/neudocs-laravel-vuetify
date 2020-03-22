<?php

namespace NeubusSrm\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use NeubusSrm\Lib\Logging\NeuLoggableDetails;
use NeubusSrm\Lib\Traits\LogSimple;

/**
 * Class NeulogModelEvent
 * @package NeubusSrm\Events\
 */
class NeulogModelEvent extends NeuloggerEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels, LogSimple;

    /**
     * @var NeuLoggableDetails
     */
    protected $neuLoggableDetails;

    /**
     * NeulogModelEvent constructor.
     * @param NeuLoggableDetails $neuLoggableDetails
     * @param Collection $params
     * @param string $actionType
     * @param string $level
     */
    public function __construct(NeuLoggableDetails $neuLoggableDetails, Collection $params, $actionType = 'create', $level = 'info')
    {
        parent::__construct();
        $details = $neuLoggableDetails->getDetailsForNeuLog($params);
        // we need to get a little clever to pull out the responsible table
        $detailArr = json_decode($details, true);

        $this->responsibleTable = $detailArr['responsible_table'];
        $this->recordId = $detailArr['record_id'];
        $this->message = $detailArr['message'];
        $this->companyId = $detailArr['company_id'] ?? '';
        $this->companyName = $detailArr['company_name'] ?? '';
        $this->operation = $actionType;
        $this->level = $level;
        if ($this->userId === '') {
            $this->userId = $params->get('user_id');
        }

        $detailArr = $this->removeUneededData($detailArr);

        $this->addLogDetail(json_encode($detailArr));
    }

    /**
     * @param array $detailArr
     * @return array
     */
    private function removeUneededData(array $detailArr) : array {
        $copy = $detailArr;
        unset($copy['record_id'], $copy['responsible_table'], $copy['message']);
        return $copy;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
