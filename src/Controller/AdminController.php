<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use App\Entity\Subscription;
use App\Form\SubscriptionType;
use App\Repository\SubscriptionRepository;
use App\Entity\ActivitySector;
use App\Form\ActivitySectorType;
use App\Repository\ActivitySectorRepository;
use App\Entity\Professions;
use App\Form\ProfessionsType;
use App\Repository\AnnouncementsRepository;
use App\Repository\CompagniesRepository;
use App\Repository\ProfessionsRepository;
use App\Repository\SubscriptionPurchasesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\IsNull;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{

    // -------------------------------------------------------------------------------
    // ----------------------- Administration des Utilisateurs -----------------------
    // -------------------------------------------------------------------------------

    /**
     * @Route("/users/", name="admin_users_index", methods={"GET"})
     */
    public function indexUsers(UsersRepository $usersRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/users/new", name="admin_users_new", methods={"GET","POST"})
     */
    public function newUsers(Request $request): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users/{slug}", name="admin_users_show", methods={"GET"})
     */
    public function showUsers(Users $user, CompagniesRepository $compagniesRepository, AnnouncementsRepository $announcementsRepository, SubscriptionPurchasesRepository $subscriptionPurchasesRepository): Response
    {
        $activeSub = $subscriptionPurchasesRepository->findBy([
            'compagny' => $compagniesRepository->findBy([
                'user' => $user,
                ]),
            'active' => true
            ]);

        $subHistory = $subscriptionPurchasesRepository->findBy([
            'compagny' => $compagniesRepository->findBy([
                'user' => $user,
                ]),
            'active' => false
            ]);

        return $this->render('admin/users/show.html.twig', [
            'activeSubs' => $activeSub,
            'subs' => $subHistory,
            'user' => $user,
            'announcements' => $announcementsRepository->findBy(['user' => $user])
        ]);
    }

    /**
     * @Route("/users/{slug}/edit", name="admin_users_edit", methods={"GET","POST"})
     */
    public function editUsers(Request $request, Users $user): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users/{id}", name="admin_users_delete", methods={"DELETE"})
     */
    public function deleteUsers(Request $request, Users $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_admin/users_index');
    }



    // -------------------------------------------------------------------------------
    // -----------------------  Administration des Abonnements -----------------------
    // -------------------------------------------------------------------------------

    /**
     * @Route("/subscription/", name="admin_subscription_index", methods={"GET"})
     */
    public function indexSubscription(SubscriptionRepository $subscriptionRepository): Response
    {
        return $this->render('admin/subscription/index.html.twig', [
            'subscriptions' => $subscriptionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/subscription/new", name="admin_subscription_new", methods={"GET","POST"})
     */
    public function newSubscription(Request $request): Response
    {
        $subscription = new Subscription();
        $form = $this->createForm(SubscriptionType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subscription);
            $entityManager->flush();

            return $this->redirectToRoute('admin_subscription_index');
        }

        return $this->render('admin/subscription/new.html.twig', [
            'subscription' => $subscription,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/subscription/{id}", name="admin_subscription_show", methods={"GET"})
     */
    public function showSubscription(Subscription $subscription): Response
    {
        return $this->render('admin/subscription/show.html.twig', [
            'subscription' => $subscription,
        ]);
    }

    /**
     * @Route("/subscription/{id}/edit", name="admin_subscription_edit", methods={"GET","POST"})
     */
    public function editSubscription(Request $request, Subscription $subscription): Response
    {
        $form = $this->createForm(SubscriptionType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_subscription_index');
        }

        return $this->render('admin/subscription/edit.html.twig', [
            'subscription' => $subscription,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/subscription/{id}", name="admin_subscription_delete", methods={"DELETE"})
     */
    public function deleteSubscription(Request $request, Subscription $subscription): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subscription->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subscription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_subscription_index');
    }



    // -------------------------------------------------------------------------------
    // -----------------  Administration des Secteurs / Professions -----------------
    // -------------------------------------------------------------------------------

    /**
     * @Route("/sectors_professions/", name="admin_sectors_professions_index", methods={"GET"})
     */
    public function index(ActivitySectorRepository $activitySectorRepository, ProfessionsRepository $professionsRepository): Response
    {
        return $this->render('admin/sectors_professions/index.html.twig', [
            'sectors' => $activitySectorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/sectors_professions/{id}", name="admin_sector_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ActivitySector $activitySector): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activitySector->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activitySector);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_sectors_professions_index');
    }

}
