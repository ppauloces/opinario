<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'razao_social' => ['required', 'string', 'max:255'],
            'porte' => ['nullable', 'string', 'max:30'],
            'cnpj' => ['required', 'string', 'max:20', 'unique:companies,cnpj,' . $this->route('company')],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255'],
            'logo' => ['nullable', 'string'],
            'site' => ['nullable'],
            'country_code' => ['nullable', 'string', 'max:2'],
            'state_id' => ['nullable'],
            'city_id' => ['nullable'],
            'cep' => ['nullable', 'string', 'max:9'],
            'bairro' => ['nullable', 'string', 'max:80'],
            'logradouro' => ['nullable', 'string', 'max:191'],
            'numero' => ['nullable', 'string', 'max:10'],
            'complemento' => ['nullable', 'string', 'max:191'],
            'ddd' => ['nullable', 'string', 'max:2'],
            'telefone' => ['nullable', 'string', 'max:20'],
        ];
    }
    
    
    


    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'cnpj.unique' => 'Essa empresa já está cadastrada no Opinário'
        ];
    }
}
