<?php

namespace App\Infrastructure\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/utilisateur", name="user_index")
     */
    public function usersList(AuthorizationCheckerInterface $authorizationChecker): Response
    {
        return $this->render('admin/utilisateur/index.html.twig', [
            'canEdit' => $authorizationChecker->isGranted('ROLE_UUTILISATEUR'),
            'canDelete' => $authorizationChecker->isGranted('ROLE_DUTILISATEUR'),
            'canRead' => $authorizationChecker->isGranted('ROLE_RUTILISATEUR'),
        ]);
    }

    /**
     * Cette route permet à un admin de modifier un utilisateur de l'application
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/utilisateur/edit/{id}", name="user_edit_admin_index")
     */
    public function editUserAdmin(int $id)
    {
        return $this->render('admin/utilisateur/edituser.html.twig', [
            'utilisateurId' => $id,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/utilisateur/show/{id}", name="user_show_admin_index")
     */
    public function showUserAdmin(int $id, AuthorizationCheckerInterface $authorizationChecker)
    {

        return $this->render('admin/utilisateur/show.html.twig', [
            'utilisateurId' => $id,
            'canEdit' => $authorizationChecker->isGranted('ROLE_UUTILISATEUR'),
        ]);
    }

    /**
     * Cette route est celle qui permet de faire la modification de son propre compte/profil
     * @Route("/utilisateur/edit/{id}", name="user_edit_admin_index")
     */
    public function editUser(int $id)
    {
        if ($this->getUser()->getId() != $id) {
            throw new AccessDeniedException('Ceci n\'est pas ta page');
        }
        return $this->render('utilisateur/edituser.html.twig', [
            'utilisateurId' => $id,
            'urlPrecedente' => $this->urlPrecedente(),
        ]);
    }

    private function urlPrecedente()
    {
        $urlPrecedente = "/";
        if (isset($_SERVER['HTTP_REFERER'])) {
            $urlPrecedente = $_SERVER['HTTP_REFERER'];
        }
        return $urlPrecedente;
    }
}
