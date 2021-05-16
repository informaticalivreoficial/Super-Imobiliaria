<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
    
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);    
    // SE TIVER PAGINAÇÃO ENVIA O PAGE
    if($getPage): $varPage = '&page='.$getPage.''; else: $varPage = ''; endif;
?>
<div id="j_ajaxident" class="painel.php?exe=clientes/clientes&post="></div>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Gerenciar Clientes</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=clientes/clientes-create<?= $varPage;?>" title="Cadastrar Cliente" class="btn btn-default btn-lg" style="float:right;">Cadastrar Cliente</a>
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
        RMErro("Oppsss: Você tentou editar um cliente que não existe no sistema!", RM_INFOR);
    endif;
    
    $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
    if($action):
        require ('models/AdminClientes.class.php');        
        $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
        $postUpdate = new AdminClientes;            
        switch($action):
            case 'delete':
                $postUpdate->ExeDelete($postAction);
                RMErro($postUpdate->getError()[0], $postUpdate->getError()[1]);
            break;            
            default :
                RMErro("Ação não foi identificada pelo sistema, favor utilize os botões!", RM_ALERT);
        endswitch;
    endif;
    
    $posti = 0;
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Pager = new Pager('painel.php?exe=clientes/clientes&page=');
    $Pager->ExePager($getPage, 1);
    
    $readClientes = new Read;
    $readClientes->ExeRead("usuario","WHERE tipo = '2' ORDER BY status ASC, data DESC LIMIT :limit OFFSET :offset","limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if($readClientes->getResult()):		    
?>
<ul class="directory-list">
    <?php
    foreach(range( 'A', 'Z' ) as $letra):
        echo '<li><a class="j_alfabeto" data-id="'.$letra.'" href="#">'.$letra.'</a></li>';
    endforeach;        
    ?>        
</ul>    
    
<div class="resultado"></div>    
    
<table class="table  table-hover general-table hideR">
<thead>
  <tr>
   <th>Imagem:</th>
   <th>Nome</th>
   <th>E-mail</th>
   <th>Categoria</th>
   <th>Status</th>
   <th>Ações</th>
  </tr>
</thead>   
						  
<tbody>	
<?php
    foreach($readClientes->getResult() as $cliente):
    extract($cliente);    
    
    $status = ($status == '0' ? '<span class="label label-warning">Inativo</span>' : '<span class="label label-success">Ativo</span>');
?>
<tr>

<?php if($avatar == null): ?>
    <td style="text-align:center;"><img src="tim.php?src=admin/images/avatar.jpg&w=60&h=60&zc=1&q=100"/></td>
<?php else: ?>
    <td style="text-align:center;"><a href="../uploads/<?= $avatar;?>" rel="ShadowBox"><?= Check::Image('../uploads/' . $avatar, $nome, 50, 50); ?></a></td>
<?php endif; ?>
           
<td><?= $nome;?></td>
<td><?= $email;?></td>
<td><?= Check::Categoria("cat_clientes", $categoria, "nome");?></td>
<td><?= $status;?></td>           

<td>        
    <a style="color: #333 !important;" class="btn btn-default btn-sm" title="Editar" href="painel.php?exe=clientes/clientes-edit&postid=<?= $id;?>"><i class="fa fa-pencil"></i></a>
    <a class="btn btn-primary btn-sm" title="Enviar E-mail" href="painel.php?exe=newsletter/envia&email=<?= $email;?>"><i style="color:#fff;" class="fa fa-envelope-o"></i></a>
    <a title="Visualizar" href="painel.php?exe=clientes/clientes-perfil&postid=<?= $id;?>" class="btn btn-info btn-sm"><i class="fa fa-search"></i></a>    
    <a class="btn btn-danger btn-sm j_modal_btn" title="Excluir" data-campo="<?= $nome;?>" data-extra="" data-option="delete" data-id="<?= $id;?>"><i class="fa fa-times"></i></a>               
</td>
</tr>
<?php endforeach;?>  

</tbody>
</table>
    
<!-- MODAL PARA EXCLUIR CLIENTE -->
<div class="modal fade hideR" id="ModalRemove">
    <div class="modal-dialog">
        <div class="modal-content">				
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Remover Cliente!</h4>
            </div>
            <div class="modal-body">
                Você tem certeza que deseja excluir o cliente: <strong><span class="j_param_data"></span></strong>                
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
        RMErro("Desculpe, ainda não existem clientes cadastrados!", RM_INFOR);  
    endif;
    
    // Chama o Paginator    
    $Pager->ExePaginator("usuario","WHERE tipo = '2' ORDER BY status ASC, data DESC",'admin');
?>

<div class="row-fluid">
    <div class="span6">
        <?php
            $readPostsCount = new Read;
            $readPostsCount->ExeRead("usuario","WHERE tipo = '2' ORDER BY status ASC, data DESC");
	        if($readPostsCount->getResult()):              
         ?> 
         <div class="dataTables_info" id="dynamic-table_info">Exibindo <?= $Pager->getPage();?> página de <?= $Pager->getTotal("usuario");?> de <?= $readPostsCount->getRowCount();?> Cliente(s)</div>
         <?php     
            endif;
          ?>
        
    </div>
    <div class="span6">
        <div class="dataTables_paginate paging_bootstrap pagination">
<?= $Pager->getPaginator('admin');?>            
        </div>
    </div>
</div>

</div>
</div>
</section>
</div>
</div>
</div>