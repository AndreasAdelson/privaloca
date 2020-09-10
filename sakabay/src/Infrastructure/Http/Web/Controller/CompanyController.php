<?php

namespace App\Infrastructure\Http\Web\Controller;

use App\Domain\Model\Company;
use App\Infrastructure\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CompanyController extends AbstractController
{

    /**
     * @Route("company", name="company_index", methods="GET")
     */
    public function index(AuthorizationCheckerInterface $authorizationChecker)
    {
        return $this->render('company/index.html.twig', [
            'canCreate' => $authorizationChecker->isGranted('ROLE_CROLE'),
            'canRead' => $authorizationChecker->isGranted('ROLE_RROLE'),
            'canEdit' => $authorizationChecker->isGranted('ROLE_UROLE'),
            'canDelete' => $authorizationChecker->isGranted('ROLE_DROLE'),
        ]);
    }

    /**
     * @Route("company/new", name="company_new", methods="GET|POST")
     */
    public function new()
    {
        return $this->render('company/form.html.twig', []);
    }

    /**
     * @Route("company/edit/{id}", name="company_edit", methods="GET|POST")
     */
    public function edit(int $id)
    {
        return $this->render('admin/company/form.html.twig', [
            'companyId' => $id,
        ]);
    }

    /**
     * @Route("company/{id}", name="company_show", methods="GET|POST")
     */
    public function show(int $id)
    {
        return $this->render('admin/company/show.html.twig', [
            'controller_name' => 'GroupController',
            'companyId' => $id
        ]);
    }
}
