<?php
$idFiltro = $_POST['id'];

if($idFiltro && !empty($idFiltro)):
    require('../../vendor/autoload.php');
    require('../../app/config.inc.php');
    
    $readAtletasFiltro = new Read;
    $readAtletasFiltro->ExeRead("usuario", "WHERE tipo = '2' AND nome LIKE '$idFiltro%'");
    if($readAtletasFiltro->getResult()):
?>
<div class="row hideR">
    <?php
    foreach($readAtletasFiltro->getResult() as $usuario):
    extract($usuario);

    $status = ($status == '0' ? '<span class="label label-warning">Inativo</span>' : '<span class="label label-success">Ativo</span>');
    ?>
    <div class="col-md-6 col-sm-6">
        <div class="panel">
            <div class="panel-body" style="min-height:290px;">
                <h4><?= $nome;?> <span class="text-muted small"> - <?= $status;?>
                    <a style="color: #333 !important;" class="btn btn-default btn-xs" title="Editar" href="painel.php?exe=atletas/atletas-edit&userid=<?= $id;?>"> <i class="fa fa-pencil"></i> </a>
                    <a style="color: #fff !important;" title="Visualizar" href="painel.php?exe=atletas/atletas-perfil&atletaId=<?= $id;?>" class="btn btn-info btn-xs"> <i class="fa fa-search"></i> </a>
                    <a style="color: #fff !important;" class="btn btn-danger btn-xs" title="Excluir" href="javascript:;" data-toggle="modal" data-target="#1<?= $id;?>"> <i class="fa fa-times"></i> </a>
                    <a style="color: #fff !important;" class="btn btn-primary btn-xs" title="Planilhas" href="painel.php?exe=atletas/planilhas&atletaId=<?= $id;?>"> Planilhas </a>
                    </span></h4>
                <div class="media">
                    <a class="pull-left" href="#">
                        <?php if($avatar == null): ?>
                            <img class="thumb media-object" src="<?= BASE;?>/tim.php?src=<?= BASE;?>/admin/images/avatar.jpg&w=103&h=103&zc=1&q=100"/>
                        <?php else: ?>
                            <?= Check::Image('../../uploads/' . $avatar, $nome, 103, 103); ?>
                        <?php endif; ?>
                    </a>
                    <div class="media-body">
                        <address>
                            <?php
                            $getAssinatura = new Read;
                            $getAssinatura->ExeRead("assinaturas","WHERE cli_id = :cliId","cliId={$id}");
                            if($getAssinatura->getResult()):
                                    $assinatura = $getAssinatura->getResult()['0'];
                            
                                    if(Check::getExpiraData($assinatura['expira']) <= '10' && Check::getExpiraData($assinatura['expira']) >= '1'):
                                        $statusExpira = '<span class="badge badge-warning">Expirando</span>';
                                    elseif(Check::getExpiraData($assinatura['expira']) >= '11'):
                                        $statusExpira = '<span class="badge badge-success">Normal</span>';
                                    else:
                                        $statusExpira = '<span class="badge badge-important">Expirado</span>';
                                    endif;
                                    
                                $getTreinamenro = new Read;
                                $getTreinamenro->ExeRead("treinamentos","WHERE id = :treinoId","treinoId={$assinatura['treinamento_id']}");
                                $treinamento = $getTreinamenro->getResult()['0'];                                            
                                    echo '<strong>Assinatura:</strong> '.$treinamento['titulo'].'<br>';
                                    echo '<strong>Iniciou em:</strong> '.date('d/m/Y', strtotime($assinatura['data'])).'<br>';
                                    echo '<strong>Período escolhido:</strong> '.Check::getPeriodo($assinatura['periodo']).'<br>';
                                    echo '<strong>Expira em:</strong> '.date('d/m/Y', strtotime($assinatura['expira'])).' - '.$statusExpira.'<br>';
                            else:
                                echo '<strong>Assinatura:</strong> Nenhuma <a title="Criar Assinatura" href="painel.php?exe=atletas/assinaturas-create&atleta='.$id.'">>> Clique para Criar <<</a><br>';
                            endif;
                            ?>
                            <?php if($email): echo '<strong>E-mail:</strong> '.$email.' <a title="Enviar E-mail" href="painel.php?exe=email/envia&email='.$email.'"> <i class="fa fa-mail-forward"></i> </a><br>'; endif;?>
                            <?php if($whatsapp): echo '<strong>WhatsApp:</strong> '.$whatsapp.' <a title="Enviar Mensagem" href="'.Check::getNumZap($whatsapp, Check::getSaudacao('Olá')).'"> <i class="fa fa-mail-forward"></i> </a>'; endif;?>                                    
                        </address>
                        <ul class="social-links">
                            <?php
                            if($facebook):
                                echo '<li><a title="Facebook" target="_blank" data-placement="top" data-toggle="tooltip" class="tooltips" href="'.$facebook.'" data-original-title="Facebook"><i class="fa fa-facebook"></i></a></li> ';
                            endif;
                            if($twitter):
                                echo '<li><a title="Twitter" target="_blank" data-placement="top" data-toggle="tooltip" class="tooltips" href="'.$twitter.'" data-original-title="Twitter"><i class="fa fa-twitter"></i></a></li> ';
                            endif;
                            if($instagram):
                                echo '<li><a title="Instagram" target="_blank" data-placement="top" data-toggle="tooltip" class="tooltips" href="'.$instagram.'" data-original-title="Instagram"><i class="fa fa-instagram"></i></a></li> ';
                            endif;
                            if($linkedin):
                                echo '<li><a title="LinkedIn" target="_blank" data-placement="top" data-toggle="tooltip" class="tooltips" href="'.$linkedin.'" data-original-title="LinkedIn"><i class="fa fa-linkedin"></i></a></li> ';
                            endif;
                            if($skype):
                                echo '<li><a title="Skype" target="_blank" data-placement="top" data-toggle="tooltip" class="tooltips" href="'.$skype.'" data-original-title="Skype"><i class="fa fa-skype"></i></a></li> ';
                            endif;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE EXCLUIR ATLETA -->
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
                            <small>Você tem certeza que deseja excluir este Atleta?<br />
                            <strong><?= $nome;?></strong></small>
                        </p>
                    </blockquote>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <a href="painel.php?exe=atletas/atletas&post=<?= $id;?>&action=delete">
                    <button type="button" class="btn btn-info">Confirmar</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php
    endforeach;
    ?>
</div>
<?php
    endif;
endif;




//echo "<option value=\"\" disabled selected> Selecione a cidade </option>";
//foreach ($readCityes->getResult() as $cidades):
//    extract($cidades);
//    echo "<option value=\"{$cidade_id}\"> {$cidade_nome} </option>";
//endforeach;

