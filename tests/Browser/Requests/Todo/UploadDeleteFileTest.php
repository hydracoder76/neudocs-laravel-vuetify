<?php

namespace Tests\Browser\Requests\Todo;

use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Relational\RequestPart;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Models\Indexing\FileUpload;
use DB;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

/**
 * Class UploadDeleteFileTest
 * @package Tests\Browser\Requests\Todo
 */
class UploadDeleteFileTest extends DuskTestCase
{
    /**
     * @var
     */
    protected $user;
    protected $company;


    public function setUp() {
        parent::setUp();
        $this->user = $this->company();
        $this->requests();
    }

    public function tearDown() {
        parent::tearDown();
    }

    /**
     * @return mixed
     */
    public function company() {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->create([
            'role'=>User::ROLE_IT,
            'company_id'=>$company->id,
            'password' => bcrypt(parent::USER_PASSWORD)
        ]);
        $project = factory(Project::class)->create(['company_id'=>$company->id]);
            $indexType = factory(IndexType::class)->create(['project_id'=>$project->id, 'created_by' => $user->id]);
            $box = factory(Box::class)->create(['company_id'=>$project->company_id, 'project_id'=>$project->id,
                'created_by'=> $user->id, 'updated_by'=>$user->id]);
            $part = factory(Part::class)->create(['box_id'=>$box->id,'project_id'=>$project->id,
                'created_by'=> $user->id, 'last_updated_by'=>$user->id]);
                    DB::table('part_indexes')->insert([
                        'part_id' => $part->id,
                        'index_type_id' => $indexType->id,
                        'box_id' => $box->id,
                        'part_index_value' => chr(rand(97, 122))
                    ]);
        $this->company = $company;

        $fileData = [
            'true_file_name' => 'TestDocument1.txt',
            'hashed_file_name' => 'dev8N89ffWD3MxiECwmhZd7rLG60DJilHFS8hRVT.txt',
            'file_mime' => 'text/plain',
            'current_fs_location' => '0aa1c8a3c52c/2c/74454/dev8N89ffWD3MxiECwmhZd7rLG60DJilHFS8hRVT.txt',
            'is_scanned' => 'f'
        ];

        factory(FileUpload::class)->create([
            'box_number' => $box->box_name,
            'part_name' => $part->part_name,
            'part_id' => $part->id,
            'uploaded_by' => $user->id,
            'true_file_name' => $fileData['true_file_name'],
            'hashed_file_name' => $fileData['hashed_file_name'],
            'file_mime' => $fileData['file_mime'],
            'current_fs_location' => $fileData['current_fs_location'],
            'is_scanned' => $fileData['is_scanned']
        ]);

        return $user;
    }

    public function requests() {
        $user = $this->user;
        Box::all()->each(function($box) use ($user){
            $request = factory(Request::class)->create(['company_id'=>$box->company_id,
                'project_id'=>$box->project_id, 'is_fulfilled' => false, 'is_in_process' => false]);
            foreach($box->parts as $part){
                $indexes = '';
                foreach($part->indexes as $index){
                    $indexes .= $index->indexType->index_label . ' ' . $index->part_index_value . ' ';
                }
                $text = $box->project->project_name . ' ' . $request->request_name . ' ' . $indexes . ' ' . $box->box_location_code .
                    $box->box_name . ' ' . $part->part_name;
                $requestPart = factory(RequestPart::class)->create(['request_id_ref'=>$request->id, 'part_id_ref'=>$part->id]);
                \DB::table('request_parts')->where(['request_id_ref'=>$request->id, 'part_id_ref'=>$part->id])->update(['searchtext'=>$text]);}
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUploadPageHasDeleteFileModal() {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('todo.home')
                ->pause(10000)
                ->waitFor('@neu-table')
                ->with('@neu-table', function ($table) {
                    $table->assertSee('Request#')
                        ->check('check-record');
                })
                ->click('@neu-todo-upload-btn')
                ->pause(5000)
                ->assertSee('Upload box files')
                ->assertPresent('@neu-upload-component')
                ->assertPresent('@neu-box-part-list-component')
                ->assertPresent('@neu-part-file-list-component')
                ->pause(10000)
                ->waitFor('@neu-table')
                ->with('@neu-table', function ($table) {
                    $table->assertSee('TestDocument1.txt')
                        ->click('.neu-delete-icon');
                })
                ->assertVisible('@modalDelete')
                ->assertPresent('@deletionReason')
                ->assertPresent('footer .btn-primary')
                ->assertPresent('footer .btn-secondary')
                ->assertPresent('button.close')
                ->with('@modalDelete', function ($modal) {
                    $modal->assertInputValue('@deletionReason', '')
                        ->press('OK')
                        ->waitFor('@statusCRUD')
                        ->assertSeeIn('@statusCRUD', 'Please fill "Reason" field')
                        ->type('@deletionReason', 'Delete because of')
                        ->assertInputValue('@deletionReason', 'Delete because of')
                        ->press('OK');
                })
                ->pause(5000)
                ->with('@neu-table', function ($table) {
                    $table->assertDontSee('TestDocument1.txt');
                });
        });
    }
}
