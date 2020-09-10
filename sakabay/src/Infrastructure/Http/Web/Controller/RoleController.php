<?php

namespace App\Infrastructure\Http\Web\Controller;

use App\Domain\Model\Role;
use App\Infrastructure\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RoleController extends AbstractController
{

    /**
     * @Route("admin/role", name="role_index", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(AuthorizationCheckerInterface $authorizationChecker)
    {
        return $this->render('admin/role/index.html.twig', [
            'canCreate' => $authorizationChecker->isGranted('ROLE_CROLE'),
            'canRead' => $authorizationChecker->isGranted('ROLE_RROLE'),
            'canEdit' => $authorizationChecker->isGranted('ROLE_UROLE'),
            'canDelete' => $authorizationChecker->isGranted('ROLE_DROLE'),
        ]);
    }

    /**
     * @Route("admin/role/new", name="role_new", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new()
    {
        return $this->render('admin/role/form.html.twig', [
            'roleId' => 'null'
        ]);
    }

    /**
     * @Route("admin/role/edit/{id}", name="role_edit", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit(int $id)
    {
        return $this->render('admin/role/form.html.twig', [
            'roleId' => $id,
        ]);
    }

    /**
     * @Route("admin/role/{id}", name="role_show", methods="GET|POST")
     */
    public function show(int $id, AuthorizationCheckerInterface $authorizationChecker)
    {
        return $this->render('admin/role/show.html.twig', [
            'canEdit' => $authorizationChecker->isGranted('ROLE_UROLE'),
            'roleId' => $id
        ]);
    }
}
