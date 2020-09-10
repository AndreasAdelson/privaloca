<?php

namespace App\Application\Service;

use App\Domain\Model\Company;
use App\Infrastructure\Repository\CompanyRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyService
{
    /**
     * @var CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * CompanyRestController constructor.
     */
    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function addCompany(string $nom, string $num_siret)
    {
        $company = new Company();
        $company->setNom($nom);
        $company->setNumSiret($num_siret);
        // $company->setLastNameCostumer($LastNameCostumer);
        // $company->setNomCostumer($NomCostumer);
        // $company->setEmail($Email);
        // $company->setImageProfil($ImageProfil);
        $this->companyRepository->save($company);
    }

    // ///Editer un Company
    // public function editCompany(string $name, string $code, int $companyId)
    // {
    //     $company = $this->companyRepository->findById($companyId);
    //     $company->setNom($name);
    //     $company->setNum_siret($code);

    //     return $company;
    // }
    // public function findOneBy(array $email): ?Company
    // {
    //     return $this->CompanyRepository->findOneBy($email);
    // }
    /// Afficher un Company
    public function getCompany(int $companyId): ?Company
    {
        return $this->companyRepository->find($companyId);
    }

    public function getAllCompanys(): ?array
    {
        return $this->companyRepository->findAll();
    }

    public function deleteCompany(int $companyId): void
    {
        $company = $this->companyRepository->find($companyId);
        if (!$company) {
            throw new EntityNotFoundException('Company with id ' . $companyId . ' does not exist!');
        }
        $this->companyRepository->delete($company);
    }
}
