<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use App\Form\AuteurModifType;

class AuteurController extends AbstractController
{
    #[Route('/auteur', name: 'app_auteur')]
    public function index(AuteurRepository $auteurRepository): Response
    {
        $auteurs = $auteurRepository->findAll();
        return $this->render('auteur/index.html.twig', [
            "auteurs" => $auteurs
        ]);
    }

    /**
     Pour enregistrer en BDD, on va utiliser un objet Entité pour les valeurs.
        Pour exécuter les requêtes, on va devoir utiliser une classe Repository.
        C'est une classe qu'on ne peut utiliser qu'en passant par les arguments 
        d'une méthode d'un controleur (comme la classe Request).
        Cette objet a des propriétés de type objet qui contiennent toutes les valeurs
        des superglobales de PHP : 
            $request->request       $_POST
            $request->query         $_GET
            $request->files         $_FILES
            ...                     ...

        Chacune de ces propriétés a des méthodes pour récupérer les valeurs
            $request->request->get(inputname)   pour récupérer la valeur d'un input
            $request->request->has(name)        pour savoir si l'indice 'name' existe
            ...

        */
    #[Route("/auteur/ajouter", name: "app_auteur_ajouter")]
    public function ajouter(Request $request, AuteurRepository $auteurRepository)
    {
        /**
            la fonction dump est l'équivalent de var_dump pour symfony
            la fonction dd fait un dump puis die (fonction qui arrête le code PHP) 
        */
        if( $request->isMethod("POST") ){
            $nom = $request->request->get("nom");
            $prenom = $request->request->get("prenom");
            $biographie = $request->request->get("biographie");

            $auteur = new Auteur;
            $auteur->setNom($nom);
            $auteur->setPrenom($prenom);
            $auteur->setBiographie($biographie);

            $auteurRepository->save($auteur, true);
            $this->addFlash("success", "Le nouvel auteur a bien été enregistré");
            return $this->redirectToRoute("app_auteur");
        }

        return $this->render("auteur/formulaire.html.twig");
    }

    #[Route("/auteur/modifier/{id}", name: "app_auteur_modifier", requirements: ["id" => "\d+"])]
    public function modifier(AuteurRepository $ar, Request $rq, int $id )
    {
        $auteur = $ar->find($id);
        $form = $this->createForm(AuteurModifType::class, $auteur);
        /**
         La méthode handelRequest (qui prend 1 argument de la classe Request) va permettre à l'objet
         formulaire de gérer la requête HTTP : l'objet form va pouvoir gérer directement les
         données envoyées par le formulaire, plus besoin d'utiliser l'objet Request.
         */
        $form->handleRequest($rq);
        if( $form->isSubmitted() && $form->isValid() ) {
            $ar->save($auteur, true);
            $this->addFlash("success", "L'auteur n°$id a bien été modifié");
            return $this->redirectToRoute("app_auteur");
        }
        return $this->render("auteur/form.html.twig", [ "formAuteur" => $form->createView() ]);
    }

    #[Route("/auteur/supprimer/{id}", name: "app_auteur_supprimer", requirements: ["id" => "\d+"])]
    public function supprimer(AuteurRepository $ar, Auteur $auteur, Request $rq)
    {
        if($rq->isMethod("POST")){
            $this->addFlash("success", "L'auteur n°" . $auteur->getId() . " a bien été supprimé");
            $ar->remove($auteur, true);
            return $this->redirectToRoute("app_auteur");
        }
        return $this->render("auteur/confirmation.html.twig", [ "auteur" => $auteur ]);
    }
}
