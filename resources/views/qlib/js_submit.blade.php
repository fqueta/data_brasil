<script>
    $(function(){

        $('[type="submit"]').on('click',function(e){
            e.preventDefault();
            let btn_press = $(this).attr('btn');
            submitFormulario($('#{{$config['frm_id']}}'),function(res){
                if(res.exec){
                    lib_formatMensagem('.mens',res.mens,res.color);
                }
                if(btn_press=='sair'){
                    var redirect = $('[btn-volter="true"]').attr('redirect');
                    if(redirect){
                        window.location = redirect;
                    }else if(res.return){
                        window.location = res.return;
                    }
                }else if(btn_press=='permanecer'){
                    if(res.redirect){
                        window.location = res.redirect;
                    }
                }
                if(res.errors){
                    alert('erros');
                    console.log(res.errors);
                }
            });
        });
    });
</script>
