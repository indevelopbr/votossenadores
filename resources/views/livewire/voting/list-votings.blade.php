<div class="min-h-screen bg-gray-100">
    {{-- Cabeçalho (caso use Jetstream ou layout similar) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Votações') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Mensagem de sucesso (caso exista) --}}
            @if (session()->has('message'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

            {{-- Card com sombra --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <!-- Barra de filtros e botão de criar -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                    <!-- Filtro -->
                    <div class="w-full sm:w-1/2 mb-2 sm:mb-0">
                        <label for="search" class="sr-only">Filtrar Votações</label>
                        <input 
                            type="text" 
                            wire:model="search" 
                            id="search"
                            placeholder="Filtrar votações..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 
                                   focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2"
                        />
                    </div>
                    
                    <!-- Botão que aciona o modal -->
                    <button 
                        wire:click="showCreateModal"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent 
                               rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                               hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none 
                               focus:border-indigo-900 focus:ring ring-indigo-300"
                    >
                        Criar Votação
                    </button>
                </div>

                <!-- Tabela de votações -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">
                                    Título
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">
                                    Link
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">
                                    Criado em
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($votings as $votacao)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $votacao->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $votacao->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <a href="{{ config('app.url') . '/' . $votacao->voting_uri }}">
                                            {{ config('app.url') . '/' . $votacao->voting_uri }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $votacao->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <!-- Botões de ação -->
                                        <div class="inline-flex space-x-1">
                                            <!-- Botão de Votos -->
                                            <button 
                                                wire:click="showVotos({{ $votacao->id }})"
                                                class="px-3 py-1 bg-indigo-500 hover:bg-indigo-600 text-white text-sm 
                                                       rounded-md focus:outline-none focus:ring-2 
                                                       focus:ring-offset-2 focus:ring-indigo-500"
                                            >
                                                Votos
                                            </button>
                                            
                                            <!-- Botão de Editar -->
                                            <button 
                                                wire:click="edit({{ $votacao->id }})"
                                                class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm 
                                                       rounded-md focus:outline-none focus:ring-2 
                                                       focus:ring-offset-2 focus:ring-blue-500"
                                            >
                                                Editar
                                            </button>

                                            <!-- Botão de Excluir -->
                                            <button 
                                                wire:click="delete({{ $votacao->id }})"
                                                class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-sm 
                                                       rounded-md focus:outline-none focus:ring-2 
                                                       focus:ring-offset-2 focus:ring-red-500"
                                            >
                                                Excluir
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        Nenhuma votação encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $votings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para criar/editar votação -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-50 cursor-pointer" 
                wire:click="closeModal">
            </div>

            <!-- Container do Modal -->
            <div class="relative bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
                <!-- Título do Modal -->
                <h2 class="text-xl font-bold mb-4">
                    {{ $modalTitle }}
                </h2>

                <!-- Campo: Título -->
                <div class="mb-4">
                    <label for="name" class="block font-semibold mb-1">
                        {{ __('Título') }}
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        wire:model="name" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2" 
                        placeholder="Digite o título da votação"
                    />
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo: Upload de Foto -->
                <div class="mb-4">
                    <label for="file" class="block font-semibold mb-1">
                        {{ __('Foto da pessoa do Impeachment') }}
                    </label>
                    <input 
                        type="file" 
                        id="file" 
                        wire:model="file" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2"
                    />
                    @error('file')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <!-- Exemplo de pré-visualização da imagem (opcional) -->
                    @if($file)
                        <div class="mt-2">
                            <img src="{{ $file->temporaryUrl() }}" 
                                alt="Preview da Imagem"
                                class="max-h-32 rounded-md shadow">
                        </div>
                    @endif
                </div>

                <!-- Campo: Checkbox para marcar como principal -->
                <div class="mb-4 flex items-center">
                    <input 
                        type="checkbox" 
                        id="isMain" 
                        wire:model="isMain"
                        class="mr-2 border-gray-300 rounded shadow-sm focus:ring-indigo-200 focus:ring-opacity-50"
                    />
                    <label for="isMain" class="font-semibold">
                        {{ __('Marcar como votação principal') }}
                    </label>
                    @error('isMain')
                        <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botões de Ação -->
                <div class="flex justify-end mt-4">
                    <button 
                        wire:click="createVotacao" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded mr-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        {{ __('Salvar') }}
                    </button>
                    <button 
                        wire:click="closeModal" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        {{ __('Cancelar') }}
                    </button>
                </div>
            </div>
        </div>
    @endif  
</div>
