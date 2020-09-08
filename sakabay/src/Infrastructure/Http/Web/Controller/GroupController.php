<?php

namespace App\Infrastructure\Http\Web\Controller;

use App\Domain\Model\Group;
use App\Infrastructure\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class GroupController extends AbstractController
{

    /**
     * @Route("admin/group", name="group_index", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(AuthorizationCheckerInterface $authorizationChecker)
    {
        return $this->render('admin/group/index.html.twig', [
            'canCreate' => $authorizationChecker->isGranted('ROLE_CGROUP'),
            'canRead' => $authorizationChecker->isGranted('ROLE_RGROUP'),
            'canEdit' => $authorizationChecker->isGranted('ROLE_UGROUP'),
            'canDelete' => $authorizationChecker->isGranted('ROLE_DGROUP'),
        ]);
    }

    /**
     * @Route("admin/group/new", name="group_new", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new()
    {
        return $this->render('admin/group/form.html.twig', [
            'groupId' => 'null'
        ]);
    }

    /**
     * @Route("admin/group/edit/{id}", name="group_edit", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit(int $id)
    {
        return $this->render('admin/group/form.html.twig', [
            'groupId' => $id,
        ]);
    }

    /**
     * @Route("admin/group/{id}", name="group_show", methods="GET|POST")
     */
    public function show(int $id)
    {
        return $this->render('admin/group/show.html.twig', [
            'controller_name' => 'GroupController',
            'groupId' => $id
        ]);
    }
}
