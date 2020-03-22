<?php
/**
 * User: mlawson
 * Date: 2018-11-30
 * Time: 11:29
 */

namespace NeubusSrm\Lib\DataMappers\FormatterImpl;


use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Lib\DataMappers\Formatter;
use NeubusSrm\Lib\Exceptions\NeuInvalidWrapperException;
use NeubusSrm\Lib\Wrappers\Collections\ProjectsCollection;
use NeubusSrm\Models\Org\Project;
use Prophecy\Exception\Doubler\ClassNotFoundException;

/**
 * Class NeuDropdownFormatter
 * @package NeubusSrm\Lib\DataMappers\FormatterImpl
 */
class NeuDropdownFormatter implements Formatter
{

	/**
	 * @param Collection $data
	 * @param int $mode
	 * @return array
	 * @throws NeuInvalidWrapperException
	 */
	public function format(Collection $data, int $mode): array {

		switch ($mode) {
			case Formatter::MODE_PROJECT && $data->whereInstanceOf(ProjectsCollection::class):
				return $this->formatForProject($data);
            case Formatter::MODE_TODO && $data->whereInstanceOf(ProjectsCollection::class):
                return $this->formatForTodo($data);
			default:
				throw new NeuInvalidWrapperException('No wrapper found for this collection type');
		}

	}

	/**
	 * @param ProjectsCollection|Collection $projectsCollection
	 * @return array
	 */
	protected function formatForProject(ProjectsCollection $projectsCollection) : array {
		$coll = $projectsCollection->map(function(Project $project) {
			return ['label' => $project->project_name, 'value' => $project->id];
		});
		return $coll->toArray();
	}

    protected function formatForTodo(ProjectsCollection $projectsCollection) : array {
        $coll = $projectsCollection->map(function (Project $project) {
            return ['label' => $project->project_name . '-' . $project->project_description, 'value' => $project->id];
        });
        return $coll->toArray();
    }
}