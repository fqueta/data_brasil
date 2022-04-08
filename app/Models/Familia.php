<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    use HasFactory;
    protected $casts = [
        'config' => 'array',
        'tags' => 'array',
    ];
    protected $fillable = [
        'token',
        'area_alvo',
        'tipo_residencia',
        'etapa',
        'loteamento',
        'id_loteamento',
        'matricula',
        'quadra',
        'lote',
        'nome_completo',
        'cpf',
        'nome_conjuge',
        'cpf_conjuge',
        'telefone',
        'escolaridade',
        'estado_civil',
        'situacao_profissional',
        'qtd_membros',
        'membros',
        'idoso',
        'crianca_adolescente',
        'bcp_bolsa_familia',
        'renda_familiar',
        'doc_imovel',
        'endereco',
        'numero',
        'bairro',
        'cidade',
        'autor',
        'obs',
        'tags',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado',
    ];
    public function etapa()
    {
        return $this->hasOne('App\Models\Etapa');
    }
}
