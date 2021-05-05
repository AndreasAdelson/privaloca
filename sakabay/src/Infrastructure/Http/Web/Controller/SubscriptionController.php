<?php

namespace App\Infrastructure\Http\Web\Controller;

use App\Application\Service\SubscriptionService;
use App\Domain\Model\Subscription;
use App\Infrastructure\Repository\SubscriptionRepository;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SubscriptionController extends AbstractController
{

    /**
     * @Route("/subscription", name="subscription_index", methods="GET")
     */
    public function index()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        } else if (empty($this->getUser()->getCompanys())) {
            throw new NotFoundHttpException('Error !');
        }
        return $this->render('abonnement/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    /**
     * @Route("subscription/{slug}", name="subscription_details_subscriptions", methods="GET|POST")
     */
    public function detailsSubscriptions(string $slug)
    {
        #Check si l'user est connecté sinon redirige vers l'authentification
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        } else if (empty($this->getUser()->getCompanys())) {
            throw new NotFoundHttpException('Error !');
        }
        return $this->render('abonnement/details/index.html.twig', [
            'controller_name' => 'CompanyController',
            'utilisateurId' => $this->getUser()->getId(),
            'subscriptionName' => $slug
        ]);
    }
}
