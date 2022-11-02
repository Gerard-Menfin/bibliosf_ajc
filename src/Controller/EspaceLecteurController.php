<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/espace-lecteur")]
class EspaceLecteurController extends AbstractController
{
    #[Route('/', name: 'app_espace_lecteur')]
    public function index(): Response
    {
        //v1
        $abonneConnecte = $this->getUser();
        // return $this->render('espace_lecteur/index.html.twig', [
        //     'abonne' => $abonneConnecte,
        // ]);
        //v2
        return $this->render('espace_lecteur/index.html.twig');
    }
}
