<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class NeuSrmTest
 * @package NeubusSrm\Console\Commands
 */
class NeuSrmTest extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'neusrm:test
	 {testpath="" : A specific testcase to run for any phpunit based test runner}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'This command runs the full testing suite for NeuSRM';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {

		if (file_exists('.env.dusk.testing')) {
			unlink('.env.dusk.testing');
		}

		$testCasePath = $this->argument('testpath') ?? '';

		$this->info('Time to test! I hope you set the environment right!!');
		$this->info('Checking for test config');
		if (file_exists('.env.testing') && $this->laravel->environment() === 'testing') {

			copy('.env.testing', '.env.dusk.testing');
			$this->call('config:cache');

			$testToRun = $this->choice('Which tests would you like to run?',
				['Dusk', 'Unit', 'JS', 'Server', 'FrontEnd', 'All', 'None'], 'All');

			$this->info('Making sure database is up to date');
			$this->call('migrate:fresh', ['--env=dusk.testing']);

			switch ($testToRun) {
				case 'Dusk':
					$this->call('dusk', ['--env=testing', $testCasePath]);
					break;
				case 'Unit':
					`phpunit $testCasePath`;
					break;
				case 'JS':
					`npm run test`;
					break;
				case 'Server':
					`phpunit $testCasePath`;
					break;
				case 'FrontEnd':
					$this->call('dusk', ['--env=testing']);
					`npm run test`;
					break;
				case 'All':
					$this->call('dusk', ['--env=testing']);
					`phpunit $testCasePath`;
					`npm run test`;
					break;
				case 'None':
					$this->info('No tests run');
					break;
				default:
					$this->error('No tests available');

			}

			$this->info('Cleaning up...');
			unlink('.env.dusk.testing');
			$this->call('config:clear');
			$this->info('Testing complete!');
		} else {
			$this->error('Could not find valid testing env file, must be in project root,
			 or your environment was not configured right. Did you specify --env=testing ?');

			if (file_exists('.env.dusk.testing')) {
				unlink('.env.dusk.testing');
			}

			exit(1);
		}

	}
}