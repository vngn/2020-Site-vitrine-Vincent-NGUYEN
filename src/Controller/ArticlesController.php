<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Entity\ArticlesComment;
use App\Form\ArticlesCommentType;
use App\Repository\UsersRepository;
use App\Repository\ContactRepository;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/articles")
 * @package App\Controller
 */

class ArticlesController extends AbstractController
{
    /**
     * @Route("", name="articles_index")
     */
    public function index(ArticlesRepository $articlesRepo, CategoriesRepository $catsRepo, ContactRepository $contactRepo, UsersRepository $usersRepo)
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepo->findBy([],['createdAt' => 'DESC']),
            'categoriesButton' => $catsRepo->findAll(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/manage", name="articles_manage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function manage(ArticlesRepository $articlesRepo, ContactRepository $contactRepo, UsersRepository $usersRepo)
    {
        return $this->render('articles/manage.html.twig', [
            'articles' => $articlesRepo->findAll(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/add", name="articles_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, ContactRepository $contactRepo, UsersRepository $usersRepo)
    {
        $article = new Articles;

        $form = $this->createForm(ArticlesType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUsers($this->getUser());
            $article->setActive(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('articles_index');
        }

        return $this->render('articles/add.html.twig', [
            'form' => $form->createView(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/activate/{id}", name="articles_activate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function activate(Articles $article)
    {
        $article->setActive(($article->getActive()) ? false : true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return new Response("true");
    }

    /**
     * @Route("/edit/{id}", name="articles_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Articles $articles, SluggerInterface $slugger, ContactRepository $contactRepo, UsersRepository $usersRepo): Response
    {
        $form = $this->createForm(ArticlesType::class, $articles);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('articles_manage');
        }

        return $this->render('articles/edit.html.twig', [
            'articles' => $articles,
            'form' => $form->createView(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/commentEdit/{id}", name="articles_comment_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function commentEdit(Request $request, ArticlesComment $articlesComment, SluggerInterface $slugger, ContactRepository $contactRepo, UsersRepository $usersRepo): Response
    {
        $form = $this->createForm(ArticlesCommentType::class, $articlesComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('articles_manage');
        }

        return $this->render('articles/commentEdit.html.twig', [
            'articles' => $articlesComment,
            'form' => $form->createView(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="articles_delete")
     * @IsGranted("ROLE_ADMIN")
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
     * @Route("/commentDelete/{id}", name="articles_comment_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function commentDelete(ArticlesComment $articlesComment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($articlesComment);
        $em->flush();
        $this->addFlash('message', 'Commentaire supprimé avec succès');
        return $this->redirectToRoute('articles_manage');
    }

    /**
     * @Route("/{id}", name="articles_show")
     */
    public function show($id, Articles $articles, ArticlesRepository $articlesRepo, Request $request, EntityManagerInterface $manager, ContactRepository $contactRepo, UsersRepository $usersRepo)
    {
        $articlesComment = new ArticlesComment();

        $form = $this->createForm(ArticlesCommentType::class, $articlesComment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articlesComment->setCreatedAt(new \DateTime())
                ->setArticle($articles)
                ->setUsers($this->getUser());
            $manager->persist($articlesComment);
            $manager->flush();

            return $this->redirectToRoute('articles_show', ['id' => $articles->getId()]);
        }

        return $this->render('articles/show.html.twig', [
            'articles' => $articlesRepo->find($id),
            'articlesCommentForm' => $form->createView(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }
}
