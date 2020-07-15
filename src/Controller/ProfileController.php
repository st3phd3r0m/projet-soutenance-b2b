<?php

namespace App\Controller;

use App\Entity\Announcements;
use App\Entity\Users;
use App\Form\AnnouncementsType;
use App\Form\UsersType;
use App\Repository\AnnouncementsRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UsersRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{

    /**
     * @Route("/index", name="profile_index")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig');
    }

    /**
     * @Route("/list", name="profile_list", methods={"GET"})
     */
    public function list(AnnouncementsRepository $announcementsRepository, PaginatorInterface $paginator, Request $request)
    {

        //Affichage, dans l'espace utilisateur, des annonces qu'il a publiées
        $announcements = $paginator->paginate(
            $announcementsRepository->findBy(['user'=>$this->getUser()], ['created_at' => 'DESC']),
            //Le numero de la page, si aucun numero, on force la page 1
            $request->query->getInt('page', 1),
            //Nombre d'élément par page
            5
        );

        return $this->render('profile/listAnnouncements.html.twig', [
            'announcements' => $announcements
        ]);
    }


    /**
     * @Route("/edit/{slug}", name="profile_edit", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function edit(AnnouncementsRepository $announcementsRepository, Announcements $announcement,  Request $request)
    {

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

            $announcement->setUnlockCount(0);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($announcement);
            $entityManager->flush();



            //Envoi d'un message de succès
            $this->addFlash('success', 'Votre annonce a bien été modifiée.');

            return $this->redirectToRoute('profile_list');
        }

        return $this->render('profile/edit.html.twig', [
            'announcement'=>$announcement,
            'form' => $form->createView(),
        ]);

    }


    /**
     * @Route("/account", name="profile", methods={"GET"})
     */
    public function account(UsersRepository $usersRepository, SubscriptionRepository $subscriptionRepository, Request $request)
    {

        $subscriptions = $subscriptionRepository->findAll();
        $user = $usersRepository->find($this->getUser());

        return $this->render('profile/account.html.twig', [
            'user' => $user,
            'subscriptions' => $subscriptions
        ]);
    }

    /**
     * @Route("/details/change/{id}", name="profile_change_details", methods={"GET","POST"})
     * @param Request $request
     * @param Users $user
     * @return Response
     */
    public function changeDetails(Request $request, Users $user): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->remove('roles');
        $form->remove('plainPassword');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

 
            return $this->redirectToRoute('customer_details');
            
        }

        return $this->render('profile/changeDetails.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }



}
