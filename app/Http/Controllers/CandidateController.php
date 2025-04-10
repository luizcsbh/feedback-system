<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\SelectionProcess;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidates = Candidate::with(['process'])
            ->paginate(10);
        return view('candidates.index', compact('candidates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $processes = SelectionProcess::where('is_active', true)->get();
        return view('candidates.create', compact('processes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'process_id' => 'required|exists:selection_processes,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email',
            'phone' => 'nullable|string|max:20',
            'resume' => 'nullable|string',
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
        ]);

        Candidate::create($validated);

        return redirect()->route('candidates.index')
            ->with('success', 'Candidato cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        $candidate->load(['process', 'feedbacks.evaluator']);
        return view('candidates.show', compact('candidate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidate $candidate)
    {
        $processes = SelectionProcess::where('is_active', true)->get();
        return view('candidates.create', compact('candidate', 'processes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'process_id' => 'required|exists:selection_processes,id',
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('candidates')->ignore($candidate->id)],
            'phone' => 'nullable|string|max:20',
            'resume' => 'nullable|string',
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
        ]);

        $candidate->update($validated);

        return redirect()->route('candidates.show', $candidate)
            ->with('success', 'Candidato atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        $candidate->delete();

        return redirect()->route('candidates.index')
            ->with('success', 'Candidato removido com sucesso!');
    }

    /**
     * Update candidate status
     */
    public function updateStatus(Request $request, Candidate $candidate)
    {
        $request->validate([
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
        ]);

        $candidate->update(['status' => $request->status]);

        return back()->with('success', 'Status do candidato atualizado!');
    }

    /**
     * Export candidates to CSV
     */
    public function export()
    {
        // Gera arquivo temporário
        $fileName = 'candidatos_' . now()->format('Ymd_His') . '.csv';
        $filePath = storage_path('app/' . $fileName);
        
        $file = fopen($filePath, 'w');
        
        // Escreve cabeçalhos
        fputcsv($file, ['ID', 'Nome', 'Email', 'Status']);
        
        // Escreve dados
        Candidate::chunk(200, function($candidates) use ($file) {
            foreach ($candidates as $candidate) {
                fputcsv($file, [
                    $candidate->id,
                    $candidate->name,
                    $candidate->email,
                    $candidate->status
                ]);
            }
        });
        
        fclose($file);
        
        // Faz download e remove o arquivo
        return response()->download($filePath)->deleteFileAfterSend();
    }

    protected function getStatusText($status)
    {
        return match($status) {
            'approved' => 'Aprovado',
            'rejected' => 'Rejeitado',
            default => 'Pendente',
        };
    }
}