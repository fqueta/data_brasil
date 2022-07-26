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
                'categoria'=>'',
                'description'=>'Painel',
                'icon'=>'fa fa-tachometer-alt',
                'actived'=>true,
                'url'=>'painel',
                'route'=>'home',
                'pai'=>''
            ],
            // [
            //     'categoria'=>'',
            //     'description'=>'Transparencia',
            //     'icon'=>'fas fa-search-location',
            //     'actived'=>true,
            //     'url'=>'transparencia',
            //     'route'=>'transparencia',
            //     'pai'=>''
            // ],
            [
                'categoria'=>'CADASTROS',
                'description'=>'Cadastro Social',
                'icon'=>'fas fa-user',
                'actived'=>true,
                'url'=>'cad-social',
                'route'=>'',
                'pai'=>''
            ]
            ,[
                'categoria'=>'',
                'description'=>'Cadastro Topográfico',
                'icon'=>'fa fa-map-marker',
                'actived'=>true,
                'url'=>'cad-topografico',
                'route'=>'',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Famílias',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'familias',
                'route'=>'familias.index',
                'pai'=>'cad-social'
            ],
            [
                'categoria'=>'',
                'description'=>'Beneficiários',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'beneficiarios',
                'route'=>'beneficiarios.index',
                'pai'=>'cad-social'
            ],
            [
                'categoria'=>'',
                'description'=>'Lotes',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'lotes',
                'route'=>'lotes.index',
                'pai'=>'cad-topografico'
            ],
            [
                'categoria'=>'',
                'description'=>'Quadras',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'quadras',
                'route'=>'quadras.index',
                'pai'=>'cad-topografico'
            ],
            [
                'categoria'=>'',
                'description'=>'Áreas',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'bairros',
                'route'=>'bairros.index',
                'pai'=>'cad-topografico'
            ],
            [
                'categoria'=>'',
                'description'=>'Etapas',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'etapas',
                'route'=>'etapas.index',
                'pai'=>'cad-social'
            ],
            [
                'categoria'=>'',
                'description'=>'Escolaridade',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'escolaridades',
                'route'=>'escolaridades.index',
                'pai'=>'cad-social'
            ],
            [
                'categoria'=>'',
                'description'=>'Estado civil',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'estado-civil',
                'route'=>'estado-civil.index',
                'pai'=>'cad-social'
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
                'description'=>'Realidade Social',
                'icon'=>'fas fa-file',
                'actived'=>true,
                'url'=>'relatorios_social',
                'route'=>'relatorios.social',
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
            /*[
                'categoria'=>'',
                'description'=>'Listagem de Ocupantes',
                'icon'=>'fa fa-chart-bar',
                'actived'=>true,
                'url'=>'relatorios_evolucao',
                'route'=>'relatorios.evolucao',
                'pai'=>'relatorios'
            ],*/
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
                'description'=>'Documentos',
                'icon'=>'fas fa-file-word',
                'actived'=>true,
                'url'=>'documentos',
                'route'=>'documentos.index',
                'pai'=>'config'
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
                'icon'=>'far fa-list-alt ',
                'actived'=>true,
                'url'=>'permissions',
                'route'=>'permissions.index',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Listas do sistema (Tags)',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'tags',
                'route'=>'tags.index',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Avançado (Dev)',
                'icon'=>'fas fa-user',
                'actived'=>true,
                'url'=>'qoptions',
                'route'=>'qoptions.index',
                'pai'=>'config'
            ],
        ]);
    }
}
