<?php
/**
 * Company: mlawson
 * Date: 11/12/18
 * Time: 12:30 PM
 */

namespace NeubusSrm\Services;

use Illuminate\Cache\Repository;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Lib\Exceptions\CompanyInvalidCredentialsException;
use NeubusSrm\Lib\Wrappers\Collections\CompaniesCollection;
use NeubusSrm\Repositories\CompanyRepository;
use Crypt, Auth;
use NeubusSrm\Repositories\VerificationTokenRepository;
use Illuminate\Http\Request;
use NeubusSrm\Models\Org\Company;
use Symfony\Component\Debug\Debug;

/**
 * Class CompanyService
 * @package NeubusSrm\Services
 */
class CompanyService extends NeuSrmService
{

	/**
	 * @var CompanyRepository
	 */
	private $companyRepository;

	private $verificationRepo;

	/**
	 * CompanyService constructor.
	 * @param CompanyRepository $companyRepository
	 * @param VerificationTokenRepository $verificationTokenRepository
	 */
	public function __construct(CompanyRepository $companyRepository, VerificationTokenRepository $verificationTokenRepository) {
		$this->companyRepository = $companyRepository;
		$this->verificationRepo = $verificationTokenRepository;
	}


	/**
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Company[]
	 */
    public function index($numToReturn = 25)
    {
        return $this->companyRepository->getCompanies($numToReturn);
    }

    /**
     * @return CompaniesCollection
     * @throws \Throwable
     */
    public function all() : CompaniesCollection {
        return $this->companyRepository->getAllCompanies();
    }

	/**
	 * @param array $request
	 * @return string
	 * @throws \NeubusSrm\Lib\Exceptions\NeuDataStoreException
	 */
    public function createCompany(array $request):string
    {
        $params = [
            'company_name'          => $request['company_name']
        ];

        return $this->companyRepository->createCompany($params);
    }


	/**
	 * @param string $id
	 * @return Company
	 */
    public function read(string $id):Company
    {
        return $this->companyRepository->findCompany($id);
    }

	/**
	 * @param array $request
	 * @param $id
	 */
    public function updateCompany(array $request, $id):void
    {
        $params = [
            'company_name'          => $request['company_name']
        ];
        $this->companyRepository->updateCompany(  $id,$params);

    }

	/**
	 * @param $id
	 */
    public function deleteCompany($id)
    {
        return $this->companyRepository->deleteCompany($id);
    }

    /**
     * @param string $sortBy
     * @param string $order
     * @param string $keyword
     * @return array
     */
    public function companySearch(string $sortBy, string $order, string $keyword) : array {
        $query = $this->companyRepository->searchQuery($keyword);
        $query = $this->companyRepository->orderQuery($query, $sortBy, $order);
        $companies = $this->companyRepository->companySearch($query);
        return $companies;
    }
}
