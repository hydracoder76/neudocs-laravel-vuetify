<?php

namespace NeubusSrm\Console\Commands;

use Illuminate\Console\Command;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Models\Indexing\Part;

/**
 * Class CreateIndexValue
 * @package NeubusSrm\Console\Commands
 */
class CreateIndexValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neusrm:index-value';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an index value for a given part and type of index';



	/**
	 * CreateIndexValue constructor.
	 */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Now create an index value');
	    if (Box::count() == 0) {
		    $this->info('No boxes found, creating...');
		    $this->call('neusrm:box');
	    }

	    $boxes = Box::all(['id', 'box_name']);
	    $selectedBoxName = $this->choice('What box does this belong to?', array_pluck($boxes, 'box_name'));
	    $selectedBox = $boxes->keyBy('box_name')->get($selectedBoxName);


	    $fullBox = Box::whereId($selectedBox->id)->first();

	    $boxId = $fullBox->id;
        $parts = Part::whereBoxId($boxId)->get(['id', 'part_name']);
	    if (Part::count() == 0 || $parts->count() == 0) {
	    	$this->info('No parts found, creating...');
	    	$this->call('neusrm:part');
	    }
        $parts = Part::whereBoxId($boxId)->get(['id', 'part_name']);
	    $selectedPartName = $this->choice('What part does this value belong to?', array_pluck($parts, 'part_name'));
	    // gets around a quirk in the Collection object using native arrays, since numeric values are considered indexes
	    // via casting
        $something = $parts->keyBy('part_name')->get($selectedPartName);
	    $jsonCast = $something->toJson();
	    $selectedPart = json_decode($jsonCast);
	    $partId = $selectedPart->id;
	    $partIndexValue = $this->ask('What is the value to be used for this index?');
	    if (IndexType::count() == 0) {
	    	$this->info('No index types found, creating...');
	    	$this->call('neusrm:index-type');
	    }
	    $indexTypes = IndexType::all(['id', 'index_label']);
	    $selectedIndexTypeName = $this->choice('What index type does this value use?',
		    array_pluck($indexTypes, 'index_label'));
	    $selectedIndexType = $indexTypes->keyBy('index_label')->get($selectedIndexTypeName);
	    $indexTypeId = $selectedIndexType->id;
	    $indexLocationCode = $this->ask('What is the location code of this index if needed?');

	    $params = [
		    'part_index_value' => $partIndexValue,
		    'index_location_code' => $indexLocationCode,
		    'index_type_id' => $indexTypeId,
		    'box_id' => $boxId,
		    'part_id' => $partId
	    ];
	    \DB::insert('insert into part_indexes (part_index_value, index_location_code, index_type_id, box_id, part_Id) 
			values (:part_index_value, :index_location_code, :index_type_id, :box_id, :part_id)', $params);

	    $this->info('Part Index value created');
	    $this->warn('There is a field in use that will be partitioned, this command may need to be changed later');


    }
}
