<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalhes do Candidato') }}
        </h2>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden dark:bg-gray-800">
            <!-- Cabeçalho -->
            <div class="bg-gray-100 px-6 py-5 border-b border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detalhes do Candidato: {{ $candidate->name }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">
                            Cadastrado em: {{ $candidate->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('candidates.edit', $candidate) }}" 
                           class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 dark:bg-blue-500 dark:hover:bg-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </a>
                        <form action="{{ route('candidates.destroy', $candidate) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200 dark:bg-red-500 dark:hover:bg-red-600"
                                    onclick="return confirm('Tem certeza que deseja excluir este candidato?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Excluir
                            </button>
                        </form>
                        <a href="{{ route('candidates.index') }}" 
                           class="flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors duration-200 dark:bg-gray-600 dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
    
            <!-- Corpo -->
            <div class="p-6">
                <!-- Seção de Informações -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Informações Pessoais -->
                    <div class="bg-gray-50 p-6 rounded-lg dark:bg-gray-700">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 dark:text-white">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Informações Pessoais
                        </h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome Completo</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $candidate->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $candidate->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $candidate->phone ?? 'Não informado' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Processo Seletivo -->
                    <div class="bg-gray-50 p-6 rounded-lg dark:bg-gray-700">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 dark:text-white">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Processo Seletivo
                        </h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Vaga</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $candidate->process->title }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                                @php
                                    $statusClasses = [
                                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$candidate->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ match($candidate->status) {
                                        'approved' => 'Aprovado',
                                        'rejected' => 'Rejeitado',
                                        default => 'Pendente',
                                    } }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Currículo -->
                <div class="mb-8">
                    <div class="bg-gray-50 p-6 rounded-lg dark:bg-gray-700">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3 dark:text-white">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Currículo
                        </h2>
                        <div class="bg-white p-4 rounded-md dark:bg-gray-600">
                            <p class="text-gray-800 whitespace-pre-line dark:text-gray-200">
                                {{ $candidate->resume ?? 'Nenhum currículo informado' }}
                            </p>
                        </div>
                    </div>
                </div>
    
                <!-- Feedbacks -->
                <div>
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-4">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            Feedbacks
                        </h2>
                        <a href="{{ route('feedbacks.create', $candidate) }}" 
                           class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-200 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Adicionar Feedback
                        </a>
                    </div>
    
                    @if($candidate->feedbacks->isEmpty())
                        <div class="bg-gray-50 p-5 rounded-lg text-center text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                            Nenhum feedback registrado para este candidato.
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($candidate->feedbacks as $feedback)
                                <div class="border border-gray-200 rounded-lg p-6 dark:border-gray-600">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-3">
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-white">
                                                {{ $feedback->evaluator->name }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $feedback->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('feedbacks.edit', $feedback) }}" 
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            @php
                                                $sentimentClasses = [
                                                    'positive' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                    'negative' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                    'neutral' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                                ];
                                                $sentiment = match(true) {
                                                    $feedback->sentiment_score > 0.2 => 'positive',
                                                    $feedback->sentiment_score < -0.2 => 'negative',
                                                    default => 'neutral'
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $sentimentClasses[$sentiment] }}">
                                                {{ ucfirst($sentiment) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-50 p-4 rounded-md dark:bg-gray-700">
                                        <p class="text-gray-800 whitespace-pre-line dark:text-gray-200">
                                            {{ $feedback->ai_generated_feedback }}
                                        </p>
                                    </div>
                                    
                                    @if($feedback->sent_to_candidate)
                                        <div class="mt-3 text-sm text-green-600 dark:text-green-400">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Enviado ao candidato em {{ $feedback->sent_at->format('d/m/Y H:i') }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
