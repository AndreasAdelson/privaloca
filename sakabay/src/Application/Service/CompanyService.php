<?php

namespace App\Application\Service;

use App\Domain\Model\Company;
use App\Infrastructure\Repository\CompanyRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityNotFoundException;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

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


    /// Afficher un Company
    public function getCompany(int $companyId): ?Company
    {
        return $this->companyRepository->find($companyId);
    }

    public function getCompanyByUrlName(string $urlName): ?Company
    {
        return $this->companyRepository->findOneBy(['urlName' => $urlName]);
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

    public function isCompanySubscribtionActive(Company $company): bool
    {
        $companySubscriptions = $company->getCompanySubscriptions()->toArray();
        if (!empty($companySubscriptions)) {
            usort($companySubscriptions, function ($a, $b) {
                return ($a->getdtFin()->format('Y-m-d H:i:s') < $b->getDtFin()->format('Y-m-d H:i:s'));
            });
            $now = new DateTime('now');
            $lastDtFinSubscription = $companySubscriptions[0]->getDtfin();
            if ($lastDtFinSubscription > $now) {
                return true;
            }
        }
        return false;
    }
    /**
     * Retourne une page, potentiellement triée et filtrée.
     *
     *
     * @param string $sortBy
     * @param bool   $descending
     * @param string $filterFields
     * @param string $filterText
     * @param int    $currentPage
     * @param int    $perPage
     * @param int    $category
     * @param int    $city
     * @param int    $sousCategory
     *
     * @return Pagerfanta
     */
    public function getPaginatedListAdmin(
        $sortBy = 'id',
        $descending = false,
        $filterFields = '',
        $filterText = '',
        $currentPage = 1,
        $perPage = PHP_INT_MAX ? PHP_INT_MAX : 10,
        $codeStatut = ''

    ) {
        return $this->companyRepository
            ->getPaginatedListAdmin($sortBy, $descending, $filterFields, $filterText, $currentPage, $perPage, $codeStatut);
    }


    /**
     * Retourne une page, potentiellement triée et filtrée.
     *
     *
     * @param string $sortBy
     * @param bool   $descending
     * @param string $filterFields
     * @param string $filterText
     * @param int    $currentPage
     * @param int    $perPage
     * @param int    $category
     * @param int    $city
     * @param int    $sousCategory
     *
     * @return Pagerfanta
     */
    public function getPaginatedListUser(
        $sortBy = 'id',
        $descending = false,
        $filterFields = '',
        $filterText = '',
        $currentPage = 1,
        $perPage = PHP_INT_MAX ? PHP_INT_MAX : 10,
        $codeStatut = '',
        $category = '',
        $city = '',
        $sousCategory = ''

    ) {
        //Trie des entreprises recherchées.
        $companys = $this->companyRepository
            ->getPaginatedListUser($sortBy, $descending, $filterFields, $filterText, $codeStatut, $category, $city, $sousCategory);

        //Entreprises abonnées
        $subscribedCompanys = array_filter($companys, function ($company) {
            return $this->isCompanySubscribtionActive($company) === true;
        });
        usort($subscribedCompanys, function ($a, $b) {
            return (strcmp($a->getName(), $b->getName()));
        });

        //Entreprises non abonnées
        //Fonctionne très bien intelephense délire ici.
        $othersCompanys = array_udiff($companys, $subscribedCompanys, function ($a, $b) {
            return $a->getId() - $b->getId();
        });
        usort($othersCompanys, function ($a, $b) {
            return (strcmp($a->getName(), $b->getName()));
        });

        $companys = array_merge($subscribedCompanys, $othersCompanys);
        return $this->paginateArray($companys, $perPage, $currentPage);
    }

    /**
     * Retourne une page en fonction d'une requète, d'une taille et d'une position.
     *

     *
     * @param int $perPage
     * @param int $currentPage
     *
     * @throws LogicException
     * @return Pagerfanta
     */
    public function paginateArray($data, $perPage, $currentPage)
    {
        $perPage = (int) $perPage;
        if (0 >= $perPage) {
            throw new \LogicException('$perPage must be greater than 0.');
        }
        if (0 >= $currentPage) {
            throw new \LogicException('$currentPage must be greater than 0.');
        }
        $pager = new Pagerfanta(new ArrayAdapter($data));
        $pager->setMaxPerPage((int) $perPage);
        $pager->setCurrentPage((int) $currentPage);

        return $pager;
    }

    public function getCompanyByUserId($utilisateur = '')
    {
        return $this->companyRepository->getCompanyByUserId($utilisateur);
    }
}
