<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illum1inate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyStoreRequest extends CompanyUpdateRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //$rules = parent::rules();
        return [
            'cnpj' => ['required', 'string', 'max:20', Rule::unique(Company::class, 'cnpj')],
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
