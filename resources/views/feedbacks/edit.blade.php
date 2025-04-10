<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Feedback') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800">
            <!-- Cabeçalho -->
            <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-lg font-bold text-gray-800 dark:text-white">
                            Feedback para {{ $feedback->candidate->name }}
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-300">
                            Processo: {{ $feedback->candidate->process->title }}
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('candidates.show', $feedback->candidate) }}" 
                           class="flex items-center px-3 py-1 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulário -->
            <form method="POST" action="{{ route('feedbacks.update', $feedback) }}" class="p-6">
                @csrf
                @method('PUT')

                <!-- Pontos Fortes -->
                <div class="mb-6">
                    <label for="strengths" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pontos Fortes
                    </label>
                    <textarea name="strengths" id="strengths" rows="3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >{{ old('strengths', $feedback->strengths) }}</textarea>
                </div>

                <!-- Áreas de Melhoria -->
                <div class="mb-6">
                    <label for="improvements" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Áreas de Melhoria
                    </label>
                    <textarea name="improvements" id="improvements" rows="3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >{{ old('improvements', $feedback->improvements) }}</textarea>
                </div>

                <!-- Impressão Geral -->
                <div class="mb-6">
                    <label for="general_impression" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Impressão Geral
                    </label>
                    <textarea name="general_impression" id="general_impression" rows="3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >{{ old('general_impression', $feedback->general_impression) }}</textarea>
                </div>

                <!-- Feedback Gerado pela IA -->
                <div class="mb-6 bg-gray-50 p-4 rounded-md dark:bg-gray-700">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                        Feedback Gerado pela IA (pré-visualização)
                    </h3>
                    <p class="text-gray-800 whitespace-pre-line dark:text-gray-200" name="ai_generated_feedback">
                        {{ old('ai_generated_feedback', $feedback->ai_generated_feedback) }}
                    </p>
                </div>

                <!-- Ações -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div>
                        <button type="button" onclick="confirmDelete()"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-600">
                            Excluir Feedback
                        </button>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('feedbacks.send', $feedback) }}" 
                           class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-600">
                            Enviar ao Candidato
                        </a>
                        <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                            Atualizar Feedback
                        </button>
                    </div>
                </div>
            </form>

            <!-- Formulário oculto para exclusão -->
            <form id="delete-form" method="POST" action="{{ route('feedbacks.destroy', $feedback) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete() {
            if (confirm('Tem certeza que deseja excluir este feedback? Esta ação não pode ser desfeita.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
    @endpush
</x-app-layout>