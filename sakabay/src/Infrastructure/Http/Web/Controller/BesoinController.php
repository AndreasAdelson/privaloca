<?php

namespace App\Infrastructure\Http\Web\Controller;

use App\Application\Service\CompanyService;
use App\Application\Service\BesoinService;
use App\Domain\Model\Besoin;
use App\Infrastructure\Repository\BesoinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BesoinController extends AbstractController
{

    private $companyService;

    /**
     * BesoinWebController constructor.
     */
    public function __construct(CompanyService $companyService, BesoinService $besoinService)
    {
        $this->companyService = $companyService;
        $this->besoinService = $besoinService;
    }

    /**
     * @Route("admin/group", name="group_index", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/group/index.html.twig', [
            'canCreate' => $authorizationChecker->isGranted('ROLE_CGROUP'),
            'canRead' => $authorizationChecker->isGranted('ROLE_RGROUP'),
            'canEdit' => $authorizationChecker->isGranted('ROLE_UGROUP'),
            'canDelete' => $authorizationChecker->isGranted('ROLE_DGROUP'),
        ]);
    }

    /**
     * @Route("services/new", name="service_new", methods="GET|POST")
     */
    public function new()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $utilisateurId = $this->getUser()->getId();
        return $this->render('utilisateur/besoin/form.html.twig', [
            'utilisateurId' => $utilisateurId,
            'besoinId' => null
        ]);
    }

    /**
     * @Route("services/edit/{id}", name="service_edit", methods="GET|POST")
     */
    public function edit(int $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $utilisateurId = $this->getUser()->getId();
        return $this->render('utilisateur/besoin/form.html.twig', [
            'utilisateurId' => $utilisateurId,
            'besoinId' => $id
        ]);
    }

    /**
     * @Route("admin/group/{id}", name="group_show", methods="GET|POST")
     */
    public function show(int $id, AuthorizationCheckerInterface $authorizationChecker)
    {
        return $this->render('admin/group/show.html.twig', [
            'canEdit' => $authorizationChecker->isGranted('ROLE_UGROUP'),
            'groupId' => $id,
            'urlPrecedente' => $this->urlPrecedente()
        ]);
    }

    /**
     * @Route("services/list", name="service_list", methods="GET")
     */
    public function manageBesoinList()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $utilisateurId = $this->getUser()->getId();
        return $this->render('utilisateur/besoin/list.html.twig', [
            'utilisateurId' => $utilisateurId,
        ]);
    }

    /**
     * @Route("opportunities/list", name="opportunity_list", methods="GET")
     */
    public function opportunityList()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $utilisateurId = $this->getUser()->getId();
        $companies = $this->getUser()->getCompanys();
        if (empty($companies)) {
            throw new NotFoundHttpException('Page does not exist');
        }
        $subscribedCompaniesId = [];
        foreach ($companies as $company) {
            $subscribtionActive = $this->companyService->isCompanySubscribtionActive($company);
            if ($subscribtionActive) {
                array_push($subscribedCompaniesId, $company->getId());
            }
        }
        if (empty($subscribedCompaniesId)) {
            throw new NotFoundHttpException('Page does not exist');
        }

        return $this->render('opportunity/list.html.twig', [
            'utilisateurId' => $utilisateurId,
        ]);
    }

    /**
     * @Route("opportunities/recap/{id}/{slug}", name="opportunity_recap", methods="GET|POST")
     */
    public function opportunityRecap(int $id, string $slug)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $company = $this->companyService->getCompanyByUrlName($slug);
        if (empty($company)) {
            throw new NotFoundHttpException('Page does not exist');
        }
        $userCompanies = $this->getUser()->getCompanys();
        if (empty($userCompanies)) {
            throw new NotFoundHttpException('Page does not exist');
        }
        if (!$userCompanies->contains($company)) {
            throw new NotFoundHttpException('Page does not exist');
        }
        $subscribtionActive = $this->companyService->isCompanySubscribtionActive($company);
        if (!$subscribtionActive) {
            throw new NotFoundHttpException('Page does not exist');
        }
        $companyCategory = $company->getCategory();
        $opportunity = $this->besoinService->getBesoin($id);
        $opportunityCategory = $opportunity->getCategory();
        if ($companyCategory !== $opportunityCategory) {
            throw new NotFoundHttpException('Page does not exist');
        }
        $companySousCategorys = $company->getSousCategorys();
        $opportunitySousCategorys = $opportunity->getSousCategorys();
        if (!$companySousCategorys->isEmpty()) {
            if (!$opportunitySousCategorys->isEmpty()) {
                $isSousCategorysMatch = false;
                foreach ($companySousCategorys as $sousCategory) {
                    if ($opportunitySousCategorys->contains($sousCategory)) {
                        $isSousCategorysMatch = true;
                    }
                }
                if (!$isSousCategorysMatch) {
                    throw new NotFoundHttpException('Page does not exist');
                }
            }
        }

        return $this->render('opportunity/recap.html.twig', [
            'opportunityId' => $id,
            'companyId' => $company->getId(),
            'utilisateurId' => $this->getUser()->getId()
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
