
<script>
    $( function() {
        $( "#svg-img" ).draggable({
            drag: function( event, ui ) {

                // Keep the left edge of the element
                // at least 100 pixels from the container
                //ui.position.left = Math.min( 100, ui.position.left );
                console.log(ui);
            }}
        );
    });
    </script>
    <script>
        $( ".lote" ).on('click',function(){
            var id = $(this).attr('id');
            lib_conteudoMapa(id,'lotes','quadras');
        });
    </script>
