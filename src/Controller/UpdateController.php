<?php

namespace App\Controller;

use App\Entity\Update;
use App\Form\UpdateType;
use App\Repository\UsersRepository;
use App\Repository\UpdateRepository;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/update")
 * @package App\Controller
 */

class UpdateController extends AbstractController
{
    /**
     * @Route("/", name="update_index")
     */
    public function index(UpdateRepository $updateRepo, ContactRepository $contactRepo, UsersRepository $usersRepo)
    {
        return $this->render('update/index.html.twig', [
            'update' => $updateRepo->findBy([],['createdAt' => 'DESC']),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/add", name="update_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, ContactRepository $contactRepo, UsersRepository $usersRepo)
    {
        $update = new Update;

        $form = $this->createForm(UpdateType::class, $update);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($update);
            $em->flush();

            return $this->redirectToRoute('update_index');
        }

        return $this->render('update/add.html.twig', [
            'form' => $form->createView(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="update_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Update $update, Request $request, ContactRepository $contactRepo, UsersRepository $usersRepo)
    {
        $form = $this->createForm(UpdateType::class, $update);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($update);
            $em->flush();

            return $this->redirectToRoute('update_index');
        }

        return $this->render('update/add.html.twig', [
            'form' => $form->createView(),
            'contact' => $contactRepo->findAll(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="update_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Update $update)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($update);
        $em->flush();

        $this->addFlash('message', 'update supprimée avec succès');
        return $this->redirectToRoute('update_index');
    }
}
