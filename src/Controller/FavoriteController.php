<?php

namespace App\Controller;
use App\Entity\Announcements;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
class FavoriteController extends AbstractController
{
    /**
     * @Route("/favorite", name="myfavorite")
     */
    public function index()
    {
        return $this->render('favorite/index.html.twig', [
            'favoris' => 'FavoriteController',
            
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

        // $this->addFlash(
        // '	type',
        // '	flashMessage'
        // );
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

        return $this->redirectToRoute('home');
    }

}
