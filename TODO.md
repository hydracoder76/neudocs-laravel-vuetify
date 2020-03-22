# NeuSRM TODO Items

### What are these?

Todo items ar marked as // TODO: in the code base so that they can easily be searched out for future
development and use. The purpose of this document is to keep track of these items as they are today,
and should be updated regularly, perhaps on a sprint by sprint basis.

### TODOs

| Filename | line # | TODO
|:------|:------:|:------
| app/Console/Commands/CreateProject.php | 73 | add a unique attribute to the company name? Need to use relational stuff honestly
| app/Console/Commands/CreateUser.php | 63 | this can be condensed
| app/Http/Controllers/Admin/AdminController.php | 49 | all routes can be bootstrapped in the constructor using view share
| app/Http/Controllers/Requests/TodoController.php | 47 | when viewUpload is called, grab the pre selected requests available to display
| app/Http/Controllers/Requests/TodoController.php | 48 | will need a formatter
| app/Http/Controllers/api/v1/ApiController.php | 20 | switch out response for constant when available
| app/Http/Controllers/api/v1/General/FileApiController.php | 54 | this will be handled by the global handler
| app/Http/Controllers/api/v1/Indexing/IndexesApiController.php | 53 | return a master list of projects, likely for company admins who need to see all projects and not
| app/Http/Controllers/api/v1/Requests/DataEntryApiController.php | 37 | generalize this to return the correct data contextually, so that the todo
| app/Http/Controllers/api/v1/Requests/RequestApiController.php | 58 | generalize this to return the correct data contextually, so that the todo
| app/Lib/Builders/Menu/Impl/BaseMenuBuilder.php | 48 | Implement build() method.
| app/Lib/Builders/Menu/Impl/BaseMenuBuilder.php | 58 | Implement addMenuNode() method.
| app/Lib/Builders/Menu/Impl/BaseMenuBuilder.php | 67 | Implement setMenuItemRoot() method.
| app/Lib/Builders/Menu/Impl/BaseMenuBuilder.php | 74 | Implement buildMenuTree() method.
| app/Lib/Builders/Menu/Impl/BaseMenuNode.php | 19 | Implement addAttribute() method.
| app/Lib/Builders/Menu/Impl/BaseMenuNode.php | 23 | Implement addDirectChild() method.
| app/Lib/Builders/Menu/Impl/BaseMenuNode.php | 27 | Implement createEmptyNode() method.
| app/Lib/Wrappers/Database/Partitions/QueriesPartitions.php | 55 | commented out methods will be moved to the driver interface
| app/Providers/AppServiceProvider.php | 107 | proof of concept
| app/Repositories/CompanyRepository.php | 49 | company relation itself may not be needed
| app/Repositories/CompanyRepository.php | 70 | use the newCollections override in the model itself
| app/Repositories/PartRepository.php | 57 | use newCollection and verify that it works with no errors
| app/Repositories/ProjectRepository.php | 51 | company relation itself may not be needed
| app/Repositories/UserRepository.php | 52 | fix relationship saving
| app/Repositories/UserRepository.php | 130 | company relation itself may not be needed
| resources/js/components/Menu.vue | All | The entire component needs to be refactor from the ground up. It's not using any Vue constructs that it should be using, such as event handlers
| check all components | N/A | ensure that components that need data are agnostic as to where the data comes from |



## Meta

### Version 1 Modified 02/06/2018 

### Authors 
 - [Mike Lawson](mailto:mlawson@neuone.com)