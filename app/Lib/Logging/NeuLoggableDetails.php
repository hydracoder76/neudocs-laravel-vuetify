<?php


namespace NeubusSrm\Lib\Logging;

use Illuminate\Support\Collection;

/**
 * Anything that implements this interface will allow a client to specific in detail what the "details"
 * to be capture are
 * Interface NeuLoggableDetails
 * @package NeubusSrm\Lib\Logging
 */
interface NeuLoggableDetails
{

    /**
     * Return the details for a given event to be display.
     * As the details are highly contextual, it's up to anything
     * that implements this interface to report the details.
     * This is mainly to be used inside models to reflect
     * what should be logged, as most logging is the result
     * of a model change
     *
     * The arguments provided are optional and can be used
     * for detail strings which have sprintf valid placeholders
     * @param Collection $arguments
     * @return string
     */
    public function getDetailsForNeuLog(Collection $arguments) : string;

}
