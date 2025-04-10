<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $apiKey;
    protected $model;
    
    public function __construct($apiKey, $model = 'tiiuae/falcon-7b-instruct')
    {
        $this->apiKey = $apiKey;
        $this->model = $model;
    }

    public function generateFeedback(array $data): string
    {
        $prompt = $this->buildPrompt($data);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post("https://api-inference.huggingface.co/models/{$this->model}", [
                'inputs' => $prompt,
                'parameters' => [
                    'temperature' => 0.7,
                    'max_new_tokens' => 300,
                    'return_full_text' => false
                ]
            ]);

            if ($response->failed()) {
                Log::error('Hugging Face API error', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                return 'Não foi possível gerar o feedback no momento. Erro na API.';
            }

            $result = $response->json();
            
            if (isset($result['error'])) {
                Log::error('Hugging Face API error', $result);
                return 'Não foi possível gerar o feedback no momento. Problema de autenticação.';
            }

            return $result[0]['generated_text'] ?? 'Não foi possível gerar o feedback no momento.';

        } catch (\Exception $e) {
            Log::error('AI Service error: ' . $e->getMessage());
            return 'Não foi possível gerar o feedback no momento. Erro no servidor.';
        }
    }

    protected function buildPrompt(array $data): string
    {
        return sprintf(
            "Você é um especialista em RH com vasta experiência em avaliação de candidatos. Escreva um feedback personalizado para %s sobre sua participação no processo seletivo para '%s' seguindo estas regras:\n\n" .
            "1. **Formato:**\n" .
            "   - Saudação personalizada\n" .
            "   - Agradecimento pela participação\n" .
            "   - 3 parágrafos objetivos (máximo 5 linhas cada)\n" .
            "   - Fechamento encorajador\n\n" .
            "2. **Conteúdo obrigatório:**\n" .
            "   - Pontos fortes: %s\n" .
            "   - Áreas de melhoria: %s\n" .
            "   - Impressão geral: %s\n\n" .
            "3. **Estilo requerido:**\n" .
            "   - Tom: empático e profissional\n" .
            "   - Linguagem: direta e humanizada\n" .
            "   - Destaque: 2-3 qualidades específicas\n" .
            "   - Sugestões: recomendações acionáveis\n\n" .
            "4. **Exemplo de estrutura:**\n" .
            "\"Prezado(a) [Nome], [Agradecimento]. [Destaque principal]. [Sugestão específica]. [Encorajamento final].\"",
            $data['candidate_name'],
            $data['process_title'],
            $data['strengths'] ?? 'não especificados',
            $data['improvements'] ?? 'não especificadas',
            $data['general_impression'] ?? 'não especificada'
        );
    }
}