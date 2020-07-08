<?php

namespace App\Controller;

use App\Repository\AnnouncementsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{



    /**
     * @Route("/", name="home")
     */
    public function index(AnnouncementsRepository $announcementsRepository, PaginatorInterface $paginator, Request $request)
    {

        $announcements = $paginator->paginate(
            $announcementsRepository->findBy([], ['created_at' => 'DESC']),
            //Le numero de la page, si aucun numero, on force la page 1
            $request->query->getInt('page', 1),
            //Nombre d'élément par page
            5
        );

        return $this->render('home/index.html.twig', [
            'announcements' => $announcements,
        ]);
    }

    
    /**
     * @Route("/show/{slug}", name="home_show")
     * @param string $slug
     * @param Request $request
     * @return Response
     */
    public function show(AnnouncementsRepository $announcementsRepository, Request $request, string $slug)
    {
        $announcement = $announcementsRepository->findOneBy(['slug' => $slug]);

        return $this->render('home/show.html.twig', [
            'announcement' => $announcement,
        ]);
    }
}
