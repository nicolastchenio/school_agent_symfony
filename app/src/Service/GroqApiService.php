<?php

namespace App\Service;

use App\Entity\Agent;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GroqApiService
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly string $groqApiKey,
    ) {
    }

    public function askAI(string $prompt, Agent $agent): string
    {
        $payload = [
            "messages" => [
                ["role" => "system", "content" => $agent->getPromptSystem()],
                ["role" => "user", "content" => $prompt]
            ],
            "model" => $agent->getModel(),
            "temperature" => $agent->getTemperature(),
            "max_tokens" => $agent->getMaxCompletionTokens(),
        ];

        try {
            $response = $this->client->request('POST', 'https://api.groq.com/openai/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->groqApiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $data = $response->toArray();

            return $data['choices'][0]['message']['content'] ?? "❌ Erreur : Réponse de l'API invalide.";

        } catch (\Exception $e) {
            return "❌ Une erreur est survenue lors de la communication avec l'API : " . $e->getMessage();
        }
    }
}