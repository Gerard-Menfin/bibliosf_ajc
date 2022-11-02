<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use stdClass;

class TestController extends AbstractController
{
    /**
     Depuis PHP 8, certains commentaires qui commencent par #[ sont appelés
     des attributs. Cela permet de passer des méta-données (= des informations 
     supplémentaires) aux méthodes ou aux classes qui suivent l'attribut

     Pour Symfony, l'attribut Route permet d'ajouter une route au projet.
     Il s'agit du constructeur de la classe Route.
     1er paramètre : le chemin de la route (URL relative)
     Ensuite les paramètres sont nommés, par exemple 'name' permet de donner
     un nom à la route. Ce nom sera utilisé pour les redirections ou pour créer
     des liens HTML.

     Toutes les méthodes d'un contrôleur qui sont liées à une route doivent retourner
     un objet de la classe Response
     */
    #[Route('/test', name: 'app_test')]
    /**
     * @ Route("/test-premiere-route", name="app_test")
     */
    public function index(): Response
    {
        /**
         La méthode render génère l'affichage de la page. Elle prend 2 arguments :
         1 - le fichier template qui va être utilisé pour générer la page (le nom du fichier est relatif au dossier
            templates)
         2 - (optionel) un array qui va contenir toutes les variables dont on aura besoin pour le template
         */
        return $this->render('test/index.html.twig', [
            'controller_name' => '123456',
        ]);
    }

    #[Route("/nouvelle-route", name: "app_test_nouvelle")]
    public function nouvelle()
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'AJC',
        ]);
    }

    /* EXERCICE Ajouter une nouvelle route pour le chemin "/salut", dont le name sera
        salut. Cette route doit afficher "Bonjour prenom" (vous devez créer un nouveau
        fichier twig, et le menu et le footer de base.html.twig doit apparâitre).
    */

    /**
     Lorque l'on ajoute des {} dans le chemin d'une route, on crée une route paramétrée.
     Ce paramètre peut être remplacé par n'importe quelle valeur.
     On peut récupérer cette valeur dans une variable en définissant un argument ayant le
     même nom dans la méthode du controleur.
     */
    #[Route("/salut/{prenom}", name: "salut")]
    public function salut($prenom)
    {
        return $this->render("test/salut.html.twig", [ "prenom" => $prenom ]);
    }

    /**
     Si on ajoute un ? après le nom d'un paramètre (mais dans les {})
     le paramètre devient optionel, c'est-à-dire que la route correspond
     qu'il y ait ce paramètre ou pas. 

     L'argument 'requirements' permet de définir une expression régulière 
     à laquelle le paramètre doit correspondre. Si ce n'est pas le cas,
     la route sera considéré comme n'existant pas.
     */
    #[Route("/test/calcul/{a}/{b?}", name: "calcul", 
                requirements: ["a" => "\d+", "b" => "[0-9]+"])]
    public function calcul($a, $b)
    {
        $b = !empty($b) ? $b : 0;
        return $this->render("test/calcul.html.twig", [
            "a" => $a,
            "b" => $b
        ]);
    }

    #[Route("/test/tableau", name: "app_test_tableau")]
    public function tableau()
    {
        $tableau = [ 4, "ceci est du texte", true, "encore du texte" ];
        return $this->render("test/tableau.html.twig", [ "variable" => $tableau ]);
    }

    #[Route("/test/tableau-associatif", name:"app_test_tableau_associatif")]
    public function associatif()
    {
        $personne = [ "prenom" => "Jean", "nom" => "Aymar" ];
        return $this->render("test/variable.html.twig", [ "variable" => $personne ]);
    }

    #[Route("/test/objet", name:"app_test_objet")]
    public function objet()
    {
        $personne = new stdClass;
        $personne->nom = "Onyme";
        $personne->prenom = "Anne";
        return $this->render("test/variable.html.twig", [ "variable" => $personne ]);
    }

    /**
     Pour restreindre l'accès de certaines routes, on peut utiliser les annotations (ou attributs)
     avec la classe IsGranted. Seuls les utilisateurs connectés ayant le rôle passé en argument
     pourront accéder à cette route. Sinon, ils seront redirigés vers une page erreur 403 
     */
    #[Route("/test/admin", name:"app_test_admin")]
    #[IsGranted("ROLE_ADMIN")]
    public function testAdmin()
    {
        return $this->render("test/index.html.twig", [
            "controller_name" => "cette page n'est accessible qu'aux Administrateurs"
        ]);
    }

}
