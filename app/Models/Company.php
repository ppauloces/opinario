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
