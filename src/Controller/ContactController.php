<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\UsersRepository;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/contact")
 * @package App\Controller
 */

class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact_index")
     */
    public function index(ContactRepository $contactRepo, Request $request, UsersRepository $usersRepo)
    {

        $contact = new Contact;

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contact->setActive(true);


            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('contact/index.html.twig', [
            'contact' => $contactRepo->findAll(),
            'form' => $form->createView(),
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("/activate/{id}", name="contact_activate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function activate(Contact $contact)
    {
        $contact->setActive(($contact->getActive()) ? false : true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($contact);
        $em->flush();
        return new Response("true");
    }
}
