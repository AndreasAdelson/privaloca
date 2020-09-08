<?php

namespace App\Infrastructure\Http\Rest\Controller;

use App\Application\Form\Type\EditUtilisateurType;
use App\Application\Form\Type\EditAccountType;
use App\Application\Service\UtilisateurService;
use App\Application\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UtilisateurController extends AbstractFOSRestController
{
    private $entityManager;
    private $utilisateurService;
    private $translator;

    /**
     * UtilisateurRestController constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, UtilisateurService $utilisateurService, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->utilisateurService = $utilisateurService;
    }

    /**
     * @Rest\View(serializerGroups={"api_utilisateurs"})
     * @Rest\Get("/admin/utilisateurs")
     *
     * @QueryParam(name="filterFields",
     *             default="email,login,firstName,lastName",
     *             description="Liste des champs sur lesquels le filtre s'appuie"
     * )
     * @QueryParam(name="filter",
     *             default="",
     *             description="Filtre"
     * )
     * @QueryParam(name="sortBy",
     *             default="lastName",
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
     *
     * - - - - - - - - - - - - Autocomplete - - - - - - - - - - - -
     *
     * @QueryParam(name="autocomplete",
     *             default="",
     *             description="Nom &| Prénom &| Email de l'utilisateur"
     * )
     * @return View
     */
    public function getUtilisateurs(ParamFetcher $paramFetcher): View
    {
        if ($paramFetcher->get('autocomplete')) {
            $utilisateurs = $this->utilisateurService->findUsersForAutocomplete($paramFetcher->all());

            return View::create($utilisateurs, Response::HTTP_OK);
        } else {
            $filterFields = $paramFetcher->get('filterFields');
            $filter = $paramFetcher->get('filter');
            $sortBy = $paramFetcher->get('sortBy');
            $sortDesc = $paramFetcher->get('sortDesc');
            $currentPage = $paramFetcher->get('currentPage');
            $perPage = $paramFetcher->get('perPage');

            $pager = $this->utilisateurService
                ->getPaginatedList($sortBy, 'true' === $sortDesc, $filterFields, $filter, $currentPage, $perPage);
            $utilisateurs = $pager->getCurrentPageResults();
            $nbResults = $pager->getNbResults();
            $datas = iterator_to_array($utilisateurs);
            $view = $this->view($datas, Response::HTTP_OK);
            $view->setHeader('X-Total-Count', $nbResults);

            return $view;
        }
    }

    /**
     * @Rest\View(serializerGroups={"api_utilisateurs"})
     * @Rest\Get("/admin/utilisateurs/{utilisateurId}")
     *
     * @return View
     */
    public function getUtilisateur(int $utilisateurId): View
    {
        $utilisateur = $this->utilisateurService->getUtilisateur($utilisateurId);
        if (!$utilisateur) {
            throw $this->createNotFoundException('Utilisateur with id ' . $utilisateurId . ' does not exist!');
            return View::create([], Response::HTTP_NOT_FOUND);
        }

        return View::create($utilisateur, Response::HTTP_OK);
    }

    /**
     * @Rest\View()
     * @Rest\Post("/admin/utilisateurs/{utilisateurId}")
     *
     * @return View
     */
    public function editUtilisateur(int $utilisateurId, Request $request)
    {
        $utilisateur = $this->utilisateurService->getUtilisateur($utilisateurId);

        if (!$utilisateur) {
            throw new NotFoundHttpException('Utilisateur with id ' . $utilisateurId . ' does not exist!');
        }

        $formOptions = [
            'translator' => $this->translator,
        ];
        $form = $this->createForm(EditUtilisateurType::class, $utilisateur, $formOptions);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $this->entityManager->persist($utilisateur);
        $this->entityManager->flush($utilisateur);

        $ressourceLocation = $this->generateUrl('user_index');
        return View::create([], Response::HTTP_NO_CONTENT, ['Location' => $ressourceLocation]);
    }

    /**
     * @Rest\View()
     * @Rest\Post("/utilisateur/edit/{utilisateurId}")
     *
     * @return View
     */
    public function editAccount(int $utilisateurId, Request $request, string $uploadDir, FileUploader $uploader)
    {
        $utilisateur = $this->utilisateurService->getUtilisateur($utilisateurId);

        if (!$utilisateur) {
            throw new EntityNotFoundException('Utilisateur with id ' . $utilisateurId . ' does not exist!');
        }
        $file = $request->files->get('file');

        if (!empty($file)) {
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFileName);
            $newFileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
            $uploader->upload($uploadDir, $file, $newFileName);
            $request->request->set('imageProfil', $newFileName);
            $oldImage = $utilisateur->getImageProfil();
            if (!empty($oldImage)) {
                if (file_exists($oldImage)) {
                    $uploader->deleteOldFile($uploadDir, $oldImage);
                }
            }
        }
        $formOptions = [
            'translator' => $this->translator,
        ];

        $form = $this->createForm(EditAccountType::class, $utilisateur, $formOptions);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $this->entityManager->persist($utilisateur);
        $this->entityManager->flush($utilisateur);

        $ressourceLocation = $this->generateUrl('home');
        return View::create([], Response::HTTP_NO_CONTENT, ['Location' => $ressourceLocation]);
    }

    /**
     * @Rest\View()
     * @Rest\Delete("/admin/utilisateurs/{utilisateurId}")
     *
     * @return View
     */
    public function deleteUtilisateurs(int $utilisateurId): View
    {
        try {
            $utilisateur = $this->utilisateurService->deleteUtilisateur($utilisateurId);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
        $ressourceLocation = $this->generateUrl('user_index');

        return View::create($utilisateur, Response::HTTP_NO_CONTENT, ['Location' => $ressourceLocation]);
    }
}
