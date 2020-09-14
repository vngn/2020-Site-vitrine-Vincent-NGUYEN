<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController extends AbstractController
{
    /**
     * @Route("/update", name="update")
     */
    public function index()
    {
        return $this->render('update/index.html.twig', [
            'controller_name' => 'UpdateController',
        ]);
    }
}
