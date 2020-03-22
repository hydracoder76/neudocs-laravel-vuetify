<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-21
 * Time: 18:53
 */

namespace NeubusSrm\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Lib\Logging\Neulogger;
use NeubusSrm\Lib\Traits\WorksWithModels;
use NeubusSrm\Lib\Wrappers\Collections\SrmLogCollection;
use NeubusSrm\Models\Util\SrmLog;
use NeubusSrm\Models\Auth\User;

/**
 * Class SrmLogRepository
 * @package NeubusSrm\Repositories
 */
class SrmLogRepository implements NeuSrmRepository, Neulogger
{

    use WorksWithModels;

    /**
     * @var array
     */
    const SEARCH_ARR = ['user' => ['type' => 'join', 'table' => 'users', 'col' => 'name', 'relation' => 'user',
        'foreignKey' => 'users.id', 'localKey' => 'srm_logs.user_id'],
        'company' => ['type' => 'join', 'table' => 'companies', 'col' => 'company_name', 'relation' => 'company',
            'foreignKey' => 'companies.id', 'localKey' => 'srm_logs.company_id'],
        'time' => ['type' => 'nojoin', 'col' => 'created_at'],
        'operation' => ['type' => 'nojoin', 'col' => 'operation'],
        'level' => ['type' => 'nojoin', 'col' => 'level'],
        'ip_address' => ['type' => 'nojoin', 'col' => 'ip_address']];

    /**
     * @return string
     */
    public function getModelClass() : string {
        return SrmLog::class;
    }

    /**
     * @param string $message
     * @param Collection $toBeLogged
     * @param string $level
     * @param array $options
     */
    public function log(string $message, Collection $toBeLogged, string $level, array $options = []) : void {
        $companyId = $toBeLogged->get('company_id', null);
        if ($companyId == ''){
            $companyId = null;
        }
        $logItem = [
            'user_id' => $toBeLogged->get('user_id') ?? \Auth::id(),
            'record_id' => $toBeLogged->get('record_id'),
            'operation' => $toBeLogged->get('operation'),
            'level' => $level ?? Neulogger::LEVEL_INFO,
            'message' => $message,
            'details' => $toBeLogged->get('details'),
            'route_name' => $toBeLogged->get('route_name', ''),
            'route_method_name' => $toBeLogged->get('route_method_name', ''),
            'ip_address' => $toBeLogged->get('ip_address'), // could use the ip() helper, but we want to be able to log arbitrary addresses,
            'responsible_table' => $toBeLogged->get('responsible_table', ''),
            'company_id' => $companyId
        ];
        SrmLog::create($logItem);
    }

    /**
     * @param string $userId
     * @return SrmLogCollection
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     */
    public function getLogsForUser(string $userId) : SrmLogCollection {
        $results = SrmLog::whereUserId($userId)->get();
        neu_throw_if($results === null || $results->count() === 0,
            NeuEntityNotFoundException::class, 'There are no logs available for this user');
        return $results;
    }

    /**
     * @param string $projectId
     * @return SrmLogCollection
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     */
    public function getLogsForProject(string $projectId) : SrmLogCollection {
        $results = SrmLog::whereProjectId($projectId)->get();
        neu_throw_if($results === null || $results->count() === 0,
            NeuEntityNotFoundException::class, 'There are no logs available for this project');
        return $results;
    }

    /**
     * used by facade
     * @return string
     */
    public function getStorageItemClassName(): string {
        return $this->getModelClass();
    }

    /**
     * used by facade
     * @param $key
     * @param $value
     * @return mixed
     */
    public function getLogItemByKey($key, $value = null) : SrmLogCollection {
        return SrmLog::where($key, '=', $value)->get();
    }

    /**
     * @return Builder
     */
    public function logQuery() : Builder {
        return SrmLog::query();
    }

    /**
     * @param Builder $query
     * @param string $dateFirst
     * @param string $dateSecond
     * @return Builder
     */
    public function logGetDate(Builder $query, string $dateFrom, string $dateTo) : Builder {
        if (($dateFrom != null && $dateFrom != '') || ($dateTo != null && $dateTo != '')) {
            $query = $query->where(function ($subQuery) use ($dateFrom, $dateTo) {
                $subQuery->where('srm_logs.created_at', '>=', $dateFrom)->where('srm_logs.created_at', '<=', $dateTo);
            });
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param string $companyId
     * @return Builder
     */
    public function logGetPermission(Builder $query) : Builder {
        $user = Auth::user();
        if ($user->role != 'it') {
            $companyId = $user->company_id;
            $query = $query->where('responsible_table', '!=', 'boxes')->where('responsible_table', '!=', 'parts')
                ->where('responsible_table', '!=', 'part_indexes')->where('responsible_table', '!=', 'file_uploads')
                ->where('message', '!=', 'Request Rejected')
                ->where('message', '!=', 'Request Reopened');
            $query = $query->where(function ($subQuery) use ($companyId) {
                $subQuery->where('company_id', $companyId)->orWhereNull('company_id');
            });
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param string $sortBy
     * @param string $order
     * @return Builder
     */
    public function logSortBy(Builder $query, string $sortBy, string $order) : Builder {
        if ($sortBy != null && $sortBy != ''){
            $arr = self::SEARCH_ARR[$sortBy];
            if ($arr['type'] == 'nojoin'){
                $query = $query->orderBy($arr['col'], $order);
            }
            else {
                $query = $query->leftJoin($arr['table'], $arr['foreignKey'], '=', $arr['localKey'])
                    ->orderBy($arr['table'] . '.' . $arr['col'], $order);
            }
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @return array
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     */
    public function logSearch(Builder $query) : array {
        $role = Auth::user()->role;
        if ($role === User::ROLE_ADMIN){
            $logs = $query->select('srm_logs.*')->where('responsible_table','!=', 'index_types')->paginate(25);
        }else {
            $logs = $query->select('srm_logs.*')->paginate(25);
        }
        neu_throw_if($logs == null || $logs->total() == 0, NeuEntityNotFoundException::class, 'No logs found');
        $logs->load('company', 'user');
        return ['results' => $logs->getCollection(), 'total' => $logs->total()];
    }
}
