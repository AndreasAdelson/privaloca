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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SubscriptionController extends AbstractController
{
    /**
     * @Route("/subscription", name="subscription_index", methods="GET")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (empty($this->getUser()->getCompanys())) {
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (empty($this->getUser()->getCompanys())) {
            throw new NotFoundHttpException('Error !');
        }
        return $this->render('abonnement/details/index.html.twig', [
            'controller_name' => 'CompanyController',
            'utilisateurId' => $this->getUser()->getId(),
            'subscriptionName' => $slug
        ]);
    }

    /**
     * @Route("admin/subscription", name="subscription_admin_index", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAdmin(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/subscription/index.html.twig', [
            'canCreate' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canRead' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canEdit' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canDelete' => $authorizationChecker->isGranted('ROLE_ADMIN'),
        ]);
    }

    /**
     * @Route("admin/subscription/new", name="subscription_admin_new", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/subscription/form.html.twig', [
            'subscriptionId' => 'null'
        ]);
    }

    /**
     * @Route("admin/subscription/edit/{id}", name="subscription_admin_edit", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit(int $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/subscription/form.html.twig', [
            'subscriptionId' => $id,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("admin/subscription/{id}", name="subscription_admin_show", methods="GET|POST")
     */
    public function show(int $id, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/subscription/show.html.twig', [
            'canEdit' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'subscriptionId' => $id,
            'urlPrecedente' => $this->urlPrecedente()
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
