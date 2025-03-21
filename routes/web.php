<?php

use App\Livewire\Admin;
use App\Livewire\Client;
use App\Livewire\Public;
use Illuminate\Support\Facades\Route;
use App\Livewire\Public\Home\HomeIndex;

Route::get('/', HomeIndex::class);
Route::get('/inicio', HomeIndex::class)->name('home');

Route::get('/test/components/mary-ui', \App\Livewire\Components\ComponentsMaryUi::class)->name('components-maryui.index');

Route::middleware(['auth'])->as('panel.')->group(function () {

    Route::get('/minha-conta', Public\Account\AccountView::class)->name('account.view');
    Route::get('/minha-conta/edit', Public\Account\AccountEdit::class)->name('account.edit');

    Route::middleware('verified')->group(function () {
        Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
            Route::get('/dashboard', Admin\Dashboard\DashboardIndex::class)->name('dashboard');
            Route::get('/usuarios', Admin\Users\UserIndex::class)->name('accounts.index');
            Route::get('/vendedores', Admin\Vendedores\VendedoresIndex::class)->name('vendedores.index')->middleware('permission:vendedores.viewAny');
            Route::get('/clientes', Admin\Clientes\ClientesIndex::class)->name('clientes.index')->middleware('permission:clientes.viewAny');
            Route::get('/contratos', Admin\Contratos\ContratosIndex::class)->name('contratos.index')->middleware('permission:contratos.viewAny');
            Route::get('/financiamentos', Admin\Financiamentos\FinanciamentosIndex::class)->name('financiamentos.index')->middleware('permission:financiamentos.viewAny');
            
            Route::get('/roles', Admin\Roles\RoleIndex::class)->name('roles.index')->middleware('permission:roles.viewAny');
            Route::get('/permissions', Admin\Permissions\PermissionIndex::class)->name('permissions.index')->middleware('permission:permissions.viewAny');
        });
    });
});

require __DIR__.'/auth.php';