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

            **IMPORTANTE**: Avalie se a tradução está CORRETA, não se está idêntica à referência. Contrações (She's, They're, I'm) são equivalentes às formas completas (She is, They are, I am) e devem ser consideradas igualmente corretas.

            **Frase original (PT):** {$textPt}
            **Tradução de referência (EN):** {$textEnReference}
            **Tradução do aluno (EN):** {$userTextEn}

            CRITÉRIOS DE CORREÇÃO:
            1. Se a tradução está gramaticalmente correta E transmite o mesmo significado → is_correct = true, score = 100
            2. Contrações são SEMPRE corretas (She's = She is, They're = They are, etc)
            3. Pequenas variações estilísticas que não mudam o significado são corretas
            4. Só considere erro se houver: gramática errada, vocabulário errado, significado diferente, ou falta de naturalidade óbvia
            5. A referência é apenas um EXEMPLO, não a única resposta correta

            Retorne um JSON com esta estrutura EXATA:
            {
              "is_correct": true ou false,
              "score": número de 0 a 100,
              "overall_comment": "comentário geral em português",
              "mistakes": [
                {
                  "type": "grammar, spelling, vocabulary, preposition, article, verb_tense, word_order ou punctuation",
                  "original": "trecho errado",
                  "suggestion": "correção",
                  "explanation_pt": "explicação simples em português do erro REAL"
                }
              ],
              "corrected_sentence": "frase corrigida (ou a própria frase do aluno se estiver correta)",
              "natural_alternatives": [
                "forma alternativa 1",
                "forma alternativa 2"
              ],
              "positive_points": [
                "o que o aluno acertou"
              ]
            }

            REGRAS IMPORTANTES:
            - Se não há ERRO REAL de gramática/vocabulário/significado → is_correct = true, score = 100, mistakes = []
            - "She's" e "She is" são IGUALMENTE corretos
            - "They're" e "They are" são IGUALMENTE corretos
            - Não penalize por escolhas estilísticas válidas
            - Só aponte erros se houver algo genuinamente errado
            - Se a frase do aluno está perfeita, corrected_sentence = frase do aluno (não mude para a referência)

            IMPORTANTE: Retorne APENAS o JSON, sem texto antes ou depois, sem ```json ou ```.
        PROMPT;
    }
}
