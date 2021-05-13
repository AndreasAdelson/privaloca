<?php

namespace App\Infrastructure\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CompanyStatutController extends AbstractController
{

    /**
     * @Route("admin/company-statut", name="company_statut_index", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(AuthorizationCheckerInterface $authorizationChecker)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/company-statut/index.html.twig', [
            'canCreate' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canRead' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canEdit' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canDelete' => $authorizationChecker->isGranted('ROLE_ADMIN'),
        ]);
    }

    /**
     * @Route("admin/company-statut/new", name="company_statut_new", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/company-statut/form.html.twig', [
            'companyStatutId' => 'null'
        ]);
    }

    /**
     * @Route("admin/company-statut/edit/{id}", name="companyStatut_edit", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit(int $id)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/company-statut/form.html.twig', [
            'companyStatutId' => $id,
        ]);
    }

    /**
     * @Route("admin/company-statut/show/{id}", name="company_statut_show", methods="GET|POST")
     */
    public function show(int $id, AuthorizationCheckerInterface $authorizationChecker)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/company-statut/show.html.twig', [
            'canEdit' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'companyStatutId' => $id,
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
