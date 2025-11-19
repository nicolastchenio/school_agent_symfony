<?php

namespace App\Service;

use Gemini\Client;
use Gemini\Data\Content;
use Gemini\Data\GenerationConfig;
use Gemini\Data\SafetySetting;
use Gemini\Enums\HarmBlockThreshold;
use Gemini\Enums\HarmCategory;
use Gemini\Enums\ModelName;

class GeminiService
{
    public function __construct(private readonly Client $client)
    {
    }

    public function getResponse(string $systemPrompt, string $userMessage, float $temperature): string
    {
        $safetySettings = [
            new SafetySetting(
                category: HarmCategory::HARM_CATEGORY_HARASSMENT,
                threshold: HarmBlockThreshold::BLOCK_NONE
            ),
            new SafetySetting(
                category: HarmCategory::HARM_CATEGORY_HATE_SPEECH,
                threshold: HarmBlockThreshold::BLOCK_NONE
            ),
            new SafetySetting(
                category: HarmCategory::HARM_CATEGORY_SEXUALLY_EXPLICIT,
                threshold: HarmBlockThreshold::BLOCK_NONE
            ),
            new SafetySetting(
                category: HarmCategory::HARM_CATEGORY_DANGEROUS_CONTENT,
                threshold: HarmBlockThreshold::BLOCK_NONE
            ),
        ];

        $generationConfig = new GenerationConfig(
            temperature: $temperature,
        );

        $response = $this->client
            ->geminiPro()
            ->withSafetySettings($safetySettings)
            ->withGenerationConfig($generationConfig)
            ->generateContent(
                Content::text($systemPrompt),
                Content::text($userMessage)
            );

        return $response->text();
    }
}
