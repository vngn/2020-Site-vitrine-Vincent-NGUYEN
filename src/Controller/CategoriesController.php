<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\UsersRepository;
use App\Repository\ContactRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/categories")
 * @package App\Controller
 */

class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="categories_index")
     */
    public function index(CategoriesRepository $catsRepo, ContactRepository $contactRepo, UsersRepository $usersRepo)
    {
        return $this->render('categories/index.html.twig', [
            'categories' => $catsRepo->findAll(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/add", name="categories_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, ContactRepository $contactRepo, UsersRepository $usersRepo)
    {
        $categorie = new Categories;

        $form = $this->createForm(CategoriesType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('categories_index');
        }

        return $this->render('categories/add.html.twig', [
            'form' => $form->createView(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="categories_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Categories $categorie, Request $request)
    {
        $form = $this->createForm(CategoriesType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('categories_index');
        }

        return $this->render('categories/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="categories_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Categories $categorie)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        $this->addFlash('message', 'Categorie supprimée avec succès');
        return $this->redirectToRoute('categories_index');
    }
}
