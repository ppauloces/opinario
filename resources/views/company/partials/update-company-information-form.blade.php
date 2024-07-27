
<section>
   
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ 'Informações da empresa' }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ 'Atualize as informações que você considera necessárias.' }}
        </p>
    </header>
    <x-alerts></x-alerts>
    <form method="post" id="company-form" action="{{ route('company.update', $company->id) }}" class="mt-6 space-y-6"
        enctype="multipart/form-data">
        @csrf
        @method('put')

        <div>
            <x-input-label for="razao_social" :value="'Razão Social'" />
            <x-text-input id="razao_social" name="razao_social" type="text" class="mt-1 block w-full" :value="old('razao_social', $company->razao_social)"
                required autofocus autocomplete="razao_social" />
            <x-input-error class="mt-2" :messages="$errors->get('razao_social')" />
        </div>

        <div>
            <x-input-label for="cnpj" :value="'CNPJ'" />
            <x-text-input id="cnpj" name="cnpj" type="text" class="mt-1 block w-full" :value="old('cnpj', $company->cnpj)"
                required autofocus autocomplete="cnpj" />
            <x-input-error class="mt-2" :messages="$errors->get('cnpj')" />
        </div>

        <div>
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" name="email" type="text" class="mt-1 block w-full" :value="old('email', $company->email)"
                autofocus autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex space-x-4 mt-4">
            <div class="flex-1 w-1/4">
                <x-input-label for="ddd" :value="'DDD'" />
                <x-text-input id="ddd" name="ddd" type="text" class="mt-1 block w-full" :value="old('ddd', $company->ddd)"
                    required autocomplete="ddd" />
                <x-input-error class="mt-2" :messages="$errors->get('ddd')" />
            </div>
            <div class="flex-1 w-3/4">
                <x-input-label for="telefone" :value="'Telefone'" />
                <x-text-input id="telefone" name="telefone" type="text" class="mt-1 block w-full" :value="old('telefone', $company->telefone)"
                    required autocomplete="telefone" />
                <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
            </div>
        </div>


        <hr>

        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ 'Localização' }}
            </h2>
        </header>

        <div x-data="fetchCidades()" x-init="init()" class="flex space-x-4">
            <div class="flex-1">
                <x-input-label for="state" :value="'Estado'" />
                <select id="state" name="state"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    x-on:change="fetchCidades" x-model="selectedEstado">
                    <option value="">Selecione um estado</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado->id }}"
                            {{ old('state_id', $company->state_id) == $estado->id ? 'selected' : '' }}>
                            {{ $estado->nome }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('state_id')" />
            </div>

            <div class="flex-1 ml-2">
                <x-input-label for="city" :value="'Cidade'" />
                <select id="city" name="city"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    required autofocus autocomplete="city_id" x-model="selectedCidade">
                    <option value="">Selecione uma cidade</option>
                    <template x-for="cidade in cidades" :key="cidade.id">
                        <option :value="cidade.id" x-text="cidade.nome"></option>
                    </template>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('city_id')" />
            </div>
        </div>

        <div x-data="getCep()" x-init="init()" class="space-y-4">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <div>
                        <x-input-label for="cep" :value="'CEP'" />
                        <x-text-input id="cep" name="cep" type="text" class="mt-1 block w-full"
                            x-model="cep" x-on:blur="fetchCep" required autofocus autocomplete="cep"
                            :value="old('cep', $company->cep)" />
                        <x-input-error class="mt-2" :messages="$errors->get('cep')" />
                    </div>
                </div>

                <div class="flex-1">
                    <x-input-label for="bairro" :value="'Bairro'" />
                    <x-text-input id="bairro" name="bairro" type="text" class="mt-1 block w-full"
                        x-model="bairro" required autofocus autocomplete="bairro" :value="old('bairro', $company->bairro)" />
                    <x-input-error class="mt-2" :messages="$errors->get('bairro')" />
                </div>


            </div>
            <div class="flex space-x-4">
                <div class="flex-1">
                    <x-input-label for="logradouro" :value="'Logradouro'" />
                    <x-text-input id="logradouro" name="logradouro" type="text" class="mt-1 block w-full"
                        x-model="logradouro" required autofocus autocomplete="logradouro" :value="old('logradouro', $company->logradouro)" />
                    <x-input-error class="mt-2" :messages="$errors->get('logradouro')" />
                </div>

                <div class="flex-1">
                    <x-input-label for="complemento" :value="'Complemento'" />
                    <x-text-input id="complemento" name="complemento" type="text" class="mt-1 block w-full"
                        x-model="complemento" autofocus autocomplete="complemento" :value="old('complemento', $company->complemento)" />
                    <x-input-error class="mt-2" :messages="$errors->get('complemento')" />
                </div>

                <div class="flex-1">
                    <x-input-label for="numero" :value="'Número'" />
                    <x-text-input id="numero" name="numero" type="text" class="mt-1 block w-full" autofocus
                        autocomplete="numero" :value="old('numero', $company->numero)" />
                    <x-input-error class="mt-2" :messages="$errors->get('numero')" />
                </div>
            </div>
        </div>

        <hr>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ 'Logotipo' }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ 'Adicione uma imagem que identifique sua empresa.' }}
            </p>
        </header>

        @if($company->logo)
        <img class="rounded w-36 h-36" src="{{ asset('uploads/' . $company->logo) }}" alt="{{ str_replace(' ', '_', $company->razao_social) }}">
        @endif
        <input type="hidden" id="base64Logo" name="logo">
    
        <div x-data x-init="() => {
                const inputElement = $refs.input;
                const pond = FilePond.create(inputElement, {
                    labelIdle: 'Arraste e solte arquivos aqui ou clique para selecionar',
                    labelFileWaitingForSize: 'Calculando tamanho do arquivo',
                    labelFileProcessing: 'Processando arquivo',
                    imagePreviewHeight: 170,
                    allowProcess: false,
                    allowRevert: false,
                    allowRemove: true,
                    allowReplace: true,
                    server: {
                        process: (fieldName, file, metadata, load, error, progress, abort) => {
                            const reader = new FileReader();
                            reader.readAsDataURL(file);
                            reader.onload = () => {
                                document.querySelector('#base64Logo').value = reader.result;
                                load(reader.result);
                            };
                            reader.onerror = error;
                        }
                    }
                });
    
                document.querySelector('form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    pond.processFiles().then(() => {
                        e.target.submit();
                    });
                });
            }">
            <input type="file" x-ref="input" name="logo">
            <x-input-error class="mt-2" :messages="$errors->get('logo')" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ 'Salvar' }}</x-primary-button>

            @if (session('status') === 'informacoes-atualizadas')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ 'Salvo' }}</p>
            @endif
        </div>
    </form>
    <hr>
