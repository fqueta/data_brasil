
<script>
    $( function() {
        $( "#svg-img" ).draggable();
    });
    </script>
    <script>
        function zoom(c) {
            var s = new Number(50);
            let box = document.querySelector('#svg-img');
            let width = box.style.width;
            let height = box.offsetHeight;
            var w = width.replace('%','');
            w = new Number(w);
            if(w==0){
                w=100;
            }
            if(c=='p'){
                box.style.width = (w+s)+'%';
            }
            if(c=='m'){
                box.style.width = (w-s)+'%';
            }
            console.log({ width, height });

        }
        $( ".lote" ).on('click',function(){
            var id = $(this).attr('id');
            alert(id);
        });
    </script>
