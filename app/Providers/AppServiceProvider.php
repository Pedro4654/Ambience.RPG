<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Broadcast; // ← ADICIONAR ESTE USE
use App\Models\Usuario;
use App\Policies\UsuarioPolicy;
use App\Models\Post;
use App\Policies\PostPolicy;

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
        // Registrar Policy de Usuário
        Gate::policy(Usuario::class, UsuarioPolicy::class);
        
        // ==================== REGISTRAR ROTAS DE BROADCASTING ====================
        // ADICIONE ESTAS LINHAS:
        Broadcast::routes(['middleware' => ['web', 'auth']]);
    }

    protected $policies = [
    Post::class => PostPolicy::class,
];

}
