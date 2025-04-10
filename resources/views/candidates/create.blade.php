<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($candidate) ? 'Editar Candidato' : 'Novo Candidato' }}
        </h2>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
            <form method="POST" 
                  action="{{ isset($candidate) ? route('candidates.update', $candidate) : route('candidates.store') }}">
                @csrf
                @if(isset($candidate))
                    @method('PUT')
                @endif
    
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Processo Seletivo -->
                    <div class="md:col-span-2">
                        <label for="process_id" class="block mb-2">Processo Seletivo</label>
                        <select name="process_id" id="process_id" required
                                class="w-full rounded-md border-gray-300 shadow-sm">
                            @foreach($processes as $process)
                                <option value="{{ $process->id }}" 
                                    {{ (isset($candidate) && $candidate->process_id == $process->id) ? 'selected' : '' }}>
                                    {{ $process->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <!-- Nome -->
                    <div>
                        <label for="name" class="block mb-2">Nome Completo</label>
                        <input type="text" name="name" id="name" required
                               value="{{ $candidate->name ?? old('name') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm">
                    </div>
    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2">Email</label>
                        <input type="email" name="email" id="email" required
                               value="{{ $candidate->email ?? old('email') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm">
                    </div>
    
                    <!-- Telefone -->
                    <div>
                        <label for="phone" class="block mb-2">Telefone</label>
                        <input type="text" name="phone" id="phone"
                               value="{{ $candidate->phone ?? old('phone') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm">
                    </div>
    
                    <!-- Status -->
                    <div>
                        <label for="status" class="block mb-2">Status</label>
                        <select name="status" id="status" required
                                class="w-full rounded-md border-gray-300 shadow-sm">
                            <option value="pending" {{ (isset($candidate) && $candidate->status === 'pending') ? 'selected' : '' }}>Pendente</option>
                            <option value="approved" {{ (isset($candidate) && $candidate->status === 'approved') ? 'selected' : '' }}>Aprovado</option>
                            <option value="rejected" {{ (isset($candidate) && $candidate->status === 'rejected') ? 'selected' : '' }}>Rejeitado</option>
                        </select>
                    </div>
    
                    <!-- Currículo -->
                    <div class="md:col-span-2">
                        <label for="resume" class="block mb-2">Currículo</label>
                        <textarea name="resume" id="resume" rows="5"
                                  class="w-full rounded-md  border-gray-800 shadow-sm text-black">{{ $candidate->resume ?? old('resume') }}</textarea>
                    </div>
                </div>
    
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('candidates.index') }}" 
                        class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150
                        {{ isset($candidate) ? 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500' }}">
                        {{ isset($candidate) ? 'Atualizar' : 'Cadastrar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

