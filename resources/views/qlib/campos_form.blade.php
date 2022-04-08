@if (isset($config['type']))
    @if ($config['type']=='select')
        @if (isset($config['arr_opc']))
        <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            @if ($config['label'])
                 <label for="{{$config['campo']}}">{{$config['label']}}</label>
            @endif
            <select name="{{$config['campo']}}" {{$config['event']}} id="sele-{{$config['campo']}} @error($config['campo']) is-invalid @enderror" class="form-control custom-select selectpicker {{$config['class']}}">
                @if ($config['option_select'])
                    <option value=""> {{$config['label_option_select']}} </option>
                @endif
                @foreach ($config['arr_opc'] as $k=>$v)
                    <option value="{{$k}}" @if(isset($config['value']) && $config['value'] == $k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
            @error($config['campo'])
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        @endif
    @elseif ($config['type']=='select_multiple')
        @if (isset($config['arr_opc']))
        <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            @php
                //$config['value'] = json_decode($config['value'],true);
            @endphp
            @if ($config['label'])
                <label for="{{$config['campo']}}">{{$config['label']}}</label>
            @endif
            <select name="{{$config['campo']}}" multiple="true" {{$config['event']}} id="sele-{{$config['campo']}} @error($config['campo']) is-invalid @enderror" class="form-control custom-select select2 {{$config['class']}}">
                @if ($config['option_select'])
                    <option value=""> {{$config['label_option_select']}} </option>
                @endif
                @foreach ($config['arr_opc'] as $k=>$v)
                    <option value="{{$k}}" @if(isset($config['value']) && is_array($config['value']) && in_array($k,$config['value'])) selected @endif>{{$v}}</option>
                @endforeach
            </select>
            @error($config['campo'])
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        @endif
    @elseif ($config['type']=='selector')
        @if (isset($config['arr_opc']))
            <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
                @if ($config['label'])
                    <label for="{{$config['campo']}}">{{$config['label']}}</label>
                @endif
                <select name="{{$config['campo']}}" {{$config['event']}} data-selector="{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}" selector-event id="sele-{{$config['campo']}} @error($config['campo']) is-invalid @enderror" class="form-control custom-select selectpicker {{$config['class']}}">
                    @if ($config['option_select'])
                        <option value=""> {{$config['label_option_select']}} </option>
                    @endif
                    <option value="cad"> Cadastrar {{$config['label']}}</option>
                    <option value="" disabled>--------------</option>

                    @foreach ($config['arr_opc'] as $k=>$v)
                        <option value="{{$k}}" @if(isset($config['value']) && $config['value'] == $k) selected @endif>{{$v}}</option>
                    @endforeach
                </select>
                @error($config['campo'])
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        @endif
    @elseif ($config['type']=='radio')
        <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}} @error($config['campo']) is-invalid @enderror">
            <div class="btn-group" data-toggle="buttons">
                @if ($config['label'])
                    <label for="{{$config['campo']}}">{{$config['label']}}</label>
                @endif

                @foreach ($config['arr_opc'] as $k=>$v)
                <label class="{{ $config['class'] }} @if(isset($config['value']) && $config['value'] == $k) active @endif ">
                    <input type="radio" name="{{ $config['campo']}}" {{$config['event']}} value="{{$k}}" id="" autocomplete="off" @if(isset($config['value']) && $config['value'] == $k) checked @endif > {{ $v }}
                </label>
                @endforeach
            </div>
        </div>
    @elseif ($config['type']=='hidden')
        <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}} d-none" div-id="{{$config['campo']}}" >
            @if ($config['label'])
                <label for="{{$config['campo']}}">{{$config['label']}}</label>
            @endif
            <input type="{{$config['type']}}" class="form-control @error($config['campo']) is-invalid @enderror {{$config['class']}}" id="inp-{{$config['campo']}}" name="{{$config['campo']}}" aria-describedby="{{$config['campo']}}" placeholder="{{$config['placeholder']}}" value="@if(isset($config['value'])){{$config['value']}}@elseif($config['ac']=='cad'){{old($config['campo'])}}@endif" {{$config['event']}} />
            @error($config['campo'])
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    @elseif ($config['type']=='chave_checkbox')
        <!--config['checked'] é o gravado no bando do dedos e o value é o valor para ficar checado-->
        <div class="form-group col-{{$config['col']}}-{{$config['tam']}}">
            <div class="custom-control custom-switch @error($config['campo']) is-invalid @enderror {{$config['class']}}">
                <input type="checkbox" class="custom-control-input" @if(isset($config['checked']) && $config['checked'] == $config['value']) checked @endif  value="{{$config['value']}}"  name="{{$config['campo']}}" id="{{$config['campo']}}">
                <label class="custom-control-label" for="{{$config['campo']}}">{{$config['label']}}</label>
            </div>
            @error($config['campo'])
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    @elseif ($config['type']=='textarea')
        <!--config['checked'] é o gravado no bando do dedos e o value é o valor para ficar checado-->
        <div class="col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            <div class="form-group">
            <label for="{{$config['campo']}}">{{$config['label']}}</label><br>
            <textarea name="{{$config['campo']}}" class="form-control @error($config['campo']) is-invalid @enderror {{$config['class']}}" rows="{{@$config['rows']}}" cols="{{@$config['cols']}}">@if(isset($config['value'])){{$config['value']}}@elseif($config['ac']=='cad'){{old($config['campo'])}}@endif</textarea>
            </div>
        </div>
    @elseif ($config['type']=='html')
        @php
           $config['script'] = isset($config['script'])?$config['script']:false;
        @endphp
        <div class="col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            @if ($config['script'])
                @if(isset($config['dados']))
                    @include($config['script'],@$config['dados'])
                @else
                    @include($config['script'])
                @endif
            @endif
        </div>
    @else
    <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}" >
        @if ($config['label'])
            <label for="{{$config['campo']}}">{{$config['label']}}</label>
        @endif
        <input type="{{$config['type']}}" class="form-control @error($config['campo']) is-invalid @enderror {{$config['class']}}" id="inp-{{$config['campo']}}" name="{{$config['campo']}}" aria-describedby="{{$config['campo']}}" placeholder="{{$config['placeholder']}}" value="@if(isset($config['value'])){{$config['value']}}@elseif($config['ac']=='cad'){{old($config['campo'])}}@endif" {{$config['event']}} />
        @error($config['campo'])
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    @endif
@endif
