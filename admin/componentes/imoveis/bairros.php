<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;   
    
    
    $post = filter_input_array(INPUT_POST, FILTER_DEFAULT); 
    $ufId = filter_input(INPUT_GET, 'uf_id', FILTER_VALIDATE_INT);
    $cidadeId = filter_input(INPUT_GET, 'cidade_id', FILTER_VALIDATE_INT);

    
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);    
    // SE TIVER PAGINAÇÃO ENVIA O PAGE
    if($getPage): $varPage = '&page='.$getPage.''; else: $varPage = ''; endif;
    
    if(!isset($post['cidade_id'])):
        $post['cidade_id'] = $cidadeId;
    endif;
    if(!isset($post['uf_id'])):
        $post['uf_id'] = $ufId;
    endif;
?>
<!-- page heading start-->
<div class="page-heading">
    <div class="row">
    <div class="col-sm-6">
        <h3>Gerenciar Bairros</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=imoveis/bairros-create&uf_id=<?= $post['uf_id'];?>&cidade_id=<?= $post['cidade_id'];?><?= $varPage;?>" title="Cadastrar Bairro" class="btn btn-default btn-lg" style="float:right;">Cadastrar Bairro</a>
    </div>
</div>
</div>

<!-- page heading end-->
<div class="wrapper">
<div class="row">
<div class="col-sm-12">
<section class="panel">

    
    
