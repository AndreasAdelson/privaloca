<?php

namespace App\Application\Service;

use App\Domain\Model\Utilisateur;
use App\Infrastructure\Repository\UtilisateurRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UtilisateurService
{
    /**
     * @var UtilisateurRepositoryInterface
     */
    private $utilisateurRepository;

    /**
     * UtilisateurRestController constructor.
     */
    public function __construct(UtilisateurRepositoryInterface $utilisateurRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
    }

    // public function addUtilisateur(string $email, string $firstName, string $password, string $lastName)
    // {
    //     $utilisateur = new Utilisateur();
    //     $utilisateur->setEmail($email);
    //     $utilisateur->setConsigne($consigne);
    //     $this->utilisateurRepository->save($utilisateur);
    // }

    ///Editer un utilisateur
    public function editUtilisateur(string $email, string $firstName, string $lastName, string $login, int $utilisateurId)
    {
        $utilisateur = $this->utilisateurRepository->findById($utilisateurId);
        $utilisateur->setEmail($email);
        $utilisateur->setFirstName($firstName);
        $utilisateur->setLastName($lastName);
        $utilisateur->setLogin($login);

        return $utilisateur;
    }
    // public function findOneBy(array $email): ?Utilisateur
    // {
    //     return $this->utilisateurRepository->findOneBy($email);
    // }
    /// Afficher un utilisateur
    public function getUtilisateur(int $utilisateurId): ?Utilisateur
    {
        return $this->utilisateurRepository->find($utilisateurId);
    }

    public function getAllUtilisateurs(): ?array
    {
        return $this->utilisateurRepository->findAll();
    }

    public function deleteUtilisateur(int $utilisateurId): void
    {
        $utilisateur = $this->utilisateurRepository->find($utilisateurId);
        if (!$utilisateur) {
            throw new EntityNotFoundException('Utilisateur with id ' . $utilisateurId . ' does not exist!');
        }
        $this->utilisateurRepository->delete($utilisateur);
    }
}
