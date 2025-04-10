<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Visualizar Feedback') }}
        </h2>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                @if(!$feedback->sent_to_candidate)
                    <form action="{{ route('feedbacks.send', $feedback) }}" method="POST">
                        @csrf
                        <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Enviar para o Candidato
                        </button>
                    </form>
                @else
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        Enviado em {{ $feedback->sent_at->format('d/m/Y H:i') }}
                    </span>
                @endif
            </div>
            
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Pontos Fortes</h2>
                <p class="text-gray-600">{{ $feedback->strengths }}</p>
            </div>
            
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Áreas de Melhoria</h2>
                <p class="text-gray-600">{{ $feedback->improvements }}</p>
            </div>
            
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Impressão Geral</h2>
                <p class="text-gray-600">{{ $feedback->general_impression }}</p>
            </div>
            
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Feedback Gerado pela IA</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($feedback->ai_generated_feedback)) !!}
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <a href="{{ route('candidates.show', $feedback->candidate) }}" 
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Voltar
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

