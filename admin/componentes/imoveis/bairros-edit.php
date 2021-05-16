<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
    
    $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $ufId = filter_input(INPUT_GET, 'uf_id', FILTER_VALIDATE_INT);
    $cidadeId = filter_input(INPUT_GET, 'cidade_id', FILTER_VALIDATE_INT);
    $postId = filter_input(INPUT_GET, 'postid', FILTER_VALIDATE_INT);
    
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);    
    // SE TIVER PAGINAÇÃO ENVIA O PAGE
    if($getPage): $varPage = '&page='.$getPage.''; else: $varPage = ''; endif;
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Editar Bairro</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=imoveis/bairros&uf_id=<?= $ufId;?>&cidade_id=<?= $cidadeId;?><?= $varPage;?>" title="Voltar e listar os Bairros" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e listar os Bairros</a>	
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
    
    if(isset($post) && $post['SendPostForm']):
    
    $post['status'] = ($post['SendPostForm'] == 'Atualizar' ? '0' : '1' );
    $post['img'] = ($_FILES['img']['tmp_name'] ? $_FILES['img'] : 'null');
    unset($post['SendPostForm']);
    
    require('models/AdminBairros.class.php');
    $cadastra = new AdminBairros;
    $cadastra->ExeUpdate($postId, $post);

    RMErro("O Bairro <b>{$post['nome']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT);
            
    else: 
        $read = new Read;
        $read->ExeRead("bairros","WHERE id = :id","id={$postId}");
        if(!$read->getResult()):
            header('Location: painel.php?exe=imoveis/bairros&create=true');
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
        RMErro("O Bairro <b>{$post['nome']}</b> foi cadastrado com sucesso no sistema!", RM_ACCEPT);
    endif;
?>

<form method="post" action="" enctype="multipart/form-data">

<div class="row">
    <div class="col-md-12">

        <div class="panel">                    

            <div class="panel-body">
            
            <div class="row">                               
                <div class="col-md-6 form-group">
                    <label><strong>Bairro</strong></label>
                    <input type="hidden" name="uf" value="<?= $ufId;?>" />
                    <input type="hidden" name="cidade" value="<?= $cidadeId;?>" />
                    <input type="hidden" name="data" value="<?php if(isset($post['data'])): echo $post['data']; else: echo date('d/m/Y'); endif;?>" />
                    <input type="text" class="form-control input-lg" name="nome" value="<?php if(isset($post['nome'])) echo $post['nome'];?>" />
                </div> 
                <div class="col-md-3 form-group">
                    <label><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-info btn-lg" name="SendPostForm" value="Atualizar">Atualizar</button>
                </div>
                <div class="col-md-3 form-group">
                    <label><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Atualizar & Publicar">Atualizar & Publicar</button>
                </div>
            </div>
             
             <div class="row">
                            <div class="col-md-6 form-group">
                                <div style="margin-top: 10px;" class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 100%; height: 350px;">
                                        <?php 
                                            $read1 = new Read;
                                            $read1->ExeRead("bairros","WHERE id = :id","id={$postId}");
                                            if(!$read1->getResult()):
                                              echo '<img src="images/image.jpg">';  
                                            else:
                                            
                                             foreach($read1->getResult() as $capa1);
                                             if($capa1['img'] == ''):
                                                echo '<img src="images/image.jpg">';
                                             else:                                                
                                                echo '<img src="../uploads/'.$capa1['img'].'" />';
                                             endif; 
                                                                                           
                                            endif;                    
                        				  ?>                                        
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; height: 350px; line-height: 20px;"></div>
                                    <div>
                                           <span class="btn btn-default btn-file">
                                           <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                           <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                                           <input type="file" name="img" class="default" value="" />
                                           </span>
                                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 form-group"> 
                                                               
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label><strong>CEP</strong></label>
                                        <input type="text" class="form-control input-lg" name="cep" data-mask="99.999-999" value="<?php if(isset($post['cep'])) echo $post['cep'];?>" />
                                    </div>                                    
                                </div>                                
                                
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="exampleInputEmail1"><strong>Legenda da Imagem</strong></label>
                                        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="thumb_legenda" value="<?php if(isset($post['thumb_legenda'])) echo $post['thumb_legenda'];?>" />
                                    </div>
                                </div>
                                
                            </div>
                        </div> 
</div>            
            
            
            </div>
        </div>
    </div>
</div>
             

</form>	

</div>

</section>
</div>
</div>
</div>