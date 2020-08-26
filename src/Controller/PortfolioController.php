<?php

namespace App\Controller;

use App\Entity\Portfolio;
use App\Form\PortfolioType;
use App\Entity\PortfolioComment;
use App\Form\PortfolioCommentType;
use App\Repository\PortfolioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/portfolio")
 * @package App\Controller
 */

class PortfolioController extends AbstractController
{
    /**
     * @Route("/", name="portfolio_index")
     */
    public function index(PortfolioRepository $portfolioRepo)
    {
        return $this->render('portfolio/index.html.twig', [
            'portfolio' => $portfolioRepo->findAll()
        ]);
    }

    /**
     * @Route("/add", name="portfolio_add")
     */
    public function add(Request $request)
    {
        $portfolio = new Portfolio;

        $form = $this->createForm(PortfolioType::class, $portfolio);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $portfolio->setUsers($this->getUser());
            $portfolio->setActive(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($portfolio);
            $em->flush();

            return $this->redirectToRoute('users');
        }

        return $this->render('portfolio/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="portfolio_show")
     */
    public function show($id, Portfolio $portfolio, PortfolioRepository $portfolioRepo, Request $request, EntityManagerInterface $manager) 
    {
        $portfolioComment = new PortfolioComment();
        
        $form = $this->createForm(PortfolioCommentType::class, $portfolioComment);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
                $portfolioComment->setCreatedAt(new \DateTime())
                        ->setArticle($portfolio);
                        
                $manager->persist($portfolioComment);
                $manager->flush();
                
                return $this->redirectToRoute('portfolio_show', ['id' => $portfolio->getId()]);
            }

        return $this->render('portfolio/show.html.twig', [
            'portfolio' => $portfolioRepo->find($id),
            'portfolioCommentForm' => $form->createView()
        ]);
    }
}
