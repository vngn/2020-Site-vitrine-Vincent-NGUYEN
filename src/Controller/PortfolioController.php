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
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @Route("/manage", name="portfolio_manage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function manage(PortfolioRepository $portfolioRepo)
    {
        return $this->render('portfolio/manage.html.twig', [
            'portfolio' => $portfolioRepo->findAll()
        ]);
    }

    /**
     * @Route("/add", name="portfolio_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request)
    {
        $portfolio = new Portfolio;

        $form = $this->createForm(PortfolioType::class, $portfolio);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $portfolio->setUsers($this->getUser());
            $portfolio->setActive(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($portfolio);
            $em->flush();

            return $this->redirectToRoute('portfolio_index');
        }

        return $this->render('portfolio/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activate/{id}", name="portfolio_activate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function activate(Portfolio $portfolio)
    {
        $portfolio->setActive(($portfolio->getActive())?false:true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($portfolio);
        $em->flush();
        return new Response("true");
    }

    /**
     * @Route("/edit/{id}", name="portfolio_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Portfolio $portfolio, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('portfolio_index');
        }

        return $this->render('portfolio/edit.html.twig', [
            'portfolio' => $portfolio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="portfolio_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Portfolio $portfolio)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($portfolio);
        $em->flush();
        $this->addFlash('message', 'Réalisation supprimée avec succès');
        return $this->redirectToRoute('portfolio_manage');
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
                        ->setArticle($portfolio)
                        ->setUsers($this->getUser());
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