<div class="panel-body">
<div class="adv-table">   
    <form method="post" action="">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label><strong>UF</strong></label>
                                <select name="uf_id" class="form-control input-lg j_loadstate">
                                        <option selected> Selecione </option>
                                        <?php
                                        $readState = new Read;
                                        $readState->ExeRead("estados", "ORDER BY estado_nome ASC");
                                        foreach ($readState->getResult() as $estado):
                                            extract($estado);
                                            echo "<option value=\"{$estado_id}\" ";
                                            if (isset($post['uf_id']) && $post['uf_id'] == $estado_id || isset($ufId) && $ufId == $estado_id): 
                                                echo 'selected';
                                            endif;
                                            echo "> {$estado_nome} - {$estado_uf} </option>";
                                        endforeach;
                                        ?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label><strong>Cidade</strong></label>
                                <select class="form-control input-lg j_loadcity" name="cidade_id">
                                    <?php if (!isset($post['cidade_id'])): ?>
                                        <option selected disabled> Selecione antes um estado </option>
                                        <?php
                                    else:                                        
                                        $City = new Read;
                                        $City->ExeRead("cidades", "WHERE estado_id = :uf ORDER BY cidade_nome ASC", "uf={$post['uf_id']}");
                                        if ($City->getRowCount()):
                                            foreach ($City->getResult() as $cidade1):
                                                extract($cidade1);
                                                echo "<option value=\"{$cidade_id}\" ";
                                                if (isset($post['cidade_id']) && $post['cidade_id'] == $cidade_id || isset($cidadeId) && $cidadeId == $cidade_id):
                                                    echo "selected";
                                                endif;
                                                echo "> {$cidade_nome} </option>";
                                            endforeach;
                                        endif;
                                    endif;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                                <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostCidade" value="Gerenciar">Gerenciar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <?php
        $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
        if($action):
            require ('models/AdminBairros.class.php');

            $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
            $postUpdate = new AdminBairros;

            switch($action):
                case 'delete':
                    $postUpdate->ExeDelete($postAction);
                    RMErro("O Bairro foi excluído com sucesso no sistema!", RM_ACCEPT);
                break;

                case 'Rascunho':
                    $postUpdate->ExeStatus($postAction, '0');
                    RMErro("O status do bairro foi atualizado para <b>Rascunho</b>. O Bairro agora é um rascunho!", RM_ALERT);                
                break;

                case 'Publicar':
                    $postUpdate->ExeStatus($postAction, '1');
                    RMErro("O status do bairro foi atualizado para <b>Publicado</b>. O Bairro publicada!", RM_ACCEPT);                
                break;

                default :
                    RMErro("Ação não foi identificada pelo sistema, favor utilize os botões!", RM_ALERT);
            endswitch;
        endif;
    
    
    
        $Pager = new Pager('painel.php?exe=imoveis/bairros&uf_id='.$post['uf_id'].'&cidade_id='.$post['cidade_id'].'&page=');
        $Pager->ExePager($getPage, 30);
        $readBairros = new Read;
        $readBairros->ExeRead("bairros", "WHERE uf = :ufId AND cidade = :cidadeId ORDER BY status ASC, nome ASC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}&ufId={$post['uf_id']}&cidadeId={$post['cidade_id']}");
        if($readBairros->getResult()):            
    ?>
    <table class="table  table-hover general-table">
    <thead>
      <tr>
       <th>Capa</th>
       <th>Bairro</th>
       <th style="text-align: center;">Cep</th>
       <th style="text-align:center;">Imóveis</th> 
       <th>Ações:</th>
      </tr>
    </thead>
    <tbody>
        <?php
        foreach($readBairros->getResult() as $bairro):
            $statusPost = (!$bairro['status'] ? '<button type="button" class="btn btn-warning btn-sm"><i class="fa fa-warning"></i></button>' : '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>');
            $stSta = ($bairro['status'] == '0' ? 'Publicar' : 'Rascunho');
            $statusColor = (!$bairro['status'] ? 'style="background: #fffed8"' : '');
            
            $readImoveisCount = new Read;
            $readImoveisCount->ExeRead("imoveis", "WHERE bairro_id = :bairroId", "bairroId={$bairro['id']}");
        ?>
        <tr <?= $statusColor;?>>
            <?php if($bairro['img'] == null): ?>
                <td style="text-align:center;"><img src="tim.php?src=admin/images/image.jpg&w=60&h=60&zc=1&q=100"/></td>
            <?php else: ?>
                <td style="text-align:center;">
                <a href="../uploads/<?= $bairro['img'];?>" title="<?= $bairro['nome'];?>" rel="ShadowBox">
                <?= Check::Image('../uploads/' . $bairro['img'], $bairro['nome'], 49, 49); ?>
                </a>
                </td>
            <?php endif; ?>
            <td title="<?= $bairro['nome'];?>"><?= $bairro['nome'];?></td>
            <td style="text-align: center;"><?= $bairro['cep'];?></td>
            <td style="text-align: center;"><?= $readImoveisCount->getRowCount();?></td>
            <td>
                <a href="painel.php?exe=imoveis/bairros&uf_id=<?= $bairro['uf'];?>&cidade_id=<?= $bairro['cidade'];?>&post=<?= $bairro['id'];?>&action=<?= $stSta;?><?= $varPage;?>" title="<?= $stSta;?>"><?= $statusPost;?></a>        
                <a style="color: #333 !important;" class="btn btn-default btn-sm" title="Editar" href="painel.php?exe=imoveis/bairros-edit&uf_id=<?= $bairro['uf'];?>&cidade_id=<?= $bairro['cidade'];?>&postid=<?= $bairro['id'];?><?= $varPage;?>"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-danger btn-sm" title="Excluir" href="javascript:;" data-toggle="modal" data-target="#1<?= $bairro['id'];?>"><i class="fa fa-times"></i></a>
            </td>
        </tr>
        <?php        
        // MODAL DE EXCLUSÃO
        echo '<div class="modal fade" id="1'.$bairro['id'].'">
                        <div class="modal-dialog">
                                <div class="modal-content">				
                                        <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title"><strong>Alerta!</strong></h4>
                                        </div>              
                        <div class="modal-body">

                                <blockquote class="blockquote-red">			
                                <p>
                                        <small>Você tem certeza que deseja excluir este Bairro?<br />
                            <strong>'.$bairro['nome'].'</strong></small>
                                </p>
                                </blockquote>
                          </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <a href="painel.php?exe=imoveis/bairros&uf_id='.$post['uf_id'].'&cidade_id='.$post['cidade_id'].'&post='.$bairro['id'].'&action=delete'.$varPage.'">
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
            RMErro("Desculpe, ainda não existem bairros cadastrados!", RM_INFOR);
        endif;    
        // Chama o Paginator    
        $Pager->ExePaginator("bairros","WHERE uf = :ufId AND cidade = :cidadeId ORDER BY nome ASC","&ufId={$post['uf_id']}&cidadeId={$post['cidade_id']}");
        
    ?>
    <div class="row-fluid">
        <div class="span6">
            <?php
                $readPostsCount = new Read;
                $readPostsCount->ExeRead("bairros","WHERE uf = :ufId AND cidade = :cidadeId ORDER BY nome ASC","&ufId={$post['uf_id']}&cidadeId={$post['cidade_id']}");
                    if($readPostsCount->getResult()):              
             ?> 
             <div class="dataTables_info" id="dynamic-table_info">Exibindo <?= $Pager->getPage();?> de <?= $Pager->getTotal("bairros");?> de <?= $readPostsCount->getRowCount();?> Bairro(s)</div>
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