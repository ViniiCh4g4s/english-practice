<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiCorrectionService
{
    protected string $apiKey;

    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
        $this->model = 'gpt-4o-mini'; // Modelo mais barato da OpenAI
    }

    public function correctTranslation(string $textPt, string $textEnReference, string $userTextEn): array
    {
        $prompt = $this->buildPrompt($textPt, $textEnReference, $userTextEn);

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Você é um professor de inglês especializado em correção de traduções. Sempre responda em JSON válido.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.3,
                ]);

            if ($response->failed()) {
                Log::error('OpenAI API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception('Erro OpenAI API: '.$response->body());
            }

            $content = $response->json('choices.0.message.content');

            if (! $content) {
                Log::error('Empty response from OpenAI', [
                    'full_response' => $response->json(),
                ]);
                throw new \Exception('Resposta vazia da API');
            }

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

            **IMPORTANTE**:
            - Avalie se a tradução está CORRETA, não se está idêntica à referência
            - Contrações (She's, They're, I'm) são equivalentes às formas completas (She is, They are, I am)
            - A referência é apenas um EXEMPLO, não a única resposta correta

            **Frase original (PT):** {$textPt}
            **Tradução de referência (EN):** {$textEnReference}
            **Tradução do aluno (EN):** {$userTextEn}

            CRITÉRIOS DE CORREÇÃO:
            1. Se a tradução está gramaticalmente correta E transmite o mesmo significado → is_correct = true, score = 100
            2. Contrações são SEMPRE corretas (She's = She is, They're = They are, etc)
            3. Pequenas variações estilísticas que não mudam o significado são corretas
            4. Só considere erro se houver: gramática errada, vocabulário errado, significado diferente
            5. CAMPO "corrected_sentence": Se houver erros, retorne a frase CORRIGIDA. Se não houver erros, retorne a frase EXATA do aluno

            Retorne um JSON com esta estrutura EXATA:
            {
              "is_correct": true ou false,
              "score": número de 0 a 100,
              "overall_comment": "comentário geral em português",
              "mistakes": [
                {
                  "type": "grammar, spelling, vocabulary, preposition, article, verb_tense, word_order ou punctuation",
                  "original": "palavra ou trecho EXATO que está errado",
                  "suggestion": "correção EXATA",
                  "explanation_pt": "explicação do erro em português"
                }
              ],
              "corrected_sentence": "SE HOUVER ERROS: frase totalmente corrigida. SE NÃO HOUVER ERROS: copie a frase exata do aluno",
              "natural_alternatives": [
                "forma alternativa 1 (diferente da resposta do aluno)",
                "forma alternativa 2 (diferente da resposta do aluno)"
              ],
              "positive_points": [
                "o que o aluno acertou (seja específico)"
              ]
            }

            EXEMPLOS DE CORREÇÃO CORRETA:

            Exemplo 1 - ERRO REAL:
            Aluno: "I like summer than winter"
            Referência: "I like summer more than winter"
            Resposta correta:
            {
              "is_correct": false,
              "score": 60,
              "mistakes": [{"type": "grammar", "original": "I like summer than winter", "suggestion": "I like summer more than winter", "explanation_pt": "A frase correta deve incluir 'more' para comparar as duas estações corretamente."}],
              "corrected_sentence": "I like summer more than winter.",
              "positive_points": ["O aluno usou a estrutura básica da frase corretamente."]
            }

            Exemplo 2 - SEM ERRO (contração):
            Aluno: "She's learning to drive"
            Referência: "She is learning to drive"
            Resposta correta:
            {
              "is_correct": true,
              "score": 100,
              "mistakes": [],
              "corrected_sentence": "She's learning to drive.",
              "positive_points": ["Frase perfeita! Gramática, vocabulário e estrutura estão corretos. O uso da contração 'She's' é natural e apropriado."]
            }

            REGRAS CRÍTICAS:
            - ❌ NUNCA copie a frase errada do aluno no campo "corrected_sentence" se houver erros
            - ✅ SEMPRE aplique TODAS as correções necessárias no campo "corrected_sentence"
            - ✅ Se não houver erros, mantenha a frase EXATA do aluno (incluindo contrações)
            - ✅ "natural_alternatives" devem ser DIFERENTES da resposta do aluno

            IMPORTANTE: Retorne APENAS o JSON, sem texto antes ou depois, sem ```json ou ```.
        PROMPT;
    }
}
