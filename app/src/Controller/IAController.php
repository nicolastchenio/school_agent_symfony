<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Message;
use App\Entity\Utilisateur;
use App\Entity\Conversation;
use App\Service\GroqApiService;
use App\Repository\AgentRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use Symfony\Component\Security\Http\Attribute\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/ia')]
#[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ETUDIANT')")]// Sécurité : toutes les routes de ce contrôleur nécessitent d'être connecté
final class IAController extends AbstractController
{

    public function __construct(
        private readonly AgentRepository $agentRepository,
        private readonly ConversationRepository $conversationRepository,
        private readonly MessageRepository $messageRepository,
        private readonly GroqApiService $groqApiService,
        private readonly EntityManagerInterface $entityManager
        ) {
    }
    
    /**
     * Affiche la liste des agents disponibles pour l'utilisateur.
     * Équivalent de l'ancienne méthode IaController->index().
    */
    #[Route('', name: 'app_ia_index')]
    public function index(): Response
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        // Sécurité : s'assurer qu'un utilisateur est bien connecté
        if (!$user) {
            // Normalement, la sécurité de Symfony devrait déjà empêcher ça.
            // C'est une double sécurité.
            return $this->redirectToRoute('app_login');
        }

        // On récupère les agents directement depuis l'utilisateur
        $agents = $user->getAgents();

        return $this->render('ia/index.html.twig', [
        'agents' => $agents,
        ]);
    }

    /**
     * Affiche l'historique des conversations pour un agent donné.
     * Équivalent de IaController->showConversations().
    */
    #[Route('/conversations/{id}', name: 'app_ia_conversations')]
    public function conversations(Agent $agent): Response
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        // Sécurité : Vérifier que l'agent appartient bien à l'utilisateur connecté
        if (!$agent->getUtilisateurs()->contains($user)) {
            $this->addFlash('error', 'Accès non autorisé à cet agent.');
            return $this->redirectToRoute('app_ia_index');
        }

        // Récupérer les conversations pour cet agent et cet utilisateur
        $conversations = $this->conversationRepository->findBy([
            'utilisateur' => $user,
            'agent' => $agent,
        ], ['creerLe' => 'DESC']); // Tri par date de création

        return $this->render('ia/conversations.html.twig', [
            'agent' => $agent,
            'conversations' => $conversations,
        ]);
    }

    /**
     * Affiche le chat d'une conversation existante ou démarre une nouvelle conversation.
     * Gère à la fois IaController->showChat() et la logique de POST.
    */
    #[Route('/chat/{id?}', name: 'app_ia_chat')]
    public function chat(Request $request, ?Conversation $conversation, ?Agent $agent = null): Response
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();
        $isNew = !$conversation;
        $messages = [];

        if ($isNew) {
            // C'est une nouvelle conversation
            $agentId = $request->query->get('agentId');
            if (!$agentId) {
                $this->addFlash('error', 'Agent non spécifié pour une nouvelle conversation.');
                return $this->redirectToRoute('app_ia_index');
            }
            $agent = $this->agentRepository->find($agentId);

            // On vérifie si l'utilisateur fait partie de la collection.
            if (!$agent || !$agent->getUtilisateurs()->contains($user)) {
                $this->addFlash('error', 'Agent non trouvé ou accès non autorisé.');
                return $this->redirectToRoute('app_ia_index');
            }

            // On prépare une conversation "virtuelle" pour le titre de la page
            $conversation = (new Conversation())->setTitre('Nouvelle conversation');

        } else {
            // C'est une conversation existante
            if ($conversation->getUtilisateur() !== $user) {
                $this->addFlash('error', 'Accès non autorisé à cette conversation.');
                return $this->redirectToRoute('app_ia_index');
            }
            $agent = $conversation->getAgent();
            $messages = $this->messageRepository->findBy(['conversation' => $conversation], ['id' => 'ASC']);
        }

        // Traitement de l'envoi d'un message (pour nouveau ou existant)
        if ($request->isMethod('POST') && $request->request->has('prompt')) {
            $prompt = $request->request->get('prompt');

            if ($isNew) {
                // Logique pour le PREMIER message : créer la conversation + le message
                $conversation->setTitre('Conversation du ' . (new \DateTime())->format('d-m-Y H:i'))
                    ->setCreerLe(new \DateTime())
                    ->setAgent($agent)
                    ->setUtilisateur($user);
                $this->entityManager->persist($conversation);
                $this->entityManager->flush(); // Pour obtenir l'ID de la nouvelle conversation
            }

            // Demander à l'IA
            $responseAI = $this->groqApiService->askAI($prompt, $agent);

            // Créer et persister le message avec la question et la réponse
            $message = (new Message())
                ->setQuestion($prompt)
                ->setReponse($responseAI)
                ->setConversation($conversation);
            $this->entityManager->persist($message);

            $this->entityManager->flush();

            // Rediriger vers la page de chat avec l'ID permanent
            return $this->redirectToRoute('app_ia_chat', ['id' => $conversation->getId()]);
        }

        return $this->render('ia/chat.html.twig', [
            'conversation' => $conversation,
            'agent' => $agent,
            'messages' => $messages,
            'isNew' => $isNew
        ]);
    }

    /**
     * Gère la suppression d'une conversation.
     * Équivalent de IaController->deleteConversation().
    */
    #[Route('/conversation/delete/{id}', name: 'app_ia_conversation_delete', methods: ['POST'])]
    public function deleteConversation(Request $request, Conversation $conversation): Response
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        // Sécurité : Vérifier que la conversation appartient bien à l'utilisateur
        if ($conversation->getUtilisateur() !== $user) {
            $this->addFlash('error', 'Action non autorisée.');
            return $this->redirectToRoute('app_ia_index');
        }

        // On s'assure que le token CSRF est valide pour la sécurité
        $token = $request->request->get('_token');
        if (is_string($token) && $this->isCsrfTokenValid('delete'.$conversation->getId(), $token)) {
            $agentId = $conversation->getAgent()->getId(); // Récupérer l'ID de l'agent avant suppression
            
            $this->entityManager->remove($conversation);
            $this->entityManager->flush();

            $this->addFlash('success', 'Conversation supprimée avec succès.');
            return $this->redirectToRoute('app_ia_conversations', ['id' => $agentId]);
        }

        $this->addFlash('error', 'Token CSRF invalide.');
        return $this->redirectToRoute('app_ia_conversations', ['id' => $conversation->getAgent()->getId()]);
    }

}
