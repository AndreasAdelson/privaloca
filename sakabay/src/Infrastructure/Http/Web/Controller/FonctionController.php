<?php

namespace App\Infrastructure\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class FonctionController extends AbstractController
{
    /**
     * @Route("admin/fonction", name="fonction_index", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(AuthorizationCheckerInterface $authorizationChecker)
    {
        return $this->render('admin/fonction/index.html.twig', [
            'canDelete' => $authorizationChecker->isGranted('ROLE_DFONCTION'),
            'canCreate' => $authorizationChecker->isGranted('ROLE_CFONCTION'),
        ]);
    }

    /**
     * @Route("admin/fonction/new", name="fonction_new", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new()
    {
        return $this->render('admin/fonction/new.html.twig', []);
    }
}
