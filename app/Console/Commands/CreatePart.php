<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\Part;

class CreatePart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neusrm:part';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

	/**
	 * @var Part
	 */
    private $part;

    /**
     * Create a new command instance.
     *
     * @param Part $part
     * @return void
     */
    public function __construct(Part $part)
    {
        parent::__construct();
        $this->part = $part;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Creating part...');
        if (Box::count() == 0) {
        	$this->info('No boxes found, creating...');
        	$this->call('neusrm:box');
        }

        $boxes = Box::all(['id', 'box_name']);
        $selectedBoxName = $this->choice('What box does this belong to?', array_pluck($boxes, 'box_name'));
        $selectedBox = $boxes->keyBy('box_name')->get($selectedBoxName);


        $fullBox = Box::whereId($selectedBox->id)->first();

        $fullBox->load(['project', 'createdBy']);
        $shouldUsePartFactory = $this->confirm('Would you like to use a factory to generate more than 1?');
        if ($shouldUsePartFactory) {
            $numToCreate = (int) $this->ask('How many?');
            factory(Part::class, $numToCreate)->create([
                'box_id' => $selectedBox->id,
                'project_id' => $fullBox->project_id,
                'last_updated_by' => $fullBox->created_by
            ]);
            $this->info('Finished creating parts');
        }
        else {
            $this->part->box_id = $selectedBox->id;
            $this->part->part_name = $this->ask('Part name?');
            $this->part->part_description = $this->ask('Part description?');
            $this->part->part_location_code = $this->ask('Part location code?');
            $users = User::all(['id', 'email']);
            $this->part->project_id = $fullBox->project_id;
            $selectedUser = $this->choice('Who created this part?', array_pluck($users, 'email'));
            $selectedUserInfo = $users->keyBy('email')->get($selectedUser);
            $this->part->created_by = $selectedUserInfo->id;
            $this->part->last_updated_by = $selectedUserInfo->id;
            $this->part->save();
            $this->info('Part created with id: ' . $this->part->id);
        }


    }
}
