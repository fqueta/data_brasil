@php
    $config = $conf['config'];
    $campos = $conf['campos'];
    $value = $conf['value'];
@endphp

    @if($config['ac']=='alt')
    @endif
    <div class="row mb-4">
        <div class="col-md-12 text-right">
            @if (isset($value['id']))
                <label for="">Id:</label> {{ $value['id'] }}
                @elseif (isset($value['ID']))
                <label for="">Id:</label> {{ $value['ID'] }}
            @endif
            @if (isset($value['created_at']))
                <label for="">Cadastro:</label> {{ Carbon\Carbon::parse($value['created_at'])->format('d/m/Y') }}
                @elseif (isset($value['post_date']))
                <label for="">Cadastro:</label> {{ Carbon\Carbon::parse($value['post_date'])->format('d/m/Y') }}
            @endif

        </div>
        @if (isset($campos) && is_array($campos))
            @foreach ($campos as $k=>$v)
                @php
                    if($v['type'] == 'checkbox'){
                        if(isset($v['cp_busca']) && !empty($v['cp_busca'])){
                            $arrcp = explode('][', $v['cp_busca']);
                            if(isset($arrcp[1]) && !empty($arrcp[1])){
                                //exe $value['config'][$arrcp[1]];
                                if(isset($value[$arrcp[0]][$arrcp[1]])){
                                    $value[$k] = $value[$arrcp[0]][$arrcp[1]];
                                }else{
                                    $value[$k] = false;
                                    if($v['arr_opc']){
                                        $value[$k] = 'n';
                                    }
                                }
                            }
                        }
                        if(isset($v['arr_opc'][$value[$k]])){
                            $v['value'] = $v['arr_opc'][$value[$k]];
                        }
                    }
                @endphp
                @if ($v['type']=='select_multiple' || $v['type']=='html_vinculo')
                    @php
                        $nk = str_replace('[]','',$k);
                        if (isset($v['cp_busca'])&&!empty($v['cp_busca'])){
                            $cf = explode('][',$v['cp_busca']);
                            if(isset($cf[1])){
                                if(empty($value[$k])){
                                    $value[$k] = @$value[$cf[0]][$cf[1]];
                                    if(!$value[$k]){
                                        $value[$k] = $value[$nk];
                                    }
                                }
                            }
                        }else{
                            $value[$k] = isset($value[$nk])?$value[$nk]:false;
                        }
                    @endphp
                @endif

                {!! App\Qlib\Qlib::qShow([
                        'type'=>@$v['type'],
                        'campo'=>$k,
                        'label'=>$v['label'],
                        'placeholder'=>@$v['placeholder'],
                        'ac'=>$config['ac'],
                        'value'=>isset($v['value'])?$v['value']: @$value[$k],
                        'tam'=>@$v['tam'],
                        'event'=>@$v['event'],
                        'checked'=>@$value[$k],
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
                ]) !!}
            @endforeach
        @endif
        {{-- @csrf --}}
    </div>
    @include('qlib.btnedit')
