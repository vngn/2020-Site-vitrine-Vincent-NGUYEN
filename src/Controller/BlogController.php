<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Entity\BlogComment;
use App\Form\BlogCommentType;
use App\Repository\BlogRepository;
use App\Repository\UsersRepository;
use App\Repository\ContactRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/blog")
 * @package App\Controller
 */

class BlogController extends AbstractController
{
    /**
     * @Route("", name="blog_index")
     */
    public function index(BlogRepository $blogRepo, CategoriesRepository $catsRepo, ContactRepository $contactRepo, UsersRepository $usersRepo)
    {
        return $this->render('blog/index.html.twig', [
            'blog' => $blogRepo->findAll(),
            'categoriesButton' => $catsRepo->findAll(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/manage", name="blog_manage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function manage(BlogRepository $blogRepo, ContactRepository $contactRepo, UsersRepository $usersRepo)
    {
        return $this->render('blog/manage.html.twig', [
            'blog' => $blogRepo->findAll(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/add", name="blog_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, ContactRepository $contactRepo, UsersRepository $usersRepo)
    {
        $blog = new Blog;

        $form = $this->createForm(BlogType::class, $blog);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setUsers($this->getUser());
            $blog->setActive(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('blog_index');
        }
        return $this->render('/blog/add.html.twig', [
            'form' => $form->createView(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/activate/{id}", name="blog_activate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function activate(Blog $blog)
    {
        $blog->setActive(($blog->getActive()) ? false : true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($blog);
        $em->flush();
        return new Response("true");
    }

    /**
     * @Route("/edit/{id}", name="blog_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Blog $blog, SluggerInterface $slugger, ContactRepository $contactRepo, UsersRepository $usersRepo): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_manage');
        }

        return $this->render('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/commentEdit/{id}", name="blog_comment_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function commentEdit(Request $request, BlogComment $blogComment, SluggerInterface $slugger, ContactRepository $contactRepo, UsersRepository $usersRepo): Response
    {
        $form = $this->createForm(BlogCommentType::class, $blogComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_manage');
        }

        return $this->render('blog/commentEdit.html.twig', [
            'blog' => $blogComment,
            'form' => $form->createView(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="blog_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Blog $blog)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($blog);
        $em->flush();
        $this->addFlash('message', 'Article supprimé avec succès');
        return $this->redirectToRoute('blog_manage');
    }

    /**
     * @Route("/commentDelete/{id}", name="blog_comment_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function commentDelete(BlogComment $blogComment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($blogComment);
        $em->flush();
        $this->addFlash('message', 'Commentaire supprimé avec succès');
        return $this->redirectToRoute('blog_manage');
    }

    /**
     * @Route("/{id}", name="blog_show")
     */
    public function show($id, Blog $blog, BlogRepository $blogRepo, Request $request, EntityManagerInterface $manager)
    {
        $blogComment = new BlogComment();

        $form = $this->createForm(BlogCommentType::class, $blogComment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogComment->setCreatedAt(new \DateTime())
                ->setArticle($blog)
                ->setUsers($this->getUser());
            $manager->persist($blogComment);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $blog->getId()]);
        }

        return $this->render('blog/show.html.twig', [
            'blog' => $blogRepo->find($id),
            'blogCommentForm' => $form->createView()
        ]);
    }
}
