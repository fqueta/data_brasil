<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrPermiss = [
            "master"=>
            [
                "ler"=>["cad-cursos"=>"s","cursos"=>"s","categorias"=>"s","modulos"=>"s","questoes"=>"s","provas"=>"s","relatorios"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","config"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "create"=>["cursos"=>"s","categorias"=>"s","modulos"=>"s","questoes"=>"s","provas"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "update"=>["cursos"=>"s","categorias"=>"s","modulos"=>"s","questoes"=>"s","provas"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "delete"=>["cursos"=>"s","categorias"=>"s","modulos"=>"s","questoes"=>"s","provas"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"]
            ],
            "admin"=>
            [
                "ler"=>["cad-cursos"=>"s","cursos"=>"s","categorias"=>"s","modulos"=>"s","questoes"=>"s","provas"=>"s","relatorios"=>"n","relatorios_geral"=>"n","relatorios_evolucao"=>"n","config"=>"s","sistema"=>"n","users"=>"s","permissions"=>"s"],
                "create"=>["cursos"=>"s","categorias"=>"s","modulos"=>"s","questoes"=>"s","provas"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "update"=>["cursos"=>"s","categorias"=>"s","modulos"=>"s","questoes"=>"s","provas"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "delete"=>["cursos"=>"s","categorias"=>"s","modulos"=>"s","questoes"=>"s","provas"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"]
            ],
        ];
        DB::table('permissions')->insert([
            [
                'name'=>'Master',
                'description'=>'Desenvolvedores',
                'active'=>'s',
                'id_menu'=>json_encode($arrPermiss['master']),
            ],
            [
                'name'=>'Adminstrador',
                'description'=>'Adiminstradores do sistema',
                'active'=>'s',
                'id_menu'=>json_encode($arrPermiss['admin']),
            ],
            [
                'name'=>'Gerente',
                'description'=>'Gerente do sistema menos que administrador secundário',
                'active'=>'s',
                'id_menu'=>json_encode([]),
            ],
            [
                'name'=>'Escritório',
                'description'=>'Pessoas do escritório',
                'active'=>'s',
                'id_menu'=>json_encode([]),
            ],
            [
                'name'=>'Alunos',
                'description'=>'Somente clientes, Sem privilêgios de administração acesso a área restrita do site','active'=>'s',
                'id_menu'=>json_encode([]),
            ],
        ]);
    }
}
