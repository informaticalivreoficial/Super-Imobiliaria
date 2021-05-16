(function ($) {
    
    var basesite = 'https://imobiliariaubatuba.com/';     
    var ajaxbase = basesite + 'ajax/ajax-central.php';    
    var ajaxfiltro = basesite + 'ajax/filtro-search.php';
    //AJAX CARREGA CIDADES
    var ajaxcidade = basesite + 'ajax/cidades.php';
    
    // SIMULADOR INICIO
    $('.financiamento').css("display", "none");
    $('.consorcio').css("display", "none");
    
    $('.loadtipo_s').change(function() {
        if($(this).val() == 0){            
            $('.financiamento').css("display", "block");
            $('.consorcio').css("display", "none");
            $('.opcaoconsorcio').prop('disabled', 'disabled');
            $('.opcaofinanciamento').removeAttr('disabled');
        }else{            
            $('.consorcio').css("display", "block");
            $('.financiamento').css("display", "none");
            $('.opcaoconsorcio').removeAttr('disabled');
            $('.opcaofinanciamento').prop('disabled', 'disabled');
        }
    });   
    
    $('.j_submitsimulador').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('.button-md').html("Carregando...");
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
               $('html, body').animate({scrollTop:$('.alertas1').offset().top-135}, 'slow'); 
               if(resposta.error){                    
                    form.find('.alertas1').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
                    form.find('.alert-danger').fadeIn();                    
                }else{
                    form.find('.alertas1').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
                    form.find('.alert-sucess').fadeIn();                    
                    //form.find('input[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            complete: function(resposta){
                form.find('.button-md').html("Enviar Agora");                               
            }
        });
        return false;
    });
    // SIMULADOR FIM
    
    // MASCARAS
    $("#valor").mask("999.999.999,99",{placeholder:" "});
    $("#nascimento").mask("99/99/9999");
    $("#whatsapp").mask("(99) 99999-9999",{placeholder:" "});
    $("#cep").mask("99.999-999");
    $("#rg").mask("99.999.999 - 9");
    
    $('#dinheiroComZero1').maskMoney({ decimal: ',', thousands: '.', precision: 2 });
    $('#dinheiroComZero2').maskMoney({ decimal: ',', thousands: '.', precision: 2 });
    $('#dinheiroComZero').maskMoney({ decimal: ',', thousands: '.', precision: 2 });
    $('.dinheiroComZero').maskMoney({ decimal: ',', thousands: '.', precision: 2 });
    //$('#dinheiroSemZero').maskMoney({ decimal: ',', thousands: '.', precision: 0 });
    //$('#dinheiroVirgula').maskMoney({ decimal: '.', thousands: ',', precision: 2 });
    
    //AJAX LOAD ESTADOS E CIDADES
    $('.j_loadstate').change(function() {
        $('.j_loadcity').removeAttr('disabled');
        $('.j_loadcity').html('<option value=""> Carregando... </option>');
        $('.j_loadcity').load(ajaxcidade + '?estado=' + $(this).val());
    });
    
    $('.loadfinalidade').change(function(){        
        // efeito da combo 
        $('.loadcategoria').removeAttr('disabled');
        $('.loadcategoria').html('<option value=""> Carregando... </option>');
        //Carrega a combo Tipo
        $('.loadcategoria').load(ajaxfiltro + '?categoria=' + $(this).val());
    });

    
    $('.loadcategoria').change(function(){        
        $('.loadtipo').removeAttr('disabled');              
    });
    
    $('.rcomprar').css("display", "block");
    
    $('.loadtipo').change(function(){        
        //$('.loadbairro').removeAttr('disabled');
        if($(this).val() == 0){            
            $('.rcomprar').css("display", "block");
            $('.ralugar').css("display", "none");            
        }else{            
            $('.ralugar').css("display", "block");
            $('.rcomprar').css("display", "none");
        }                             
    });     
    
        
    //FUNÇÕES DO FORM DE BUSCA AVANÇADA
    $('.j_formsubmitbusca').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('.search-button').html("Carregando...");
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
               $('html, body').animate({scrollTop:$('.alertas').offset().top-100}, 'slow'); 
               if(resposta.error){                    
                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
                    form.find('.alert-danger').fadeIn();                    
                }else{
                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
                    form.find('.alert-sucess').fadeIn();                    
                    form.find('input[class!="noclear"]').val('');
                    window.setTimeout(function(){
                        $(location).attr('href',basesite + 'imoveis/busca-avancada' + 
                        '&cat_pai=' + $('.loadfinalidade').val() + 
                        '&categoria=' + $('.loadcategoria').val() +
                        '&tipo=' + $('.loadtipo').val() +
                        '&bairro_id=' + $('.loadbairro').val() +
                        '&dormitorios=' + $('.loaddormitorios').val() +
                        '&min_pricec=' + $('.min-value').text() +
                        '&max_pricec=' + $('.max-value').text() +
                        '&min_pricea=' + $('.min-value').text() +
                        '&max_pricea=' + $('.max-value').text());
                    },1000);                    
                }
            },
            complete: function(resposta){
                form.find('.search-button').html("Buscar Imóveis");                               
            }
        });
        return false;
    });
    
    
    //FUNÇÕES DO FORM DE NEWSLETTER
    $('.j_formsubmitnews').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('.button-sm').html("Carregando...");
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
               if(resposta.error){                    
                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
                    form.find('.alert-danger').fadeIn();
                    
                }else{
                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
                    form.find('.alert-sucess').fadeIn();                    
                    form.find('input[class!="noclear"]').val('');
                    form.find('textarea[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            complete: function(resposta){
                form.find('.button-sm').html("Cadastrar");                               
            }
        });
        return false;
    });
    
    //FUNÇÕES DO FORM DE COMENTÁRIOS
    $('.j_formcomentarios').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('.button-md').html("Carregando...");
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
               $('html, body').animate({scrollTop:$('.alertas').offset().top-135}, 'slow'); 
               if(resposta.error){                    
                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
                    form.find('.alert-danger').fadeIn();                    
                }else{
                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
                    form.find('.alert-sucess').fadeIn();                    
                    form.find('input[class!="noclear"]').val('');
                    form.find('textarea[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            complete: function(resposta){
                form.find('.button-md').html("Enviar Comentário");                               
            }
        });
        return false;
    });
    
    // Dropzone initialization
    Dropzone.autoDiscover = false;
    
    $("div#myDropZone").dropzone({
        autoProcessQueue: false,
        url: "../template/thema-2018/imoveis/upload-image.php"
    });
            
    //FUNÇÕES DO FORM DE BUSCA AVANÇADA
    //$('.j_formsubmitcadimovel').submit(function (){
