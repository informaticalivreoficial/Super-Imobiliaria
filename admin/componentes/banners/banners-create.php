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
        <h3>Cadastrar Banner</h3>
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
    if(isset($post) && $post['SendPostForm']):    
        $post['status'] = ($post['SendPostForm'] == 'Salvar' ? '0' : '1' );    
        $post['imagem'] = ($_FILES['imagem']['tmp_name'] ? $_FILES['imagem'] : null);
        unset($post['SendPostForm']);

        require('models/AdminBanners.class.php');
        $cadastra = new AdminBanners;
        $cadastra->ExeCreate($post);

        if ($cadastra->getResult()):
            
            header('Location: painel.php?exe=banners/banners-edit&create=true&postid=' . $cadastra->getResult());
        else:
            RMErro($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;
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
                            <?= '<img src="images/image.jpg">';  ?>                                         
                        </div>
                        <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; height: 350px; line-height: 20px;"></div>
                        <div>
                               <span class="btn btn-default btn-file">
                               <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                               <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                               <input type="file" name="imagem" class="default" value="<?php if(isset($post['imagem'])) echo $post['imagem'];?>" />
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