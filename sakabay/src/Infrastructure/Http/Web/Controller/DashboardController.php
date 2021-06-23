<?php

namespace App\Infrastructure\Http\Web\Controller;

// use App\Domain\Model\Dashboard;
// use App\Infrastructure\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DashboardController extends AbstractController
{

    /**
     * @Route("/Dashboard", name="dashboard", methods="GET")
     */
    public function index(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'UtilisateurController',
            'utilisateurId' => $this->getUser()->getId()
        ]);
    }
}