//        var form = $(this);
//        var data = $(this).serialize();
//        
//        $.ajax({
//            url: ajaxbase,
//            data: data,
//            type: 'POST',
//            dataType: 'json',
//            
//            beforeSend: function(){
//                form.find('.button-md').html("Carregando...");
//                form.find('.alert').fadeOut(500, function(){
//                    $(this).remove();
//                });
//            },
//            success: function(resposta){
//                $('html, body').animate({scrollTop:$('.alertas').offset().top-135}, 'slow');
//                if(resposta.error){
//                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
//                    form.find('.alert-danger').fadeIn();
//                    form.find('.button-md').html("Cadastrar Agora");
//                    //$("div#myDropZone").processQueue();  
//                    console.log();                  
//                }else{
//                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
//                    form.find('.alert-sucess').fadeIn();                    
//                    form.find('input[class!="noclear"]').val('');
//                    //form.find('.form_hide').fadeOut(500);
//                    //$('.loadimages').css("display","block");                   
//                }
//            },
//            complete: function(resposta){
//                                               
//            }
//        });
//        return false;
//    });
    
    //$('.j_formsubmitcadimovel').submit(function(){
//        var form = $(this);
//        var data = $(this).serialize();
//          
//		form.ajaxSubmit({
//			url: 'http://localhost/Imobiliaria-Ubatuba/ajax/cadastro-de-imovel.php',
//			data: data,
//            type: 'POST',
//            beforeSubmit: function(){
//				form.find('.button-md').html("Carregando...");
//                form.find('.alert').fadeOut(500, function(){
//                    $(this).remove();
//                });
//			},
//					
//			success: function( resposta ){
//				$('html, body').animate({scrollTop:$('.alertas').offset().top-135}, 'slow');
//                form.find('.alertas').empty().html('<div class="alert alert-danger">Arquivo enviado com sucesso!</div>');
//                //if(resposta.error){
////                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
////    				form.find('.alert-danger').fadeIn();
////                    form.find('.button-md').html("Cadastrar Agora");
//                    console.log(data);
////                }else{
////                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
////                    form.find('.alert-sucess').fadeIn();
////                    form.find('input[class!="noclear"]').val('');
////                }
//			},
//			complete: function(){
//				
//			}
//			
//		});
//		return false;
//	});

    //FUNÇÕES DO FORM DE ATENDIMENTO
    $('.j_formsubmit').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('.button-md').html("Carregando...");
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
               $('html, body').animate({scrollTop:$('.alertas1').offset().top-135}, 'slow'); 
               if(resposta.error){                    
                    form.find('.alertas1').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
                    form.find('.alert-danger').fadeIn();                    
                }else{
                    form.find('.alertas1').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
                    form.find('.alert-sucess').fadeIn();                    
                    form.find('input[class!="noclear"]').val('');
                    form.find('textarea[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            complete: function(resposta){
                form.find('.button-md').html("Enviar Agora");                               
            }
        });
        return false;
    });
    
    $('#shareIcons').jsSocials({
        //url: "http://www.google.com",
        showLabel: false,
        showCount: false,
        shareIn: "popup",
        shares: ["email", "twitter", "facebook", "whatsapp"]
    });
    $('.shareIcons').jsSocials({
        //url: "http://www.google.com",
        showLabel: false,
        showCount: false,
        shareIn: "popup",
        shares: ["email", "twitter", "facebook", "whatsapp"]
    });
    
    $('.j_btnwhats').click(function (){         
        $('.balao').slideDown();
        return false;
    });
    
    
})(jQuery);