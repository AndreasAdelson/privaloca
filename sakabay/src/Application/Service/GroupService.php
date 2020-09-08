<?php

namespace App\Application\Service;

use App\Domain\Model\Group;
use App\Infrastructure\Repository\GroupRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GroupService
{
    /**
     * @var GroupRepositoryInterface
     */
    private $groupRepository;

    /**
     * GroupRestController constructor.
     */
    public function __construct(GroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function addGroup(string $name, string $code)
    {
        $group = new Group();
        $group->setName($name);
        $group->setCode($code);
        $this->groupRepository->save($group);
    }

    ///Editer un Groupe
    public function editGroup(string $name, string $code, int $groupId)
    {
        $group = $this->groupRepository->findById($groupId);
        $group->setName($name);
        $group->setCode($code);

        return $group;
    }

    /// Afficher un Group
    public function getGroup(int $groupId): ?Group
    {
        return $this->groupRepository->find($groupId);
    }

    public function getAllGroups(): ?array
    {
        return $this->groupRepository->findAll();
    }

    public function deleteGroup(int $groupId): void
    {
        $group = $this->groupRepository->find($groupId);
        if (!$group) {
            throw new EntityNotFoundException('Group with id ' . $groupId . ' does not exist!');
        }
        $this->groupRepository->delete($group);
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
        return $this->groupRepository
            ->getPaginatedList($sortBy, $descending, $filterFields, $filterText, $currentPage, $perPage);
    }
}
