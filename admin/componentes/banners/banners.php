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
        <h3>Gerenciar Banners</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=banners/banners-create<?= $varPage;?>" title="Cadastrar Banner" class="btn btn-default btn-lg" style="float:right;">Cadastrar Banner</a>
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
        RMErro("Oppsss: Você tentou editar um banner que não existe no sistema!", RM_INFOR);
    endif;
    
    $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
    if($action):
        require ('models/AdminBanners.class.php');
        
        $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
        $postUpdate = new AdminBanners;
            
        switch($action):
            case 'delete':
                $postUpdate->ExeDelete($postAction);
                RMErro("O banner foi excluído com sucesso no sistema!", RM_ACCEPT);
                //RMErro($postUpdate->getError()[0], $postUpdate->getError()[1]);
            break;
            
            case 'Rascunho':
                $postUpdate->ExeStatus($postAction, '0');
                RMErro("O status do banner foi atualizado para <b>Rascunho</b>. O Banner agora é um rascunho!", RM_ALERT);                
            break;
            
            case 'Publicar':
                $postUpdate->ExeStatus($postAction, '1');
                RMErro("O status do banner foi atualizado para <b>Publicado</b>. O Banner publicada!", RM_ACCEPT);                
            break;
            
            default :
                RMErro("Ação não foi identificada pelo sistema, favor utilize os botões!", RM_ALERT);
        endswitch;
    endif;
    
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Pager = new Pager('painel.php?exe=banners/banners&page=');
    $Pager->ExePager($getPage, 15);
    
    $readPosts = new Read;
    $readPosts->ExeRead("banners","WHERE id ORDER BY status ASC, data DESC LIMIT :limit OFFSET :offset","limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if($readPosts->getResult()):  
    
?>
<table class="table  table-hover general-table">

<thead>
  <tr>
   <th>Imagem</th>
   <th>Título</th>
   <th style="text-align:center;">Criado em</th>
   <th style="text-align:center;">Link</th> 
   <th style="text-align:center;">Expira</th> 
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
    $linkstatus = (!$link ? '<a target="_blank" href="'.$link.'"><img src="images/ok.png" alt="Sim" title="Sim" /></a>' : '<img src="images/no.png" alt="Não" title="Não" />');
        
?>
<tr <?= $statusColor;?>>
<?php if($imagem == null): ?>
    <td style="text-align:center;"><img src="tim.php?src=admin/images/image.jpg&w=50&h=50&zc=1&q=100"/></td>
<?php else: ?>
    <td style="text-align:center;">
    <a href="../uploads/<?= $imagem;?>" title="<?= $titulo;?>" rel="ShadowBox">
    <?= Check::Image('../uploads/' . $imagem, $titulo, 50, 50); ?>
    </a>
    </td>
<?php endif; ?>

<td title="<?= $titulo;?>"><?= Check::Words($titulo,5);?></td>
<td style="text-align:center;"><?= date('d/m/Y', strtotime($data));?></td>
<td style="text-align:center;"><?= $linkstatus;?></td>

<td style="text-align:center;"><?= Check::getDataExpira('banners', $id, date('Y-m-d'), date('Y-m-d', strtotime($expira)));?></td>
<td>
<a href="painel.php?exe=banners/banners&post=<?= $id;?>&action=<?= $stSta;?><?= $varPage;?>" title="<?= $stSta;?>"><?= $statusPost;?></a>        
<a style="color: #333 !important;" class="btn btn-default btn-sm" title="Editar" href="painel.php?exe=banners/banners-edit&postid=<?= $id;?><?= $varPage;?>"><i class="fa fa-pencil"></i></a>
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
                        <small>Você tem certeza que deseja excluir este Banner?<br />
                        <strong>'.$titulo.'</strong></small>
                        </p>
                    </blockquote>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <a href="painel.php?exe=banners/banners&post='.$id.'&action=delete'.$varPage.'">
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
        RMErro("Desculpe, ainda não existem banners cadastrados!", RM_INFOR);  
    endif; 


// Chama o Paginator    
$Pager->ExePaginator("banners","WHERE id ORDER BY status ASC, data DESC");       	
?>
<div class="row-fluid">
    <div class="span6">
        <?php
            $readPostsCount = new Read;
            $readPostsCount->ExeRead("banners","WHERE id ORDER BY status ASC, data DESC");
	        if($readPostsCount->getResult()):              
         ?> 
         <div class="dataTables_info" id="dynamic-table_info">Exibindo <?= $Pager->getPage();?> de <?= $Pager->getTotal("banners");?> de <?= $readPostsCount->getRowCount();?> Banner(s)</div>
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