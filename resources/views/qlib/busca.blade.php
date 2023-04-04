<!-- Modal -->

<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{__('Pesquisar cadastros')}}</h5>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <!--<form action="" method="GET">-->
                <div class="row">
                    @if (isset($campos_tabela))
                        @foreach ($campos_tabela as $kbu=>$vbu)
                            @php
                                $exibe_busca = isset($vbu['exibe_busca']) ? $vbu['exibe_busca']:true;
                                if($vbu['type'] == 'hidden'&&$exibe_busca){
                                    $vbu['type']='text';
                                }
                            @endphp
                            @if ($vbu['active'] && $exibe_busca)
                                @php
                                    $value1[$kbu] = isset($_GET['filter'][$kbu])?$_GET['filter'][$kbu]:false;
                                    if($vbu['type']!='text' && $kbu=='id'){
                                        $vbu['type'] = 'text';
                                    }elseif($vbu['type']=='chave_checkbox'){
                                        if(!isset($_GET['filter'][$kbu]) && isset($vbu['valor_padrao'])){
                                            $value1[$kbu] = $vbu['valor_padrao'];
                                            // $_GET['filter'][$kbu] = $vbu['valor_padrao'];
                                        }

                                    }
                                    if($kbu!='obs')
                                        $vbu['tam'] = 3;
                                    $cp_busca = isset($vbu['cp_busca'])?$vbu['cp_busca']:$kbu;
                                @endphp
                                {{App\Qlib\Qlib::qForm([
                                    'type'=>isset($vbu['type'])?$vbu['type']:'text',
                                    'campo'=>'filter['.$cp_busca.']',
                                    'placeholder'=>isset($vbu['placeholder'])?$vbu['placeholder']:'',
                                    'label'=>$vbu['label'],
                                    'ac'=>'alt',
                                    'value'=>@$value1[$kbu],
                                    'tam'=>isset($vbu['tam'])?$vbu['tam']:'3',
                                    'class_div'=>$vbu['exibe_busca'],
                                    'valor_padrao'=>@$vbu['valor_padrao'],
                                    'event'=>isset($vbu['event'])?$vbu['event']:'',
                                    'arr_opc'=>isset($vbu['arr_opc'])?$vbu['arr_opc']:'',
                                    'label_option_select'=>'Todas',
                                    'checked'=>@$value1[$kbu],
                                    'selected'=>@$vbu['selected'],

                                ])}}
                            @endif

                        @endforeach
                    @else
                    {{App\Qlib\Qlib::qForm([
                        'type'=>'text',
                        'campo'=>'filter[loteamento]',
                        'placeholder'=>'Loteamento',
                        'label'=>'Loteamento',
                        'ac'=>'alt',
                        'value'=>@$_GET['filter']['loteamento'],
                        'tam'=>'4',
                        'event'=>'',
                    ])}}
                    {{App\Qlib\Qlib::qForm([
                        'type'=>'text',
                        'campo'=>'filter[area_alvo]',
                        'placeholder'=>'Informe Área',
                        'label'=>'Área Alvo',
                        'ac'=>'alt',
                        'value'=>@$_GET['filter']['area_alvo'],
                        'tam'=>'2',
                        'event'=>'',
                        ])}}
                    @endif
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button class="btn btn-primary" type="submit"> <i class="fas fa-search"></i> Localizar</button>
                            <a href=" {{route($routa.'.index')}} " class="btn btn-default" title="Limpar Filtros" type="button"> <i class="fas fa-times"></i> Limpar</a>
                            @include($view.'.dropdow_actions')
                        </div>
                    </div>
                </div>
            <!--</form>-->
        </div>
    </div>
</div>
