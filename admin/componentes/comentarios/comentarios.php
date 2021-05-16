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
        <h3>Gerenciar Comentários</h3>
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
    $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
    if ($empty):
        RMErro("Oppsss: Você tentou editar um comentário que não existe no sistema!", RM_INFOR);
    endif;
    
    $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
    if($action):
        require ('models/AdminComentarios.class.php');
        
        $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
        $postUpdate = new AdminComentarios;
            
        switch($action):
            case 'delete':
                $postUpdate->ExeDelete($postAction);
                RMErro("O comentário foi excluído com sucesso no sistema!", RM_ACCEPT);
                //RMErro($postUpdate->getError()[0], $postUpdate->getError()[1]);
            break;
            
            case 'Rascunho':
                $postUpdate->ExeStatus($postAction, '0');
                RMErro("O status do comentário foi atualizado para <b>Pendente</b>. O comentário agora é pendente de moderação!", RM_ALERT);                
            break;
            
            case 'Publicar':
                $postUpdate->ExeStatus($postAction, '1');
                RMErro("O status do comentário foi atualizado para <b>Publicado</b>. O Comentário agora está publicado!", RM_ACCEPT);                
            break;
            
            default :
                RMErro("Ação não foi identificada pelo sistema, favor utilize os botões!", RM_ALERT);
        endswitch;
    endif;
    
    $posti = 0;
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Pager = new Pager('painel.php?exe=comentarios/comentarios&page=');
    $Pager->ExePager($getPage, 25);
    
    $readPosts = new Read;
    $readPosts->ExeRead("comentarios","WHERE id ORDER BY status ASC, data DESC LIMIT :limit OFFSET :offset","limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if($readPosts->getResult()):  
    
?>
<table class="table  table-hover general-table">

<thead>
  <tr>
   <th>Nome</th>
   <th style="text-align:center;">Data</th>
   <th style="text-align:center;">Visualizar</th>
   <th style="text-align:center;">Status</th>
   <th>Ações:</th>
  </tr>
</thead> 
						  
<tbody>
<?php
        foreach($readPosts->getResult() as $post):
        extract($post);       
        
        $sstatusPost = (!$status ? '<button type="button" class="btn btn-warning btn-sm"><i class="fa fa-warning"></i> Aceitar Comentário</button>' : '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Rejeitar Comentário</button>');
        $stSta = ($status == '0' ? 'Publicar' : 'Rascunho');
        $sstSta = ($status == '0' ? '<span class="label label-warning">Pendente</span>' : '<span class="label label-success">Aprovado</span>');
        $statusColor = (!$status ? 'style="background: #fffed8"' : '');
        
        
?>
<tr <?= $statusColor;?>>
<td><?= $post['nome'];?></td>

<td style="text-align:center;"><?= date('d/m/Y', strtotime($post['data']));?></td>
<td style="text-align:center;"><a title="Visualizar" href="javascript:;" data-toggle="modal" data-target="#2<?= $id;?>" class="btn btn-info btn-sm"><i class="fa fa-search"></i></a></td>
<td style="text-align:center;"><?= $sstSta;?></td>
<td>
<a style="color: #333 !important;" class="btn btn-default btn-sm" title="Editar" href="painel.php?exe=comentarios/comentarios-edit&postid=<?= $id;?><?= $varPage;?>"><i class="fa fa-pencil"></i> Moderar Comentário</a>        
<a class="btn btn-danger btn-sm" title="Excluir" href="javascript:;" data-toggle="modal" data-target="#1<?= $id;?>"><i class="fa fa-times"></i></a>
</td>
</tr>
<?php
// MODAL DE EXCLUSÃO
echo '<div class="modal fade" id="1'.$id.'">
        <div class="modal-dialog">
            <div class="modal-content">				
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Alerta!</strong></h4>
                </div>              
                <div class="modal-body">    				
                    <blockquote class="blockquote-red">			
                    <p>
                    <small>Você tem certeza que deseja excluir este Comentário?<br />
                    Quem postou:<strong> '.$post['nome'].'</strong></small>
                    </p>
                    </blockquote>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <a href="painel.php?exe=comentarios/comentarios&post='.$id.'&action=delete'.$varPage.'">
                    <button type="button" class="btn btn-info">Confirmar</button>
                    </a>
                </div>
            </div>
        </div>
</div>';
// FIM MODAL DE EXCLUSÃO

?>
<div class="modal fade" id="2<?= $id;?>">
    <div class="modal-dialog">
        <div class="modal-content">				
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Quem postou:<strong> <?= $post['nome'];?></strong></h4>
            </div>              
            <div class="modal-body"> 
                <h4><b>Post:</b> 
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
                </h4>                			
                <p><b>Comentário:</b> <?= $post['comentario'];?></p>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Fechar</button>
                <a href="painel.php?exe=comentarios/comentarios&post=<?= $id;?>&action=<?= $stSta;?><?= $varPage;?>">
                <?= $sstatusPost;?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php
    endforeach;
?>
</tbody>
</table>
<?php  
    else:
        $Pager->ReturnPage();
        RMErro("Desculpe, ainda não existem comentários cadastrados!", RM_INFOR);  
    endif;

// Chama o Paginator    
$Pager->ExePaginator("comentarios","WHERE id ORDER BY status ASC, data DESC");       	
?>
<div class="row-fluid">
    <div class="span6">
        <?php
            $readPostsCount = new Read;
            $readPostsCount->ExeRead("comentarios","WHERE id ORDER BY status ASC, data DESC");
	        if($readPostsCount->getResult()):              
        ?> 
         <div class="dataTables_info" id="dynamic-table_info">Exibindo <?= $Pager->getPage();?> de <?= $Pager->getTotal("posts");?> de <?= $readPostsCount->getRowCount();?> Comentário(s)</div>
        <?php endif;?>
        
    </div>
    <div class="span6">
        <div class="dataTables_paginate paging_bootstrap pagination">
            <?= $Pager->getPaginator();?>            
        </div>
    </div>
</div>
</div>
</div>
</section>
</div>
</div>
</div>