<?php

namespace App\Infrastructure\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="utilisateur")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }
}