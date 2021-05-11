<?php

namespace App\Infrastructure\Http\Rest\Controller;

use App\Application\Form\Type\CommentType;
use App\Application\Service\CommentService;
use App\Domain\Model\Comment;
use App\Infrastructure\Factory\NotificationFactory;
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
use Symfony\Component\Mailer\Mailer;

final class CommentController extends AbstractFOSRestController
{
    private $entityManager;
    private $commentService;
    private $translator;

    /**
     * CommentRestController constructor.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CommentService $commentService,
        TranslatorInterface $translator,
        NotificationFactory $notificationFactory
    ) {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->commentService = $commentService;
        $this->notificationFactory = $notificationFactory;
    }

    /**
     * @Rest\View()
     * @Rest\Post("/comment")
     * @param Request $request
     *
     * @return View
     */
    public function createComment(Request $request)
    {
        $comment = new Comment();

        $formOptions = [
            'translator' => $this->translator,
        ];
        $form = $this->createForm(CommentType::class, $comment, $formOptions);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }

        //Send notification to user
        $authorOpportunity = $comment->getBesoin()->getAuthor();
        $ressourceLocation = $this->generateUrl('service_list');
        $this->notificationFactory->createCommentNotification([$authorOpportunity], $ressourceLocation, $comment);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return View::create([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\View(serializerGroups={"api_comments"})
     * @Rest\Get("admin/comments")
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
    public function getComments(ParamFetcher $paramFetcher): View
    {
        $filterFields = $paramFetcher->get('filterFields');
        $filter = $paramFetcher->get('filter');
        $sortBy = $paramFetcher->get('sortBy');
        $sortDesc = $paramFetcher->get('sortDesc');
        $currentPage = $paramFetcher->get('currentPage');
        $perPage = $paramFetcher->get('perPage');

        $pager = $this->commentService
            ->getPaginatedList($sortBy, 'true' === $sortDesc, $filterFields, $filter, $currentPage, $perPage);
        $comments = $pager->getCurrentPageResults();
        $nbResults = $pager->getNbResults();
        $datas = iterator_to_array($comments);
        $view = $this->view($datas, Response::HTTP_OK);
        $view->setHeader('X-Total-Count', $nbResults);

        return $view;
    }

    /**
     * @Rest\View(serializerGroups={"api_comment"})
     * @Rest\Get("admin/comments/{commentId}")
     *
     * @return View
     */
    public function getComment(int $commentId): View
    {
        $comment = $this->commentService->getComment($commentId);

        return View::create($comment, Response::HTTP_OK);
    }


    /**
     * @Rest\View()
     * @Rest\Post("admin/comments/edit/{commentId}")
     * @return View
     */
    public function editComment(int $commentId, Request $request)
    {
        $comment = $this->commentService->getComment($commentId);

        if (!$comment) {
            throw new EntityNotFoundException('Comment with id ' . $commentId . ' does not exist!');
        }

        $formOptions = [
            'translator' => $this->translator,
        ];
        $form = $this->createForm(CommentType::class, $comment, $formOptions);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        $ressourceLocation = $this->generateUrl('comment_index');
        return View::create([], Response::HTTP_NO_CONTENT, ['Location' => $ressourceLocation]);
    }

    /**
     * @Rest\View()
     * @Rest\Delete("admin/comments/{commentId}")
     *
     * @return View
     */
    public function deleteComments(int $commentId): View
    {
        try {
            $comment = $this->commentService->deleteComment($commentId);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
        $ressourceLocation = $this->generateUrl('comment_index');

        return View::create($comment, Response::HTTP_NO_CONTENT, ['Location' => $ressourceLocation]);
    }
}
