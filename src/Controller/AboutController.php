<?php

namespace App\Controller;

use App\Entity\About;
use App\Repository\AboutRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/about")
 * @package App\Controller
 */

class AboutController extends AbstractController
{
    /**
     * @Route("/", name="about_index")
     */
    public function index(AboutRepository $aboutRepo)
    {
        return $this->render('about/index.html.twig', [
            'about' => $aboutRepo->findAll()
        ]);
    }
}
