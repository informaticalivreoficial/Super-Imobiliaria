<script type="text/javascript">
(function ($) {
    var basesite = "<?= BASE;?>/template/<?= TEMPLATE;?>/"; 
    var base = "<?= BASE;?>/"; 
    var ajaxbase = basesite + 'ajax/ajax-central.php';
    
    //$('.loading-filtro').css("display", "none");
    
    // FILTRO DE BUSCA DE IMOVEIS -> CIDADES
    $('.loadcidadeFiltro').change(function() {
       var id_cidade = $('#cidade option:selected').val(); 
       var bairro = $('.j_loadbairros'); 
       //Loading
       //$('.loading-filtro').css("display", "block");
       bairro.val('Carregando bairros...');
       bairro.fadeOut(500);
       $('.selectBairro').load(basesite + '/ajax/ajax-bairros.php?cidade=' + id_cidade);
       //$('.resultado').load(basesite + 'ajax/filtro-imoveis.php?cidade=' + id_cidade);
       //$('.loading-filtro').css("display", "none");
    });

    $('.loadtipo').change(function(){        
        var tipo = $('#tipo option:selected').val();
        if(tipo == 1){
            $('.loadvalores').fadeOut(500);
            $('.selectValores').load(basesite + 'ajax/ajax-tipos.php?tipo=' + tipo);
        }else{
            $('.loadvalores').fadeOut(500);
            $('.selectValores').load(basesite + 'ajax/ajax-tipos.php?tipo=' + tipo);
        }
    });

    // Seletor, Evento/efeitos, CallBack, Ação
    // $('.j_searchimoveis').submit(function (){
    //     var form = $(this);
    //     var data = $(this).serialize();
    //
    //     $.ajax({
    //         url: ajaxbase,
    //         data: data,
    //         type: 'POST',
    //         datatype: 'json',
    //
    //         beforeSend: function(){
    //             form.find('#b_nome').html('Carregando...');
    //             form.find('.alert').fadeOut(500, function(){
    //                 $(this).remove();
    //             });
    //         },
    //         success: function(){
    //
    //         },
    //         complete: function(){
    //             form.find('#b_nome').html('Pesquisar');
    //         }
    //     });
    //     return false;
    // });

    $('.rcomprar').css("display", "block");
    
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
                $('html, body').animate({scrollTop:$('.alertas').offset().top-135}, 'slow'); 
               if(resposta.error){                    
                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
                    form.find('.alert-danger').fadeIn();                    
                }else{
                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
                    form.find('.alert-sucess').fadeIn();                    
                    form.find('input[class!="noclear"]').val('');
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
    
        
    $('.j_loadstate').change(function() {
        var uf = $('.j_loadstate');
        var city = $('.j_loadcity');
        var patch = basesite + 'ajax/cidades.php';

        city.attr('disabled', 'true');
        uf.attr('disabled', 'true');

        city.html('<option value=""> Carregando cidades... </option>');

        $.post(patch, {estado: $(this).val()}, function(cityes) {   
            city.html(cityes).removeAttr('disabled');
            uf.removeAttr('disabled');
        });
    });
    
    // CONSULTA NA PÁGINA DOS IMÓVEIS
    $('.j_formsubmitconsulta').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('.b_nome').html("Carregando...");                
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
                //console.clear();
                //console.log(resposta);
                //$('html, body').animate({scrollTop:0}, 'slow');
                if(resposta.error){
                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
                    form.find('.alert-danger').fadeIn();                    
                }else{
                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
                    form.find('.alert-success').fadeIn();                    
                    //form.find('input[class!="noclear"]').val('');
                    //form.find('textarea[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            complete: function(resposta){
                form.find('.b_nome').html("Consultar Agora");                                
            }
            
        });
        
        return false;
    });
    
    // Seletor, Evento/efeitos, CallBack, Ação
    $('.j_formsubmit').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('#b_nome').html("Carregando...");                
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
                //console.clear();
                //console.log(resposta);
                $('html, body').animate({scrollTop:0}, 'slow');
                if(resposta.error){
                    form.find('.alertas').html('<div class="dt-sc-error-box">'+ resposta.error +'</div>');
                    form.find('.dt-sc-error-box').fadeIn();                    
                }else{
                    form.find('.alertas').html('<div class="dt-sc-success-box">'+ resposta.sucess +'</div>');
                    form.find('.dt-sc-success-box').fadeIn();                    
                    //form.find('input[class!="noclear"]').val('');
                    //form.find('textarea[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            complete: function(resposta){
                form.find('#b_nome').html("Enviar Agora");                                
            }
            
        });
        
        return false;
    });
    
    // Seletor, Evento/efeitos, CallBack, Ação
    $('.j_formsubmitcomentario').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('#b_nome').html("Carregando...");                
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
                //console.clear();
                //console.log(resposta);
                $('html, body').animate({scrollTop:$('.alertas').offset().top-135}, 'slow');
                if(resposta.error){
                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
                    form.find('.alert-message').fadeIn();                    
                }else{
                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
                    form.find('.alert-message').fadeIn();                    
                    form.find('input[class!="noclear"]').val('');
                    form.find('textarea[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            complete: function(resposta){
                form.find('#b_nome').html("Enviar Comentário");                                
            }
            
        });        
        return false;
    });
    
    // Seletor, Evento/efeitos, CallBack, Ação
    $('.j_formsubmitanuncioSend').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('#b_nome').html("Carregando...");                
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
                //console.clear();
                //console.log(resposta);
                $('html, body').animate({scrollTop:0}, 'slow');
                if(resposta.error){
                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
                    form.find('.alert-message').fadeIn();                    
                }else{
                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
                    form.find('.alert-message').fadeIn();                    
                    //form.find('input[class!="noclear"]').val('');
                    //form.find('textarea[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            complete: function(resposta){
                form.find('#b_nome').html("Enviar Mensagem");                                
            }
            
        });        
        return false;
    });
    
        
    // Seletor, Evento/efeitos, CallBack, Ação
    //$('.j_formcadastro').submit(function (){

       // var data = new FormData(this);

       // var form = $(this);
        //var data = $(this).serialize();
        
