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
                                <svg xmlns="http://www.w3.org/2000/svg" class="animate-spin h-5 w-5 text-white"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </template>

                            <template x-if="!loading">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
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
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($companies as $company)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-3">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="company">
                            <h3>{{ $company->razao_social }} 
                                @if($company->situacao == 1)
                                <p class="ml-1 text-sm bg-green-500 text-white p-1 rounded-lg inline-block">Ativo</p>
                                @else
                                <p class="ml-1 text-sm bg-yellow-500 text-white p-1 rounded-lg inline-block">Inativo</p>
                                @endif
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $company->cidades->nome }} - {{ $company->estados->uf }}</p>
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
