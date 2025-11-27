<?php

namespace App\Controller;

use App\Entity\NiveauScolaire;
use App\Form\NiveauScolaireType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NiveauScolaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/niveau/scolaire')]
#[IsGranted('ROLE_ADMIN')]
final class NiveauScolaireController extends AbstractController
{
    #[Route(name: 'app_niveau_scolaire_index', methods: ['GET'])]
    public function index(NiveauScolaireRepository $niveauScolaireRepository): Response
    {
        return $this->render('niveau_scolaire/index.html.twig', [
            'niveau_scolaires' => $niveauScolaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_niveau_scolaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $niveauScolaire = new NiveauScolaire();
        $form = $this->createForm(NiveauScolaireType::class, $niveauScolaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($niveauScolaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_niveau_scolaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('niveau_scolaire/new.html.twig', [
            'niveau_scolaire' => $niveauScolaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_niveau_scolaire_show', methods: ['GET'])]
    public function show(NiveauScolaire $niveauScolaire): Response
    {
        return $this->render('niveau_scolaire/show.html.twig', [
            'niveau_scolaire' => $niveauScolaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_niveau_scolaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NiveauScolaire $niveauScolaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NiveauScolaireType::class, $niveauScolaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_niveau_scolaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('niveau_scolaire/edit.html.twig', [
            'niveau_scolaire' => $niveauScolaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_niveau_scolaire_delete', methods: ['POST'])]
    public function delete(Request $request, NiveauScolaire $niveauScolaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$niveauScolaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($niveauScolaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_niveau_scolaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
