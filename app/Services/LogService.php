<?php

namespace NeubusSrm\Services;

use NeubusSrm\Lib\DataMappers\Formatter;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Lib\Wrappers\Collections\LogsCollection;
use Carbon\Carbon;
use Crypt, Auth;
use NeubusSrm\Repositories\SrmLogRepository;


/**
 * Class LogService
 * @package NeubusSrm\Services
 */
class LogService extends NeuSrmService
{
    /**
     * @var SrmLogRepository
     */
    protected $logRepo;

    /**
     * @var Formatter
     */
    protected $formatter;

    /**
     * LogService constructor.
     * @param LogRepository $logRepo
     */
    public function __construct(SrmLogRepository $logRepo, Formatter $formatter) {
        $this->logRepo = $logRepo;
        $this->formatter = $formatter;
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @param string $sortBy
     * @param string $order
     * @return array
     * @throws NeuSrmException
     */
    public function logSearch(string $dateFrom, string $dateTo, string $sortBy, string $order) : array {
        $query = $this->logRepo->logQuery();
        $query = $this->logRepo->logGetDate($query, $dateFrom, $dateTo);
        $query = $this->logRepo->logGetPermission($query);
        $query = $this->logRepo->logSortBy($query, $sortBy, $order);
        $results = $this->logRepo->logSearch($query);
        $logResults = $this->formatter->format($results['results'], Formatter::MODE_LOG);
        return ['results' => $logResults, 'total' => $results['total']];
    }
}
