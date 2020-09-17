<?php

namespace App\Infrastructure\Http\Web\Controller;

use App\Domain\Model\Category;
use App\Infrastructure\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CategoryController extends AbstractController
{

    /**
     * @Route("admin/category", name="category_index", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(AuthorizationCheckerInterface $authorizationChecker)
    {
        return $this->render('admin/category/index.html.twig', [
            'canCreate' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canRead' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canEdit' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canDelete' => $authorizationChecker->isGranted('ROLE_ADMIN'),
        ]);
    }

    /**
     * @Route("admin/category/new", name="category_new", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new()
    {
        return $this->render('admin/category/form.html.twig', [
            'categoryId' => 'null'
        ]);
    }

    /**
     * @Route("admin/category/edit/{id}", name="category_edit", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit(int $id)
    {
        return $this->render('admin/category/form.html.twig', [
            'categoryId' => $id,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("admin/category/{id}", name="category_show", methods="GET|POST")
     */
    public function show(int $id, AuthorizationCheckerInterface $authorizationChecker)
    {
        return $this->render('admin/category/show.html.twig', [
            'canEdit' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'categoryId' => $id
        ]);
    }
}
