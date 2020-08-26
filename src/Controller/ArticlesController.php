<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Entity\ArticlesComment;
use App\Form\ArticlesCommentType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/articles")
 * @package App\Controller
 */

class ArticlesController extends AbstractController
{
    /**
     * @Route("/", name="articles_index")
     */
    public function index(ArticlesRepository $articlesRepo)
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepo->findAll()
        ]);
    }

    /**
     * @Route("/manage", name="articles_manage")
     */
    public function manage(ArticlesRepository $articlesRepo)
    {
        return $this->render('articles/manage.html.twig', [
            'articles' => $articlesRepo->findAll()
        ]);
    }

    /**
     * @Route("/add", name="articles_add")
     */
    public function add(Request $request)
    {
        $article = new Articles;

        $form = $this->createForm(ArticlesType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $article->setUsers($this->getUser());
            $article->setActive(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('users');
        }

        return $this->render('articles/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activate/{id}", name="articles_activate")
     */
    public function activate(Articles $article)
    {
        $article->setActive(($article->getActive())?false:true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return new Response("true");
    }

    /**
     * @Route("/delete/{id}", name="articles_delete")
     */
    public function delete(Articles $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        $this->addFlash('message', 'Article supprimé avec succès');
        return $this->redirectToRoute('articles_manage');
    }

    /**
     * @Route("/{id}", name="articles_show")
     */
    public function show($id, Articles $articles, ArticlesRepository $articlesRepo, Request $request, EntityManagerInterface $manager) 
    {
        $articlesComment = new ArticlesComment();
        
        $form = $this->createForm(ArticlesCommentType::class, $articlesComment);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
                $articlesComment->setCreatedAt(new \DateTime())
                        ->setArticle($articles);
                        
                $manager->persist($articlesComment);
                $manager->flush();
                
                return $this->redirectToRoute('articles_show', ['id' => $articles->getId()]);
            }

        return $this->render('articles/show.html.twig', [
            'articles' => $articlesRepo->find($id),
            'articlesCommentForm' => $form->createView()
        ]);
    }
}
