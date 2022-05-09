
<script>
    $( function() {
        $( "#svg-img" ).draggable();
    });
    </script>
    <script>
        $( ".lote" ).on('click',function(){
            var id = $(this).attr('id');
            lib_conteudoMapa(id,'lotes','quadras');
        });
    </script>
