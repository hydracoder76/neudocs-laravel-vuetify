<?php

namespace NeubusSrm\Listeners;

use NeubusSrm\Events\NeulogActionEvent;
use NeubusSrm\Lib\Logging\Neulogger;
use Neulog;

/**
 * Class NeulogActionListener
 * @package NeubusSrm\Listeners
 */
class NeulogActionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param NeulogActionEvent $event
     * @throws \ReflectionException
     */
    public function handle(NeulogActionEvent $event)
    {
        $logItems = collect($event->getEventLogPacket());
        Neulog::write($event->getActionName(), $logItems, Neulogger::LEVEL_INFO);
    }
}
