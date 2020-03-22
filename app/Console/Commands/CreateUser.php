<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;
use OTPHP\TOTP;

/**
 * Class CreateUser
 * @package NeubusSrm\Console\Commands
 */
class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neusrm:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user in the Neubus SRM';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * @var User
     */
    private $user;

    /**
     * CreateUser constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Time to create an srm user...');
        $this->user->name = $this->ask('What is the user\'s name');
        $this->user->email = $this->ask('What is the user\'s email address');
        $this->user->password = bcrypt($this->secret('What is the user\'s password to be'));

        // TODO: this can be condensed
        $companies = Company::all(['company_name','id']);
        if ($companies->count() == 0) {
            $this->error('No companies available');
        }
        $companyId = Company::whereCompanyName($this->choice('What company does this user belong to',
            array_pluck($companies, 'company_name')))->first()->id;
        $this->user->company_id = $companyId;
        $this->user->role = $this->choice('What type of user is this?', ['client', 'admin', 'neubus', 'it']);

        $requiresMfa = config('srm.requires_mfa.'.$this->user->role);
        if ($requiresMfa) {
            $this->user->has_mfa = true;
        }

        $this->user->save();
        $this->info('User saved with id: ' . $this->user->id);
    }
}
