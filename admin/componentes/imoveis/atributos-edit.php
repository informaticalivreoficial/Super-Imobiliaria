<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
    
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);    
    // SE TIVER PAGINAÇÃO ENVIA O PAGE
    if($getPage): $varPage = '&page='.$getPage.''; else: $varPage = ''; endif;
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Editar Acessório</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=imoveis/atributos<?= $varPage;?>" title="Voltar e listar os Acessórios" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e listar os Acessórios</a>	
    </div>
</div>
</div>


<div class="wrapper">
<div class="row">
<div class="col-sm-12">
<section class="panel">

<div class="panel-body">
<div class="adv-table">

<?php
    $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $postId = filter_input(INPUT_GET, 'postid', FILTER_VALIDATE_INT);
    
    if(isset($post) && $post['SendPostForm']):
    
    $post['status'] = ($post['SendPostForm'] == 'Atualizar' ? '0' : '1' );
    unset($post['SendPostForm']);    
       
    require('models/AdminAcessorios.class.php');
    $cadastra = new AdminAcessorios;
    $cadastra->ExeUpdate($postId, $post);

    RMErro("O Acessório <b>{$post['nome']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT);
    
    else: 
        $read = new Read;
        $read->ExeRead("imoveis_atributos","WHERE id = :id","id={$postId}");
        if(!$read->getResult()):
            header('Location: painel.php?exe=imoveis/atributos&create=true');
        else:
            $post = $read->getResult()[0];            
            $post['data'] = date('d/m/Y', strtotime($post['data']));
        endif;
    endif;
    
    if(!empty($_SESSION['errCapa'])):
        RMErro($_SESSION['errCapa'], E_USER_WARNING);
        unset($_SESSION['errCapa']);
    endif;
    
    $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
    if ($checkCreate && empty($cadastra)):
        RMErro("O Acessório <b>{$post['nome']}</b> foi cadastrado com sucesso no sistema!", RM_ACCEPT);
    endif;
?>

<form method="post" action="" enctype="multipart/form-data">

    <div class="row">
        <div class="col-md-12">

            <div class="panel">                    

                <div class="panel-body">

                    <div class="row">                               
                        <div class="col-md-6 form-group">
                            <label for="exampleInputEmail1"><strong>Nome</strong></label>
                            <input name="data" type="hidden" value="<?php if(isset($post['data'])): echo $post['data']; else: echo date('d/m/Y'); endif;?>"/>
                            <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="nome" value="<?php if(isset($post['nome'])) echo $post['nome'];?>" />
                        </div> 
                        <div class="col-md-3 form-group">
                            <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                            <button type="submit" style="width:100%;" class="btn btn-info btn-lg" name="SendPostForm" value="Atualizar">Atualizar</button>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                            <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Atualizar & Publicar">Atualizar & Publicar</button>
                        </div>
                    </div>

                </div>            
            
            
            </div>
        </div>
    </div>
</div>
             

</form>	
    
    
<!-- MODAL AJUDA MAPA -->
<div class="modal fade" id="1">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">				
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Ajuda</strong></h4>
            </div>              
            <div class="modal-body">
                <h4>Copie do Google Maps e cole somente o que está em vermelho como no exemplo abaixo:</h4>
                <p style="word-wrap: break-word;">iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="<b>http://maps.google.com.br/maps?q=padang+itamambuca&hl=pt&cd=5&ei=WKRATO_oD5qYyASLp8G8BA&sig2=w3RAiseOdsO7C12FC844Wg&sll=-23.40367,-45.013046&sspn=3.598987,4.938354&ie=UTF8&view=map&cid=3882811409949230167&ved=0CBgQpQY&hq=padang+itamambuca&hnear=&ll=-23.391737,-45.009162&spn=0.006893,0.00912&z=16&iwloc=A&output=embed</b>">iframe</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>                
            </div>
         </div>
    </div>
</div>


</div>
    
</section>
</div>
</div>
</div>