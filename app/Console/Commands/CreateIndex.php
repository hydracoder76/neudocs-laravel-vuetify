<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;
use NeubusSrm\Models\Indexing\Index;
use NeubusSrm\Models\Indexing\IndexType;

/**
 * Class CreateIndex
 * @package NeubusSrm\Console\Commands
 */
class CreateIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neusrm:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new index with specified type';

	/**
	 * @var IndexType
	 */
    private $indexType;

	/**
	 * CreateIndex constructor.
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
        $this->info('Now creating an index');
        $indexTypes = IndexType::all();
        if ($indexTypes->count() == 0) {
        	$this->info('No index types available, please create one');
        	$this->call('neusrm:index-type');

        }
        $indexTypes = IndexType::all(['id', 'index_name']);
        $selectedTypeName = $this->choice('Please select a type', array_pluck($indexTypes, 'index_name'));
        $selectedType = $indexTypes->keyBy('index_name')->get($selectedTypeName);
        $this->indexType->index_type_id = IndexType::whereId($selectedType->id)->first()->id;
        $this->indexType->index_label = $this->ask('What would you like the label to be?');
        $this->indexType->index_internal_name = str_replace(' ', '_', strtolower($this->indexType->index_label));
        $this->indexType->index_description = $this->ask('Enter an option description');
        $this->indexType->is_required = $this->confirm('Is this indexing field required?', false);
        $this->indexType->is_required_double = $this->confirm('Is this indexing field required
         to be entered twice if given?', false);
        $this->indexType->save();
        $this->info('Index created with id: ' . $this->indexType->id);

    }
}
