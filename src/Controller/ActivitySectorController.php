<?php

namespace App\Controller;

use App\Entity\ActivitySector;
use App\Form\ActivitySectorType;
use App\Repository\ActivitySectorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/activity/sector")
 */
class ActivitySectorController extends AbstractController
{
    /**
     * @Route("sectors/", name="activity_sector_index", methods={"GET"})
     */
    public function index(ActivitySectorRepository $activitySectorRepository): Response
    {
        return $this->render('activity_sector/index.html.twig', [
            'activity_sectors' => $activitySectorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="activity_sector_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $activitySector = new ActivitySector();
        $form = $this->createForm(ActivitySectorType::class, $activitySector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activitySector);
            $entityManager->flush();

            return $this->redirectToRoute('activity_sector_index');
        }

        return $this->render('activity_sector/new.html.twig', [
            'activity_sector' => $activitySector,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="activity_sector_show", methods={"GET"})
     */
    public function show(ActivitySector $activitySector): Response
    {
        return $this->render('activity_sector/show.html.twig', [
            'activity_sector' => $activitySector,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="activity_sector_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ActivitySector $activitySector): Response
    {
        $form = $this->createForm(ActivitySectorType::class, $activitySector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activity_sector_index');
        }

        return $this->render('activity_sector/edit.html.twig', [
            'activity_sector' => $activitySector,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="activity_sector_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ActivitySector $activitySector): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activitySector->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activitySector);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activity_sector_index');
    }
}
