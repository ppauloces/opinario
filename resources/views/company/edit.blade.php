<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ 'Editar empresa' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <x-secondary-button-link class="mb-3" href="{{ route('dashboard') }}" >Voltar</x-secondary-button-link>
                <div class="max-w-xl">
                    @include('company.partials.company-form')
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
