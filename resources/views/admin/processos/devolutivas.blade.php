@php
    $r = explode('.',Route::currentRouteName());
    $route = isset($r[1]) ? $r[1] : false;
    $dconf = isset($dados['config'])?$dados['config']:false;
    // dd($dados['config']);
    $campo_his_dev = 'config[hist_dev][{id}]';
    if(isset($dconf['nota_devolutiva']) && $dconf['nota_devolutiva']=='s'){
        $class_display = '';
    }else{
        $class_display = 'd-none d-print-none';
    }
@endphp
@if ($route=='edit')
@php
    $tm = '<tr id="tr-{id}">
                <td>
                    <div class="row">
                        <div class="col-3">
                            <label>Data:</label> <input type="date" class="form-control" value="{data}" name="{campo}[data]" />
                        </div>
                        <div class="col-3">
                            <label>Calc Dias: <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" title="{title_calc_dias}"></i></label> <input title="{title_calc_dias}" disabled type="tel" class="form-control bg-secondary" value="{cal_dias}" name="{campo}[cal_dias]" />
                        </div>
                        <div class="col-3">
                            <label>Protocolo:</label> <input type="tel" class="form-control" value="{protocolo}" name="{campo}[protocolo]" />
                        </div>
                        <div class="col-3">
                            <label>Talão:</label> <input class="form-control" type="tel" value="{talao}" name="{campo}[talao]" />
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <textarea class="form-control mt-1" placeholder="Motivo" name="{campo}[motivo]">{motivo}</textarea>
                        </div>
                        <div class="col-4">
                            <label>Cumprimento:</label> <input class="form-control" type="date" value="{data_cumprimento}" name="{campo}[data_cumprimento]" />
                        </div>
                        <div class="col-4">
                            <label>Calc Dias: <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" title="{title_calc_dias2}"></i></label> <input title="{title_calc_dias2}" disabled type="tel" class="form-control bg-secondary" value="{cal_dias2}" name="{campo}[cal_dias2]" />
                        </div>
                        <div class="col-4">
                            <label>Área afim:</label>
                            <select class="form-control" name="{campo}[area]">
                                <option value="Jurídico" {sel_juri}>Jurídico</option>
                                <option value="Topografia" {sel_topo}>Topografia</option>
                                <option value="Administrativo"  {sel_admi}>Administrativo</option>
                            </select>
                        </div>

                    </div>
                </td>
                <td class="text-right">
                    {acao}
                </td>
            </tr>';

@endphp

    <div id="card-historico-dev" class="card card-secondary card-outline {{$class_display}}">
        <div class="card-header">
            {{__('Histórico de devoluções')}}
        </div>
        <div class="card-body">
            <table id="dv_historico" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 90%">{{__('Informações')}}</th>
                        {{-- <th style="width: 45%">{{__('Detalhes')}}</th> --}}
                        <th style="width: 5%" class="text-right">...</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $tr = (new App\Http\Controllers\admin\processosController)->list_historico_devolutivas($dados,$tm);
                        echo $tr;
                    @endphp
                </tbody>
            </table>
        </div>
        <div class="card-footer text-muted">
            <button type="button" data-campo="{!!$campo_his_dev!!}" class="btn btn-outline-secondary" data-tm="{!!base64_encode($tm)!!}" onclick="add_historico_devolucao(this)" ><i class="fas fa-plus"></i> {{__('Adicionar Histórico')}}</button>
        </div>
    </div>
@elseif ($route=='show')
@php
    $tm = '<tr id="tr-{id}">
                <td>
                    <div class="row">
                        <div class="col-3">
                            <label>Data:</label> <span> {data} </span>
                        </div>
                        <div class="col-3">
                            <label>Calc Dias:</label> <span> {cal_dias} </span>
                        </div>
                        <div class="col-3">
                            <label>Protocolo:</label> <span> {protocolo} </span>
                        </div>
                        <div class="col-3">
                            <label>Talão:</label> <span> {talao} </span>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label>Cumprimento:</label> <span> {data_cumprimento} </span>
                        </div>
                        <div class="col-4">
                            <label>Calc Dias:</label> <span> {cal_dias2} </span>
                        </div>
                        <div class="col-4">
                            <label>Área afim:</label> <span>{area}</span>
                        </div>
                        <div class="col-12">
                            <label>Motivo:</label>
                            <span>{motivo}</span>
                        </div>
                    </div>
                </td>
            </tr>';
@endphp
<div class="card card-secondary card-outline {{$class_display}}">
    <div class="card-header">
        {{__('Histórico de devoluções')}}
    </div>
    <div class="card-body">
        <table id="dv_historico" class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 100%">{{__('Informações')}}</th>
                    {{-- <th style="width: 50%">{{__('Detalhes')}}</th> --}}
                </tr>
            </thead>
            <tbody>
                @php
                    $tr = (new App\Http\Controllers\admin\processosController)->list_historico_devolutivas($dados,$tm);
                    echo $tr;
                @endphp
            </tbody>
        </table>
    </div>
    {{-- <div class="card-footer text-muted">
        <button type="button" data-campo="{!!$campo_his_dev!!}" class="btn btn-outline-secondary" data-tm="{!!base64_encode($tm)!!}" onclick="add_historico_devolucao(this)" ><i class="fas fa-plus"></i> {{__('Adicionar Histórico')}}</button>
    </div> --}}
</div>
@endif
