<?php

namespace App\Controller;

use App\Repository\BlogRepository;
use App\Repository\ContactRepository;
use App\Repository\ArticlesRepository;
use App\Repository\PortfolioRepository;
use App\Repository\BlogCommentRepository;
use App\Repository\ArticlesCommentRepository;
use App\Repository\PortfolioCommentRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PortfolioRepository $portfolioRepo, PortfolioCommentRepository $portfolioCommentRepo, ArticlesRepository $articlesRepo, ArticlesCommentRepository $articlesCommentRepo, BlogRepository $blogRepo, BlogCommentRepository $blogCommentRepo, ContactRepository $contactRepo)
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'MainController',
            'portfolio' => $portfolioRepo->findAll(),
            'portfolioComment' => $portfolioCommentRepo->findAll(),
            'articles' => $articlesRepo->findAll(),
            'articlesComment' => $articlesCommentRepo->findAll(),
            'blog' => $blogRepo->findAll(),
            'blogComment' => $blogCommentRepo->findAll(),
            'contact' => $contactRepo->findAll()
        ]);
    }
}
