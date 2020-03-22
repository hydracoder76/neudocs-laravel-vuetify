<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Index;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Indexing\IndexType;

/**
 * Class CreateIndexType
 * @package NeubusSrm\Console\Commands
 */
class CreateIndexType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neusrm:index-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an index type in the database';

	/**
	 * @var IndexType
	 */
    private $indexType;

	/**
	 * CreateIndexType constructor.
	 * @param IndexType $indexType
	 */
    public function __construct(IndexType $indexType)
    {
        parent::__construct();
        $this->indexType = $indexType;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Now creating an index type, these can be used to actually build indexes');

	    $this->indexType->index_type_name = $this->choice('Index type name?',
		    ['date', 'date_range', 'email', 'multi_select', 'numeric',
		    'single_select', 'text', 'text_area']);
	    $this->indexType->index_label = $this->ask('What label or display name would you like to use?');
	    $this->indexType->index_description = $this->ask('Any description?');
	    $validationChoice = $this->confirm('Is there any validation associated with this type?', false);
	    if ($validationChoice) {
	    	$this->indexType->has_validation = true;
	    	$this->indexType->validation_regex = $this->ask('Enter a regular expression to validate this field');
	    	$this->indexType->validation_rule_class_name = $this->ask('If needed, enter the fully qualified
	    	 namespace of a class you need for further validation of this field');
	    }
	    $createdBy = User::all();
	    if ($createdBy->count() == 0) {
	    	$this->info('No users found, creating...');
	    	$this->call('neusrm:user');
	    }

	    $createdBy = User::first();
	    $this->indexType->created_by = $createdBy->id;
	    $this->indexType->save();

	    $this->info('Index type created with id: ' . $this->indexType->id);

    }
}
