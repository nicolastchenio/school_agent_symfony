<?php

namespace App\Controller;

use App\Enum\Role;
use App\Entity\Utilisateur;
use App\Entity\NiveauScolaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class HomeController extends AbstractController
{
    #[Route(path: ['/', '/home'], name: 'app_home')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {
        // --- Niveau scolaire commun (obligatoire selon MCD : 1,1) ---
        $niveau = $em->getRepository(NiveauScolaire::class)->findOneBy(['niveau' => 'L1']);
        if (!$niveau) {
            $niveau = (new NiveauScolaire())->setNiveau('L1');
            $em->persist($niveau);
            $em->flush(); // On flush ici pour avoir un ID
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/home/test', name: 'app_home_test')]
    public function test(): Response
    {
        $this->denyAccessUnlessGranted(Role::ETUDIANT->value);
        return $this->render('home/test.html.twig', [
            'message' => 'Bonjour, vous êtes sur home test',
        ]);
    }
}