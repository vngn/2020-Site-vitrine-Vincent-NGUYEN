<?php
namespace App\Controller;
use App\Entity\Blog;
use App\Form\BlogType;
use App\Entity\BlogComment;
use App\Form\BlogCommentType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/blog")
 * @package App\Controller
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index")
     */
    public function index(BlogRepository $blogRepo)
    {
        return $this->render('blog/index.html.twig', [
            'blog' => $blogRepo->findAll()
        ]);
    }

    /**
     * @Route("/manage", name="blog_manage")
     */
    public function manage(BlogRepository $blogRepo)
    {
        return $this->render('blog/manage.html.twig', [
            'blog' => $blogRepo->findAll()
        ]);
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add(Request $request)
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

            return $this->redirectToRoute('users');
        }
        return $this->render('/blog/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activate/{id}", name="blog_activate")
     */
    public function activate(Blog $blog)
    {
        $blog->setActive(($blog->getActive())?false:true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($blog);
        $em->flush();
        return new Response("true");
    }
    
    /**
     * @Route("/delete/{id}", name="blog_delete")
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
     * @Route("/{id}", name="blog_show")
     */
    public function show($id, Blog $blog, BlogRepository $blogRepo, Request $request, EntityManagerInterface $manager) 
    {
        $blogComment = new BlogComment();
        
        $form = $this->createForm(BlogCommentType::class, $blogComment);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
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





