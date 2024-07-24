<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Estado;
use App\Models\Cidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{

    public function index()
    {
        $user = User::find(auth()->user()->id);
        $companies = Company::where('user_id', $user->id)
            ->with(['estados', 'cidades']) // Inclua as relações state e city
            ->get();
        
        return view('dashboard', compact('companies', 'user'));
    }

    public function searchCNPJ(Request $request)
    {

        $cnpjMascarado = $request->input('cnpj');

        $cnpj = preg_replace('/[^0-9]/', '', $cnpjMascarado);

        $url = "https://publica.cnpj.ws/cnpj/{$cnpj}";

        $response = Http::get($url);

        if ($response->successful()) {

            $data = $response->json();

            $razaoSocial = $data['razao_social'] ?? 'Razão social não encontrada';

            return response()->json(
                [
                    'razao_social' => $razaoSocial,
                    'response' => $data
                ]
            );
        } else {

            return response()->json(['error' => 'Falha ao recuperar dados'], $response->status());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function getCidades()
    {
        $estado = Estado::with('Cidades')->get();

        // Obter todos as cidades da Bahia (codigo_uf = 29)
        $cidades_bahia = Cidade::with('Estado')->where('estado_id', 29)->get();

        foreach ($cidades_bahia as $c) {
            echo $c->nome . " - " . $c->estado->nome . '<br>';
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //cnpj
        $cnpjMascarado = $request->input('cnpj');
        $cnpj = preg_replace('/[^0-9]/', '', $cnpjMascarado);

        //corpo json das outras informações
        $companies = $request->input('companyResponse');
        $companies = json_decode($companies);


        $data = [
            'user_id' => Auth::id(),
            'razao_social' => $companies->razao_social,
            'porte' => $companies->porte->descricao,
            'cnpj' => $cnpj,
            'email' => $companies->estabelecimento->email,
            'country_code' => $companies->estabelecimento->pais->iso2,
            'state_id' => $companies->estabelecimento->estado->ibge_id,
            'city_id' => $companies->estabelecimento->cidade->id,
            'cep' => $companies->estabelecimento->cep,
            'bairro' => $companies->estabelecimento->bairro,
            'logradouro' => $companies->estabelecimento->logradouro,
            'numero' => $companies->estabelecimento->numero,
            'complemento' => $companies->estabelecimento->complemento,
            'ddd' => $companies->estabelecimento->ddd1,
            'telefone' => $companies->estabelecimento->telefone1,
            'atividade_principal' => $companies->estabelecimento->atividade_principal->descricao
        ];

        $validator = Validator::make($data, Company::rules(), Company::feedback());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $company = Company::create($data);

        return redirect()->route('dashboard')->with('success', 'Empresa cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
