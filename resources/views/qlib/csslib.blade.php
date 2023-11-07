<link rel="shortcut icon" href="/vendor/adminlte/dist/img/AdminLTELogo.png">
<link rel="stylesheet" href="{{url('/')}}/vendor/summernote/summernote.min.css">
<link rel="stylesheet" href="{{url('/')}}/vendor/venobox/venobox.min.css">
<link rel="stylesheet" href="{{url('/')}}/css/jquery-ui.min.css">
<link rel="stylesheet" href="{{url('/')}}/css/lib.css?ver={{config('app.version')}}">
<!--Inicio PWA-->
<link rel="manifest" href="/app/manifest.json" />
<script src="/app/js.js?ver={{config('app.version')}}"></script>
<script>
    if (typeof navigator.serviceWorker !== 'undefined') {
        navigator.serviceWorker.register('/app/pwabuilder-sw.js')
    }
</script>
<!--FIM PWA-->
<style>
    #setup_button,.r-btn-install {
        display: none;
    }
    .r-btn-install{
        position: fixed;
        right: 0;
        bottom: 0;
        background-color: #FFF;
        width: 100%;
        padding: 10px;
        z-index: 3;
        height: 58px;
    }
    .r-btn-install img{
        height: 100%;
    }
    .note-view,.note-insert,.note-table,.note-color{
        display: none;
    }
</style>
@if (isset($_GET['popup']) && $_GET['popup'])
<style>
    aside,.wrapper nav{
        display: none;
    }
    .content-wrapper{
        margin-left:0px !important;
    }

</style>
@endif
<div id="preload">
    <div class="lds-dual-ring"></div>
</div>
{{-- <div class="col-md-12">
    @php
        $cob = new App\http\Controllers\admin\CobrancaController;
        $cob->exec();
    @endphp
</div> --}}
