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
        <h3>Editar Parceiro</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=parceiros/parceiros<?= $varPage;?>" title="Voltar e listar os Parceiros" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e listar os Parceiros</a>	
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
    
    $post['status'] = '0';
    
    $post['img'] = ($_FILES['img']['tmp_name'] ? $_FILES['img'] : null);
    unset($post['SendPostForm']);
    
    require('models/AdminParceiros.class.php');
    $cadastra = new AdminParceiros;
    $cadastra->ExeCreate($post);
    
    if ($cadastra->getResult()): 
        header('Location: painel.php?exe=parceiros/parceiros-edit&parceiros-create=true&postid=' . $cadastra->getResult());
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
                <div class="col-md-4 form-group">
                    <label for="exampleInputEmail1"><strong>Nome</strong></label>
                    <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="nome" value="<?php if(isset($post['nome'])) echo $post['nome'];?>" />
                </div> 
                <div class="col-md-4 form-group">
                    <label for="exampleInputEmail1"><strong>E-mail</strong></label>
                    <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="email" value="<?php if(isset($post['email'])) echo $post['email'];?>" />
                </div>
                <div class="col-md-4 form-group">
                    <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Salvar">Salvar</button>
                </div>
            </div>
             
             <div class="row">
                            <div class="col-md-6 form-group">
                                <div style="margin-top: 10px;" class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 100%; height: 250px;">
                                        <?= '<img src="images/image.jpg">';  ?>                                       
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
                                    <div class="col-md-6 form-group">
                                        <label for="exampleInputEmail1"><strong>Telefone</strong></label>
                                        <input type="text" class="form-control input-lg" id="exampleInputEmail1" data-mask="(99) 99999-9999" name="telefone" value="<?php if(!empty($post['telefone'])) echo $post['telefone'];?>" />
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="exampleInputEmail1"><strong>WhatsApp</strong></label>
                                        <input type="text" class="form-control input-lg" id="exampleInputEmail1" data-mask="(99) 99999-9999" name="whatsapp" value="<?php if(!empty($post['whatsapp'])) echo $post['whatsapp'];?>" />
                                    </div>
                                </div>
                                
                                <div class="row">                                    
                                    <div class="col-md-12 form-group">
                                        <label for="exampleInputEmail1"><strong>Website</strong></label>
                                        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="site" value="<?php if(!empty($post['site'])) echo $post['site'];?>" />
                                    </div>                                
                                </div>
                                
                                
                            </div>
                        </div> 
             
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <textarea class="form-control editor" rows="6" name="content" ><?php if(isset($post['content'])) echo htmlspecialchars($post['content']);?></textarea>
                            </div>                           
                        </div>
   
            
<div class="clear"></div>        
<div class="row" id="gbfoco">
    <div class="col-md-12 form-group">
        <h3>Galeria</h3>
            
            <div class="upload-btn-wrapper">
            <button class="btnup">Enviar Imagens da Galeria</button>
            <input name="gallery_covers[]" type="file" multiple  />
            </div>
    </div>
  
  
    
</div>

    
    <div class="clear"></div>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
            <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Salvar">Salvar</button>
        </div>
    </div>
</div>            
            
            
            </div>
        </div>
    </div>
</div>
             

</form>	

</div>
</div>
</section>
</div>
</div>
</div>
