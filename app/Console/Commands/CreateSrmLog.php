<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;
use NeubusSrm\Lib\Logging\Neulogger;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Util\SrmLog;

/**
 * Class CreateSrmLog
 * @package NeubusSrm\Console\Commands
 */
class CreateSrmLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neusrm:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a single log entry to the database for testing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $userList = User::all(['id', 'email']);
        $user = $this->choice('Who caused this action?', array_pluck($userList, 'email'));
        $recordYesNo = $this->confirm('Was this action associated with a particular record?');
        $recordId = null;
        if ($recordYesNo) {
            $recordId = $this->ask('Please enter the id (or something similar) for that records');
        }
        $message = $this->ask('Enter any specific message for this entry');
        $details = $this->ask('Are there any specific details, such as "box was entered" or "part was uploaded"?');
        $ipAddress = '0.0.0.0';
        $srmLog = new SrmLog();
        $srmLog->user_id = $userList->keyBy('email')->get($user)->id;
        $srmLog->record_id = $recordId;
        $srmLog->level = array_random(['info', 'debug', 'error', 'warn']);
        $srmLog->message = $message ?? '';
        $srmLog->details = $details ?? '';
        $srmLog->ip_address = $ipAddress;
        $srmLog->save();
        $this->info('Log entry saved with id: ' . $srmLog->id);
    }
}
