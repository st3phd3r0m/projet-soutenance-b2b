<?php

namespace App\Controller;

use App\Entity\Announcements;
use App\Form\AnnouncementsType;
use App\Repository\AnnouncementsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @Route("/new", name="home_new", methods={"GET","POST"})
     * 
     * 
     */
    public function new(Request $request, AnnouncementsRepository $announcementsRepository): Response
    {
        
        $maxRef = $announcementsRepository->findMaxRef();
        $maxRef = sprintf('%06d', $maxRef[0][1]+1 );

        $announcement = new Announcements;
        $form = $this->createForm(AnnouncementsType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $announcement->setUser($this->getUser());
            $announcement->setRef($maxRef);
            $announcement->setCreatedAt(new \DateTime('now'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($announcement);
            $entityManager->flush();

            return $this->redirectToRoute('comments_index');
        }

        return $this->render('home/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
     /**
      * @Route("/search", name="search")
      */
    public function search(PaginatorInterface $paginator, Request $request){
      $announcements = $paginator->paginate(
         $this->getDoctrine()->getRepository(Announcements::class)->search($request->query->get('search')),
         $request->query->getInt('page, 1'),
         10
      );
      return $this->render('home/index.html.twig', [
          'announcements' => $announcements
      ]);
    }
}
