<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            [
                'categoria'=>'CADASTROS',
                'description'=>'Cursos',
                'icon'=>'fas fa-copy',
                'actived'=>true,
                'url'=>'cad-cursos',
                'route'=>'',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Todos Cursos',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'cursos',
                'route'=>'cursos.index',
                'pai'=>'cad-cursos'
            ],
            [
                'categoria'=>'',
                'description'=>'Categoria',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'categorias',
                'route'=>'categorias.index',
                'pai'=>'cad-cursos'
            ],
            [
                'categoria'=>'',
                'description'=>'Modulos',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'modulos',
                'route'=>'modulos.index',
                'pai'=>'cad-cursos'
            ],
            [
                'categoria'=>'',
                'description'=>'Provas',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'provas',
                'route'=>'provas.index',
                'pai'=>'cad-cursos'
            ],
            [
                'categoria'=>'',
                'description'=>'Questões',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'questoes',
                'route'=>'questoes.index',
                'pai'=>'cad-cursos'
            ],
            [
                'categoria'=>'',
                'description'=>'Relatórios',
                'icon'=>'fas fa-file',
                'actived'=>true,
                'url'=>'relatorios',
                'route'=>'relatorios.index',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Geral',
                'icon'=>'fas fa-file',
                'actived'=>true,
                'url'=>'relatorios_geral',
                'route'=>'relatorios.geral',
                'pai'=>'relatorios'
            ],
            [
                'categoria'=>'',
                'description'=>'Evolução',
                'icon'=>'fa fa-chart-bar',
                'actived'=>true,
                'url'=>'relatorios_evolucao',
                'route'=>'relatorios.evolucao',
                'pai'=>'relatorios'
            ],
            [
                'categoria'=>'SISTEMA',
                'description'=>'Configurações',
                'icon'=>'fas fa-cogs',
                'actived'=>true,
                'url'=>'config',
                'route'=>'sistema.config',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Perfil',
                'icon'=>'fas fa-user',
                'actived'=>true,
                'url'=>'sistema',
                'route'=>'sistema.perfil',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Usuários',
                'icon'=>'fas fa-users',
                'actived'=>true,
                'url'=>'users',
                'route'=>'users.index',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Permissões',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'permissions',
                'route'=>'permissions.index',
                'pai'=>'config'
            ],
        ]);
    }
}
