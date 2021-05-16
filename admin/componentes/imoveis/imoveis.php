<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
    
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);    
    // SE TIVER PAGINAÇÃO ENVIA O PAGE
    if($getPage): $varPage = '&page='.$getPage.''; else: $varPage = ''; endif;
?>
<div id="j_ajaxident" class="painel.php?exe=imoveis/imoveis&post="></div>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Gerenciar Imóveis</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=imoveis/imoveis-create<?= $varPage;?>" title="Cadastrar Imóvel" class="btn btn-default btn-lg" style="float:right;">Cadastrar Imóvel</a>
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
        RMErro("Oppsss: Você tentou editar um imóvel que não existe no sistema!", RM_INFOR);
    endif;
    
    $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
    if($action):
        require ('models/AdminImoveis.class.php');
        
        $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
        $postUpdate = new AdminImoveis;
            
        switch($action):
            case 'delete':
                $postUpdate->ExeDelete($postAction);
                RMErro("O imóvel foi excluído com sucesso no sistema!", RM_ACCEPT);
                //RMErro($postUpdate->getError()[0], $postUpdate->getError()[1]);
            break;
            
            case 'Rascunho':
                $postUpdate->ExeStatus($postAction, '0');
                RMErro("O status do imóvel foi atualizado para <b>Rascunho</b>. O imóvel agora é um rascunho!", RM_ALERT);                
            break;
            
            case 'Publicar':
                $postUpdate->ExeStatus($postAction, '1');
                RMErro("O status do imóvel foi atualizado para <b>Publicado</b>. O imóvel agora é publicado!", RM_ACCEPT);                
            break;
            
            default :
                RMErro("Ação não foi identificada pelo sistema, favor utilize os botões!", RM_ALERT);
        endswitch;
    endif;
    
    $posti = 0;
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Pager = new Pager('painel.php?exe=imoveis/imoveis&page=');
    $Pager->ExePager($getPage, 25);
    
    $readPosts = new Read;
    $readPosts->ExeRead("imoveis","WHERE id ORDER BY id DESC, referencia DESC, status ASC LIMIT :limit OFFSET :offset","limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if($readPosts->getResult()):  
    
?>
<table class="table  table-hover general-table">

<thead>
  <tr>
   <th>Capa</th>
   <th>Título</th>
   <th>Categoria</th>
   <th style="text-align:center;">Imagens</th> 
   <th style="text-align:center;">Views</th>
   <th style="text-align:center;">Valor</th>
   <th style="text-align:center;">Ref.</th>
   <th>Ações:</th>
  </tr>
</thead> 
						  
<tbody>
<?php
        foreach($readPosts->getResult() as $post):
        extract($post);
        
        $rCategoria = new Read;
        $rCategoria->ExeRead("cat_imoveis", "WHERE id = :id", "id={$categoria}");        
        if($rCategoria->getResult()):
            foreach($rCategoria->getResult() as $cat);
        endif;
        
        $statusPost = (!$status ? '<button type="button" class="btn btn-warning btn-sm"><i class="fa fa-warning"></i></button>' : '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>');
        $stSta = ($status == '0' ? 'Publicar' : 'Rascunho');
        $views = (!$visitas ? '0' : $visitas);
        $statusColor = (!$status ? 'style="background: #fffed8"' : '');
        
        $gb = new Read;
        $gb->ExeRead("imovel_gb","WHERE id_imovel = '$id'");
?>
<tr <?= $statusColor;?>>
<?php if($img == null): ?>
    <td style="text-align:center;"><img src="tim.php?src=admin/images/image.jpg&w=60&h=60&zc=1&q=100"/></td>
<?php else: ?>
    <td style="text-align:center;">
    <a href="../uploads/<?= $img;?>" title="<?= $nome;?>" rel="ShadowBox">
    <?= Check::Image('../uploads/' . $img, $nome, 49, 49); ?>
    </a>
    </td>
<?php endif; ?>

<td title="<?= $nome;?>"><?= Check::Words($nome,5);?></td>
<td style="text-align:center;"><?= $cat['nome'];?></td>
<td style="text-align:center;"><?= $gb->getRowCount();?></td>
<td style="text-align:center;"><?= $views;?></td>
<td style="color:#007d3d;text-align:center;"><strong><?= 'R$ '.number_format($valor, 0 , ',' , '.');?></strong></td>
<td style="text-align:center;"><?= $referencia;?></td>
<td>
<a href="painel.php?exe=imoveis/imoveis&post=<?= $id;?>&action=<?= $stSta;?><?= $varPage;?>" title="<?= $stSta;?>"><?= $statusPost;?></a>        
<a style="color: #333 !important;" class="btn btn-default btn-sm" title="Editar" href="painel.php?exe=imoveis/imoveis-edit&postid=<?= $id;?><?= $varPage;?>"><i class="fa fa-pencil"></i></a>
<a title="Visualizar" href="../imoveis/imovel/<?= $url;?>" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-search"></i></a>        
<a class="btn btn-danger btn-sm j_modal_btn" title="Excluir" data-campo="<?= $nome;?>" data-extra="" data-option="delete" data-id="<?= $id;?>"><i class="fa fa-times"></i></a>
</td>
</tr>
<?php endforeach;?>
</tbody>
</table>
    
<!-- MODAL PARA EXCLUIR IMÓVEL -->
<div class="modal fade hideR" id="ModalRemove">
    <div class="modal-dialog">
        <div class="modal-content">				
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Remover Imóvel!</h4>
            </div>
            <div class="modal-body">
                Você tem certeza que deseja excluir o imóvel: <strong><span class="j_param_data"></span></strong>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <a href="#" class="delete-yes">
                <button type="button" class="btn btn-danger">Excluir</button>
                </a>
            </div>
        </div>
    </div>
</div>     
<?php  
    else:
        $Pager->ReturnPage();
        RMErro("Desculpe, ainda não existem imóveis cadastrados!", RM_INFOR);  
    endif; 


// Chama o Paginator    
$Pager->ExePaginator("imoveis","WHERE id ORDER BY id DESC, referencia DESC, status ASC");       	
?>
<div class="row-fluid">
    <div class="span6">
        <?php
            $readPostsCount = new Read;
            $readPostsCount->ExeRead("imoveis","WHERE id ORDER BY id DESC, referencia DESC, status ASC");
	        if($readPostsCount->getResult()):              
         ?> 
         <div class="dataTables_info" id="dynamic-table_info">Exibindo página <?= $Pager->getPage();?> de <?= $Pager->getTotal("imoveis");?> de <?= $readPostsCount->getRowCount();?> Imóveis</div>
         <?php     
            endif;
          ?>
        
    </div>
    <div class="span6" style="text-align: right;">        
        <?= $Pager->getPaginator('admin');?> 
    </div>
</div>
</div>
</div>
</section>
</div>
</div>
</div>