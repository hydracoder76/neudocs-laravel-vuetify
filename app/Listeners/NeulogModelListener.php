<?php

namespace NeubusSrm\Listeners;

use NeubusSrm\Events\NeulogModelEvent;
use Neulog;

/**
 * Class NeulogModelListener
 * @package NeubusSrm\Listeners
 */
class NeulogModelListener
{

    /**
     * @param NeulogModelEvent $event
     * @throws \ReflectionException
     */
    public function handle(NeulogModelEvent $event)
    {
        Neulog::write($event->getMessageForEvent(), collect($event->getEventLogPacket()), $event->getLevelForEvent());
    }
}
