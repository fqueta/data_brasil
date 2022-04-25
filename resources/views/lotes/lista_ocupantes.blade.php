@if (@$config['ac']=='alt')
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">{{__($config['label'])}}</h3>
        </div>
        <div class="card-body">
            @if (isset($config['value']) && is_array($config['value']))
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('Ocupante(s)')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($config['value'] as $k=>$v)
                        <tr>
                            <td>
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">{{__('Ficha de cadastro de ocupante')}}</h4>
                                        <div class="card-tools">
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="proprietario">
                                                    {{__('Proprietário')}}:
                                                </label>
                                                <span>
                                                    <a title="{{__('Editar cadastro de proprietário')}}" href="{{route('beneficiarios.edit',['id'=>$v['id_beneficiario']])}}?redirect={{route('lotes.edit',['id'=>$v['id_lote']])}}" style="text-decoration:underline">
                                                        {{@$v['beneficiario']}}
                                                    </a>
                                                </span>
                                                <label for="conjuge">{{__('Conjuge')}}:</label>
                                                <span>
                                                    <a title="{{__('Editar cadastro do Conjuge')}}" href="{{route('beneficiarios.edit',['id'=>$v['id_conjuge']])}}?redirect={{route('lotes.edit',['id'=>$v['id_lote']])}}" style="text-decoration:underline">
                                                        {{@$v['conjuge']}}
                                                    </a>
                                                </span>
                                                <label for="conjuge">{{__('Lote')}}:</label>
                                                <span>
                                                    {{@$_GET['dados']['nome']}}
                                                    <b>
                                                        <a title="{{__('Editar complemento')}}" href="{{route('familias.edit',['id'=>$v['id']])}}?redirect={{route('lotes.edit',['id'=>$v['id_lote']])}}" style="text-decoration: underline">
                                                            {{@$v['complemento_lote']}}
                                                        </a>
                                                    </b>
                                                </span>
                                            </div>
                                            <div class="col-md-12">
                                                <hr>
                                                <h5>
                                                    Declarações adicionais sobre a posse:
                                                </h5>
                                                <p>
                                                    1. Os ocupantes acima adquiriram a unidade imobiliária por:
                                                </p>
                                                @if (isset($config['arr_opc']) && is_array($config['arr_opc']))
                                                    @foreach ($config['arr_opc'] as $k2=>$v2)
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="config[declaracao_posse][{{$v['id']}}]" id="" value="{{$k2}}"
                                                        @if (@$_GET['dados']['config']['declaracao_posse'][$v['id']]==$k2)
                                                        checked
                                                        @endif
                                                        >
                                                        {{__($v2['label'])}}
                                                      </label>
                                                    </div>
                                                    @endforeach
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-muted text-right">
                                        <a href="{{route('familias.edit',['id'=>$v['id']])}}?redirect={{route('lotes.edit',['id'=>$v['id_lote']])}}" title="{{__('Editar')}}" class="btn btn-outline-primary">
                                            <i class="fa fa-pen" aria-hidden="true"></i> {{__('Editar')}}
                                        </a>
                                        <a href="" title="{{__('Emitir')}}" class="btn btn-outline-secondary">
                                            <i class="fa fa-print" aria-hidden="true"></i> {{__('Imprimir')}}
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            @endif
        </div>
        <div class="card-footer text-muted text-right">
            <!--<a target="_BLANK" href="{{route('familias.create')}}?loteamento=&redirect={{route('lotes.edit',['id'=>'12'])}}" class="btn btn-outline-secondary">{{__('Cadastrar ocupante')}}</a>-->
        </div>
    </div>
@endif
