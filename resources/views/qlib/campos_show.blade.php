@if (isset($config['type']))
    @if ($config['type']=='select')
        <div class="col-{{$config['tam']}}"  div-id="{{$config['campo']}}" >
            @if ($config['label'])
                <label for="{{$config['campo']}}">{{$config['label']}}:</label>
            @endif
            @if (isset($config['arr_opc']))
                {{@$config['arr_opc'][$config['value']]}}
            @endif
        </div>
    @elseif ($config['type']=='select_multiple')
        @if (isset($config['arr_opc']))
        <div class="col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            @if ($config['label'])
                <label for="{{$config['campo']}}">{{$config['label']}}:</label>
            @endif
            @foreach ($config['arr_opc'] as $k=>$v)
                @if(isset($config['value']) && is_array($config['value']) && in_array($k,$config['value']))
                    {{@$config['arr_opc'][$k]}},
                @endif
            @endforeach
        </div>
        @endif
    @elseif ($config['type']=='selector')
        @if (isset($config['arr_opc']))
        <div class="col-{{$config['tam']}}" div-id="{{$config['campo']}}">
            @if ($config['label'])
                <label for="{{$config['campo']}}">{{$config['label']}}</label>
            @endif
            {{@$config['arr_opc'][$config['value']]}}
        </div>
        @endif
    @elseif ($config['type']=='radio')
        @if (isset($config['arr_opc']))
        <div class="col-{{$config['tam']}}" div-id="{{$config['campo']}}">
            <label for="{{$config['campo']}}">{{$config['label']}}:</label>
                {{@$config['arr_opc'][$config['value']]}}
        </div>
        @endif
    @elseif ($config['type']=='chave_checkbox')
        <div class="col-{{$config['tam']}}" div-id="{{$config['campo']}}">
            <label class="" for="{{$config['campo']}}">
                @if(isset($config['checked']) && $config['checked'] == $config['value'])
                    <i class="fas fa-check-square"></i>
                @endif
                {{$config['label']}}
            </label>

        </div>
    @elseif ($config['type']=='textarea')
        <!--config['checked'] é o gravado no bando do dedos e o value é o valor para ficar checado-->
        <div class="col-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            <label for="{{$config['campo']}}">{{$config['label']}}</label><br>
            @if(isset($config['value'])){{$config['value']}}@endif
        </div>
    @elseif ($config['type']=='html')
        @php
           $config['script'] = isset($config['script'])?$config['script']:false;
        @endphp
        <div class="col-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            @if ($config['script'])
                @if(isset($config['dados']))
                    @include($config['script'],@$config['dados'])
                @else
                    @include($config['script'])
                @endif
            @endif
        </div>
    @elseif ($config['type']=='html_vinculo')
        @php
           $config['script'] = isset($config['script'])?$config['script']:false;
        @endphp
        <div class="col-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            <div class="card card-secondary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        {{__($config['label'])}}
                    </h3>
                </div>
                <div class="card-body">
                   <div class="row" id="row-{{$config['data_selector']['campo']}}">
                        @if ($config['script'])
                            @if(isset($config['dados']))
                                @include($config['script'],@$config['dados'])
                            @else
                                @include($config['script'])
                            @endif
                        @endif
                        @php
                            $d = $config['data_selector'];

                        @endphp
                        @if (isset($d['list']) && is_array($d['list']))
                        @foreach ($d['campos'] as $k=>$v)
                            @if(@$d['tipo']=='array')
                                        @foreach ($d['list'] as $kk=>$vv)
                                                        @php
                                                            if(isset($v['cp_busca']) && !empty($v['cp_busca']))
                                                            {
                                                                $ck = explode('][',$v['cp_busca']);
                                                                if(isset($ck[1])){
                                                                    $value = @$vv[$ck[0]][$ck[1]];
                                                                }else{
                                                                    $value = '';
                                                                }
                                                            }else{
                                                                $value = @$vv[$k];
                                                            }
                                                        @endphp

                                                            {{App\Qlib\Qlib::qShow([
                                                                'type'=>@$v['type'],
                                                                'campo'=>$k,
                                                                'label'=>$v['label'],
                                                                'placeholder'=>@$v['placeholder'],
                                                                'ac'=>$config['ac'],
                                                                'value'=>@$value,
                                                                'tam'=>@$v['tam'],
                                                                'event'=>@$v['event'],
                                                                'checked'=>@$value,
                                                                'selected'=>@$v['selected'],
                                                                'arr_opc'=>@$v['arr_opc'],
                                                                'option_select'=>@$v['option_select'],
                                                                'class'=>@$v['class'],
                                                                'class_div'=>@$v['class_div'],
                                                                'rows'=>@$v['rows'],
                                                                'cols'=>@$v['cols'],
                                                                'data_selector'=>@$v['data_selector'],
                                                                'script'=>@$v['script_show'],
                                                                'valor_padrao'=>@$v['valor_padrao'],
                                                                'dados'=>@$v['dados'],
                                                                ])}}

                                        @endforeach

                                @else
                                    @if(isset($d['list']) && $v['type']!='hidden')
                                        @php
                                            if(isset($d['campos'][$k]['cp_busca']) && !empty($d['campos'][$k]['cp_busca']))
                                            {
                                                $ck = explode('][',$d['campos'][$k]['cp_busca']);
                                                if(isset($ck[1])){
                                                    $value = @$d['list'][$ck[0]][$ck[1]];
                                                }else{
                                                    $value = '';
                                                }

                                            }else{
                                                $value = @$d['list'][$k];
                                            }
                                        @endphp
                                    {{App\Qlib\Qlib::qShow([
                                        'type'=>@$v['type'],
                                        'campo'=>$k,
                                        'label'=>$v['label'],
                                        'placeholder'=>@$v['placeholder'],
                                        'ac'=>$config['ac'],
                                        'value'=>@$value,
                                        'tam'=>@$v['tam'],
                                        'event'=>@$v['event'],
                                        'checked'=>@$value,
                                        'selected'=>@$v['selected'],
                                        'arr_opc'=>@$v['arr_opc'],
                                        'option_select'=>@$v['option_select'],
                                        'class'=>@$v['class'],
                                        'class_div'=>@$v['class_div'],
                                        'rows'=>@$v['rows'],
                                        'cols'=>@$v['cols'],
                                        'data_selector'=>@$v['data_selector'],
                                        'script'=>@$v['script_show'],
                                        'valor_padrao'=>@$v['valor_padrao'],
                                        'dados'=>@$v['dados'],
                                        ])}}
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="card-footer text-muted">
                        {{@$footer}}
                </div>
            </div>
        </div>
    @elseif($config['type']=='text')
    <div class="col-{{$config['tam']}}" div-id="{{$config['campo']}}">
        <label for="{{$config['campo']}}">{{$config['label']}}:</label>
        {{@$config['value']}}
    </div>
    @else
    <div class="col-{{$config['tam']}}" div-id="{{$config['campo']}}">
        <label for="{{$config['campo']}}">{{$config['label']}}:</label>
        {{@$config['value']}}
    </div>
    @endif
@endif
