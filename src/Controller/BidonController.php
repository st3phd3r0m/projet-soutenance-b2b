<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BidonController extends AbstractController
{
    /**
     * @Route("/bidon", name="bidon")
     */
    public function index()
    {
        return $this->render('bidon/index.html.twig', [
            'controller_name' => 'BidonController',
        ]);
    }
}