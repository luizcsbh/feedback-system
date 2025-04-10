<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Phpml\Classification\SVC;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Pipeline;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\ModelManager;
use Phpml\Tokenization\WhitespaceTokenizer;

class CandidateFeedbackAnalyzer
{
    protected $pipeline;
    protected $modelPath;
    protected $professionalWords = [
        'liderança', 'competências', 'habilidades', 'desempenho', 
        'potencial', 'técnicas', 'comportamental', 'feedback'
    ];

    public function __construct()
    {
        $this->modelPath = config('feedback.model_path', storage_path('app/models/feedback_model.md'));
        $this->initializePipeline();
    }

    protected function initializePipeline(): void
    {
        if (file_exists($this->modelPath)) {
            $this->loadModel();
        } else {
            $this->createNewPipeline();
            $this->trainBaseModel();
            $this->saveModel();
        }
    }

    protected function createNewPipeline(): void
    {
        $this->pipeline = new Pipeline([
            new TokenCountVectorizer(new WhitespaceTokenizer($this->professionalWords)),
            new TfIdfTransformer()
        ], new SVC(Kernel::RBF, 10.0, 0.1));
    }

    protected function trainBaseModel(): void
    {
        // Dataset específico para feedback de candidatos
        $samples = [
            'O candidato demonstrou excelentes habilidades técnicas',
            'Perfil alinhado com os valores da empresa',
            'Bom desempenho na avaliação comportamental',
            'Mostrou grande potencial para a posição',
            'Não atendeu aos requisitos mínimos da vaga',
            'Faltou domínio nas competências essenciais',
            'A entrevista foi satisfatória mas sem destaques',
            'Candidato mediano em todas as avaliações',
            'Não conseguiu demonstrar as habilidades necessárias'
        ];

        $labels = ['positivo', 'positivo', 'positivo', 
                  'negativo', 'negativo', 'negativo',
                  'neutro', 'neutro', 'neutro'];

        $this->pipeline->train($samples, $labels);
    }

    public function analyzeFeedback(string $feedback): array
    {
        $predicted = $this->pipeline->predict([$feedback])[0];
        
        return [
            'sentiment' => $predicted,
            'professionalism' => $this->calculateProfessionalismScore($feedback),
            'constructiveness' => $this->calculateConstructivenessScore($feedback),
            'suggestions' => $this->generateFeedbackSuggestions($predicted, $feedback),
            'compound' => (float)$this->ensureNumericScore($this->pipeline->predict([$feedback])[0])
        ];
    }

    protected function ensureNumericScore(string $sentiment): float
    {
        return match($sentiment) {
            'positivo' => 0.8,
            'negativo' => -0.8,
            'misto' => 0.1,
            'neutro' => 0.0,
            default => 0.0 // Valor padrão para casos desconhecidos
        };
    }

    protected function getCompoundScore(string $sentiment): float
    {
        return match($sentiment) {
            'positivo' => 0.8,
            'negativo' => -0.8,
            'misto' => 0.0,
            default => 0.1
        };
    }

    protected function calculateProfessionalismScore(string $text): float
    {
        $words = preg_split('/\s+/', strtolower($text));
        $professionalWordsCount = count(array_intersect($words, $this->professionalWords));
        
        return min(1, $professionalWordsCount / 5); // Normalizado para 0-1
    }

    protected function calculateConstructivenessScore(string $text): float
    {
        $positiveMarkers = ['sugerir', 'recomendar', 'melhorar', 'desenvolver', 'evoluir'];
        $negativeMarkers = ['ruim', 'péssimo', 'horrível', 'decepcionante', 'fracasso'];
        
        $score = 0;
        $textLower = strtolower($text);
        
        foreach ($positiveMarkers as $marker) {
            if (strpos($textLower, $marker) !== false) $score += 0.2;
        }
        
        foreach ($negativeMarkers as $marker) {
            if (strpos($textLower, $marker) !== false) $score -= 0.1;
        }
        
        return max(0, min(1, $score));
    }

    protected function generateFeedbackSuggestions(string $sentiment, string $feedback): array
    {
        $suggestions = [];
        
        if ($sentiment === 'negativo') {
            $suggestions[] = 'Considere adicionar pontos de melhoria específicos';
            $suggestions[] = 'Sugira recursos ou treinamentos que possam ajudar';
            
            if (strpos(strtolower($feedback), 'mas') === false) {
                $suggestions[] = 'Use a técnica "sanduíche" (positivo + melhoria + positivo)';
            }
        }
        
        if ($this->calculateProfessionalismScore($feedback) < 0.3) {
            $suggestions[] = 'Inclua mais termos profissionais relevantes para a posição';
        }
        
        if ($this->calculateConstructivenessScore($feedback) < 0.4) {
            $suggestions[] = 'Foque em feedbacks acionáveis e específicos';
        }
        
        return empty($suggestions) ? 
            ['Seu feedback está bem estruturado e profissional'] : 
            $suggestions;
    }

    public function isConstructiveFeedback(string $feedback): bool
    {
        $analysis = $this->analyzeFeedback($feedback);
        return $analysis['constructiveness'] >= 0.5 && 
               $analysis['professionalism'] >= 0.4;
    }

    public function saveModel(): void
    {
        $modelManager = new ModelManager();
      
         // Garante que o diretório existe
        if (!file_exists(dirname($this->modelPath))) {
            mkdir(dirname($this->modelPath), 0755, true);
        }
        
        $modelManager->saveToFile($this->pipeline, $this->modelPath);
    }

    public function loadModel(): void
    {
        $modelManager = new ModelManager();
        $this->pipeline = $modelManager->restoreFromFile($this->modelPath);
    }

    public function addTrainingSample(string $feedback, string $label): void
    {
        $modelManager = new ModelManager();
        $tempPath = storage_path('app/temp_model.md');
        
        // Salva estado atual
        $modelManager->saveToFile($this->pipeline, $tempPath);
        
        try {
            $this->pipeline->train([$feedback], [$label]);
            $this->saveModel();
        } catch (\Exception $e) {
            // Restaura modelo original em caso de erro
            $this->pipeline = $modelManager->restoreFromFile($tempPath);
            throw $e;
        } finally {
            // Limpa o arquivo temporário
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }
    }
}