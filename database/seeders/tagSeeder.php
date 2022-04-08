<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class tagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            ['nome'=>'Tags situação dos cadastros','obs'=>'Informar os cadastros que estão faltando alguma informação ou documentos.'],
            ['nome'=>'Tags Tipo do imóvel','obs'=>'Informar os cadastros que estão faltando alguma informação ou documentos.'],
            [
                'nome'=>'Cadastros com pendências',
                'pai'=>1,
                'obs'=>'Informar os cadastros que estão faltando alguma informação ou documentos.',
                'config'=>['color'=>'danger','icon'=>'fa fa-times']
            ],
            [
                'nome'=>'Imóveis com registros',
                'pai'=>1,
                'obs'=>'Informar se o imóvel já possui matrícula no cartório de registros.',
                'config'=>['color'=>'info','icon'=>'fas fa-calendar-check']
            ],
            [
                'nome'=>'Recusas',
                'pai'=>1,
                'obs'=>'Informa que o proprietário do imóvel foi localizado mas se recusou a participar do projeto de regularização fundiária.',
                'config'=>['color'=>'warning','icon'=>'fas fa-search-minus']
            ],
            [
                'nome'=>'Proprietários não localizados',
                'pai'=>1,
                'obs'=>'Informa que o proprietário do imóvel não foi localizado durante as etapas do projeto de regularização fundiária.',
                'config'=>['color'=>'warning','icon'=>'fa fa-times']
            ],
            ['nome'=>'Residencial','pai'=>2,'obs'=>''],
            ['nome'=>'Comercial','pai'=>2,'obs'=>''],
            ['nome'=>'Lote vago','pai'=>2,'obs'=>''],
        ];

        foreach ($arr as $key => $value) {
            $d = $value;
            $d['token']=uniqid();
            Tag::create($d);
        }
    }
}