</section>


<script>
    function fetchCidades() {
        return {
            selectedEstado: '{{ old('state_id', $company->state_id) }}',
            selectedCidade: '{{ old('city_id', $company->city_id) }}',
            cidades: [],
            fetchCidades() {
                if (this.selectedEstado) {
                    fetch(`/cidades/${this.selectedEstado}`)
                        .then(response => response.json())
                        .then(data => {
                            this.cidades = data;
                            // Set selected city if exists
                            if (this.selectedCidade) {
                                document.getElementById('city').value = this.selectedCidade;
                            }
                        });
                } else {
                    this.cidades = [];
                }
            },
            init() {
                this.fetchCidades();
            }
        }
    }

    function getCep() {
        return {
            cep: '{{ old('cep', $company->cep) }}',
            logradouro: '{{ old('logradouro', $company->logradouro) }}',
            complemento: '{{ old('complemento', $company->complemento) }}',
            bairro: '{{ old('bairro', $company->bairro) }}',
            fetchCep() {

                if (this.cep) {
                    fetch(`/cep/${this.cep}`)
                        .then(response => response.json())
                        .then(data => {
                            this.bairro = data.bairro;
                            this.logradouro = data.logradouro;
                            this.complemento = data.complemento;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            },
            init() {
                // Não é necessário inicializar a busca aqui
            }
        }
    }
</script>
