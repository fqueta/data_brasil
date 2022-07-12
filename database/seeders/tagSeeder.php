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
            ['nome'=>'Tags situação dos cadastros','obs'=>'Informar os cadastros que estão faltando alguma informação ou documentos.','ordem'=>1],
            ['nome'=>'Tags Tipo do imóvel','obs'=>'Informar os cadastros que estão faltando alguma informação ou documentos.','ordem'=>2],
            [
                'nome'=>'CADASTROS COM PENDÊNCIAS',
                'pai'=>1,
                'ordem'=>2,
                'obs'=>'São cadastros incompletos devido a três fatores: Recusas (quando o beneficiário não quer, ao menos naquele momento, cadastra-se e fazer parte do Programa); Ocupantes não localizados (quando o ocupante de determinado lote não é encontrado), e Outros (quando faltam documentos como uma Certidão de Casamento, por exemplo).',
                'config'=>['color'=>'danger','icon'=>'fa fa-times']
            ],
            [
                'nome'=>'IMÓVEIS COM REGISTROS',
                'pai'=>1,
                'ordem'=>3,
                'obs'=>'São imóveis que são cadastrados, mas que não comporão o processo jurídico por já apresentarem registro imobiliário. Devem ser considerados como cadastros completos.',
                'config'=>['color'=>'info','icon'=>'fas fa-calendar-check']
            ],
            [
                'nome'=>'Recusas',
                'pai'=>11,
                'value'=>'recusas',
                'ordem'=>1,
                'obs'=>'Quando o beneficiário não quer, ao menos naquele momento, cadastra-se e fazer parte do Programa.',
                'config'=>['color'=>'warning','icon'=>'fas fa-search-minus']
            ],
            [
                'nome'=>'Ocupantes não localizados',
                'pai'=>11,
                'ordem'=>2,
                'value'=>'ocupantes_nao_localizados',
                'obs'=>'Quando o ocupante de determinado lote não é encontrado.',
                'config'=>['color'=>'warning','icon'=>'fa fa-times']
            ],
            ['nome'=>'Residencial','pai'=>2,'ordem'=>1,'obs'=>''],
            ['nome'=>'Comercial','pai'=>2,'ordem'=>2,'obs'=>''],
            ['nome'=>'Lote vago','pai'=>2,'ordem'=>3,'obs'=>''],
            [
                'nome'=>'CADASTROS COMPLETOS',
                'pai'=>1,
                'ordem'=>1,
                'obs'=>'São cadastros socioeconômicos preenchidos e com toda a documentação necessária para serem encaminhados à Assistência Social para a elaboração do Relatório Social, documento que irá compor o processo jurídico de regularização fundiária. Sem o cadastro completo não há condições de avançar no processo.',
                'config'=>['color'=>'success','icon'=>'fa fa-check']
            ],
            ['nome'=>'Tags categoria de pendências','obs'=>'tipos de pendencias','ordem'=>3],
            [
                'nome'=>'Outros',
                'pai'=>11,
                'value'=>'outros',
                'ordem'=>3,
                'obs'=>'Quando faltam documentos como uma Certidão de Casamento, por exemplo',
                'config'=>['color'=>'warning','icon'=>'fa fa-times']
            ],


        ];

        foreach ($arr as $key => $value) {
            $d = $value;
            $d['token']=uniqid();
            Tag::create($d);
        }
    }
}
