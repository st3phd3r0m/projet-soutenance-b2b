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
use App\Repository\AnnouncementsRepository;
use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    // -------------------------------------------------------------------------------
    // ----------------------- Page d'acceuil Administration -----------------------
    // -------------------------------------------------------------------------------

    /**
     * @Route("/", name="admin_dashboard")
     */
    public function dashboard()
    {
        return $this->render('admin/index.html.twig');
    }


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
     * @Route("/users/{slug}", name="admin_users_show", methods={"GET"})
     */
    public function showUsers(Users $user, AnnouncementsRepository $announcementsRepository): Response
    {
        return $this->render('admin/users/show.html.twig', [
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
    // -------------------  Administration des Secteurs d'activité -------------------
    // -------------------------------------------------------------------------------

    /**
     * @Route("/activity_sector/", name="admin_activity_sector_index", methods={"GET"})
     */
    public function indexActivitySector(ActivitySectorRepository $activitySectorRepository): Response
    {
        return $this->render('admin/activity_sector/index.html.twig', [
            'sectors' => $activitySectorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/activity_sector/{id}", name="admin_activity_sector_delete", methods={"DELETE"})
     */
    public function deleteActivitySector(Request $request, ActivitySector $activitySector): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activitySector->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activitySector);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_activity_sector_index');
    }

    /**
     * @Route("/activity_sector/new", name="admin_activity_sector_new", methods={"GET","POST"})
     */
    public function newActivitySector(Request $request): Response
    {
        $activitySector = new ActivitySector();
        $form = $this->createForm(ActivitySectorType::class, $activitySector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activitySector);
            $entityManager->flush();

            return $this->redirectToRoute('admin_activity_sector_index');
        }

        return $this->render('admin/activity_sector/new.html.twig', [
            'activitySector' => $activitySector,
            'form' => $form->createView(),
        ]);
    }


    // -------------------------------------------------------------------------------
    // ------------------  Administration des catégories d'annonces ------------------
    // -------------------------------------------------------------------------------

    /**
     * @Route("/categories/", name="admin_categories_index", methods={"GET"})
     */
    public function indexCategories(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/categories/new", name="admin_categories_new", methods={"GET","POST"})
     */
    public function newCategories(Request $request): Response
    {
        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/categories/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/categories/{id}/edit", name="admin_categories_edit", methods={"GET","POST"})
     */
    public function editCategories(Request $request, Categories $category): Response
    {
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/categories/{id}", name="admin_categories_delete", methods={"DELETE"})
     */
    public function deleteCategories(Request $request, Categories $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_categories_index');
    }

}
