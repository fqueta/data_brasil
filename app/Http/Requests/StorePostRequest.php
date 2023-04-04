<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{

    public $routa;
    public function __construct()
    {
        $seg1 = request()->segment(1);
        $this->routa = $seg1;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->routa=='processos' || $this->routa=='processos-campo' || $this->routa=='processos-prefeitura' || $this->routa=='processos-cartorio'){
            return [
                'post_type' => ['required'],
                //'post_content' => ['required'],
            ];
        }else{
            return [
                'post_type' => ['required'],
                'post_name' => ['required'],
                'post_title' => ['required'],
            ];
        }
    }
    public function messages()
    {
        if($this->routa=='processos' || $this->routa=='processos-campo' || $this->routa=='processos-prefeitura' || $this->routa=='processos-cartorio'){
            return [
                'post_type.required' => __('Selecione o local'),
            ];
        }else{
            return [
                'post_name.required' => __('O campo Slug é obrigatório'),
                'post_title.required' => __('O campo Nome é obrigatório'),
                'post_type.required' => __('Selecione o local'),
            ];
        }
    }
}
