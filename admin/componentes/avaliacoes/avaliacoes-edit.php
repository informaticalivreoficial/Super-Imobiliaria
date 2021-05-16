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
        <h3>Editar Avaliação</strong></h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=avaliacoes/avaliacoes<?= $varPage;?>" title="Voltar e listar Avaliações" class="btn btn-primary btn-lg" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e listar Avaliações</a>	
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
    $post['status'] = ($post['SendPostForm'] == 'Salvar Rascunho' ? '0' : '1' );
    $post['avatar'] = ($_FILES['avatar']['tmp_name'] ? $_FILES['avatar'] : 'null');
    unset($post['SendPostForm']);
    
    require('models/AdminAvaliacoes.class.php');
    $cadastra = new AdminAvaliacoes;
    $cadastra->ExeUpdate($postId, $post);

    RMErro($cadastra->getError()[0], $cadastra->getError()[1]);
    
    else: 
        $read = new Read;
        $read->ExeRead("avaliacoes","WHERE id = :id","id={$postId}");
        if(!$read->getResult()):
            header('Location: painel.php?exe=avaliacoes/avaliacoes&create=true');
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
        RMErro("A Avaliação de <b>{$post['nome']}</b> foi cadastrada com sucesso no sistema!", RM_ACCEPT);
    endif;			
?>
<form method="post" action="" enctype="multipart/form-data">
<div class="row">
            <div class="col-md-12">

                <div class="panel">                    

                    <div class="panel-body">
                    
                        <div class="row">
                            <div class="col-md-2 form-group" style="text-align: center;">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 160px; height: 160px;">
                                        <?php 
                                            $read1 = new Read;
                                            $read1->ExeRead("avaliacoes","WHERE id = :id","id={$postId}");
                                            if(!$read1->getResult()):
                                              echo '<img src="images/200x200.gif"/>';  
                                            else:
                                            
                                             foreach($read1->getResult() as $capa1);
                                             if($capa1['avatar'] == ''):
                                                echo '<img src="images/200x200.gif"/>';
                                             else:                                                
                                                echo '<img src="../uploads/'.$capa1['avatar'].'" />';
                                             endif; 
                                                                                           
                                            endif;                    
                                        ?>                                        
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="width: 160px; height: 160px; line-height: 20px;"></div>
                                    <div>
                                           <span class="btn btn-default btn-file">
                                           <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                           <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                                           <input type="file" name="avatar" class="default" value="" />
                                           </span>
                                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-10 form-group"> 
                                <div class="row">                               
                                    <div class="col-md-6 form-group">
                                        <label><strong>Nome</strong></label>
                                        <input name="data" type="hidden" value="<?php if(isset($post['data'])): echo $post['data']; else: echo date('d/m/Y'); endif;?>"/>
                                        <input type="text" class="form-control input-lg" name="nome" value="<?php if(!empty($post['nome'])) echo $post['nome'];?>" />
                                    </div> 
                                    <div class="col-md-3 form-group">
                                        <label><strong>&nbsp;</strong></label>
                                        <button type="submit" style="width:100%;" class="btn btn-info btn-lg" name="SendPostForm" value="Salvar Rascunho">Salvar Rascunho</button>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label><strong>&nbsp;</strong></label>
                                        <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Salvar & Publicar">Salvar & Publicar</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label><strong>E-mail</strong></label>
                                        <input type="text" class="form-control input-lg" name="email" value="<?php if(!empty($post['email'])) echo $post['email'];?>" />
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label><strong>Avaliação</strong></label>
                                        <select name="avaliacao" class="form-control input-lg m-bot15">
                                            <option value=""> Selecione </option>
                                            <option <?php if(isset($post['avaliacao']) && $post['avaliacao'] == '100') echo 'selected="selected"';?>  value="100">Excelente</option>
                                            <option <?php if(!isset($post['avaliacao']) || $post['avaliacao'] == '80') echo 'selected="selected"';?>  value="80">Ótimo</option>
                                            <option <?php if(!isset($post['avaliacao']) || $post['avaliacao'] == '60') echo 'selected="selected"';?>  value="60">Bom</option>
                                            <option <?php if(!isset($post['avaliacao']) || $post['avaliacao'] == '40') echo 'selected="selected"';?>  value="40">Ruim</option>
                                            <option <?php if(!isset($post['avaliacao']) || $post['avaliacao'] == '20') echo 'selected="selected"';?>  value="20">Péssimo</option>
                                        </select>
                                    </div>                                    
                                </div>                                
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-sm-12 form-group">
                            <label><strong>Depoimento</strong></label>
                            <textarea class="form-control" name="depoimento" rows="6"><?php if(!empty($post['depoimento'])) echo htmlspecialchars($post['depoimento']);?></textarea>                        	    
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