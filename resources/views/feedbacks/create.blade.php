<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gerar Feedback para {{ $candidate->name }}
        </h2>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('feedbacks.store', $candidate) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="strengths" class="block text-sm font-medium text-gray-700 mb-2">Pontos Fortes</label>
                    <textarea name="strengths" id="strengths" rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required></textarea>
                </div>
                
                <div class="mb-6">
                    <label for="improvements" class="block text-sm font-medium text-gray-700 mb-2">Áreas de Melhoria</label>
                    <textarea name="improvements" id="improvements" rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required></textarea>
                </div>
                
                <div class="mb-6">
                    <label for="general_impression" class="block text-sm font-medium text-gray-700 mb-2">Impressão Geral</label>
                    <textarea name="general_impression" id="general_impression" rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required></textarea>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <a href="{{ route('candidates.index') }}" 
                        class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar
                    </a>
                    <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Gerar Feedback com IA
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>



