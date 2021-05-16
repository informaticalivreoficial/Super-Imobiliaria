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
        <h3>Moderar Comentário</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=comentarios/comentarios<?= $varPage;?>" title="Voltar e listar os Comentários" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e listar os Comentários</a>	
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
    
    require('models/AdminComentarios.class.php');
    $cadastra = new AdminComentarios;
    $cadastra->ExeUpdate($postId, $post);

    RMErro("O Comentário de <b>{$post['nome']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT);    
        
    else: 
        $read = new Read;
        $read->ExeRead("comentarios","WHERE id = :id","id={$postId}");
        if(!$read->getResult()):
            header('Location: painel.php?exe=comentarios/comentarios&empty=true');
        else:
            $post = $read->getResult()[0];            
            $post['data'] = date('d/m/Y', strtotime($post['data']));            
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
                    <label for="exampleInputEmail1"><strong>Nome</strong></label>
                    <input type="hidden" name="post_id" value="<?php if(isset($post['post_id'])) echo $post['post_id'];?>" />
                    <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="nome" value="<?php if(isset($post['nome'])) echo $post['nome'];?>" />
                </div> 
                <div class="col-md-3 form-group">
                    <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-info btn-lg" name="SendPostForm" value="Atualizar">Atualizar</button>
                </div>
                <div class="col-md-3 form-group">
                    <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Aceitar Comentário">Aceitar Comentário</button>
                </div>
            </div>
             
             <div class="row">
                <div class="col-md-12 form-group"> 

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="exampleInputEmail1"><strong>E-mail</strong></label>
                            <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="email" value="<?php if(isset($post['email'])) echo $post['email'];?>" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="exampleInputEmail1"><strong>Data da Publicação</strong></label>
                            <input class="form-control form-control-inline input-lg default-date-picker" name="data" size="16" type="text" value="<?php if(isset($post['data'])): echo $post['data']; else: echo date('d/m/Y'); endif;?>" disabled/>
                        </div>                                    
                    </div>


                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="exampleInputEmail1"><strong>Post</strong></label><br>
                            <?php
                            $postId = $post['post_id'];
                            $readPost = new Read;
                            $readPost->ExeRead("posts", "WHERE id = :postId", "postId={$postId}");
                            if($readPost->getResult()):
                                $postView = $readPost->getResult()['0'];
                                if($postView['tipo'] == 'noticia'):
                                    $postView['tipo'] = BASE.'/noticia/'.$postView['url'];
                                elseif($postView['tipo'] == 'artigo'):
                                    $postView['tipo'] = BASE.'/blog/artigo/'.$postView['url'];
                                else:
                                    $postView['tipo'] = BASE.'/sessao/'.$postView['url']; 
                                endif;
                                echo '<a target="_blank" href="'.$postView['tipo'].'">'.$postView['titulo'].'</a>';
                            endif;                                        
                            ?>
                        </div>
                    </div>

                </div>
            </div> 
             
            <div class="row"> 
                <div class="col-md-12 form-group">
                    <label for="exampleInputEmail1"><strong>Comentário</strong></label>
                    <textarea class="form-control" rows="6" name="comentario" ><?php if(isset($post['comentario'])) echo strip_tags($post['comentario']);?></textarea>
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
</div>
</div>