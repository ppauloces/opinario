<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ "Bem vindo, $user->name" }}
            </h2>
            <x-primary-button class="inline-flex items-center" x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'cadastrar-empresa')">
                <x-icon-plus viewBox="0 -2 28 28"></x-icon-plus>
                Nova empresa
            </x-primary-button>


            {{-- Início da modal --}}
            <x-modal name="cadastrar-empresa" focusable>
                <form method="post" action="{{ route('company.store') }}" x-data="companySearch()" class="p-6">
                    @csrf

                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Pronto para cadastrar sua empresa? Vamos lá!') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Digite abaixo o CNPJ da empresa e clique em buscar. O resto é com a gente.') }}
                    </p>

                    <div class="mt-6 flex items-center">
                        <div class="w-3/4">
                            <x-input-label for="cnpj" value="CNPJ" class="sr-only" />
                            <x-text-input id="cnpj" name="cnpj" type="text" x-model="cnpj"
                                class="block w-full" placeholder="CNPJ" />
                            {{-- Exibir mensagem de erro se houver --}}
                            <p x-show="error" class="mt-2 text-red-600 dark:text-red-400 text-sm">
                                <span x-text="error"></span>
                            </p>
                            <x-text-input id="request" name="request" type="hidden" />
                        </div>
                        <x-primary-button class="ms-3 ml-2" type="button" x-on:click="searchCnpj">

                            <template x-if="loading">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                </svg>
                            </template>

                            <template x-if="!loading">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </template>
                        </x-primary-button>
                    </div>

                    <div class="mt-6 items-center">
                        <p class="mt-1 text-base text-gray-600 dark:text-gray-400" x-text="companyName">
                            {{ 'NOME DA EMPRESA' }}
                        </p>
                        <a x-show="companyName !== ''" href="#"
                            class="mt-1 text-sm text-blue-600 visited:text-blue-600">
                            Empresa incorreta? Cadastre manualmente.
                        </a>
                    </div>

                    <input type="hidden" name="companyResponse" x-model="companyResponse">

                    <div class="mt-6 flex justify-left">
                        <x-primary-button x-bind:disabled="!companyName" x-on:click="submitForm">
                            {{ 'CADASTRAR EMPRESA' }}
                        </x-primary-button>

                        <x-secondary-button class="ms-3" x-on:click="$dispatch('close')">
                            {{ 'Cancelar' }}
                        </x-secondary-button>
                    </div>
                </form>
            </x-modal>
            {{-- Fim da modal --}}


        </div>
    </x-slot>




    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-alerts></x-alerts>

            @forelse ($companies as $company)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-3 relative">
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center">
                        <div class="company">
                            <h3 class="text-lg font-semibold">{{ $company->razao_social }}
                                @if ($company->situacao == 1)
                                    <p class="ml-1 text-sm bg-green-500 text-white p-1 rounded-lg inline-block">Ativo
                                    </p>
                                @else
                                    <p class="ml-1 text-sm bg-yellow-500 text-white p-1 rounded-lg inline-block">Inativo
                                    </p>
                                @endif
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $company->cidades->nome }} -
                                {{ $company->estados->uf }}</p>
                        </div>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 text-gray-600 dark:text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zm0 6a.75.75 0 110-1.5.75.75 0 010 1.5zm0 6a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="fixed right-0 mt-2 mr-16 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-10">
                                <a href="{{ route('company.edit', $company->id) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Editar
                                    empresa</a>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Opção
                                    2</a>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Opção
                                    3</a>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <p>Ops... Você não cadastrou nenhuma empresa ainda.</p>
            @endforelse
            
        </div>
    </div>
</x-app-layout>

<script>
    function companySearch() {
        return {
            cnpj: '',
            companyName: '',
            companyResponse: '',
            loading: false,
            error: '',
            searchCnpj() {
                this.loading = true;
                this.error = '';

                fetch('{{ route('search-cnpj') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            cnpj: this.cnpj
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.razao_social) {
                            this.companyName = data.razao_social;
                            this.companyResponse = JSON.stringify(data.response);
                        } else if (data.error) {
                            this.error = data.error;
                        }
                    })
                    .catch(error => {
                        this.error = error.message;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            submitForm() {
                document.querySelector('form').submit();
            }
        }
    }
</script>
