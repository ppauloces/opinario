<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'razao_social',
        'porte',
        'cnpj',
        'email',
        'logo',
        'site',
        'country_code',
        'state_id',
        'city_id',
        'cep',
        'bairro',
        'logradouro',
        'numero',
        'complemento',
        'ddd',
        'telefone',
        'atividade_principal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function rules()
    {
        return [
            'user_id' => ['required'],
            'razao_social' => ['required', 'string', 'max:255'],
            'porte' => ['nullable', 'string', 'max:30'],
            'cnpj' => ['required', 'string', 'max:14', 'unique:companies'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:companies'],
            'logo' => ['nullable', 'mimes:png,jpg,jpeg', 'file'],
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

    public static function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'logo.mimes' => 'O arquivo deve ser uma imagem do tipo PNG',
            'cnpj.unique' => 'Essa empresa já está cadastrada no Opinário',
            'logo.file' => "O campo de imagem é obrigatório"
        ];
    }

    public function estados()
    {
        return $this->belongsTo(Estado::class, 'state_id');
    }

    /**
     * Get the city that the company belongs to.
     */
    public function cidades()
    {
        return $this->belongsTo(Cidade::class, 'city_id');
    }
}
