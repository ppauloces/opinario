<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Estado;
use App\Models\Cidade;
use App\Models\User;
use App\Http\Requests\CompanyUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Storage;

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
    public function getCidades(string $id)
    {
        $cidades = Cidade::with('Estado')->where('estado_id', $id)->get();

        return response()->json($cidades);
    }

    public function getCep($cep)
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        //\Log::info('CEP Limpo:', ['cep' => $cep]);

        $url = "https://viacep.com.br/ws/{$cep}/json/";

        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();

            $data = [
                'cep' => $data['cep'],
                'logradouro' => $data['logradouro'],
                'complemento' => $data['complemento'],
                'bairro' => $data['bairro']
            ];

            return response()->json($data);
        } else {
            return response()->json(['error' => 'Falha ao recuperar dados'], $response->status());
        }



        //return response()->json(['cep' => $response->$logradouro]);
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

        Company::create($data);

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
    public function edit(string $id)
    {
        $company = Company::find($id);

        $estados = Estado::all();
        $cidades = Cidade::all();

        if (!$company) {
            return redirect()->route('dashboard')->with('error', 'Registro não encontrado');
        }

        return view('company.edit', compact('company', 'estados', 'cidades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, string $id)
    {
        $validatedData = $request->validated();

        // Remover máscaras dos campos
        $validatedData['cnpj'] = preg_replace('/\D/', '', $validatedData['cnpj']);
        $validatedData['cep'] = preg_replace('/\D/', '', $validatedData['cep']);
        $validatedData['telefone'] = preg_replace('/\D/', '', $validatedData['telefone']);

        $company = Company::find($id);
        if (!$company) {
            return redirect()->route('dashboard')->with('error', 'Registro não encontrado');
        }

        if ($request->has('logo')) {

            if ($company->logo) {
                $oldLogoPath = public_path('uploads/' . $company->logo);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
            }
            
            $logoData = $request->input('logo');
            // Decodificar o arquivo base64 e armazenar
            list($type, $data) = explode(';', $logoData);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);

            // Determinar a extensão do arquivo com base no tipo MIME
            $mimeType = explode('/', mime_content_type($logoData))[1];

            $razao_social_arquivo = str_replace(' ', '_', $validatedData['razao_social']);
            $filename = $razao_social_arquivo . '_' . time() . '.' . $mimeType;

            // Certificar-se de que o diretório 'uploads' existe
            $uploadsPath = public_path('uploads');
            if (!file_exists($uploadsPath)) {
                mkdir($uploadsPath, 0755, true);
            }

            file_put_contents($uploadsPath . '/' . $filename, $data);
            $validatedData['logo'] = $filename;
        }

        $company->update($validatedData);

        return redirect()->route('company.edit', $company->id)->with('status', 'informacoes-atualizadas');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
