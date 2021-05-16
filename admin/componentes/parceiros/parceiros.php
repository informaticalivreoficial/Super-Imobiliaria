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
        <h3>Gerenciar Parceiros</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=parceiros/parceiros-create<?= $varPage;?>" title="Cadastrar Parceiro" class="btn btn-default btn-lg" style="float:right;">Cadastrar Parceiro</a>
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
        RMErro("Oppsss: Você tentou editar um parceiro que não existe no sistema!", RM_INFOR);
    endif;
    
    $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
    if($action):
        require ('models/AdminParceiros.class.php');
        
        $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
        $postUpdate = new AdminParceiros;
            
        switch($action):
            case 'delete':
                $postUpdate->ExeDelete($postAction);
                RMErro("O parceiro foi excluído com sucesso no sistema!", RM_ACCEPT);
                //RMErro($postUpdate->getError()[0], $postUpdate->getError()[1]);
            break;
            
            case 'Rascunho':
                $postUpdate->ExeStatus($postAction, '0');
                RMErro("O status do parceiro foi atualizado para <b>Rascunho</b>. O parceiro agora é um rascunho!", RM_ALERT);                
            break;
            
            case 'Publicar':
                $postUpdate->ExeStatus($postAction, '1');
                RMErro("O status do parceiro foi atualizado para <b>Publicado</b>. O parceiro agora é publicado!", RM_ACCEPT);                
            break;
            
            default :
                RMErro("Ação não foi identificada pelo sistema, favor utilize os botões!", RM_ALERT);
        endswitch;
    endif;
    
    $posti = 0;
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Pager = new Pager('painel.php?exe=parceiros/parceiros&page=');
    $Pager->ExePager($getPage, 25);
    
    $readPosts = new Read;
    $readPosts->ExeRead("parceiros","WHERE id ORDER BY status ASC, data DESC LIMIT :limit OFFSET :offset","limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if($readPosts->getResult()):  
    
?>
<table class="table  table-hover general-table">

<thead>
  <tr>
   <th>Imagem</th>
   <th>Nome</th>
   <th>E-mail</th>
   <th>Visitas</th> 
   <th>Ações:</th>
  </tr>
</thead> 
						  
<tbody>
<?php
        foreach($readPosts->getResult() as $post):
        extract($post);
        
        $statusPost = (!$status ? '<button type="button" class="btn btn-warning btn-sm"><i class="fa fa-warning"></i></button>' : '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>');
        $stSta = ($status == '0' ? 'Publicar' : 'Rascunho');
        $views = (!$visitas ? '0' : $visitas);
        $statusColor = (!$status ? 'style="background: #fffed8"' : '');
        
        
?>
<tr <?= $statusColor;?>>
<?php if($img == null): ?>
    <td><img src="tim.php?src=admin/images/image.jpg&w=60&h=60&zc=1&q=100"/></td>
<?php else: ?>
    <td>
    <a href="../uploads/<?= $img;?>" title="<?= $nome;?>" rel="ShadowBox">
    <?= Check::Image('../uploads/' . $img, $nome, 60, 60); ?>
    </a>
    </td>
<?php endif; ?>

<td title="<?= $nome;?>"><?= Check::Words($nome,6);?></td>
<td><?= $email;?></td>
<td><?= $views;?></td>
<td>
<a href="painel.php?exe=parceiros/parceiros&post=<?= $id;?>&action=<?= $stSta;?><?= $varPage;?>" title="<?= $stSta;?>"><?= $statusPost;?></a>        
<a style="color: #333 !important;" class="btn btn-default btn-sm" title="Editar" href="painel.php?exe=parceiros/parceiros-edit&postid=<?= $id;?><?= $varPage;?>"><i class="fa fa-pencil"></i></a>
<a title="Visualizar" href="javascript:;" data-toggle="modal" data-target="#2<?= $id;?>" class="btn btn-info btn-sm"><i class="fa fa-search"></i></a>        
<a class="btn btn-danger btn-sm" title="Excluir" href="javascript:;" data-toggle="modal" data-target="#1<?= $id;?>"><i class="fa fa-times"></i></a>
</td>
</tr>
<!-- MODAL EXCLUIR -->
<div class="modal fade" id="1<?= $id;?>">
    <div class="modal-dialog">
        <div class="modal-content">				
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><strong>Alerta!</strong></h4>
            </div>              
            <div class="modal-body">
                <blockquote class="blockquote-red">			
                <p>
                <small>Você tem certeza que deseja excluir este Parceiro?<br />
                <strong><?= $nome;?></strong></small>
                </p>
                </blockquote>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <a href="painel.php?exe=parceiros/parceiros&post=<?= $id;?>&action=delete<?= $varPage;?>">
                <button type="button" class="btn btn-info">Confirmar</button>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MODAL VISUALIZA -->
<div class="modal fade" id="2<?= $id;?>">
    <div class="modal-dialog">
        <div class="modal-content">				
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><strong><?= $nome;?></strong></h4>
            </div>              
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <div style="margin-top: 10px;" class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 100%; height: 100px;">
                                <?php if($img == null): ?>
                                    <img src="admin/images/image.jpg"/></td>
                                <?php else: ?>
                                    <?= Check::Image('../uploads/' . $img, $nome, 300, 85); ?>                                    
                                <?php endif; ?>                                        
                            </div>                            
                        </div>
                    </div>

                    <div class="col-md-6 form-group">

                        <div class="row">                            
                            <div class="col-md-12 form-group">
                                <label for="exampleInputEmail1"><strong>E-mail:</strong></label>
                                <br /><?= $email;?> <a title="Enviar E-mail" href="painel.php?exe=email/envia&email=<?= $email;?>"><i class="fa fa-mail-forward"></i></a>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="exampleInputEmail1"><strong>Data do Cadastro:</strong></label>
                                <br /><?= date('d/m/Y', strtotime($data));?>
                            </div>                            
                        </div>
                       
                    </div>
                </div>
                <hr>
                <div class="row">                            
                    <div class="col-md-3 form-group">
                        <label for="exampleInputEmail1"><strong>Telefone:</strong></label>
                        <br /><?= $telefone;?>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="exampleInputEmail1"><strong>WhatsApp:</strong></label>
                        <br /><a target="_blank" href="<?= Check::getNumZap($whatsapp, 'Atendimento SportPlan');?>"><?= $whatsapp;?></a>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="exampleInputEmail1"><strong>Website:</strong></label>
                        <br /><a target="_blank" href="<?= $site;?>"><?= $site;?></a>
                    </div>
                </div>
                <div class="row">                            
                    <div class="col-md-12 form-group">
                        <label for="exampleInputEmail1"><strong>Descrição:</strong></label>
                        <br /><?= $content;?>
                    </div>                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>                
            </div>
        </div>
    </div>
</div>

<?php endforeach; ?>
</tbody>
</table>
<?php  
    else:
        $Pager->ReturnPage();
        RMErro("Desculpe, ainda não existem parceiros cadastrados!", RM_INFOR);  
    endif; 


// Chama o Paginator    
$Pager->ExePaginator("parceiros","WHERE id ORDER BY status ASC");       	
?>
<div class="row-fluid">
    <div class="span6">
        <?php
            $readPostsCount = new Read;
            $readPostsCount->ExeRead("parceiros","WHERE id ORDER BY status ASC");
	        if($readPostsCount->getResult()):              
         ?> 
         <div class="dataTables_info" id="dynamic-table_info">Exibindo <?= $Pager->getPage();?> de <?= $Pager->getTotal("parceiros");?> de <?= $readPostsCount->getRowCount();?> Parceiro(s)</div>
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
