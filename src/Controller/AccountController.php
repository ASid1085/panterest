<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {  
        $this->em = $em;
    }

    
    #[Route('/account', name: 'app_account', methods: 'GET')]
    public function show(): Response
    {
        return $this->render('account/show.html.twig');
    }

    #[Route('/account/edit', name: 'app_account_edit', methods: 'GET|POST')]
    public function edit(Request $request): Response
    {   
        $user = $this->getUser();
        if(!$user) {
            $this->addFlash('warning', 'You cannot edit the profile.');

            return $this->redirectToRoute('app_login');
        }
        
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->em->persist($user);
            $this->em->flush();
            

            $this->addFlash('success', 'Profil successfully updated !');

            return $this->redirectToRoute('app_home');
            
        }
        return $this->render('account/edit.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }
}
