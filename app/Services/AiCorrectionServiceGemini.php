<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiCorrectionServiceGemini
{
    protected string $apiKey;

    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->model = 'gemini-flash-latest';
    }

    public function correctTranslation(string $textPt, string $textEnReference, string $userTextEn): array
    {
        $prompt = $this->buildPrompt($textPt, $textEnReference, $userTextEn);

        // URL com a API key como query parameter
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.3,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 2048,
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception('Erro Gemini API: '.$response->body());
            }

            $content = $response->json('candidates.0.content.parts.0.text');

            if (! $content) {
                Log::error('Empty response from Gemini', [
                    'full_response' => $response->json(),
                ]);
                throw new \Exception('Resposta vazia da API');
            }

            // Limpar possíveis markdown code blocks
            $content = preg_replace('/```json\n?/', '', $content);
            $content = preg_replace('/```\n?/', '', $content);
            $content = trim($content);

            $result = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON Decode Error', [
                    'error' => json_last_error_msg(),
                    'content' => $content,
                ]);
                throw new \Exception('Erro ao processar resposta da IA: '.json_last_error_msg());
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('AI Correction Service Error', [
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    protected function buildPrompt(string $textPt, string $textEnReference, string $userTextEn): string
    {
        return <<<PROMPT
Você é um professor de inglês especializado em correção de traduções do português para o inglês.

Analise a tradução do aluno e retorne APENAS um JSON válido (sem markdown, sem explicações extras).

**Frase original (PT):** {$textPt}
**Tradução de referência (EN):** {$textEnReference}
**Tradução do aluno (EN):** {$userTextEn}

Retorne um JSON com esta estrutura EXATA:
{
  "is_correct": false,
  "score": 60,
  "overall_comment": "comentário geral em português",
  "mistakes": [
    {
      "type": "grammar",
      "original": "trecho errado",
      "suggestion": "correção",
      "explanation_pt": "explicação simples em português do erro"
    }
  ],
  "corrected_sentence": "frase corrigida mantendo a intenção do aluno",
  "natural_alternatives": [
    "forma mais natural 1",
    "forma mais natural 2"
  ],
  "positive_points": [
    "o que o aluno acertou"
  ]
}

Critérios de avaliação:
- is_correct = true apenas se está perfeito ou com detalhes muito pequenos aceitáveis
- score considera: gramática (40%), vocabulário (30%), naturalidade (30%)
- Se não houver erros, "mistakes" deve ser um array vazio []
- Sempre destaque pelo menos 1-2 pontos positivos em "positive_points"
- Seja encorajador mas honesto
- "natural_alternatives" deve ter 2-3 formas diferentes de expressar a mesma ideia

IMPORTANTE: Retorne APENAS o JSON, sem texto antes ou depois, sem ```json ou ```.
PROMPT;
    }
}