//        var Acbanco = new Array();
// 
//        $("input[name='acbanco[]']").each(function(){
//           Codigos.push($(this).val());
//        });
        
//        $.ajax({
//            url: ajaxbase,
//            data: data,
//            type: 'POST',
//            mimeType: "multipart/form-data",
//            dataType: 'json',
//            contentType: false,
//            cache: false,
//            processData:false,
//            
//            beforeSend: function(){
//                form.find('.b_cadastro').html("Carregando...");                
//                form.find('.alert').fadeOut(500, function(){
//                    $(this).remove();
//                });
//            },
//            success: function(resposta){
//                $('html, body').animate({scrollTop:$('.alertas').offset().top-135}, 'slow');
//                if(resposta.error){
//                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
//                    form.find('.alert-danger').fadeIn(); 
//                    console.log(resposta);
//                }else{
//                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
//                    form.find('.alert-success').fadeIn();                    
//                    //form.find('input[class!="noclear"]').val('');
//                    //form.find('textarea[class!="noclear"]').val('');
//                    //form.find('.form_hide').fadeOut(500);
//                    console.log(resposta);
//                }
//            },
//            complete: function(resposta){
//                form.find('.b_cadastro').html("Cadastrar");                                
//            }
//            
//        });
//        
//        return false;
//    });

    $('.btn1').on('click', function() {$('.arquivo1').trigger('click');});
    $('.btn2').on('click', function() {$('.arquivo2').trigger('click');});
    $('.btn3').on('click', function() {$('.arquivo3').trigger('click');});
    $('.btn4').on('click', function() {$('.arquivo4').trigger('click');});
    $('.btn5').on('click', function() {$('.arquivo5').trigger('click');});
    $('.btn6').on('click', function() {$('.arquivo6').trigger('click');});

    $('.arquivo1').on('change', function() {var fileName = $(this)[0].files[0].name;$('#file1').val(fileName);});
    $('.arquivo2').on('change', function() {var fileName = $(this)[0].files[0].name;$('#file2').val(fileName);});
    $('.arquivo3').on('change', function() {var fileName = $(this)[0].files[0].name;$('#file3').val(fileName);});
    $('.arquivo4').on('change', function() {var fileName = $(this)[0].files[0].name;$('#file4').val(fileName);});
    $('.arquivo5').on('change', function() {var fileName = $(this)[0].files[0].name; $('#file5').val(fileName); });
    $('.arquivo6').on('change', function() {var fileName = $(this)[0].files[0].name; $('#file6').val(fileName);});
    
    // Seletor, Evento/efeitos, CallBack, Ação
    $('.j_formNewsletter').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('.b_cadastro').html("Carregando...");                
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
                //console.clear();
                //console.log(resposta);
                //$('html, body').animate({scrollTop:0}, 'slow');
                if(resposta.error){
                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
                    form.find('.alert-danger').fadeIn();                    
                }else{
                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
                    form.find('.alert-success').fadeIn();                    
                    //form.find('input[class!="noclear"]').val('');
                    //form.find('textarea[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            complete: function(resposta){
                form.find('.b_cadastro').html("Cadastrar");                                
            }
            
        });
        
        return false;
    });
    
    // Seletor, Evento/efeitos, CallBack, Ação
    $('.j_formAtualiza').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('.b_atualiza').html("Carregando...");                
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
                $('html, body').animate({scrollTop:0}, 'slow');
                if(resposta.error){
                    form.find('.alertas').html('<div class="dt-sc-error-box">'+ resposta.error +'</div>');
                    form.find('.dt-sc-error-box').fadeIn();                    
                }else{
                    form.find('.alertas').html('<div class="dt-sc-success-box">'+ resposta.sucess +'</div>');
                    form.find('.dt-sc-success-box').fadeIn();                    
                    //form.find('input[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            complete: function(resposta){
                form.find('.b_atualiza').html("Atualizar");                                
            }
            
        });
        
        return false;
    });
    
    
    // Seletor, Evento/efeitos, CallBack, Ação
    $('.j_formLogin').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('.b_login').html("Carregando...");                
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
                //console.clear();
                //console.log(resposta);
                $('html, body').animate({scrollTop:0}, 'slow');
                if(resposta.error){
                    form.find('.alertas').html('<div class="dt-sc-error-box">'+ resposta.error +'</div>');
                    form.find('.dt-sc-error-box').fadeIn();                    
                }else{
                    form.find('.alertas').html('<div class="dt-sc-success-box">'+ resposta.sucess +'</div>');
                    form.find('.dt-sc-success-box').fadeIn();                    
                    //form.find('input[class!="noclear"]').val('');
                    //form.find('textarea[class!="noclear"]').val('');
                    //form.find('.form_hide').fadeOut(500);
                }
            },
            complete: function(resposta){
                form.find('.b_login').html("<i class=\"fa fa-check-circle\"> </i> Logar");                                
            }
            
        });
        
        return false;
    });
    
    // Seletor, Evento/efeitos, CallBack, Ação
    $('.j_formtoken').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('.b_token').html("Carregando...");                
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
                //console.clear();
                //console.log(resposta);
                $('html, body').animate({scrollTop:0}, 'slow');
                if(resposta.error){
                    form.find('.alertas').html('<div class="dt-sc-error-box">'+ resposta.error +'</div>');
                    form.find('.dt-sc-error-box').fadeIn();                    
                }else if(resposta.checklogin){
                    window.setTimeout(function(){
                        $(location).attr('href','<?= BASE;?>/atletas/' + resposta.checklogin);
                    },1000);
                }else{
                    form.find('.alertas').html('<div class="dt-sc-success-box">'+ resposta.sucess +'</div>');
                    form.find('.dt-sc-success-box').fadeIn();                    
                    //form.find('input[class!="noclear"]').val('');
                    //form.find('textarea[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            complete: function(resposta){
                form.find('.b_token').html("<i class=\"fa fa-check-circle\"> </i> Solicitar Token");                                
            }
            
        });
        
        return false;
    });
    
    //FUNÇÕES DO FORM DE NEWSLETTER
    $('.j_formsearch').submit(function (){
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: ajaxbase,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            beforeSend: function(){
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            success: function(resposta){
               if(resposta.error){    
                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
                    form.find('.alert-danger').fadeIn();
                }else if(resposta.returnSearch){
                    window.setTimeout(function(){
                        $(location).attr('href','<?= BASE;?>/blog/pesquisa&search=' + resposta.returnSearch);
                    },1000);
                }else{
                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
                    form.find('.alert-sucess').fadeIn();
                }
            },
            complete: function(resposta){
                                             
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
    
    // botão do whatsapp
    $('.j_btnwhats').click(function (){         
        $('.balao').slideDown();
        return false;
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
               $('html, body').animate({scrollTop:100}, 'slow'); 
               if(resposta.error){                    
                    form.find('.alertas').html('<div class="alert alert-danger">'+ resposta.error +'</div>');
                    form.find('.alert-danger').fadeIn();                    
                }else{
                    form.find('.alertas').html('<div class="alert alert-success">'+ resposta.sucess +'</div>');
                    form.find('.alert-sucess').fadeIn();                    
                    form.find('input[class!="noclear"]').val('');
                    window.setTimeout(function(){
                        $(location).attr('href',base + 'imoveis/busca-avancada' + 
                        '&cidade=' + $('.loadcidadeFiltro').val() + 
                        '&bairro=' + $('.j_loadbairros').val() +
                        '&categoria=' + $('.loadfinalidade').val() +
                        '&subcategoria=' + $('.loadcategoria').val() +
                        '&dormitorios=' + $('.loaddormitorios').val() +
                        '&tipo=' + $('.loadtipo').val());
                    },1000);                   
                }
            },
            complete: function(resposta){
                form.find('.search-button').html("Buscar Imóveis");                               
            }
        });
        return false;
    });  
    
    
    
})(jQuery);   
    
</script>