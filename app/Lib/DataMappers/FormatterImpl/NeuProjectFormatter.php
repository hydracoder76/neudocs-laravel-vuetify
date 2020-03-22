<?php
/**
 * User: aho
 * Date: 2019-02-19
 * Time: 11:29
 */

namespace NeubusSrm\Lib\DataMappers\FormatterImpl;



use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use NeubusSrm\Lib\DataMappers\Formatter;
use NeubusSrm\Lib\Exceptions\NeuInvalidWrapperException;
use NeubusSrm\Lib\Wrappers\Collections\RequestPartsCollection;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Models\Relational\RequestPart;
use Prophecy\Exception\Doubler\ClassNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Auth;
use NeubusSrm\Lib\Wrappers\Collections\ProjectsCollection;
use NeubusSrm\Models\Org\Project;

/**
 * Class RequestFormatter
 * @package NeubusSrm\Lib\DataMappers\FormatterImpl
 */
class NeuProjectFormatter implements Formatter
{

    /**
     * @param Collection $data
     * @param int $mode
     * @return array
     * @throws NeuInvalidWrapperException
     */
    public function format(Collection $data, int $mode): array {
        switch ($mode) {
            case Formatter::MODE_PROJECTMANAGEMENT && $data->whereInstanceOf(ProjectsCollection::class):
                return $this->formatForProject($data);
            default:
                throw new NeuInvalidWrapperException('No wrapper found for this collection type');
        }
    }

    /**
     * @param ProjectsCollection $projectsCollection
     * @return array
     */
    protected function formatForProject(ProjectsCollection $projectsCollection) : array {
        $coll = $projectsCollection->map(function(Project $project) {
            $projectCompany = isset($project->company) ? $project->company->company_name : '';
            $projectOwner = isset($project->owner) ? $project->owner->name : '';
            $projectMediaTypes = '';
            $projectMediaTypeIds = [];                
            
            foreach ($project->mediaTypes as $item){
                $projectMediaTypeIds[] = $item->id;
                $projectMediaTypes .= isset($item->id) ? $item->type . '  ' : '';
            }            

            return ['id' => $project->id,'project_name'=>$project->project_name, 'project_description'=>$project->project_description, 'company_id'=>$project->company_id,
                'company_name'=>$projectCompany, 'project_owner_id'=>$project->project_owner_id,'project_owner_name'=>$projectOwner,
                'type' => $projectMediaTypes,  'media_type_id' =>$projectMediaTypeIds];
        });

        return $coll->toArray();
    }

}