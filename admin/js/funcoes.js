$(function () {
    
    // FUNÇÃO MODAL DE CARREGAMENTO DO SISTEMA
    $(window).ready(function(){
        $('.loadsistem').fadeOut("fast",function(){
            $('.dialog').fadeOut("fast");
        });
    });
    
        
    // FUNÇÃO ALTERAR SENHA DO USUÁRIO NO PAINEL
    $('.j_submit').submit(function(){ 
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            dataType: 'json',            
            beforeSend: function(){
                $('.loginbox span').fadeOut("slow",function(){
                    $('.loginbox img').fadeIn("fast");
                });
                $('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            complete: function(){
                $('.loginbox img').fadeOut("slow",function(){
                    $('.loginbox span').fadeIn("fast");
                });                
            },
            success: function(resposta){
                //console.clear();
                //console.log(resposta);
                
                if(resposta.error){
                    $('.alertas').html('<div class="alert alert-danger">' + resposta.error + '</div>');
                    $('.alert-danger').fadeIn();
                }else{
                    $('.alertas').html('<div class="alert alert-success">' + resposta.success + '</div>');
                    $('.alert-success').fadeIn();
                    $('input[class!="noclear"]').val('');
                    $('.form_hide').fadeOut(500);
                }
            },
            error: function(){
                $('.alertas').empty().html('<div class="alert alert-danger"><strong>Erro No Sistema!</strong></div>').fadeIn('slow');
            }
            
        }); 
               
        return false;
    });
    
    
    // FUNÇÃO ENVIO DE E-MAIL PELO PAINEL
    $('.j_submitemail').submit(function(){ 
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            dataType: 'json',
            
            
            beforeSend: function(){
                form.find('.b_nome').html("Carregando...");
                form.find('.alert').fadeOut(500, function(){
                    form.find(this).remove();
                });
            },
            complete: function(){
                form.find('.b_nome').html("Enviar");               
            },
            success: function(resposta){
                console.log(resposta);
                form.find('html, body').animate({scrollTop:0}, 'slow');
                if(resposta.error){
                    form.find('.alertas').html('<div class="alert alert-danger"> <i class="fa fa-times"></i> ' + resposta.error + '</div>');
                    form.find('.alert-danger').fadeIn();
                }else{
                    form.find('.alertas').html('<div class="alert alert-success"> <i class="fa fa-check"></i> ' + resposta.success + '</div>');
                    form.find('.alert-success').fadeIn();
                    form.find('input[class!="noclear"]').val('');
                    //form.find('.form_hide').fadeOut(500);
                }
            },
            error: function(){
                form.find('.alertas').empty().html('<div class="alert alert-danger"><strong>Erro No Sistema!</strong></div>').fadeIn('slow');
            }
            
        }); 
               
        return false;
    });
    
    
    // FUNÇÃO ENVIO DE  SENHA POR E-MAIL PELO PAINEL
    $('.j_recuperasenha').submit(function(){ 
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            dataType: 'json',            
            
            beforeSend: function(){
                form.find('.phidenn').fadeOut(500);
                form.find('.b_nome').html("Carregando...");
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            complete: function(){
                form.find('.b_nome').html("Recuperar Senha");               
            },
            success: function(resposta){
                //$('html, body').animate({scrollTop:0}, 'slow');
                if(resposta.error){
                    form.find('.alertas').html('<div class="alert alert-danger"> ' + resposta.error + '</div>');
                    form.find('.alert-danger').fadeIn();
                }else{
                    form.find('.alertas').html('<div class="alert alert-success"> ' + resposta.success + '</div>');
                    form.find('.alert-success').fadeIn();
                    form.find('input[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            error: function(){
                form.find('.alertas').empty().html('<div class="alert alert-danger"><strong>Erro No Sistema!</strong></div>').fadeIn('slow');
            }
            
        }); 
               
        return false;
    });
    
});