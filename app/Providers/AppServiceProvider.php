<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // ← ESTA LINHA ESTAVA FALTANDO!
use App\Models\Usuario;
use App\Policies\UsuarioPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Usuario::class, UsuarioPolicy::class);
    }
}