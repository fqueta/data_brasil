<link rel="shortcut icon" href="/vendor/adminlte/dist/img/AdminLTELogo.png">
<link rel="stylesheet" href="{{url('/')}}/vendor/summernote/summernote.min.css">
<link rel="stylesheet" href="{{url('/')}}/vendor/venobox/venobox.min.css">
<link rel="stylesheet" href="{{url('/')}}/css/jquery-ui.min.css">
<link rel="stylesheet" href="{{url('/')}}/css/lib.css">
<link rel="manifest" href="/manifest.json" />
<script>
    if (typeof navigator.serviceWorker !== 'undefined') {
        navigator.serviceWorker.register('pwabuilder-sw.js')
    }
</script>
<style>
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
