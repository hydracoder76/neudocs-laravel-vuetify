<?php
/**
 * Company: aho
 * Date: 11/12/18
 * Time: 2:15 PM
 */

namespace NeubusSrm\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use NeubusSrm\Lib\Exceptions\NeuCompanyNotFoundException;
use NeubusSrm\Lib\Exceptions\NeuDataStoreException;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Lib\Wrappers\Collections\CompaniesCollection;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;


/**
 * Class CompanyRepository
 * @package NeubusSrm\Repositories
 */
class CompanyRepository implements NeuSrmRepository
{
    /**
     * @var array
     */
    const SEARCH_ARR = ['company_name' => ['type' => 'nojoin', 'col' => 'company_name']];

    /**
     * @return string
     */
    public function getModelClass(): string {
        return Company::class;
    }

	/**
	 * @param string $name
	 * @return Company
	 * @throws \Throwable
	 */
    public function getCompanyByName(string $name) : Company{
        $company = Company::whereCompanyName($name)->first();
        throw_if($company == null,
            NeuCompanyNotFoundException::class, 'No company found for this name');
        return $company;
    }

	/**
	 * @param string $companyId
	 * @return Collection
	 * @throws \Throwable
	 */
    public function getCompaniesByCompanyId(string $companyId) : CompaniesCollection {

    	// TODO: company relation itself may not be needed
	    $companys = Company::whereCompanyId($companyId)->get();
	    throw_if($companys == null,
		    NeuCompanyNotFoundException::class, 'No companies exist for this company');
	    $companysColl = collect($companys);


	    return new CompaniesCollection($companysColl);
    }

	/**
	 * @return Collection
	 * @throws \Throwable
	 */
    public function getAllCompanies() : CompaniesCollection {
        $role = Auth::user()->role;
        if ($role == User::ROLE_ADMIN) {
            $companyId = Auth::user()->company_id;
            $companyQuery = Company::where('id', $companyId);
            $companies = $companyQuery->get();
            return $companies;
        }
        $companies =  Company::all();
        return $companies;
    }

	/**
	 * @param int $numToReturn
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Collection|Company[]
	 */
    public function getCompanies($numToReturn = 25) : LengthAwarePaginator {
            $role = Auth::user()->role;
            if ($role == User::ROLE_ADMIN) {
                $companyId = Auth::user()->company_id;
                $companyQuery = Company::where('id', $companyId);
                $companies = $companyQuery->paginate($numToReturn);
                return $companies;
            }
            return Company::paginate($numToReturn);
    }

	/**
	 * @param array $attributes
	 * @return Company
	 * @throws NeuDataStoreException
	 */
    public function createCompany(array $attributes) : Company
    {

	    try {
		    return Company::create($attributes);
	    }
	    catch (\Exception $exception) {
	    	throw new NeuDataStoreException('Unable to create new company, 
	    	please try again or contact an administrator');
	    }

    }

	/**
	 * @param string $id
	 * @return Company
	 */
    public function findCompany(string $id):Company
    {
        return Company::find($id);
    }

	/**
	 * @param string $id
	 * @param array $attributes
	 */
    public function updateCompany(string $id,array $attributes) : void
    {
        Company::where('id','=',$id)->update($attributes);
    }

    /**
     * @param string $id
     * @throws \Exception
     */
    public function deleteCompany(string $id)
    {
        try {
            Company::whereId($id)->delete();
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            throw new NeuEntityNotFoundException('No company exists with that id');
        }
    }

    /**
     * @param string $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchQuery(string $keyword) : Builder
    {
        $query = Company::query();
        if ($keyword != null && $keyword != '') {
            foreach (self::SEARCH_ARR as $key => $arr) {
                    $query = $query->orWhere($arr['col'], 'ilike', '%' . $keyword . '%');
            }
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param string $sortBy
     * @param string $order
     * @return Builder
     */
    public function orderQuery(Builder $query, string $sortBy, string $order) : Builder {
        if ($sortBy !== null && $sortBy != ''){
            $arr = self::SEARCH_ARR[$sortBy];
            $query = $query->orderBy($arr['col'], $order);
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @return array
     * @throws \Throwable
     */
    public function companySearch(Builder $query) : array {
        $results = $query->paginate(25);
        neu_throw_if($results == null || $results->isEmpty(),
            NeuEntityNotFoundException::class, 'There are no companies for this query');
        return ['result' => $results->getCollection(), 'total' => $results->total()];
    }
}