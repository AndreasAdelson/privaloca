<?php

namespace App\Infrastructure\Http\Rest\Controller;

use App\Application\Form\Type\EditRoleType;
use App\Application\Service\RoleService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class RoleController extends AbstractFOSRestController
{
    private $entityManager;
    private $roleService;
    private $translator;

    /**
     * RoleRestController constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, RoleService $roleService, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->roleService = $roleService;
    }

    /**
     * @Rest\View()
     * @Rest\Post("/role/new")
     * @param Request $request
     *
     * @return View
     */
    public function createRole(Request $request): View
    {
        $role = $this->roleService->addRole($request->get('name'), $request->get('code'));
        // $formOptions = ['translator' => $this->translator]

        return View::create($role, Response::HTTP_CREATED);
    }

    /**
     * @Rest\View(serializerGroups={"api_role"})
     * @Rest\Get("/role")
     *
     * @return View
     */
    public function getRoles(): View
    {
        $role = $this->RoleService->getAllRoles();

        return View::create($role, Response::HTTP_OK);
    }

    /**
     * @Rest\View(serializerGroups={"api_role"})
     * @Rest\Get("/role/{roleId}")
     *
     * @return View
     */
    public function getRole(int $roleId): View
    {
        $role = $this->roleService->getRole($roleId);

        return View::create($role, Response::HTTP_OK);
    }

    /**
     * @Rest\View()
     * @Rest\Post("/role/edit/{roleId}")
     *
     * @return View
     */
    public function editRole(int $roleId, Request $request)
    {
        $role = $this->roleService->getRole($roleId);

        if (!$role) {
            throw new EntityNotFoundException('Role with id ' . $roleId . ' does not exist!');
        }

        $formOptions = [
            'translator' => $this->translator,
        ];
        $form = $this->createForm(EditRoleType::class, $role, $formOptions);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $this->entityManager->persist($role);
        $this->entityManager->flush($role);

        $ressourceLocation = $this->generateUrl('user_admin_index');
        return View::create([], Response::HTTP_NO_CONTENT, ['Location' => $ressourceLocation]);
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/role/delete/{roleId}")
     *
     * @return View
     */
    public function deleteRoles(int $roleId): View
    {
        $role = $this->roleService->deleteRole($roleId);

        return View::create($role, Response::HTTP_NO_CONTENT);
    }
}
