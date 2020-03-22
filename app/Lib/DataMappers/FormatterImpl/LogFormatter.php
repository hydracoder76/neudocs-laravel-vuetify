<?php

namespace NeubusSrm\Lib\DataMappers\FormatterImpl;




use NeubusSrm\Lib\DataMappers\Formatter;
use NeubusSrm\Lib\Exceptions\NeuInvalidWrapperException;
use NeubusSrm\Lib\Wrappers\Collections\SrmLogCollection;
use NeubusSrm\Models\Util\SrmLog;
use Illuminate\Database\Eloquent\Collection;
use Auth;

/**
 * Class LogFormatter
 * @package NeubusSrm\Lib\DataMappers\FormatterImpl
 */
class LogFormatter implements Formatter
{

    /**
     * @param Collection $data
     * @param int $mode
     * @return array
     * @throws NeuInvalidWrapperException
     */
    public function format(Collection $data, int $mode): array {
        switch ($mode) {
            case ($mode == Formatter::MODE_LOG) && $data->whereInstanceOf(SrmLogCollection::class):
                return $this->logFormat($data);
            default:
                throw new NeuInvalidWrapperException('No wrapper found for this collection type');
        }
    }

    /**
     * @param SrmLogCollection $logs
     * @return array
     */
    protected function logFormat(SrmLogCollection $logs) : array {
        return $logs->map(function (SrmLog $log){
            $user = $log->user()->withTrashed()->first();
            $userName = $user ? $user->name : '';
            $companyName = isset($log->company) ? $log->company->company_name : '';
            $time = isset($log->created_at) ? $log->created_at->format('Y-m-d g:i A') : '';
            return ['user' => $userName,
                'company' => $companyName,
                'time' => $time,
                'operation' => $log->operation, 'level' => $log->level, 'message' => $log->message,
                'details' => json_decode($log->details), 'ip_address' => $log->ip_address];
        })->toArray();
    }
}