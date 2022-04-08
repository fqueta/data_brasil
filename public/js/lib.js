function uniqid(prefix, more_entropy) {
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +    revised by: Kankrelune (http://www.webfaktory.info/)
  // %        note 1: Uses an internal counter (in php_js global) to avoid collision
  // *     example 1: uniqid();
  // *     returns 1: 'a30285b160c14'
  // *     example 2: uniqid('foo');
  // *     returns 2: 'fooa30285b1cd361'
  // *     example 3: uniqid('bar', true);
  // *     returns 3: 'bara20285b23dfd1.31879087'
  if (typeof prefix == "undefined") {
    prefix = "";
  }
  var retId;
  var formatSeed = function (seed, reqWidth) {
    seed = parseInt(seed, 10).toString(16); // to hex str
    if (reqWidth < seed.length) { // so long we split
      return seed.slice(seed.length - reqWidth);
    }
    if (reqWidth > seed.length) { // so short we pad
      return Array(1 + (reqWidth - seed.length)).join('0') + seed;
    }
    return seed;
  };
  // BEGIN REDUNDANT
  if (!this.php_js) {
    this.php_js = {};
  }
  // END REDUNDANT
  if (!this.php_js.uniqidSeed) { // init seed with big random int
    this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
  }
  this.php_js.uniqidSeed++;

  retId = prefix; // start with prefix, add current milliseconds hex string
  retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
  retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
  if (more_entropy) {
    // for more entropy we add a float lower to 10
    retId += (Math.random() * 10).toFixed(8).toString();
  }
  return retId;
}
function lib_urlAtual(){
  return window.location.href;
}
function goToByScroll2(seletor) {
    // Remove "link" from the ID
    seletor = seletor.replace("link", "");
    // Scroll
	$('html,body').animate({
        scrollTop: $(seletor).offset().top
    }, 'slow');
}
function redirect_blank(url) {
  var a = document.createElement('a');
  a.target="_blank";
  a.href=url;
  a.click();
}
function encodeArray(arr){
	var encode = JSON.stringify(btoa(arr));
	return encode
}
function decodeArray(arr){
	var decode = JSON.parse(atob(arr));
	return decode
}
function __translate(val,val2){
	return val;
}
function lib_formatMensagem(locaExive,mess,style,tempo){
	var mess = "<div class=\"alert alert-"+style+" alert-dismissable\" role=\"alert\"><button class=\"close\" type=\"button\" data-dismiss=\"alert\" aria-hidden=\"true\">X</button><i class=\"fa fa-exclamation-triangle\"></i>&nbsp;"+mess+"</div>";
	if(typeof(tempo) == 'undefined')
		var tempo = 4000;
	setTimeout(function(){$(".alert").hide('slow')}, tempo);
	$(locaExive).html(mess);
}
function abrirjanela(url, nome, w, h, param){
	if(param.length > 1)
		var popname = window.open(url, nome, 'width='+w+', height='+h+', scrollbars=yes, '+param);
	else
		var popname = window.open(url, nome, 'width='+w+', height='+h+', scrollbars=yes');
	 popname.window.focus();
}
function abrirjanela1(url, nome, w, h, param){
	var largura = $( window ).width() - w;
	var altura   = $( window ).height();
	altura =new Number(altura) + new Number(h);
	var left = new Number(largura);
	//left = (left) - (left/Number(2));
	if(param.length > 1)
		var popname = window.open(url, nome, 'width='+largura+', height='+altura+', left='+10+' scrollbars=yes, '+param);
	else
		var popname = window.open(url, nome, 'width='+largura+', height='+altura+', scrollbars=yes,left='+10+'');
	 popname.window.focus();
}
function abrirjanelaPadrao(url,windo){
	if(typeof windo == 'undefined'){
		windo = "novo_cada";
	}
	var meio = (screen.availWidth - 200)/((screen.availWidth-200)/50);
	if($(window).width() > 900){
		var wid = screen.availWidth - 100;
		//var height = $( window ).height() - ($( window ).height()/4);
		var height = screen.availHeight-90;
	}else{
		var wid = $(window).width();
		var height = screen.availHeight;
		//var height = $(document).height();
		//height = new Number(height) - new Number(100);
	}
	//alert(height);
	abrirjanela(url, windo, wid, height, "left="+meio+",toolbar=no, location=no, directories=no, status=no, menubar=no");
}
function abrirjanelaFull(url,windo){
	if(typeof windo == 'undefined'){
		windo = "janelaFull";
	}
	var params = [
		'height='+screen.height,
		'width='+screen.width,
		'fullscreen=yes' // only works in IE, but here for completeness
	].join(',');

	var popup = window.open(url, windo, params);
	popup.window.focus();
	popup.moveTo(0,0);
	//abrirjanela(url, windo, screen.availWidth, screen.availHeight, ",toolbar=no, location=no, directories=no, status=no, menubar=no");
}
function abrirjanelaPadraoConsulta(url){
	if($(window).width() > 800){
		var meio = 1050 / (6);
		var wid = 1050;
		var height = $( window ).height();
	}else{
		var meio = $(window).width() / (6);
		var wid = $(window).width();
		var height = $( window ).height();
	}
	abrirjanela(url, "consultaCliente", wid, height, "left="+meio+",toolbar=no, location=no, directories=no, status=no, menubar=no");
}

function openPageLink(ev,url,ano){
  ev.preventDefault();
  var u = url.trim()+'?ano='+ano;
	abrirjanelaPadrao(u);
	//window.location = u;
}
function gerenteAtividade(obj,ac){
  var id = obj.attr('id');
  var temaImput = '<input type="{type}" {seletor} style="width:{wid}px" name="{name}" value="{value}" class="form-control text-center"> {btn}';
  var arr = ['publicacao','video','hora','revisita','estudo','obs'];
  var selId = $('#'+id);
  var exec = selId.attr('exec');
  if(exec=='s'){
    return
  }
  for (var i = 0; i < arr.length; i++) {
    var eq = (i+1);
    var s = $('#'+id+' td:eq('+eq+')');
    if(i==0){
      selId.attr('exec','s');
    }
    if(i==5){
       var wid='200';
       var t='text';
       var b='<button type="button" onclick="submitRelatorio(\''+id+'\',\''+ac+'\')" title="Salvar" class="btn btn-primary" name="button"><i class="fa fa-check"></i></button>'+
       '<button type="button" onclick="cancelEdit(\''+id+'\')" title="Cancelar edição" data-toggle="tooltip"  class="btn btn-secondary" name="button"><i class="fa fa-times"></i></button>';
			 if(ac=='alt'){
				 b += '<button type="button" onclick="delRegistro(\''+id+'\')" title="Apagar registro" data-toggle="tooltip"  class="btn btn-danger" name="button"><i class="fa fa-trash"></i></button>';
			 }
       s.addClass('d-flex');
    }else{
      var wid='100';
      var b='';
      var t='number';
    }
    var v = s.html();
    var c = temaImput.replace('{name}',id+arr[i]);
    c = c.replace('{value}',v);
    c = c.replace('{wid}',wid);
    c = c.replace('{type}',t);
    c = c.replace('{btn}',b);
    c = c.replace('{seletor}',arr[i]);
    s.html(c);
    //array[i]
  }
  $('#'+id+' td:eq(1) input').select();
}
function cancelEdit(id,ac){
  //var temaImput = '<input type="{type}" style="width:{wid}px" name="{name}" value="{value}" class="form-control text-center"> {btn}';
  if(typeof ac == 'undefined'){
    ac = 'edit';
  }
  var temaImput = '{value}';
  var arr = ['publicacao','video','hora','revisita','estudo','obs'];
  var selId = $('#'+id);
  for (var i = 0; i < arr.length; i++) {
    var eq = (i+1);
    var td = $('#'+id+' td:eq('+eq+')');
    var s = $('#'+id+' input['+arr[i]+']');
    if(i==0){
      selId.removeAttr('exec');
    }
    if(i==5){
       var wid='200';
       var t='text';
       td.removeClass('d-flex');
    }else{
      var wid='100';
      var b='';
      var t='number';
    }
    var v = s.val();
    if(ac=='del'){
      var c = temaImput.replace('{value}','');
    }else{
      var c = temaImput.replace('{value}',v);
    }
    //c = c.replace('{value}',v);
    //c = c.replace('{wid}',wid);
    //c = c.replace('{type}',t);
    //c = c.replace('{btn}',b);
    td.html(c);
  }
}
function delRegistro(id){
  var don = $('#'+id+' input');
  console.log(don);
  var arr = [];
  var seriali = '';
  $.each(don,function(i,k){
    var ke = k.name;
    ke = ke.replace(id,'');
     arr[ke] = k.value;
     seriali += ke+'='+k.value+'&';
    //console.log(k.name);
  });
  //var var_cartao = atob(arr['var_cartao']);
    $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
         }
     });

     var formData = seriali,state = jQuery('#btn-save').val();
     if(ac=='del'){
       var type = "DELETE";
     }else{
       var type = "POST";
     }
     var ac='del',ajaxurl = $('[name="routAjax_'+ac+'"]').val();
     $.ajax({
         type: type,
         url: ajaxurl,
         data: formData,
         dataType: 'json',
         success: function (data) {

           if(data.exec){
             cancelEdit(id,'del');
             if(data.mens){
               lib_formatMensagem('.mens',data.mens,'success');
             }
           }else{
             lib_formatMensagem('.mens',data.mens,'danger');
           }
           if(data.cartao.totais){
             var array = data.cartao.totais;
             var id_pub = data.cartao.dados.id;
             var eq = 1;
             $.each(array,function(i,k){
                $('#pub-'+id_pub+' .tf-1 th:eq('+(eq)+')').html(k);
               eq++;
             });
           }
           if(data.cartao.medias){
             var array = data.cartao.medias;
             var id_pub = data.cartao.dados.id;
             var eq = 1;
             $.each(array,function(i,k){
                $('#pub-'+id_pub+' .tf-2 th:eq('+(eq)+')').html(k);
               eq++;
             });
           }
           if(data.salvarRelatorios.obs && data.salvarRelatorios.mes){
             var selector = '#'+id_pub+'_'+data.salvarRelatorios.mes+' td';
             $(selector).last().html(data.salvarRelatorios.obs);
           }

         },
         error: function (data) {
             console.log(data);
         }
     });
}
/*
function submitRelatorio(id,ac){
  var don = $('#'+id+' input');
  console.log(don);
  var arr = [];
  var seriali = '';
  $.each(don,function(i,k){
    var ke = k.name;
    ke = ke.replace(id,'');
     arr[ke] = k.value;
     seriali += ke+'='+k.value+'&';
    //console.log(k.name);
  });
  var var_cartao = atob(arr['var_cartao']);
    $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
           }
       });

       var formData = seriali+'compilado=s';
       var state = jQuery('#btn-save').val();
       if(ac=='cad'){
         var type = "POST";
       }else{
         var type = "POST";
       }
       var ajaxurl = $('[name="routAjax_'+ac+'"]').val();
       $.ajax({
           type: type,
           url: ajaxurl,
           data: formData,
           dataType: 'json',
           success: function (data) {
             if(data.exec){
               cancelEdit(id);
							 if(data.mens){
								 lib_formatMensagem('.mens',data.mens,'success');
							 }
						 }else{
							 lib_formatMensagem('.mens',data.mens,'danger');
						 }
             if(data.cartao.totais){
               var array = data.cartao.totais;
               var id_pub = data.cartao.dados.id;
               var eq = 1;
               $.each(array,function(i,k){
                  $('#pub-'+id_pub+' .tf-1 th:eq('+(eq)+')').html(k);
                 eq++;
               });
             }
             if(data.cartao.medias){
               var array = data.cartao.medias;
               var id_pub = data.cartao.dados.id;
               var eq = 1;
               $.each(array,function(i,k){
                  $('#pub-'+id_pub+' .tf-2 th:eq('+(eq)+')').html(k);
                 eq++;
               });
             }
             if(data.salvarRelatorios.obs && data.salvarRelatorios.mes){
               var selector = '#'+id_pub+'_'+data.salvarRelatorios.mes+' td';
               $(selector).last().html(data.salvarRelatorios.obs);
             }

           },
           error: function (data) {
               console.log(data);
           }
       });
  //console.log(arr);
}
*/
function alerta(msg,id,title,tam,fechar,time,fecha){

	if(typeof(fechar) == 'undefined')
        fechar = true;
    if(typeof(title) == 'undefined')
    title = 'Janela modal';
    if(typeof(fecha) != 'undefined')
        fecha = fecha;
    else
        fecha = '';
	if(typeof(id) == 'undefined')
    id = 'meuModal';
	if(typeof(tam) == 'undefined')
    tam = '';
	if(typeof(time) == 'undefined')
        time = 2000;
    if(fechar)
        fechar = '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button></div>';
    var modalHtml = '<div class="modal fade" id="'+id+'" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">'+
            '<div class="modal-dialog '+tam+'" role="document">'+
                '<div class="modal-content">'+
                    '<div class="modal-header">'+
                        '<h5 class="modal-title">'+title+'</h5>'+
                            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                                '<span aria-hidden="true">&times;</span>'+
                            '</button>'+
                    '</div>'+
                    '<div class="modal-body">'+msg+
                    '</div>'+fechar+
                '</div>'+
            '</div>'+
        '</div>';
        $('#'+id).remove();
	  var bodys = $(document.body).append(modalHtml);

	  $("#"+id).modal('show');
	if(fecha == true)
	setTimeout(function(){$("#"+id).modal("hide")}, time);
}
function editarAssistencia(obj){
    var sele = obj.attr('sele');
    var arr = sele.split('_');
    var ac = arr[0];
    var id = 0;
    var s = $('[sele="'+sele+'"] .l1');
    if(arr[0]=='edit'){
      id = arr[1];
      var d = '';
      var valor = s.find('span').html();
      valor = valor.trim();
    }else{
      var d = s.find('[name="dados"]').val();
      valor = 0;
    }
    var tema = '<form id="frm_'+sele+'">'+
                    '<div class="input-group">'+
                      '<input type="number" class="form-control" style="width:56px" name="qtd" value="{value}" placeholder="0">'+
                      '<input type="hidden" class="form-control" name="ac" value="{ac}">'+
                      '<input type="hidden" class="form-control" name="id" value="{id}">'+
                      '<span class="input-group-btn">'+
                        '<button class="btn btn-primary" onclick="salvarAssitencia(\'frm_'+sele+'\',\''+d+'\');" type="button"><i class="fa fa-check"></i></button>'+
                        '<button class="btn btn-secondary" onclick="cancelEditAssistencia(\'frm_'+sele+'\',{valor})" type="button"><i class="fa fa-times"></i></button>'+
                      '</span>'+
                    '</div>'+
                  '</form>';
    var nv = tema.replace('{value}',valor);
    nv = nv.replace('{ac}',ac);
    nv = nv.replace('{id}',id);
    nv = nv.replace('{valor}',valor);
    s.find('span').html(nv);
    s.find('[name="qtd"]').select();
}
function cancelEditAssistencia(frm,qtd){
  var sele = frm.replace('frm_','');
  var s = $('[sele="'+sele+'"] .l1').find('span');
  var tema = '{qtd}';
  var nv = tema.replace('{qtd}',qtd);
  //nv = nv.replace('{ac}',ac);
  //nv = nv.replace('{id}',id);
  s.html(nv);
}
function salvarAssitencia(frm,dados){
  //var var_cartao = atob(arr['var_cartao']);
        $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
           }
       });

       //var state = jQuery('#btn-save').val();
       var f = $('#'+frm);
       var ac = f.find('[name="ac"]').val();
       var RAIZ = $('[name="raiz"]').val();
       if(ac=='cad'){
         var type = "POST";
         var ajaxurl = RAIZ+'/assistencias';
       }else{
         var id = f.find('[name="id"]').val();
         var type = "POST";
         var ajaxurl = RAIZ+"/assistencias/"+id;
       }
       $.ajax({
           type: type,
           url: ajaxurl,
           data: f.serialize()+'&dados='+dados,
           dataType: 'json',
           success: function (data) {
             if(data.exec){
               cancelEditAssistencia(frm,data.data.qtd);
							 if(data.mens){
								 lib_formatMensagem('.mens',data.mens,'success');
							 }
						 }else{
							 lib_formatMensagem('.mens',data.mens,'danger');
						 }
             if(data.data.dados[0].semanas[6].qtd){
               var totalR1 = data.data.dados[0].semanas[6].qtd;
               $('[sele="total_0_6"] span').html(totalR1);
             }
             if(data.data.dados[0].semanas[7].qtd){
               var mediaR1 = data.data.dados[0].semanas[7].qtd;
               $('[sele="media_0_7"] span').html(mediaR1);
             }
             if(data.data.dados[1].semanas[6].qtd){
               var totalR1 = data.data.dados[1].semanas[6].qtd;
               $('[sele="total_1_6"] span').html(totalR1);
             }
             if(data.data.dados[1].semanas[7].qtd){
               var mediaR1 = data.data.dados[1].semanas[7].qtd;
               $('[sele="media_1_7"] span').html(mediaR1);
             }

             /*
             if(data.cartao.medias){
               var array = data.cartao.medias;
               var id_pub = data.cartao.dados.id;
               var eq = 1;
               $.each(array,function(i,k){
                  $('#pub-'+id_pub+' .tf-2 th:eq('+(eq)+')').html(k);
                 eq++;
               });
             }
             if(data.salvarRelatorios.obs && data.salvarRelatorios.mes){
               var selector = '#'+id_pub+'_'+data.salvarRelatorios.mes+' td';
               $(selector).last().html(data.salvarRelatorios.obs);
             }
              */
           },
           error: function (data) {
               console.log(data);
           }
       });
}
function mask(o, f) {
	setTimeout(function() {
		var v = clientes_mascaraTelefone(o.value);
		if (v != o.value && o.value!='') {
		  o.value = v;
		}
	  }, 1);
}
function clientes_mascaraTelefone(v) {
	var r = v.replace(/\D/g, "");
	  r = r.replace(/^0/, "");
	  if (r.length > 10) {
		r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1)$2-$3");
	  } else if (r.length > 5) {
		r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1)$2-$3");
	  } else if (r.length > 2) {
		r = r.replace(/^(\d\d)(\d{0,5})/, "($1)$2");
	  } else {
		r = r.replace(/^(\d*)/, "($1");
	  }
	  return r;
}
function confirmDelete(obj){
    var id = obj.data('id');
    if(window.confirm('DESEJA MESMO EXCLUIR?')){
        // $('#frm-'+id).submit();
        submitFormulario($('#frm-'+id),function(res){
            if(res.mens){
                lib_formatMensagem('.mens',res.mens,res.color);
            }
            if(res.return){
                location.reload();
                //window.location = res.return
            }
            if(res.errors){
                alert('erros');
                console.log(res.errors);
            }
        });
    }

}
function urlAtual(){
    return window.location.href;
}
function isEmpty(str) {
    return (!str || 0 === str.length);
}
function __translate(val,val2){
      return val;
}
function lib_trataAddUrl(campo,valor,urlinic){
	var ret = '';
	if(typeof urlinic == 'undefined')
		var urla = urlAtual();
	else
		var urla = urlinic;
	var urlAtua = urla.split('?');
	if(typeof urlAtua[1]=='undefined'){
		urlAtua[1] = '';
	}
	var urlA1 = urlAtua[1];
	var opc = 1;
	urlA1 = urlA1.replace('&=','',urlAtua[1]);
	if(opc==1){
			ret += urlAtua[0]+'?';
			var arr_url = urlAtua[opc];
			arr_url = arr_url.split('&');
			var mudou = false;
			arr_url.forEach(function (element, index) {
				//console.log("[" + index + "] = " + element);
				var arr_vu = element.split('=');
				if(!isEmpty(arr_vu[0])){
					if(arr_vu[0]==campo){
						mudou = true;
						ret += arr_vu[0] +'='+valor+'&';
					}else{
						ret += arr_vu[0] +'='+arr_vu[1]+'&';
					}
				}

			});
			if(!mudou){
				ret += '&'+campo+'='+valor;
			}
			ret = ret.replace('&&','&');
			ret = ret.replace('?&','?');
			ret = ret.replace('/?','?');
	}
	console.log(ret);
	return ret;
}
function lib_trataRemoveUrl(campo,valor,urlinic){
	var ret = '';
   if(typeof urlinic == 'undefined')
		var urla = urlAtual();
	else
		var urla = urlinic;
	var urlAtua = urla.split('?');
	var urlA1 = urlAtua[1];
	var opc = 1;
	urlA1 = urlA1.replace('&=','',urlAtua[1]);
	if(opc==1){
			ret += urlAtua[0]+'?';
			var arr_url = urlAtua[opc];
			arr_url = arr_url.split('&');
			var mudou = false;
			arr_url.forEach(function (element, index) {
				//console.log("[" + index + "] = " + element);
				var arr_vu = element.split('=');
				if(!isEmpty(arr_vu[0])){
					if(arr_vu[0]==campo){
						mudou = true;
						//ret += arr_vu[0] +'='+valor+'&';
					}else{
						ret += arr_vu[0] +'='+arr_vu[1]+'&';
					}
				}

			});
			if(!mudou){
				ret += '&'+campo+'='+valor;
			}
			ret = ret.replace('&&','&');
			//alert('&&','&');
			//console.log(urlAtua[1]);
	}
	return ret;
}
function visualizaArquivos(token_produto,ajaxurl){

    $.ajax({
        type: 'GET',
        url: ajaxurl,
        data: {
            token_produto:token_produto,
        },
        dataType: 'json',
        success: function (data) {

          if(data.exec && data.arquivos){
            var list = listFiles(data.arquivos,token_produto);
            $('#lista-files').html(list);
            if(data.mens){
              lib_formatMensagem('.mens',data.mens,'success');
            }
          }else{
            lib_formatMensagem('.mens',data.mens,'danger');
          }
        },
        error: function (data) {
            console.log(data);
        }
    });
}
function listFiles(arquivos,token_produto){
    if(typeof token_produto == 'undefined'){
        token_produto = '';
    }
    if(arquivos.length>0){
        var tema1 = '<ul class="list-group">{li}</ul>';
        var tema2 = '<li class="list-group-item d-flex justify-content-between align-items-center" id="item-{id}">'+
        '<a href="{href}" target="_blank">{icon} {nome}</a>'+
        '<button type="button" {event} class="btn btn-default" title="Excluir"><i class="fas fa-trash "></i></button></li>';
        var li = '';
        var temaIcon = '<i class="fas fa-file-{tipo} fa-2x"></i>';
        for (let index = 0; index < arquivos.length; index++) {
            const arq = arquivos[index];
            var event = 'onclick="excluirArquivo(\''+arq.id+'\',\'/uploads/'+arq.id+'\')"';
            var href = '/storage/'+arq.pasta;
            var icon = '';
            li += tema2.replace('{event}',event);
            li = li.replace('{nome}',arq.nome);
            li = li.replace('{id}',arq.id);
            li = li.replace('{href}',href);
            if(conf = arq.config){
                var config = JSON.parse(conf);
                if(config.extenssao == 'jpg' || config.extenssao=='png' || config.extenssao == 'jpeg'){
                    var tipo = 'image';
                }else if(config.extenssao == 'doc' || config.extenssao == 'docx') {
                    var tipo = 'word';
                }else if(config.extenssao == 'xls' || config.extenssao == 'xlsx') {
                    var tipo = 'excel';
                }else{
                    var tipo = 'download';
                }
                icon = temaIcon.replace('{tipo}',tipo);
            }
            li = li.replace('{icon}',icon);
        }
        ret = tema1.replace('{li}',li);
        return ret;

    }
}
function excluirArquivo(id,ajaxurl){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            id:id,
        },
        dataType: 'json',
        success: function (data) {

          if(data.exec){
            //cancelEdit(id,'del');
            $('#item-'+id).remove();
            if(data.dele_file){
              lib_formatMensagem('.mens','Arquivo excluido com sucesso!','success');
            }
          }else{
            lib_formatMensagem('.mens','Erro ao excluir entre em contato com o suporte','danger');
          }


        },
        error: function (data) {
            console.log(data);
        }
    });
}
function carregaDropZone(seletor){
    $(seletor).dropzone({ url: "/file/post" });
}
function submitFormulario(objForm,funCall,funError){
    if(typeof funCall == 'undefined'){
        funCall = function(res){
            console.log(res);
        }
    }
    if(typeof funError == 'undefined'){
        funError = function(res){
            lib_funError(res);
        }
    }
    var route = objForm.attr('action');
    console.log(route);
    $.ajax({
        type: 'POST',
        url: route,
        data: objForm.serialize()+'&ajax=s',
        dataType: 'json',
        success: function (data) {
            funCall(data);
        },
        error: function (data) {
            if(data.responseJSON.errors){
                funError(data.responseJSON.errors);
                console.log(data.responseJSON.errors);
            }else{
                lib_formatMensagem('.mens','Erro','danger');
            }
        }
    });
}
function getAjax(config,funCall,funError){

    if(typeof config.url == 'undefined'){
        alert('informe a Url');
        return false;
    }
    if(typeof config.type == 'undefined'){
        config.type = 'GET';
    }
    if(typeof config.data == 'undefined'){
        config.data = {ajax:'s'};
    }
    if(typeof funCall == 'undefined'){
        funCall = function(res){
            console.log(res);
        }
    }
    if(typeof funError == 'undefined'){
        funError = function(res){
            lib_funError(res);
        }
    }
    $.ajax({
        type: config.type,
        url: config.url,
        data: config.data,
        dataType: 'json',
        success: function (data) {
            funCall(data);
        },
        error: function (data) {
            if(data.errors){
                funError(data.errors);
                console.log(data.errors);
            }else{
                lib_formatMensagem('.mens','Erro','danger');
            }
        }
    });
}
function submitFormularioCSRF(objForm,funCall,funError){
    if(typeof funCall == 'undefined'){
        funCall = function(res){
            console.log(res);
        }
    }
    if(typeof funError == 'undefined'){
        funError = function(res){
            lib_funError(res);
        }
    }
    var route = objForm.attr('action');
    console.log(route);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: route,
        data: objForm.serialize()+'&ajax=s',
        dataType: 'json',
        success: function (data) {
            funCall(data);
        },
        error: function (data) {
            if(data.responseJSON.errors){
                funError(data.responseJSON.errors);
                console.log(data.responseJSON.errors);
            }else{
                lib_formatMensagem('.mens','Erro','danger');
            }
        }
    });
}
function lib_funError(res){
    var mens = '';
    Object.entries(res).forEach(([key, value]) => {
        console.log(key + ' ' + value);
        var s = $('[name="'+key+'"]');
        var v = s.val();
        mens += value+'<br>';
        if(key=='cpf'){
           s.addClass('is-invalid');
        }else{
            if(v=='')
                s.addClass('is-invalid');
            else{
                s.removeClass('is-invalid');
            }
        }
    });
    lib_formatMensagem('.mens',mens,'danger');

}
function modalGeral(id,titulo,conteudo){
    var m = $(id);
    m.modal('show');
    m.find('.modal-title').html(titulo);
    m.find('.conteudo').html(conteudo);

}
function initSelector(obj){
    if(obj.val()!='cad'){
        return
    }
    var d = decodeArray(obj.data('selector'));
    if(d.campos){
        var f = qFormCampos(d.campos);
        if(f){
            var tf = '<form id="{id_form}" action="{action}"><div class="row"><div class="col-md-12 mens"></div>{conte}</div></form>';
            var b = '<button type="button" class="btn btn-primary" f-submit>Salvar <i class="fas fa-chevron-circle-right"></i></button>';
            var m = '#modal-geral';
            tf = tf.replace('{id_form}',d.id_form);
            tf = tf.replace('{conte}',f);
            tf = tf.replace('{action}',d.action);
            modalGeral(m,'Cadastrar '+d.label,tf);
            //obj.find('option').attr('selected',false);
            //obj.find('option[value=\'\']:first').attr('selected','selected');
            obj.find('option[value=\'\']').attr('selected','selected');
            $('[f-submit]').remove();
            $(b).insertAfter(m+' .modal-footer button');
            $('[f-submit]').on('click',function(){

                submitFormularioCSRF($('#'+d.id_form),function(res){
                    if(res.mens){
                        lib_formatMensagem('.mens',res.mens,res.color);
                    }
                    if(res.exec){
                        $(m).modal('hide');
                        obj.append($('<option>', {
                            value: res.idCad,
                            text: res.dados[d.campo_bus]
                        }));
                        obj.find('option[value='+res.idCad+']').attr('selected','selected');
                    }
                });
            });
        }
        //$('.mens').html(campo_nome);
    }
}
function qFormCampos(config){
    if(typeof config == 'undefined'){
        return false;
    }
    //console.log(config);
    const tl = '<label for="{campo}">{label}</label>';
    var tema = {
        text : '<div class="form-group col-{col}-{tam} {class_div}" div-id="{campo}" >{label}<input type="{type}" class="form-control {class}" id="inp-{campo}" name="{campo}" aria-describedby="{campo}" placeholder="{placeholder}" value="{value}" {event} /></div>',
        number : '<div class="form-group col-{col}-{tam} {class_div}" div-id="{campo}" >{label}<input type="{type}" class="form-control {class}" id="inp-{campo}" name="{campo}" aria-describedby="{campo}" placeholder="{placeholder}" value="{value}" {event} /></div>',
        hidden : '<div class="form-group col-{col}-{tam} {class_div} d-none" div-id="{campo}" >{label}<input type="{type}" class="form-control {class}" id="inp-{campo}" name="{campo}" aria-describedby="{campo}" placeholder="{placeholder}" value="{value}" {event} /></div>',
        chave_checkbox : '<div class="form-group col-{col}-{tam}"><div class="custom-control custom-switch  {class}"><input type="checkbox" class="custom-control-input" {checked} value="{value}"  name="{campo}" id="{campo}"><label class="custom-control-label" for="{campo}">{label}</label></div></div>',
        select : {
            tm1 : '<div class="form-group col-{col}-{tam} {class_div}" div-id="{campo}" >{label}<select name="{campo}" {event} class="form-control custom-select {class}">{op}</select></div>',
            tm2 : '<option value="{k}" {selected}>{v}</option>'
        }
    };
    var r = '';
    var ret = '';
    if(Object.entries(config).length>0){
        Object.entries(config).forEach(([key, v]) => {
            if(v.type!='hidder' && v.active==true){
                if(v.type == 'selector' || v.type == 'select'){
                    let op='',arr = v.arr_opc,tm1 = tema['select'].tm1,tm2 = tema['select'].tm2;
                    //console.log(arr);return;
                    Object.entries(arr).forEach(([i, el]) => {
                        op += tm2.replace('{k}',i);
                        op = op.replace('{v}',el);
                    });
                    var type = v.type;
                    r += tm1.replaceAll('{type}',v.type);
                    var label = tl.replaceAll('{campo}',key);
                    label.replaceAll('{label}',);
                    var value = v.value?v.value:'';
                    var classe = v.class?v.class:'';
                    var placeholder = v.placeholder?v.placeholder:'';
                    r = r.replaceAll('{campo}',key);
                    r = r.replaceAll('{label}',v.label);
                    r = r.replaceAll('{value}',value);
                    r = r.replaceAll('{tam}',v.tam);
                    r = r.replaceAll('{col}','md');
                    r = r.replaceAll('{class}',classe);
                    r = r.replaceAll('{op}',op);
                }else{
                    var type = v.type;
                    r += tema[type].replaceAll('{type}',v.type);
                    var label = tl.replaceAll('{campo}',key);
                    label.replaceAll('{label}',);
                    var value = v.value?v.value:'';
                    var classe = v.class?v.class:'';
                    var placeholder = v.placeholder?v.placeholder:'';
                    r = r.replaceAll('{campo}',key);
                    r = r.replaceAll('{label}',v.label);
                    r = r.replaceAll('{value}',value);
                    r = r.replaceAll('{tam}',v.tam);
                    r = r.replaceAll('{col}','md');
                    r = r.replaceAll('{class}',classe);
                    r = r.replaceAll('{placeholder}',v.placeholder);
                }
            }
        });
    }
    ret = r;

    return ret;
}
function color_select1_0(val,val1){
    if(val==true){
		$('#tr_'+val1).addClass('table-info');
	}
	if(val==false){
		$('#tr_'+val1).removeClass('table-info');
	}
}
function gerSelect(obj){
	if(obj.is(':checked')){
        $('.table .checkbox').each(function(){
            this.checked = true;
            color_select1_0(this.checked,this.value);
        });
	}else{
        $('.table .checkbox').each(function(){
            this.checked = false;
            color_select1_0(this.checked,this.value);
        });
	}
}
function coleta_checked(obj){
    let id = '';
    obj.each(function (i) {
        id += this.value+'_';
    });
    return id;
}
function dps_salvarEpatas(res,etapa,m){
    $.each(res,function(v,k) {
        var sl = '#tr_'+v+' .etapa';
        $(sl).html(etapa);
    });
    //if(typeof m!='undefined')
    $(m).modal('hide');
}
function janelaEtapaMass(selecionandos){
    if(typeof selecionandos =='undefined'){
        return ;
    }
    if(selecionandos==''){
        var msg = '<div class="row"><div id="exibe_etapas" class="col-md-12 text-center"><p>Por favor selecione um registro!</p></div></div>';
        alerta(msg,'modal-etapa','Alerta','',true,3000,true)
        return;
    }else{
       var msg = '<form id="frm-etapas" action="/familias/ajax"><div class="row"><div id="exibe_etapas" class="col-md-12">seleEta</div></div></form>',btnsub = '<button type="button" id="submit-frm-etapas" class="btn btn-primary">Salvar</button>',m='modal-etapa';

       alerta(msg,m,'Editar Etapas');
       $.ajax({
            type:"GET",
            url:"/familias/campos",
            dataType:'json',
            success: function(res){
                res.etapa.type = 'select';
                res.etapa.tam = '12';
                res.etapa.option_select = true;
                var conp = {etapa:res.etapa};
                var et = qFormCampos(conp);
                et += '<input type="hidden" name="opc" value="salvar_etapa_massa"/>';
                et += '<input type="hidden" name="ids" value="'+selecionandos+'"/>';
                $('#exibe_etapas').html(et);
                $(btnsub).insertAfter('#'+m+' .modal-footer button');
                $('#submit-frm-etapas').on('click',function(e){
                    e.preventDefault();
                    submitFormularioCSRF($('#frm-etapas'),function(res){
                        if(res.mens){
                            lib_formatMensagem('.mens',res.mens,res.color);
                        }
                        if(res.exec && (a = res.atualiza)){
                            dps_salvarEpatas(a,res.etapa,'#'+m);
                        }
                    });
                });
            }
       });
    }
}
function carregaMascaraMoeda(s){
    $(s).maskMoney({
        prefix: 'R$ ',
        allowNegative: true,
        thousands: '.',
        decimal: ','
    });
}
