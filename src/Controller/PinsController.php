<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {  
        $this->em = $em;
    }

    #[Route('/', name: 'app_home', methods: 'GET')]
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    #[Route("/pins/create", name: 'app_pins_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {   
        $user_current = $this->getUser();
        if(!$user_current) {
            $this->addFlash('info', 'For creating pin thanks sign in or register.');

                return $this->redirectToRoute('app_login');
        } else {
            $pin = new Pin;

            $form = $this->createForm(PinType::class, $pin);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) { 

                $pin->setUser($user_current);

                $this->em->persist($pin);
                $this->em->flush();

                $this->addFlash('success', 'Pin successfully created !');

                return $this->redirectToRoute('app_pins_show', ['id' => $pin->getId()]);
            }

            return $this->render('pins/create.html.twig', [
                'form' => $form->createView()
            ]); 
        }
    }

    #[Route("/pins/{id}", name: 'app_pins_show', methods: 'GET')]
    public function show(Pin $pin): Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    #[Route("/pins/{id}/edit", name: 'app_pins_edit', methods: 'GET|POST')]
    public function edit(Pin $pin, Request $request): Response
    {   
        $user_current = $this->getUser();
        if(!$user_current || $user_current != $pin->getUser()) {
            $this->addFlash('warning', 'You cannot edit this pin.');

            return $this->render('pins/show.html.twig', compact('pin'));
        } else {
            $form = $this->createForm(PinType::class, $pin);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) { 
                $this->em->flush();

                $this->addFlash('success', 'Pin successfully updated !');

                return $this->redirectToRoute('app_home');
            }

            return $this->render('pins/edit.html.twig', [
                'pin'  => $pin,
                'form' => $form->createView(),
            ]); 
        }
        
    }

    #[Route("/pins/{id}", name: 'app_pins_delete', methods: 'DELETE')]
    public function delete(Pin $pin, Request $request): Response
    {   
        $user_current = $this->getUser();
        if(!$user_current || $user_current != $pin->getUser()) {
            $this->addFlash('warning', 'You cannot delete this pin.');

            return $this->render('pins/show.html.twig', compact('pin'));
        } else {
            $token = $request->request->get('csrf_token');

            if($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(), $token)) {
                $this->em->remove($pin);
                $this->em->flush();
            }

            $this->addFlash('info', 'Pin successfully deleted !');
            
            return $this->redirectToRoute('app_home');
        }
        
    }
}
