<?php

namespace App\Application\Service;

use App\Domain\Model\Role;
use App\Infrastructure\Repository\RoleRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleService
{
    /**
     * @var RoleRepositoryInterface
     */
    private $RoleRepository;

    /**
     * RoleRestController constructor.
     */
    public function __construct(RoleRepositoryInterface $RoleRepository)
    {
        $this->RoleRepository = $RoleRepository;
    }

    public function addRole(string $name, string $code)
    {
        $Role = new Role();
        $Role->setName($name);
        $Role->setCode($code);
        $this->RoleRepository->save($Role);
    }

    ///Editer un Role
    public function editRole(string $name, string $code, int $RoleId)
    {
        $Role = $this->RoleRepository->findById($RoleId);
        $Role->setName($name);
        $Role->setCode($code);

        return $Role;
    }
    // public function findOneBy(array $email): ?Role
    // {
    //     return $this->RoleRepository->findOneBy($email);
    // }
    /// Afficher un Role
    public function getRole(int $RoleId): ?Role
    {
        return $this->RoleRepository->find($RoleId);
    }

    public function getAllRoles(): ?array
    {
        return $this->RoleRepository->findAll();
    }

    public function deleteRole(int $RoleId): void
    {
        $Role = $this->RoleRepository->find($RoleId);
        if (!$Role) {
            throw new EntityNotFoundException('Role with id ' . $RoleId . ' does not exist!');
        }
        $this->RoleRepository->delete($Role);
    }
}
