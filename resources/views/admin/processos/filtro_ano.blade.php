@php
    $form = isset($form)?$form:true;
    $onclick=isset($onclick)?$onclick:"$('#frm-ano').submit();$('#preload').css('display','block')";
    $active=false;
@endphp
@if(isset($arr_ano) && is_array($arr_ano))
    @if($form)
    <form id="frm-ano" method="get">
    @endif
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            @foreach ($arr_ano as $ka=>$va)
                @if(!empty($va->vl))
                    @php
                        $checked=false;
                        if(isset($_GET['ano'])&&$_GET['ano']==$va->vl){
                            $active='active';
                            $checked='checked';
                        }
                    @endphp
                    <label class="btn btn-outline-secondary {{$active}}">
                        <input {{$checked}} onclick="{{$onclick}}" type="radio" name="ano" id="ano-{{$va->vl}}" value="{{$va->vl}}"/>
                        {{$va->vl}}
                    </label>
                @endif
            @endforeach
            @php
                $checkedTodos = false;
                if(isset($_GET['ano'])&&$_GET['ano']==''){
                    $checkedTodos = 'checked';
                }elseif (!isset($_GET['ano'])) {
                    $checkedTodos = 'checked';
                }
            @endphp
            <label class="btn btn-outline-secondary {{@$active}}">
                <input {{$checkedTodos}} onclick="checkTodosAnos();" type="radio" name="ano" id="todos" value=""/>
                {{__('Todos Anos')}}
            </label>
        </div>
    @if($form)
    </form>
    @endif
@endif
