<?php
	if(empty($login)):
        header('Location: painel.php');
        die;
    endif; 
    
    $linkid = filter_input(INPUT_GET, 'linkid', FILTER_VALIDATE_INT);  
    $linkSubid = filter_input(INPUT_GET, 'linksubid', FILTER_VALIDATE_INT); 
    
    //SE FOR SUB-LINK
    if($linkSubid):
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-8">
        <h3>Editar Sub-Link</h3>
    </div>
    <div class="col-sm-4">
        <a href="painel.php?exe=menu/menu" title="Voltar e Listar Links" class="btn btn-info btn-lg" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e Listar Links</a>
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
    $post  = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
    
	if(!empty($post && $post['sendForm'])):
    unset($post['sendForm']);
    
    require('./models/AdminMenu.class.php');
    $cadastra = new AdminMenu;
    $cadastra->ExeUpdate($linkSubid, $post);
    
    RMErro($cadastra->getError()[0], $cadastra->getError()[1]);
    
    else:
        $read = new Read;
        $read->ExeRead("menu_topo", "WHERE id = :id", "id={$linkSubid}");
        if (!$read->getResult()):
            header('Location: painel.php?exe=menu/menu&empty=true');
        else:
            $post = $read->getResult()[0];
        endif; 
    endif;
    
    $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
    if($checkCreate && empty($cadastra)):
        $tipo = ( empty($post['id_pai']) ? 'Link' : 'Sub-Link');
        RMErro("O {$tipo} <b>{$post['nome']}</b> foi cadastrado com sucesso no sistema! Continue atualizando o mesmo!", RM_ACCEPT);
    endif;	
?>          
<form method="post" role="form" action="">

<div class="row">	
	<div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Título</strong></label>
        <input type="hidden" name="data" value="<?php if(isset($post['data'])) echo date('d/m/Y H:i:s', strtotime($post['data']));?>" />
        <input type="hidden" name="uppdate" value="<?= date('d/m/Y H:i:s'); ?>" />
        <input type="hidden" name="id_pai" value="<?= $linkid;?>" />
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="nome" value="<?php if(isset($post['nome'])) echo $post['nome'];?>" />
    </div>
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Status</strong></label>
        <select name="status" class="form-control input-lg m-bot15">
            <option>Selecione</option>
            <option <?php if(isset($post['status']) && $post['status'] == '1') echo 'selected="selected"';?>  value="1">Publicado</option>
            <option <?php if(!isset($post['status']) || $post['status'] == '0') echo 'selected="selected"';?>  value="0">Rascunho</option>
        </select>
    </div>
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Página</strong></label>
        <select name="link" class="form-control input-lg m-bot15">
            <?php
               $readPaginas = new Read;
               $readPaginas->ExeRead("posts","WHERE status = '1' AND tipo = 'pagina'");
               if($readPaginas->getResult()):
                        echo '<optgroup label="Selecione uma Página">';
                        echo '<option value="0">Nenhuma</option>';
                    foreach($readPaginas->getResult() as $paginas):
                        extract($paginas);                        
                        echo '<option value="'.$url.'" ';
                        if($url == $post['link']):
                         echo 'selected="selected"';
                        endif; 
                        echo '>'.$titulo.'</option>'; 
                    endforeach;
                        echo '</optgroup>';
               else:
                    echo '<optgroup label="Selecione uma Página">';
                    echo '<option value="0">Nenhuma Página habilitada ainda!</option>';
                    echo '</optgroup>';
               endif;    	       
            ?>
        </select>
    </div>   
</div>


<div class="row">
    <div class="col-md-8 form-group">
        <label for="exampleInputEmail1"><strong>URL</strong> &nbsp;<span style="font-size:10px; color:#C0C0C0;font-weight:normal;">Ex: http://www.dominio.com</span></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="url" value="<?php if(isset($post['url'])) echo $post['url'];?>" />
    </div>
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Destino</strong></label>
        <select name="target" class="form-control input-lg m-bot15">
            <option>Selecione</option>
            <option <?php if(isset($post['target']) && $post['target'] == '1') echo 'selected="selected"';?>  value="1">Nova Janela</option>
            <option <?php if(!isset($post['target']) || $post['target'] == '0') echo 'selected="selected"';?>  value="0">Mesma Janela</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-4 form-group">
        
    </div>
    <div class="col-md-4 form-group">
        
    </div>
    <div class="col-md-4 form-group">
        <button name="sendForm" type="submit" style="width:100%;" class="btn btn-success btn-lg" value="Atualizar">Atualizar</button>
    </div>
