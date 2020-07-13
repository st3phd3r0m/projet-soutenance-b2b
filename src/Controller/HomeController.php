<?php

namespace App\Controller;

use App\Entity\Announcements;
use App\Entity\UnlockedAnnouncements;
use App\Form\AnnouncementsType;
use App\Repository\ActivitySectorRepository;
use App\Repository\AnnouncementsRepository;
use App\Repository\CategoriesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(AnnouncementsRepository $announcementsRepository, CategoriesRepository $categoriesRepository, ActivitySectorRepository $activitySectorRepository, PaginatorInterface $paginator, Request $request)
    {

        // dd($this->getUser());

        //Récupération des données de la requete GET
        $criteria = $request->query->all();

        //Est-ce que le boutton de la barre de recherche a été cliqué
        if (!empty($criteria)) {
            //oui
            $announcements = $paginator->paginate(
                //Appel de la méthode de requete DQL de recherche
                $announcementsRepository->searchFilter($criteria),
                //Le numero de la page, si aucun numero, on force la page 1
                $request->query->getInt('page', 1),
                //Nombre d'élément par page
                5
            );
        } else {
            //non
            $announcements = $paginator->paginate(
                $announcementsRepository->findBy([], ['created_at' => 'DESC']),
                //Le numero de la page, si aucun numero, on force la page 1
                $request->query->getInt('page', 1),
                //Nombre d'élément par page
                5
            );
        }

        $announcementsNoPag = $announcementsRepository->findAll();
        $categories = $categoriesRepository->findAll();
        $activitySectors = $activitySectorRepository->findAll();


        return $this->render('home/index.html.twig', [
            'announcements' => $announcements,
            'categories' => $categories,
            'activitySectors' => $activitySectors,
            'announcementsNoPag' => $announcementsNoPag
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
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, AnnouncementsRepository $announcementsRepository): Response
    {

        if ($this->getUser()) {

            $maxRef = $announcementsRepository->findMaxRef();
            $maxRef = sprintf('%06d', $maxRef[0][1] + 1);

            $announcement = new Announcements;
            $form = $this->createForm(AnnouncementsType::class, $announcement);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                //Récupération des mots-clés en tant que chaine de caractères et séparation en array avec un délimiteur ";"
                $location = json_decode($form->get("coordinates")->getData());
                $announcement->setCity($location->name);
                $announcement->setPostalCode($location->cp);
                $announcement->setDeptCode($location->deptCode);
                $announcement->setDepartememt($location->dept);
                $gps = [
                    'long' => $location->long,
                    'lat' => $location->lat
                ];
                $announcement->setGpsLocation($gps);


                //Récupération des mots-clés en tant que chaine de caractères et séparation en array avec un délimiteur ";"
                $key_words = $form->get("key_words")->getData();
                $key_words = explode(";", $key_words);
                $key_words = array_filter($key_words);
                $announcement->setKeyWords($key_words);

                //Récupération de la délimitation du budget
                $price_range[0] = $form->get("price_min")->getData();
                $price_range[1] = $form->get("price_max")->getData();
                $announcement->setPriceRange($price_range);

                $announcement->setUser($this->getUser());
                $announcement->setRef($maxRef);
                $announcement->setCreatedAt(new \DateTime('now'));

                $announcement->setUnlockCount(0);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($announcement);
                $entityManager->flush();

                //L'auteur de l'annonce accède aux contenus bloqués de l'annonce
                //On instancie l'entité UnlockedAnnouncements
                $unlockedAnnouncement = new UnlockedAnnouncements;
                $unlockedAnnouncement->setUser($this->getUser());
                $unlockedAnnouncement->setAnnouncements($announcement);
                $entityManager->persist($unlockedAnnouncement);
                $entityManager->flush();

                //Envoi d'un message de succès
                $this->addFlash('success', 'Votre annonce a bien été enregistrée.');

                return $this->redirectToRoute('home');
            }

            return $this->render('home/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } else {

            //Envoi d'un message de succès
            $this->addFlash('pleaseConnect', 'Veuillez vous connecter pour publier une annonce.');

            return $this->redirectToRoute('app_login');
        }
    }



    /**
     * @Route("/search", name="search")
     */
    public function search(PaginatorInterface $paginator, Request $request)
    {
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
