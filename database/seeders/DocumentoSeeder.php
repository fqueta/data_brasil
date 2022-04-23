<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('documentos')->insert([
            [
                'nome'=>'Cabeçario',
                'tipo'=>'html',
                'url'=>'cacario-lista-beneficiario',
                'ativo'=>'s',
                'conteudo'=>'<p>
                </p><table width="662" cellspacing="0" cellpadding="7">
                    <colgroup><col width="142">

                    <col width="492">

                    </colgroup><tbody><tr valign="top">
                        <td style="background: transparent" width="142" height="80"><p style="orphans: 0; widows: 0">
                            </p>
                        <br></td>
                        <td style="background: transparent" width="492"><p style="margin-bottom: 0.35cm; orphans: 0; widows: 0" align="center">
                            <font face="Arial, serif"><b>MUNICÍPIO DE CONCEIÇÃO DO MATO
                            DENTRO<br>
                </b>Rua Daniel de Carvalho, 161, Centro – CEP
                            35.860-000</font></p>
                            <p style="orphans: 0; widows: 0" align="center"><font face="Arial, serif">ESTADO
                            DE MINAS GERAIS</font></p>
                        </td>
                    </tr>
                </tbody></table>',
                'excluido'=>'n',
            ],
            [
                'nome'=>'Lista de beneficiário parte1',
                'tipo'=>'html',
                'url'=>'lista-beneficiario',
                'ativo'=>'s',
                'conteudo'=>'<p style="margin-bottom: 0cm; line-height: 100%" align="justify"><a name="_Hlk82675389"></a>
                <font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>{lote}-
                BENEFICIÁRIO(A): {tipo_beneficiario}:</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
                </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>{nome_beneficiario}</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">,
                {nacionalidade}, {estado_civil}, {profissao}, nascida aos {nascimento}, filha de
                {pai} e {mae}, RG:
                {rg}, CPF: {cpf} </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">e</font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
                seu companheiro</font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>
                {nome_conjuge}, </b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">{nacionalidade_conjuge},
                </font></font><font color="#000000"><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">{estado_civil_conjuge},
                {profissao_conjuge}</font></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">,
                nascido aos 20/01/1991,</font></font><font color="#ff0000"><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
                </font></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">filho
                de {pai_conjuge} e {mae_conjuge}, RG: {rg_conjuge},
                CPF: {cpf_conjuge}, vivendo em união estável desde {nascimento_conjuge},
                residentes e domiciliadas na {parte2}</font></font></p><p></p>',
                'excluido'=>'n',
            ],
            [
                'nome'=>'Lista de beneficiário parte2',
                'tipo'=>'html',
                'url'=>'lista-beneficiario-2',
                'ativo'=>'s',
                'conteudo'=>'<p><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">Rua {endereco}, n°{numero},
                ',
                'excluido'=>'n',
            ],
            [
                'nome'=>'Lista de beneficiário Prefeitura',
                'tipo'=>'html',
                'url'=>'lista-beneficiario-prefeitura',
                'ativo'=>'s',
                'conteudo'=>'<p style="margin-bottom: 0cm; line-height: 100%" align="justify">
                <font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>{lote}</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">-
                </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>BENEFICIÁRIO
                (A): Município de Conceição do Mato Dentro/MG, </b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">pessoa
                Jurídica de Direito Público Interno, inscrita no CNPJ:
                18.303.156/0001-07, com sede à Rua Daniel de Carvalho, nº 161,
                Centro, nesta cidade e comarca Conceição do Mato Dentro/MG, CEP:
                35.680-000, representado por José Fernando Aparecido de Oliveira,
                brasileiro, casado, prefeito, portador do RG: M-3.618.630, inscrito
                no CPF sob o nº 032.412.426-09, residente e domiciliado na Rua Raul
                Soares, nº 253, Bairro centro, Conceição do Mato Dentro, CEP:
                35.680-000.</font></font></p>
                <p style="margin-bottom: 0cm; line-height: 100%" align="justify"><br>

                </p>
                <p style="margin-bottom: 0cm; line-height: 100%" align="justify"><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>IMÓVEL</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">:
                LOTE {lote} ({lote_extenso}) – QUADRA {quadra} ({quadra_extenso}), conforme memorial
                descritivo do PRF.  </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>Valor
                Lote</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">:
                </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>R$</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
                </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>{valor}</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
                 </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>Valor
                Edificação</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">:
                </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>R$</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
                </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>{valor_edificacao}.</b></font></font></p>
                <p></p>',
                'excluido'=>'s',
            ],
        ]);
    }
}
