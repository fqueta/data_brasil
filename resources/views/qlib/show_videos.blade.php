<div class="row">

    @foreach ($dados as $k=>$v)
        @isset($v['src'])
            <div class="col-md-6 mb-3">
                <iframe src="{{$v['src']}}" width="100%" height="250px" frameborder="0"></iframe>
            </div>
        @endisset
    @endforeach
</div>
