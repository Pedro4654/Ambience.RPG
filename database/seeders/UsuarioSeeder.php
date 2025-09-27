<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        // Inserir usuário admin de teste
        DB::table('usuarios')->insert([
            'username' => 'admin',
            'nickname' => 'Administrador',
            'email' => 'admin@teste.com',
            'senha_hash' => Hash::make('123456'),
            'bio' => 'Usuário administrador do sistema',
            'data_de_nascimento' => '1990-01-01',
            'data_criacao' => now(),
            'status' => 'ativo',
            'nivel_usuario' => 'admin',
            'verificado' => true,
            'pontos_reputacao' => 100,
            'ranking_posicao' => 1,
        ]);

        // Inserir usuário comum de teste
        DB::table('usuarios')->insert([
            'username' => 'usuario',
            'nickname' => 'Usuário Teste',
            'email' => 'usuario@teste.com',
            'senha_hash' => Hash::make('123456'),
            'bio' => 'Usuário comum para testes',
            'data_de_nascimento' => '1995-05-15',
            'data_criacao' => now(),
            'status' => 'ativo',
            'nivel_usuario' => 'usuario',
            'verificado' => false,
            'pontos_reputacao' => 50,
            'ranking_posicao' => 2,
        ]);
    }
}
