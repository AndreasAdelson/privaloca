<?php

namespace App\Infrastructure\Http\Rest\Controller;

use App\Application\Form\Type\BesoinType;
use App\Application\Service\BesoinService;
use App\Application\Service\BesoinStatutService;
use App\Domain\Model\Besoin;
use App\Infrastructure\Factory\NotificationFactory;
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

final class BesoinController extends AbstractFOSRestController
{
    private $entityManager;
    private $besoinService;
    private $besoinStatutService;
    private $translator;
    private $notificationFactory;


    /**
     * BesoinRestController constructor.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        BesoinService $besoinService,
        TranslatorInterface $translator,
        BesoinStatutService $besoinStatutService,
        NotificationFactory $notificationFactory
    ) {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->besoinService = $besoinService;
        $this->besoinStatutService = $besoinStatutService;
        $this->notificationFactory = $notificationFactory;
    }

    /**
     * @Rest\View()
     * @Rest\Post("/besoins")
     * @param Request $request
     *
     * @return View
     */
    public function createBesoin(Request $request)
    {
        $besoin = new Besoin();
        $formOptions = ['translator' => $this->translator];
        $form = $this->createForm(BesoinType::class, $besoin, $formOptions);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $besoinsStatut = $this->besoinStatutService->getBesoinStatutByCode('PUB');
        $besoin->setBesoinStatut($besoinsStatut);
        $this->entityManager->persist($besoin);
        $this->entityManager->flush();

        $ressourceLocation = $this->generateUrl('service_list');
        $this->notificationFactory->createService([$besoin->getAuthor()], $ressourceLocation, $besoin);

        $ressourceLocation = $this->generateUrl('dashboard');

        return View::create([], Response::HTTP_CREATED, ['Location' => $ressourceLocation]);
    }

    /**
     * @Rest\View(serializerGroups={"api_besoins"})
     * @Rest\Get("/admin/besoins")
     *
     * @QueryParam(name="filterFields",
     *             default="name",
     *             description="Liste des champs sur lesquels le filtre s'appuie"
     * )
     * @QueryParam(name="filter",
     *             default="",
     *             description="Filtre"
     * )
     * @QueryParam(name="sortBy",
     *             default="name",
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

    public function getBesoins(ParamFetcher $paramFetcher): View
    {
        $filterFields = $paramFetcher->get('filterFields');
        $filter = $paramFetcher->get('filter');
        $sortBy = $paramFetcher->get('sortBy');
        $sortDesc = $paramFetcher->get('sortDesc');
        $currentPage = $paramFetcher->get('currentPage');
        $perPage = $paramFetcher->get('perPage');

        $pager = $this->besoinService
            ->getPaginatedList($sortBy, 'true' === $sortDesc, $filterFields, $filter, $currentPage, $perPage);
        $besoins = $pager->getCurrentPageResults();
        $nbResults = $pager->getNbResults();
        $datas = iterator_to_array($besoins);
        $view = $this->view($datas, Response::HTTP_OK);
        $view->setHeader('X-Total-Count', $nbResults);

        return $view;
    }

    /**
     * @Rest\View(serializerGroups={"api_besoins"})
     * @Rest\Get("besoins/{besoinId}")
     *
     * @QueryParam(name="filterFields",
     *             default="name",
     *             description="Liste des champs sur lesquels le filtre s'appuie"
     * )
     *
     * @return View
     */
    public function getBesoin(int $besoinId): View
    {
        $besoin = $this->besoinService->getBesoin($besoinId);

        return View::create($besoin, Response::HTTP_OK);
    }

    /**
     * @Rest\View(serializerGroups={"api_besoins"})
     * @Rest\Get("besoins/utilisateur/{utilisateurId}")
     *
     * @QueryParam(name="codeStatut",
     *             default="",
     *             description="Trie par besoinStatut"
     * )
     *
     * @return View
     */
    public function getBesoinByUser(int $utilisateurId, ParamFetcher $paramFetcher): View
    {
        $codeStatut = $paramFetcher->get('codeStatut');
        $besoin = $this->besoinService->getBesoinByUserId($utilisateurId, $codeStatut);

        return View::create($besoin, Response::HTTP_OK);
    }

    /**
     * @Rest\View()
     * @Rest\Post("besoins/{besoinId}")
     *
     * @return View
     */
    public function editBesoin(int $besoinId, Request $request)
    {
        $besoin = $this->besoinService->getBesoin($besoinId);

        if (!$besoin) {
            throw new EntityNotFoundException('Besoin with id ' . $besoinId . ' does not exist!');
        }

        $formOptions = [
            'translator' => $this->translator,
        ];
        $form = $this->createForm(BesoinType::class, $besoin, $formOptions);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $this->entityManager->persist($besoin);
        $this->entityManager->flush();

        $ressourceLocation = $this->generateUrl('service_list');
        return View::create([], Response::HTTP_NO_CONTENT, ['Location' => $ressourceLocation]);
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/besoins/{besoinId}")
     *
     * @return View
     */
    public function deleteBesoin(int $besoinId): View
    {
        try {
            $this->besoinService->deleteBesoin($besoinId);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
        $ressourceLocation = $this->generateUrl('service_list');

        return View::create([], Response::HTTP_NO_CONTENT, ['Location' => $ressourceLocation]);
    }
}
