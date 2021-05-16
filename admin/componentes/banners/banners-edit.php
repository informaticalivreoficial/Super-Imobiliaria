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
        <h3>Editar Banner</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=banners/banners<?= $varPage;?>" title="Voltar e listar os Banners" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e listar os Banners</a>	
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
    $post['imagem'] = ($_FILES['imagem']['tmp_name'] ? $_FILES['imagem'] : 'null');
    unset($post['SendPostForm']);
    
    require('models/AdminBanners.class.php');
    $cadastra = new AdminBanners;
    $cadastra->ExeUpdate($postId, $post);

    RMErro("O Banner <b>{$post['titulo']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT);    
        
    else: 
        $read = new Read;
        $read->ExeRead("banners","WHERE id = :id","id={$postId}");
        if(!$read->getResult()):
            header('Location: painel.php?exe=banners/banners&create=true');
        else:
            $post = $read->getResult()[0];            
            $post['data'] = date('d/m/Y', strtotime($post['data']));
            $post['expira'] = date('d/m/Y', strtotime($post['expira']));
        endif;
    endif;
    
    if(!empty($_SESSION['errCapa'])):
        RMErro($_SESSION['errCapa'], E_USER_WARNING);
        unset($_SESSION['errCapa']);
    endif;
    
    $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
    if ($checkCreate && empty($cadastra)):
        RMErro("O Banner <b>{$post['titulo']}</b> foi cadastrado com sucesso no sistema!", RM_ACCEPT);
    endif;
?>

<form method="post" action="" enctype="multipart/form-data">

<div class="row">
    <div class="col-md-12">

        <div class="panel">                    

            <div class="panel-body">
            
            <div class="row">                               
                <div class="col-md-6 form-group">
                    <label><strong>Título</strong></label>
                    <input type="text" class="form-control input-lg" name="titulo" value="<?php if(isset($post['titulo'])) echo $post['titulo'];?>" />
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
                <div class="col-md-12 form-group">
                    <label><strong>URL</strong></label> &nbsp;<span style="font-size:10px; color:#C0C0C0;font-weight:normal;">Ex: http://www.dominio.com</span>
                    <input type="text" class="form-control input-lg" name="link" value="<?php if(isset($post['link'])) echo $post['link'];?>" />
                </div>
            </div>
                
            <div class="row">
                <div class="col-md-3 form-group">
                    <label><strong>Data de publicação</strong></label>
                    <input type="hidden" name="data" value="<?php if(isset($post['data'])): echo $post['data']; else: echo date('d/m/Y'); endif;?>" />
                    <input class="form-control form-control-inline input-lg default-date-picker" name="data" size="16" type="text" value="<?php if(isset($post['data'])): echo $post['data']; else: echo date('d/m/Y'); endif;?>" disabled/>
                </div> 
                <div class="col-md-3 form-group">
                    <label><strong>Expira</strong></label>
                    <input class="form-control form-control-inline input-lg default-date-picker" name="expira" size="16" type="text" value="<?php if(isset($post['expira'])): echo $post['expira']; else: echo date('d/m/Y'); endif;?>"/>
                </div>
                <div class="col-md-3 form-group">
                    <label><strong>Destino</strong></label>
                    <select name="target" class="form-control input-lg m-bot15">
                        <option value=""> Selecione </option>
                        <option <?php if(isset($post['target']) && $post['target'] == '1') echo 'selected="selected"';?>  value="1">Nova Janela</option>
                        <option <?php if(!isset($post['target']) || $post['target'] == '0') echo 'selected="selected"';?>  value="0">Mesma Janela</option>	
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label><strong>Exibir Título?</strong></label>
                    <select name="exibir_titulo" class="form-control input-lg m-bot15">
                        <option value=""> Selecione </option>
                        <option <?php if(isset($post['exibir_titulo']) && $post['exibir_titulo'] == '1') echo 'selected="selected"';?>  value="1">Sim</option>
                        <option <?php if(!isset($post['exibir_titulo']) || $post['exibir_titulo'] == '0') echo 'selected="selected"';?>  value="0">Não</option>	
                    </select>
                </div>
            </div> 
             
            <div class="row">
                <div class="col-md-12 form-group">
                    <label><strong>Imagem Tamanho recomendado 2200X1200 pixels</strong></label>
                    <div style="margin-top: 10px;" class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail" style="width: 100%; height: 350px;">
                            <?php 
                                $read1 = new Read;
                                $read1->ExeRead("banners","WHERE id = :id","id={$postId}");
                                if(!$read1->getResult()):
                                    echo '<img src="images/image.jpg">';  
                                else:
                                    foreach($read1->getResult() as $capa1);
                                    if($capa1['imagem'] == ''):
                                       echo '<img src="images/image.jpg">';
                                    else:                                                
                                       echo '<img src="../uploads/'.$capa1['imagem'].'" />';
                                    endif; 
                                endif;                    
                            ?>                                        
                        </div>
                        <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; height: 350px; line-height: 20px;"></div>
                        <div>
                               <span class="btn btn-default btn-file">
                               <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                               <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                               <input type="file" name="imagem" class="default" value="" />
                               </span>
                            <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                        </div>
                    </div>
                </div>
            </div> 
             
            <div class="row">                                                        
                <div class="col-md-12 form-group">
                    <label><strong>Descrição</strong></label>
                    <textarea class="form-control editor" rows="6" name="content" ><?php if(isset($post['content'])) echo htmlspecialchars($post['content']);?></textarea>
                </div>                            
            </div>
        
            <div class="clear"></div>
            <div class="row">
                <div class="col-md-3 form-group">
                    <label><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-info btn-lg" name="SendPostForm" value="Atualizar">Atualizar</button>
                </div>
                <div class="col-md-3 form-group">
                    <label><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Atualizar & Publicar">Atualizar & Publicar</button>
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