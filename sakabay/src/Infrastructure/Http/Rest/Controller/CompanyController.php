<?php

namespace App\Infrastructure\Http\Rest\Controller;

use App\Application\Form\Type\CompanyType;
use App\Application\Service\CompanyService;
use App\Domain\Model\Company;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CompanyController extends AbstractFOSRestController
{
    private $entityManager;
    private $companyService;
    private $translator;

    /**
     * CompanyRestController constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, CompanyService $companyService, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->companyService = $companyService;
    }

    /**
     * @Rest\View()
     * @Rest\Post("/companies")
     * @param Request $request
     *
     * @return View
     */
    public function createCompany(Request $request)
    {
        $company = new Company();
        $formOptions = ['translator' => $this->translator];
        $form = $this->createForm(CompanyType::class, $company, $formOptions);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $this->entityManager->persist($company);
        $this->entityManager->flush();

        $ressourceLocation = $this->generateUrl('company_index');

        return View::create([], Response::HTTP_CREATED, ['Location' => $ressourceLocation]);
    }

    /**
     * @Rest\View(serializerGroups={"api_companies"})
     * @Rest\Get("/companies")
     *
     * @QueryParam(name="filterFields",
     *             default="description",
     *             description="Liste des champs sur lesquels le filtre s'appuie"
     * )
     * @QueryParam(name="filter",
     *             default="",
     *             description="Filtre"
     * )
     * @QueryParam(name="sortBy",
     *             default="description",
     *             description="Champ unique sur lequel s'opère le tri"
     * )
     * @QueryParam(name="sortDesc",
     *             default="false",
     *             description="Sens du tri"
     * )
     * @QueryParam(name="currentPage",
     *             default="1",
     *             description="Page courante"
     * )
     * @QueryParam(name="perPage",
     *             default="1000000",
     *             description="Taille de la page"
     * )
     * @return View
     */

    public function getCompanys(ParamFetcher $paramFetcher): View
    {
        $filterFields = $paramFetcher->get('filterFields');
        $filter = $paramFetcher->get('filter');
        $sortBy = $paramFetcher->get('sortBy');
        $sortDesc = $paramFetcher->get('sortDesc');
        $currentPage = $paramFetcher->get('currentPage');
        $perPage = $paramFetcher->get('perPage');

        $pager = $this->companyService
            ->getPaginatedList($sortBy, 'true' === $sortDesc, $filterFields, $filter, $currentPage, $perPage);
        $companies = $pager->getCurrentPageResults();
        $nbResults = $pager->getNbResults();
        $datas = iterator_to_array($companies);
        $view = $this->view($datas, Response::HTTP_OK);
        $view->setHeader('X-Total-Count', $nbResults);

        return $view;
    }
    /**
     * @Rest\View(serializerGroups={"api_companies"})
     * @Rest\Get("companies/{companyId}")
     *
     * @return View
     */
    public function getCompany(int $companyId): View
    {
        $company = $this->companyService->getCompany($companyId);

        return View::create($company, Response::HTTP_OK);
    }

    /**
     * @Rest\View()
     * @Rest\Post("companies/{companyId}")
     * @Security("is_granted('ROLE_UROLE')")
     *
     * @return View
     */
    public function editCompany(int $companyId, Request $request)
    {
        $company = $this->companyService->getCompany($companyId);

        if (!$company) {
            throw new EntityNotFoundException('Company with id ' . $companyId . ' does not exist!');
        }

        $formOptions = [
            'translator' => $this->translator,
        ];
        $form = $this->createForm(CompanyType::class, $company, $formOptions);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $this->entityManager->persist($company);
        $this->entityManager->flush($company);

        $ressourceLocation = $this->generateUrl('company_index');
        return View::create([], Response::HTTP_NO_CONTENT, ['Location' => $ressourceLocation]);
    }

    /**
     * @Rest\View()
     * @Rest\Delete("companies/{companyId}")
     * @Security("is_granted('ROLE_DROLE')")
     *
     * @return View
     */
    public function deleteCompanys(int $companyId): View
    {
        try {
            $this->companyService->deleteCompany($companyId);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
        $ressourceLocation = $this->generateUrl('company_index');

        return View::create([], Response::HTTP_NO_CONTENT, ['Location' => $ressourceLocation]);
    }
}
