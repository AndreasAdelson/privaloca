<?php

namespace App\Infrastructure\Http\Rest\Controller;

use App\Application\Form\Type\AnswerType;
use App\Application\Service\AnswerService;
use App\Domain\Model\Answer;
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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


final class AnswerController extends AbstractFOSRestController
{
    private $entityManager;
    private $answerService;
    private $translator;

    /**
     * AnswerRestController constructor.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        AnswerService $answerService,
        TranslatorInterface $translator,
        NotificationFactory $notificationFactory
    ) {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->answerService = $answerService;
        $this->notificationFactory = $notificationFactory;
    }

    /**
     * @Rest\View()
     * @Rest\Post("/answer")
     * @param Request $request
     *
     * @return View
     */
    public function createAnswer(Request $request)
    {
        $answer = new Answer();

        $formOptions = [
            'translator' => $this->translator,
        ];
        $form = $this->createForm(AnswerType::class, $answer, $formOptions);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }

        //Send notification to user
        $company = $answer->getCompany();
        $authorOpportunity = $answer->getBesoin()->getAuthor();
        $ressourceLocation = $this->generateUrl('service_list');
        $this->notificationFactory->createAnswerNotification([$authorOpportunity], $ressourceLocation, $answer, $company);

        $this->entityManager->persist($answer);
        $this->entityManager->flush();

        return View::create([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\View(serializerGroups={"api_answers"})
     * @Rest\Get("admin/answers")
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
    public function getAnswers(ParamFetcher $paramFetcher): View
    {
        $filterFields = $paramFetcher->get('filterFields');
        $filter = $paramFetcher->get('filter');
        $sortBy = $paramFetcher->get('sortBy');
        $sortDesc = $paramFetcher->get('sortDesc');
        $currentPage = $paramFetcher->get('currentPage');
        $perPage = $paramFetcher->get('perPage');

        $pager = $this->answerService
            ->getPaginatedList($sortBy, 'true' === $sortDesc, $filterFields, $filter, $currentPage, $perPage);
        $answers = $pager->getCurrentPageResults();
        $nbResults = $pager->getNbResults();
        $datas = iterator_to_array($answers);
        $view = $this->view($datas, Response::HTTP_OK);
        $view->setHeader('X-Total-Count', $nbResults);

        return $view;
    }

    /**
     * @Rest\View(serializerGroups={"api_answer"})
     * @Rest\Get("admin/answers/{answerId}")
     *
     * @return View
     */
    public function getAnswer(int $answerId): View
    {
        $answer = $this->answerService->getAnswer($answerId);

        return View::create($answer, Response::HTTP_OK);
    }

    /**
     * @Rest\View()
     * @Rest\Post("admin/answers/edit/{answerId}")
     * @return View
     */
    public function editAnswer(int $answerId, Request $request)
    {
        $answer = $this->answerService->getAnswer($answerId);

        if (!$answer) {
            throw new EntityNotFoundException('Answer with id ' . $answerId . ' does not exist!');
        }

        $formOptions = [
            'translator' => $this->translator,
        ];
        $form = $this->createForm(AnswerType::class, $answer, $formOptions);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $this->entityManager->persist($answer);
        $this->entityManager->flush();

        $ressourceLocation = $this->generateUrl('answer_index');
        return View::create([], Response::HTTP_NO_CONTENT, ['Location' => $ressourceLocation]);
    }

    /**
     * @Rest\View()
     * @Rest\Delete("admin/answers/{answerId}")
     *
     * @return View
     */
    public function deleteAnswers(int $answerId): View
    {
        try {
            $answer = $this->answerService->deleteAnswer($answerId);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
        $ressourceLocation = $this->generateUrl('answer_index');

        return View::create($answer, Response::HTTP_NO_CONTENT, ['Location' => $ressourceLocation]);
    }
}
