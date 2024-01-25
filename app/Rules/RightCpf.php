<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RightCpf implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $valor;
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
     public function passes($attribute, $value)
     {
        $this->valor = $value;
        $value = trim($value);
        $value = str_replace('.', '', $value);
        $value = str_replace('-', '', $value);
        $value = str_replace('/', '', $value);
        if(strlen($value)>11){
            return $this->validateCnpj($value);
        }else{
            return $this->validateCpf($value);
        }
     }

     /**
      * Get the validation error message.
      *
      * @return string
      */
     public function message()
     {
        if(strlen($this->valor)>14){
            return 'Este CNPJ não é válido';
        }else{
            return 'Este CPF não é válido';
        }
     }

     public function validateCpf($cpf){
         if(empty($cpf))
            return true;
         $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
         if (strlen($cpf) != 11) {
             return false;
         }
         if (preg_match('/(\d)\1{10}/', $cpf)) {
             return false;
         }
         for ($t = 9; $t < 11; $t++) {
             for ($d = 0, $c = 0; $c < $t; $c++) {
                 $d += $cpf[$c] * (($t + 1) - $c);
             }
             $d = ((10 * $d) % 11) % 10;
             if ($cpf[$c] != $d) {
                 return false;
             }
         }
         return true;
     }
    public function validateCnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}
