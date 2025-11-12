<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Dom\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $user = new Utilisateur;
        // $user->setEmail('nicolas@nicolas.com')
        //     ->setPassword($hasher->hashPassword($user, 'nicolas'))
        //     ->setNom('Nicolas')
        //     ->setPrenom('Nicolas')
        //     ->setRoles(['ROLE_USER']);


        // $user->setEmail('jean@jean.com')
        //     ->setPassword($hasher->hashPassword($user, 'jean'))
        //     ->setNom('jean')
        //     ->setPrenom('jean')
        //     ->setRoles(['ROLE_USER']);

        // $em->persist($user);
        // $em->flush();
        
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route('/home/test', name: 'app_home_test')]
    public function test(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('home/test.html.twig', [
            'message' => 'Bonjour, vous êtes sur home test 👋',
        ]);
    }

}


