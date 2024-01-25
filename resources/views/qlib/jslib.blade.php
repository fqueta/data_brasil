@include('qlib.partes_html',['config'=>[
    'parte'=>'modal',
    'id'=>'modal-geral',
    'conteudo'=>false,
    'botao'=>false,
    'botao_fechar'=>true,
    'tam'=>'modal-lg',
]])
@if (isset($config['media']))
    @include('admin.media.painel_select_media')
@endif
@include('qlib.modal_pesquisa')
@include('admin.partes.footer')
<script src="{{url('/js/jquery.maskMoney.min.js')}}"></script>
<script src="{{url('/js/jquery-ui.min.js')}}"></script>
<script src="{{url('/js/jquery.inputmask.bundle.min')}}.js"></script>
<script src="{{url('/js/jquery.mask.js')}}"></script>
<script src="{{url('/vendor/summernote/summernote.min.js')}}"></script>
<script src="{{url('/vendor/venobox/venobox.min.js')}}"></script>
<script src="{{url('/js/jquery.validate.min.js')}}"></script>
<script src=" {{url('/js/lib.js')}}?ver={{config('app.version')}}"></script>
<script>
    $(function(){
        $('.dataTable').DataTable({
                "paging":   false,
                stateSave: true,
                language: {
                    url: '/DataTables/datatable-pt-br.json'
                }
        });
        carregaMascaraMoeda(".moeda");
        $('[selector-event]').on('change',function(){
            initSelector($(this));
        });
        $('[vinculo-event]').on('click',function(){
            var funCall = function(res){};
            initSelector($(this));
        });

        $('.select2').select2();
        $('[fachar-alerta-fatura="true"]').on('click',function(){
            fecharAlertaFatura('{{route('alerta.cobranca.fechar')}}');
        });

        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        lib_autocompleteGeral('.autocomplete');
        lib_autocompleteGeral('.autocomplete-pesq',function(ui,el){
            console.log(ui);
            try {
                if(ui.id){
                    let ur = '/beneficiarios/'+ui.id+'?redirect='+window.location.href;
                    window.location = ur;
                }
            } catch (error) {
                console.log(error);
            }
        });
        $('.summernote').summernote({
            height: 250,
            placeholder: 'Digite o conteudo',
        });
        new VenoBox({
            selector: ".venobox",
            numeration: true,
            infinigall: true,
            share: false,
            spinner: 'rotating-plane'
        });
        $('[data-toggle="tooltip"]').tooltip({html:true});
        $('[data-toggle="popover"]').popover({html:true,container: 'body'});
        $('[data-widget="navbar-search"]').on('click', function(){
            $('.navbar-search-block').hide();
            $('#pesquisar').modal('show');
            // $('[type="button"][data-widget="navbar-search"]').click();
        });
        var options = {
            onKeyPress: function (cpf, ev, el, op) {
                var masks = ['000.000.000-000', '00.000.000/0000-00'];
                $('[mask-cpfcnpj]').mask((cpf.length > 14) ? masks[1] : masks[0], op);
            }
        }

        $('[mask-cpfcnpj]').length > 11 ? $('[mask-cpfcnpj]').mask('00.000.000/0000-00', options) : $('[mask-cpfcnpj]').mask('000.000.000-00#', options);

    });
</script>
