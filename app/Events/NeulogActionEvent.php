<?php

namespace NeubusSrm\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use NeubusSrm\Lib\Traits\LogSimple;

/**
 * Class NeulogActionEvent
 * @package NeubusSrm\Events
 */
class NeulogActionEvent extends NeuloggerEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels, LogSimple;

    /**
     * @var string
     */
    protected $actionName;

    /**
     * Create a new event instance.
     *
     * @param string $actionName
     * @param Collection $params
     * @return void
     */
    public function __construct(string $actionName, Collection $params = null)
    {
        parent::__construct();

        $logItemArr = [
            'fields' => $this->getParamPayload($params)
        ];
        $this->actionName = $actionName;
        $this->companyId = \Auth::user()->company_id;
        $this->addLogDetail(json_encode($logItemArr));
    }

    /**
     * @param Collection $params
     * @return array
     */
    private function getParamPayload(?Collection $params) : array {
        $paramArr = [];
        if ($params !== null) {
            $paramArr = $params->flatMap(static function($param) {
                return [$param['name'] => sprintf(ucfirst($param['name']) . ': %s' , $param['value'])];
            });
        }
        return $paramArr->toArray();
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

    /**
     * @return array
     */
    public function getPacket() : array {
        return $this->getEventLogPacket();
    }

    /**
     * @return string
     */
    public function getActionName() : string {
        return $this->actionName;
    }
}
