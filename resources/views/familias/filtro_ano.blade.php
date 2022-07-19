@php
    $form = isset($form)?$form:true;
    $onclick=isset($onclick)?$onclick:"$('#frm-ano').submit();$('#preload').css('display','block')";
@endphp
@if(isset($arr_ano) && is_array($arr_ano))
    @if($form)
    <form id="frm-ano" method="get">
    @endif
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            @foreach ($arr_ano as $ka=>$va)
                @php
                    $active=false;
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
            @endforeach
        </div>
    @if($form)
    </form>
    @endif
@endif
