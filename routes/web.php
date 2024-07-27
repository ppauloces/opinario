<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [CompanyController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //empresas
    Route::post('/search-cnpj', [CompanyController::class, 'searchCNPJ'])->name('search-cnpj');
    Route::get('/cep/{cep}', [CompanyController::class, 'getCep']);
    Route::post('/company', [CompanyController::class, 'store'])->name('company.store');
    Route::post('/company', [CompanyController::class, 'store'])->name('company.store');
    //exibir formulario de edicao
    Route::get('/company/{company}/edit', [CompanyController::class, 'edit'])->name('company.edit');
    Route::put('/company/{company}', [CompanyController::class, 'update'])->name('company.update');
    
    Route::get('/cidades/{estado_id}', [CompanyController::class, 'getCidades']);

    Route::get('/testes', function () {
        return view('layouts.tests');
    });

});

require __DIR__.'/auth.php';
