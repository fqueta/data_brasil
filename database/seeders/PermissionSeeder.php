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
                "ler"=>["cad-social"=>"s","cad-topografico"=>"s","familias"=>"s","quadras"=>"s","lotes"=>"s","beneficiarios"=>"s","bairros"=>"s","etapas"=>"s","escolaridades"=>"s","estado-civil"=>"s","relatorios"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","config"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "create"=>["familias"=>"s","bairros"=>"s","quadras"=>"s","lotes"=>"s","beneficiarios"=>"s","etapas"=>"s","escolaridades"=>"s","estado-civil"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "update"=>["familias"=>"s","bairros"=>"s","quadras"=>"s","lotes"=>"s","beneficiarios"=>"s","etapas"=>"s","escolaridades"=>"s","estado-civil"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "delete"=>["familias"=>"s","bairros"=>"s","quadras"=>"s","lotes"=>"s","beneficiarios"=>"s","etapas"=>"s","escolaridades"=>"s","estado-civil"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"]
            ],
            "admin"=>
            [
                "ler"=>["cad-social"=>"s","cad-topografico"=>"s","familias"=>"s","quadras"=>"s","lotes"=>"s","beneficiarios"=>"s","bairros"=>"s","etapas"=>"s","escolaridades"=>"s","estado-civil"=>"s","relatorios"=>"n","relatorios_geral"=>"n","relatorios_evolucao"=>"n","config"=>"s","sistema"=>"n","users"=>"s","permissions"=>"s"],
                "create"=>["familias"=>"s","quadras"=>"s","lotes"=>"s","bairros"=>"s","beneficiarios"=>"s","etapas"=>"s","escolaridades"=>"s","estado-civil"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "update"=>["familias"=>"s","quadras"=>"s","lotes"=>"s","bairros"=>"s","beneficiarios"=>"s","etapas"=>"s","escolaridades"=>"s","estado-civil"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "delete"=>["familias"=>"s","quadras"=>"s","lotes"=>"s","bairros"=>"s","beneficiarios"=>"s","etapas"=>"s","escolaridades"=>"s","estado-civil"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"]
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
