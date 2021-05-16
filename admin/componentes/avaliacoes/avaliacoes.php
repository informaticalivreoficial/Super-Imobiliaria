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
        <h3>Gerenciar Avaliações</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=avaliacoes/avaliacoes-create<?= $varPage;?>" title="Cadastrar Avaliação" class="btn btn-default btn-lg" style="float:right;">Cadastrar Avaliação</a>
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
        RMErro("Oppsss: Você tentou editar uma Avaliação que não existe no sistema!", RM_INFOR);
    endif;
    
    $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
    if($action):
        require ('models/AdminAvaliacoes.class.php');
        
        $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
        $postUpdate = new AdminAvaliacoes;
            
        switch($action):
            case 'delete':
                $postUpdate->ExeDelete($postAction);
                RMErro("A Avaliação foi excluída com sucesso no sistema!", RM_ACCEPT);
                //RMErro($postUpdate->getError()[0], $postUpdate->getError()[1]);
            break;
            
            case 'Rascunho':
                $postUpdate->ExeStatus($postAction, '0');
                RMErro("O status da Avaliação foi atualizado para <b>Rascunho</b>. A Avaliação agora é um rascunho!", RM_ALERT);                
            break;
            
            case 'Publicar':
                $postUpdate->ExeStatus($postAction, '1');
                RMErro("O status da Avaliação foi atualizado para <b>Publicado</b>. A Avaliação agora está publicada!", RM_ACCEPT);                
            break;
            
            default :
                RMErro("Ação não foi identificada pelo sistema, favor utilize os botões!", RM_ALERT);
        endswitch;
    endif;
    
    $posti = 0;
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Pager = new Pager('painel.php?exe=avaliacoes/avaliacoes&page=');
    $Pager->ExePager($getPage, 25);
    
    $readPosts = new Read;
    $readPosts->ExeRead("avaliacoes","WHERE id ORDER BY status ASC, data DESC LIMIT :limit OFFSET :offset","limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if($readPosts->getResult()):  
    
?>
<table class="table  table-hover general-table">

<thead>
  <tr>
   <th>Foto</th>
   <th>Nome</th>
   <th>Data</th>
   <th>Avaliação</th> 
   <th>Ações:</th>
  </tr>
</thead> 
						  
<tbody>
<?php
        foreach($readPosts->getResult() as $post):
        extract($post);
        
        $statusPost = (!$status ? '<button type="button" class="btn btn-warning btn-sm"><i class="fa fa-warning"></i></button>' : '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>');
        $stSta = ($status == '0' ? 'Publicar' : 'Rascunho');
        $statusColor = (!$status ? 'style="background: #fffed8"' : '');
        
        $gb = new Read;
        $gb->ExeRead("posts_gb","WHERE post_id = '$id'");
?>
<tr <?= $statusColor;?>>
<?php if($avatar == null): ?>
    <td><img src="tim.php?src=admin/images/image.jpg&w=60&h=60&zc=1&q=100"/></td>
<?php else: ?>
    <td>
    <a href="../uploads/<?= $avatar;?>" title="<?= $nome;?>" rel="ShadowBox">
    <?= Check::Image('../uploads/' . $avatar, $nome, 60, 60); ?>
    </a>
    </td>
<?php endif; ?>

<td title="<?= $nome;?>"><?= $nome;?></td>
<td><?= date('d/m/Y', strtotime($data));?></td>
<td><?=Check::getStars($avaliacao);?></td>
<td>
<a href="painel.php?exe=avaliacoes/avaliacoes&post=<?= $id;?>&action=<?= $stSta;?><?= $varPage;?>" title="<?= $stSta;?>"><?= $statusPost;?></a>        
<a style="color: #333 !important;" class="btn btn-default btn-sm" title="Editar" href="painel.php?exe=avaliacoes/avaliacoes-edit&postid=<?= $id;?><?= $varPage;?>"><i class="fa fa-pencil"></i></a>
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
                <small>Você tem certeza que deseja excluir esta Avaliação?<br />
                <strong>'.$nome.'</strong></small>
                </p>
                </blockquote>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <a href="painel.php?exe=avaliacoes/avaliacoes&post='.$id.'&action=delete'.$varPage.'">
                <button type="button" class="btn btn-info">Confirmar</button>
                </a>
            </div>
            </div>
        </div>
</div>';
// FIM MODAL DE EXCLUSÃO
        endforeach;
?>
</tbody>
</table>
<?php  
    else:
        $Pager->ReturnPage();
        RMErro("Desculpe, ainda não existem Avaliações cadastradas!", RM_INFOR);  
    endif; 


// Chama o Paginator    
$Pager->ExePaginator("avaliacoes","WHERE id ORDER BY status ASC, data DESC");       	
?>
<div class="row-fluid">
    <div class="span6">
        <?php
            $readPostsCount = new Read;
            $readPostsCount->ExeRead("avaliacoes","WHERE id ORDER BY status ASC, data DESC");
	        if($readPostsCount->getResult()):              
         ?> 
         <div class="dataTables_info" id="dynamic-table_info">Exibindo <?= $Pager->getPage();?> de <?= $Pager->getTotal("avaliacoes");?> de <?= $readPostsCount->getRowCount();?> Avaliações</div>
         <?php     
            endif;
          ?>
        
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