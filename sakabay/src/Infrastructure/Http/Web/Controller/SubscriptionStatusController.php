<?php

namespace App\Infrastructure\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SubscriptionStatusController extends AbstractController
{

    /**
     * @Route("admin/subscription-status", name="subscription_status_index", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(AuthorizationCheckerInterface $authorizationChecker)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/subscription-status/index.html.twig', [
            'canCreate' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canRead' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canEdit' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canDelete' => $authorizationChecker->isGranted('ROLE_ADMIN'),
        ]);
    }

    /**
     * @Route("admin/subscription-status/new", name="subscription_status_new", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/subscription-status/form.html.twig', [
            'susbcriptionStatusId' => 'null'
        ]);
    }

    /**
     * @Route("admin/subscription-status/edit/{id}", name="subscription_status_edit", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit(int $id)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/subscription-status/form.html.twig', [
            'susbcriptionStatusId' => $id,
        ]);
    }

    /**
     * @Route("admin/subscription-status/show/{id}", name="subscription_status_show", methods="GET|POST")
     */
    public function show(int $id, AuthorizationCheckerInterface $authorizationChecker)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/subscription-status/show.html.twig', [
            'canEdit' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'susbcriptionStatusId' => $id,
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
