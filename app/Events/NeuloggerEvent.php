<?php


namespace NeubusSrm\Events;

use Illuminate\Support\Collection;
use NeubusSrm\Lib\Logging\Neulogger;

/**
 * This class serves as a sort of default for Neulogger events to extend from
 * to easily create the data sets necessary to send to the Neulogger facade
 * Class NeuloggerEvent
 * @package NeubusSrm\Events
 */
abstract class NeuloggerEvent
{

    /**
     * @var string
     */
    protected $eventIp = '';

    /**
     * @var string
     */
    protected $userId = '';

    /**
     * @var string
     */
    protected $operation = Neulogger::OP_UPDATE;

    /**
     * @var string
     */
    protected $recordId = '';

    /**
     * @var string
     */
    protected $level = Neulogger::LEVEL_INFO;

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var Collection
     */
    protected $details;

    /**
     * @var string
     */
    protected $responsibleTable = '';

    /**
     * @var string
     */
    protected $companyId = '';

    /**
     * @var string
     */
    protected $companyName = '';

    /**
     * NeuloggerEvent constructor.
     */
    public function __construct() {
        $this->seedDefaults();
    }

    /**
     * Just need to set defaults for any instance variables so
     * that if they aren't overridden the are no issues.
     */
    private function seedDefaults() : void {
        $this->eventIp = request()->ip();

        $this->userId = \Auth::check() ? \Auth::id() : '';

        $this->details = collect([]);
    }

    /**
     * @return string
     */
    public function getIpForEvent() : string {
        return $this->eventIp;
    }

    /**
     * @return string
     */
    public function getUserIdForEvent() : string {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getRecordIdForEvent() : string {
        return $this->recordId;
    }

    /**
     * @return string
     */
    public function getLevelForEvent() : string {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getOperationForEvent() : string {
        return $this->operation;
    }

    /**
     * @return string
     */
    public function getMessageForEvent() : string {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getDetailsForEvent() : string {
        return $this->details->implode(' : ');
    }

    /**
     * @return string
     */
    public function getResponsibleTable() : string {
        return $this->responsibleTable;
    }

    /**
     * @return string
     */
    public function getCompanyIdForEvent() : string{
        return $this->companyId;
    }

    /**
     * @return string
     */
    public function getCompanyNameForEvent() : string {
        return $this->companyName;
    }

    /**
     * @param string $detailString
     */
    public function addLogDetail(string $detailString) : void {
        $this->details->push($detailString);
    }

}
