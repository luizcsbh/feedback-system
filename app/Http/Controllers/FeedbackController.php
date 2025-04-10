<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Feedback;
use App\Services\AIService;
use App\Mail\CandidateFeedbackMail;
use App\Services\CandidateFeedbackAnalyzer;
use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    public function __construct(
        protected AIService $aiService,
        protected CandidateFeedbackAnalyzer $analyzer
    ) {}

    // ==================== MÉTODOS PÚBLICOS ====================

    public function create(Candidate $candidate)
    {
        return view('feedbacks.create', compact('candidate'));
    }

    public function show(Feedback $feedback)
    {
        return view('feedbacks.show', [
            'feedback' => $feedback->load(['candidate.process', 'evaluator'])
        ]);
    }

    public function edit(Feedback $feedback)
    {
        return view('feedbacks.edit', [
            'feedback' => $feedback->load(['candidate', 'evaluator'])
        ]);
    }

    public function store(StoreFeedbackRequest $request, Candidate $candidate)
    {
        $validated = $request->validated();
        $aiFeedback = $this->generateAIFeedback($candidate, $validated);
        $this->createFeedback($candidate, $validated, $aiFeedback);

        return redirect()
            ->route('candidates.show', $candidate)
            ->with('success', 'Feedback gerado com sucesso!');
    }

    public function update(UpdateFeedbackRequest $request, Feedback $feedback)
    {
        try {
            $validated = $request->validated();
            $aiFeedback = $this->generateAIFeedback($feedback->candidate, $validated);
            $this->updateFeedback($feedback, $validated, $aiFeedback);
            
            return redirect()
                ->route('feedbacks.show', $feedback)
                ->with('success', 'Feedback atualizado com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Feedback update failed', [
                'feedback_id' => $feedback->id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Erro ao atualizar feedback: ' . $e->getMessage());
        }
    }

    public function send(Feedback $feedback)
    {
        $this->sendFeedbackEmail($feedback);
        $feedback->update(['sent_to_candidate' => true, 'sent_at' => now()]);

        return back()->with('success', 'Feedback enviado ao candidato!');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()
            ->route('candidates.show', $feedback->candidate)
            ->with('success', 'Feedback excluído com sucesso!');
    }

    public function analyze(Request $request)
    {
        $request->validate(['feedback' => 'required|string']);
        $feedbackText = $request->input('feedback');
        
        return response()->json([
            'analysis' => $this->analyzer->analyzeFeedback($feedbackText),
            'is_constructive' => $this->analyzer->isConstructiveFeedback($feedbackText)
        ]);
    }

    // ==================== MÉTODOS PROTEGIDOS ====================

    protected function createFeedback(Candidate $candidate, array $data, string $aiFeedback): Feedback
    {
        $analysis = $this->analyzer->analyzeFeedback($aiFeedback);

        return Feedback::create([
            'candidate_id' => $candidate->id,
            'evaluator_id' => Auth::id(),
            'strengths' => $data['strengths'],
            'improvements' => $data['improvements'],
            'general_impression' => $data['general_impression'],
            'ai_generated_feedback' => $aiFeedback,
            'sentiment_score' => $this->convertToFloat($analysis['compound'] ?? 0),
            'sentiment_label' => $this->getSentimentLabel($analysis['compound'] ?? 0),
            'is_appropriate_tone' => $this->analyzer->isConstructiveFeedback($aiFeedback)
        ]);
    }

    protected function updateFeedback(Feedback $feedback, array $data, string $aiFeedback): void
    {
        $analysis = $this->analyzer->analyzeFeedback($aiFeedback);
        
        $feedback->update([
            'strengths' => $data['strengths'],
            'improvements' => $data['improvements'],
            'general_impression' => $data['general_impression'],
            'ai_generated_feedback' => $aiFeedback,
            'sentiment_score' => $this->convertToFloat($analysis['compound'] ?? 0),
            'sentiment_label' => $this->getSentimentLabel($analysis['compound'] ?? 0),
            'is_appropriate_tone' => $this->analyzer->isConstructiveFeedback($aiFeedback)
        ]);
    }

    protected function generateAIFeedback(Candidate $candidate, array $data): string
    {
        return $this->aiService->generateFeedback([
            'candidate_name' => $candidate->name,
            'process_title' => $candidate->process->title,
            'strengths' => $data['strengths'],
            'improvements' => $data['improvements'],
            'general_impression' => $data['general_impression'],
        ]);
    }

    protected function sendFeedbackEmail(Feedback $feedback): void
    {
        Mail::to($feedback->candidate->email)
            ->send(new CandidateFeedbackMail($feedback));
    }

    // ==================== MÉTODOS DE APOIO ====================

    protected function getSentimentLabel($score): string
    {
        $floatScore = $this->convertToFloat($score);
        
        return match(true) {
            $floatScore > 0.2 => 'positive',
            $floatScore < -0.2 => 'negative',
            default => 'neutral'
        };
    }

    protected function convertToFloat($value): float
    {
        if (is_float($value)) return $value;
        if (is_int($value)) return (float)$value;
        
        if (is_string($value)) {
            if (is_numeric($value)) return (float)$value;
            if (preg_match('/[-+]?[0-9]*\.?[0-9]+/', $value, $matches)) {
                return (float)$matches[0];
            }
            return match(strtolower($value)) {
                'positive', 'positivo' => 0.8,
                'negative', 'negativo' => -0.8,
                default => 0.0
            };
        }
        
        throw new \InvalidArgumentException("Cannot convert value to float: " . print_r($value, true));
    }
}