</div>

</form>

</div>
</div>
</section>
</div>
</div>
</div>
<?php
    //SE FOR LINK
	else:
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-8">
        <h3>Editar Link</h3>
    </div>
    <div class="col-sm-4">
        <a href="painel.php?exe=menu/menu" title="Voltar e Listar Links" class="btn btn-info btn-lg" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e Listar Links</a>
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
    $post  = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
    
	if(!empty($post && $post['sendForm'])):
    unset($post['sendForm']);
    
    require('./models/AdminMenu.class.php');
    $cadastra = new AdminMenu;
    $cadastra->ExeUpdate($linkid, $post);
    
    RMErro($cadastra->getError()[0], $cadastra->getError()[1]);
    
    else:
        $read = new Read;
        $read->ExeRead("menu_topo", "WHERE id = :id", "id={$linkid}");
        if (!$read->getResult()):
            header('Location: painel.php?exe=menu/menu&empty=true');
        else:
            $post = $read->getResult()[0];
        endif; 
    endif;
    
    $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
    if($checkCreate && empty($cadastra)):
        $tipo = ( empty($post['id_pai']) ? 'Link' : 'Sub-Link');
        RMErro("O {$tipo} <b>{$post['nome']}</b> foi cadastrado com sucesso no sistema! Continue atualizando o mesmo!", RM_ACCEPT);
    endif;	
?>          
<form method="post" role="form" action="">

<div class="row">	
	<div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Título</strong></label>
        <input type="hidden" name="data" value="<?php if(isset($post['data'])) echo date('d/m/Y H:i:s', strtotime($post['data']));?>" />
        <input type="hidden" name="uppdate" value="<?= date('d/m/Y H:i:s'); ?>" />
        <input type="hidden" name="id_pai" value="null" />
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="nome" value="<?php if(isset($post['nome'])) echo $post['nome'];?>" />
    </div>
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Status</strong></label>
        <select name="status" class="form-control input-lg m-bot15">
            <option>Selecione</option>
            <option <?php if(isset($post['status']) && $post['status'] == '1') echo 'selected="selected"';?>  value="1">Publicado</option>
            <option <?php if(!isset($post['status']) || $post['status'] == '0') echo 'selected="selected"';?>  value="0">Rascunho</option>
        </select>
    </div>
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Página</strong></label>
        <select name="link" class="form-control input-lg m-bot15">
            <?php
               $readPaginas = new Read;
               $readPaginas->ExeRead("posts","WHERE status = '1' AND tipo = 'pagina'");
               if($readPaginas->getResult()):
                        echo '<optgroup label="Selecione uma Página">';
                        echo '<option value="0">Nenhuma</option>';
                    foreach($readPaginas->getResult() as $paginas):
                        extract($paginas);    
                        $linkUrl = 'sessao/'.$url;
                        echo '<option value="'.$url.'" ';
                        if($linkUrl == $post['link']):
                         echo 'selected="selected"';
                        endif; 
                        echo '>'.$titulo.'</option>'; 
                    endforeach;
                        echo '</optgroup>';
               else:
                    echo '<optgroup label="Selecione uma Página">';
                    echo '<option value="0">Nenhuma Página habilitada ainda!</option>';
                    echo '</optgroup>';
               endif;    	       
            ?>
        </select>
    </div>   
</div>


<div class="row">
    <div class="col-md-8 form-group">
        <label for="exampleInputEmail1"><strong>URL</strong> &nbsp;<span style="font-size:10px; color:#C0C0C0;font-weight:normal;">Ex: http://www.dominio.com</span></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="url" value="<?php if(isset($post['url'])) echo $post['url'];?>" />
    </div>
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Destino</strong></label>
        <select name="target" class="form-control input-lg m-bot15">
            <option>Selecione</option>
            <option <?php if(isset($post['target']) && $post['target'] == '1') echo 'selected="selected"';?>  value="1">Nova Janela</option>
            <option <?php if(!isset($post['target']) || $post['target'] == '0') echo 'selected="selected"';?>  value="0">Mesma Janela</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-4 form-group">
        
    </div>
    <div class="col-md-4 form-group">
        
    </div>
    <div class="col-md-4 form-group">
        <button name="sendForm" type="submit" style="width:100%;" class="btn btn-success btn-lg" value="Atualizar">Atualizar</button>
    </div>
</div>

</form>

</div>
</div>
</section>
</div>
</div>
</div>
<?php
	endif;
?>
