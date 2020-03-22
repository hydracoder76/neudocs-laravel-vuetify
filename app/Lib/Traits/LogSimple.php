<?php


namespace NeubusSrm\Lib\Traits;

use NeubusSrm\Lib\Logging\Neulogger;

/**
 * This trait tells a log event or any other log class to just fill in the
 * bare minimum attributes of a log via that log's event handler
 * Trait LogSimple
 * @package NeubusSrm\Lib\Traits
 */
trait LogSimple
{
    /**
     * @return array
     */
    public function getEventLogPacket() : array {
        return [
            'user_id' => $this->getUserIdForEvent(),
            'record_id' => $this->getRecordIdForEvent(),
            'operation' => $this->getOperationForEvent(),
            'level' => $this->getLevelForEvent(),
            'message' => $this->getMessageForEvent(),
            'details' => $this->getDetailsForEvent(),
            'ip_address' => $this->getIpForEvent(), // could use the ip() helper, but we want to be able to log arbitrary addresses
            'responsible_table' => $this->getResponsibleTable(),
            'company_id' => $this->getCompanyIdForEvent(),
            'company_name' => $this->getCompanyNameForEvent()
        ];

    }
}
