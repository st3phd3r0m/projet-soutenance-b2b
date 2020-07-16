<?php

namespace App\Controller;

use App\Entity\Announcements;
use App\Entity\Users;
use App\Repository\AnnouncementsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
class FavoriteController extends AbstractController
{
    /**
     * @Route("/favorite/{id}", name="myfavorite")
     */
    public function index(Users $user, PaginatorInterface $paginator, Request $request)
    {
        //Affichage, dans l'espace utilisateur, des annonces qu'il a publiées
        $favoris = $paginator->paginate(
            $user->getFavoris(),
            //Le numero de la page, si aucun numero, on force la page 1
            $request->query->getInt('page', 1),
            //Nombre d'élément par page
            5
        );

        return $this->render('profile/listAnnouncements.html.twig', [
            'announcements' => $favoris,
            'favoris'=> true
        ]);
    }

    /** 
     * @Route("/favorite/ajout/{id}", name="ajout_favoris")
     */
    public function ajoutFavoris(Announcements $announcement)
    {
        $announcement->addFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($announcement);
        $em->flush();

        //Envoi d'un message de succès
        $this->addFlash('success', 'L\'annonce a bien été enregistrée dans vos favoris.');

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/favorite/retrait/{id}", name="retrait_favoris")
     */
    public function retraitFavoris(Announcements $announcement)
    {
        $announcement->removeFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($announcement);
        $em->flush();

        //Envoi d'un message de succès
        $this->addFlash('success', 'L\'annonce a bien été retirée de vos favoris.');

        return $this->redirectToRoute('home');
    }
}
