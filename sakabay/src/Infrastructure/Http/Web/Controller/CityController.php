<?php

namespace App\Infrastructure\Http\Web\Controller;

use App\Domain\Model\City;
use App\Infrastructure\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CityController extends AbstractController
{

    /**
     * @Route("admin/city", name="city_index", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(AuthorizationCheckerInterface $authorizationChecker)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/city/index.html.twig', [
            'canCreate' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canRead' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'canEdit' => $authorizationChecker->isGranted('ROLE_ADMIN'),
        ]);
    }

    /**
     * @Route("admin/city/new", name="city_new", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/city/form.html.twig', [
            'cityId' => 'null'
        ]);
    }

    /**
     * @Route("admin/city/edit/{id}", name="city_edit", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit(int $id)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/city/form.html.twig', [
            'cityId' => $id,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("admin/city/{id}", name="city_show", methods="GET|POST")
     */
    public function show(int $id, AuthorizationCheckerInterface $authorizationChecker)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/city/show.html.twig', [
            'canEdit' => $authorizationChecker->isGranted('ROLE_ADMIN'),
            'cityId' => $id,
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
