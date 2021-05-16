<?php
	if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
?>
<div class="page-heading">

<div class="row">
    <div class="col-sm-6">
        <h3>Gerenciar Links</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=menu/menu-create" title="Cadastrar Link" class="btn btn-default btn-lg" style="float:right;">Cadastrar Link</a>
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
  $empty = filter_input_array(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
  if($empty):
    RMErro("Você tentou editar um Link que não existe no sistema!", RM_INFOR);
  endif;
   
   $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
   if($action):
        require('./models/AdminMenu.class.php');
        
        $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
        $postUpdate = new AdminMenu;
        
        switch($action):
        case 'dellink':
            $postUpdate->ExeDelete($postAction);
            RMErro($postUpdate->getError()[0], $postUpdate->getError()[1]);
        break;
        
        case 'inativar':
            $postUpdate->ExeStatus($postAction, '0');
            RMErro("O status do Link foi atualizado para <b>Inativo</b>!", RM_ALERT);                
        break;
        
        case 'ativar':
            $postUpdate->ExeStatus($postAction, '1');
            RMErro("O status do Link foi atualizado para <b>Ativo</b>!", RM_ACCEPT);                
        break;
        
        default :
            RMErro("Ação não foi identificada pelo sistema, favor utilize os botões!", RM_ALERT);
    endswitch;
        
   endif;
  
  
  $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
  $Pager = new Pager('painel.php?exe=menu/menu&page=');
  $Pager->ExePager($getPage, 8);
  
  $readMenu = new Read;
  $readMenu->ExeRead("menu_topo","WHERE id_pai IS NULL ORDER BY nome ASC LIMIT :limit OFFSET :offset","limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
  if(!$readMenu->getResult()):
    RMErro("<strong>Atenção!</strong> Não existem Links cadastrados ainda!", RM_INFOR);    
  else:
?>
<table class="display table table-bordered table-striped" >

<thead>
<tr>
<th>Título</th>                
<th>Link</th> 
<th>Data</th>       
<th>Ações</th>
</tr>
</thead> 

<tbody>
<?php 
  foreach($readMenu->getResult() as $Link):
  extract($Link); 
  
  $stIco1 = ($status == '0' ? '<button type="button" class="btn btn-warning btn-sm"><i class="fa fa-warning"></i></button>' : '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>');
  $stSta1 = ($status == '0' ? 'ativar' : 'inativar');
?>
<tr>
<td><img src="images/seta.png"/> <strong><?= $nome;?></strong></td>
<?php  
  if($url == '' && $link != ''):
    echo '<td align="center"><a target="_blank" href="'.BASE.'/'.$link.'" title="'.$link.'">'.substr($link,0,20).'</a></td>';
  elseif($url != '' && $link == ''):
    echo '<td align="center"><a target="_blank" href="'.$url.'" title="'.$url.'">'.substr($url,0,20).'</a></td>';
  else:
    echo '<td align="center">-------------</td>';
  endif;
?>
<td align="center"><?= date('d/m/Y',strtotime($data));?></td>
<td>  
<a  href="painel.php?exe=menu/menu&post=<?= $id;?>&action=<?= $stSta1;?>" title="<?= $stSta1;?>"><?= $stIco1;?></a>                  
<a class="btn btn-default btn-sm" href="painel.php?exe=menu/menu-edit&linkid=<?= $id;?>" title="Editar Link <?= $nome;?>"><i class="fa fa-pencil"></i></a>
<a title="Criar Sub-link para <?= $nome;?>" class="btn btn-success btn-sm" href="painel.php?exe=menu/menu-create&idpai=<?= $id;?>"><i class="icon-file icon-white"></i>&nbsp;Criar Sub-Link</a>            
<a title="Excluir Link <?= $nome;?>" class="btn btn-danger btn-sm" href="javascript:;" data-toggle="modal" data-target="#2<?= $id;?>"><i class="fa fa-times"></i></a>     
</td>
</tr>

<!-- Modal de exclusão do Link --> 
<div class="modal fade" id="2<?= $id;?>">
	<div class="modal-dialog">
		<div class="modal-content">				
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><strong>Alerta!</strong></h4>
			</div>              
            <div class="modal-body">
				
  			<blockquote class="blockquote-red">			
			<p>
				<small>Você tem certeza que deseja excluir este Link?<br />
                Link <strong><?= $nome;?></strong></small>
			</p>
	        </blockquote>
              </div>
    		<div class="modal-footer">
    			<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <a href="painel.php?exe=menu/menu&post=<?= $id;?>&action=dellink">
    			<button type="button" class="btn btn-info">Confirmar</button>
                </a>
    		</div>
	 </div>
  </div>
</div>

<?php
  $readSubMenu = new Read;
  $readSubMenu->ExeRead("menu_topo","WHERE id_pai = :idpai ORDER BY nome ASC","idpai={$id}");
  if($readSubMenu->getResult()):
    foreach($readSubMenu->getResult() as $catSubLink):
    
    $stIco = ($catSubLink['status'] == '0' ? '<button type="button" class="btn btn-warning btn-sm"><i class="fa fa-warning"></i></button>' : '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>');
    $stSta = ($catSubLink['status'] == '0' ? 'ativar' : 'inativar');
?>
<tr>
<td><img src="images/setaseta.png"/> <?= $catSubLink['nome'];?></td>
<?php  
  if($catSubLink['url'] == '' && $catSubLink['link'] != ''):
    echo '<td align="center"><a target="_blank" href="'.BASE.'/'.$catSubLink['link'].'" title="'.$catSubLink['link'].'">'.substr($catSubLink['link'],0,20).'</a></td>';
  elseif($catSubLink['url'] != '' && $catSubLink['link'] == ''):
    echo '<td align="center"><a target="_blank" href="'.$catSubLink['url'].'" title="'.$catSubLink['url'].'">'.substr($catSubLink['url'],0,20).'</a></td>';
  else:
    echo '<td align="center">-------------</td>';
  endif;
?>
<td align="center"><?= date('d/m/Y',strtotime($catSubLink['data']));?></td>
<td>                  
<a  href="painel.php?exe=menu/menu&post=<?= $catSubLink['id'];?>&action=<?= $stSta;?>" title="<?= $stSta;?>"><?= $stIco;?></a>
<a class="btn btn-default btn-sm" href="painel.php?exe=menu/menu-edit&linkid=<?= $id;?>&linksubid=<?= $catSubLink['id'];?>" title="Editar Sub-link <?= $catSubLink['nome'];?>"><i class="fa fa-pencil"></i></a>
<a title="Excluir Sub-link <?= $catSubLink['nome'];?>" class="btn btn-danger btn-sm"  href="javascript:;" data-toggle="modal" data-target="#1<?= $catSubLink['id'];?>"><i class="fa fa-times"></i></a>
</td>
</tr>

<!-- Modal de exclusão do SubLink -->
<div class="modal fade" id="1<?= $catSubLink['id'];?>">
	<div class="modal-dialog">
		<div class="modal-content">				
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><strong>Alerta!</strong></h4>
			</div>              
            <div class="modal-body">
				
  			<blockquote class="blockquote-red">			
			<p>
				<small>Você tem certeza que deseja excluir este Sub-Link?<br />
                Sub-Link <strong><?= $catSubLink['nome'];?></strong></small>
			</p>
	        </blockquote>
              </div>
    		<div class="modal-footer">
    			<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <a href="painel.php?exe=menu/menu&post=<?= $catSubLink['id'];?>&action=dellink">
    			<button type="button" class="btn btn-info">Confirmar</button>
                </a>
    		</div>
	 </div>
  </div>
</div>
<?php    
    endforeach; 
  endif;
?>

<?php   
  endforeach;  
  endif;
?>  
       
            
</tbody>
</table>      

<?php      
// Chama o Paginator    
$Pager->ExePaginator("menu_topo","WHERE id_pai IS NULL ORDER BY nome ASC");       	
?>
<div class="row-fluid">
    <div class="span6">
        <?php
            $readPostsCount = new Read;
            $readPostsCount->ExeRead("menu_topo","WHERE id_pai IS NULL");
	        if($readPostsCount->getResult()):              
         ?> 
         <div class="dataTables_info" id="dynamic-table_info">Exibindo <?= $Pager->getPage();?> de <?= $Pager->getTotal("menu_topo");?> de <?= $readPostsCount->getRowCount();?> Link(s)</div>
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
