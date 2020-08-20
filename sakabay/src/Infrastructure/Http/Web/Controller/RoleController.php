<?php

namespace App\Infrastructure\Http\Web\Controller;

use App\Domain\Model\Role;
use App\Infrastructure\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class RoleController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $words = [
            'sky', 'cloud', 'wood', 'rock', 'forest',
            'mountain', 'breeze'
        ];
        return $this->render('role/index.html.twig', [
            'words' => $words,
            'controller_name' => 'RoleController',
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/role", name="user_admin_index")
     */
    public function usersList(RoleRepository $users)
    {
        return $this->render('admin/index.html.twig', []);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/role/edit/{id}", name="modifier_role")
     */
    public function editUser(int $id)
    {
        return $this->render('admin/edituser.html.twig', [
            'roleId' => $id,
        ]);
    }
}
