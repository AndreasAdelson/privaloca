<?php

namespace App\Application\Service;

use App\Domain\Model\Fonction;
use App\Infrastructure\Repository\FonctionRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FonctionService
{
    /**
     * @var FonctionRepositoryInterface
     */
    private $fonctionRepository;

    /**
     * FonctionRestController constructor.
     */
    public function __construct(FonctionRepositoryInterface $fonctionRepository)
    {
        $this->fonctionRepository = $fonctionRepository;
    }

    public function addFonction(string $name, string $code)
    {
        $fonction = new Fonction();
        $fonction->setName($name);
        $fonction->setCode($code);
        $this->fonctionRepository->save($fonction);
    }

    ///Editer un Fonction
    public function editFonction(string $name, string $code, int $fonctionId)
    {
        $fonction = $this->fonctionRepository->findById($fonctionId);
        $fonction->setName($name);
        $fonction->setCode($code);

        return $fonction;
    }
    // public function findOneBy(array $email): ?Fonction
    // {
    //     return $this->FonctionRepository->findOneBy($email);
    // }
    /// Afficher un Fonction
    public function getFonction(int $fonctionId): ?Fonction
    {
        return $this->fonctionRepository->find($fonctionId);
    }

    public function getAllFonctions(): ?array
    {
        return $this->fonctionRepository->findAll();
    }

    /**
     * Retourne une page, potentiellement triée et filtrée.
     *
     * @author vbioret
     *
     * @param string $sortBy
     * @param bool   $descending
     * @param string $filterFields
     * @param string $filterText
     * @param int    $currentPage
     * @param int    $perPage
     *
     * @return Pagerfanta
     */
    public function getPaginatedList(
        $sortBy = 'id',
        $descending = false,
        $filterFields = '',
        $filterText = '',
        $currentPage = 1,
        $perPage = PHP_INT_MAX ? PHP_INT_MAX : 10
    ) {
        return $this->fonctionRepository
            ->getPaginatedList($sortBy, $descending, $filterFields, $filterText, $currentPage, $perPage);
    }

    public function deleteFonction(int $fonctionId): void
    {
        $fonction = $this->fonctionRepository->find($fonctionId);
        if (!$fonction) {
            throw new EntityNotFoundException('Fonction with id ' . $fonctionId . ' does not exist!');
        }
        $this->fonctionRepository->delete($fonction);
    }
}
