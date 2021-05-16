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
        <h3>Editar Imóvel</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=imoveis/imoveis<?= $varPage;?>" title="Voltar e listar os Imóveis" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e listar os Imóveis</a>	
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
    
    $post['img'] = ($_FILES['img']['tmp_name'] ? $_FILES['img'] : 'null');
    unset($post['SendPostForm']);
    
    // PEGA OS VALORES DE CHECKBOX
    if(isset($post['acbanco'])):
        $Acessorio = $post['acbanco'];
        $opcoes_text = implode(", ", $Acessorio);            
        $post['acessorios'] = $opcoes_text;
        unset($post['acbanco']);
    endif;
    
    require('models/AdminImoveis.class.php');
    $cadastra = new AdminImoveis;
    $cadastra->ExeUpdate($postId, $post);

    RMErro("O Imóvel <b>{$post['nome']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT);
    
    if (!empty($_FILES['gallery_covers']['tmp_name'])):
        $sendGallery = new AdminImoveis;
        $sendGallery->gbSend($_FILES['gallery_covers'], $postId, 'imoveis/imagens');        
    endif;
    
    else: 
        $read = new Read;
        $read->ExeRead("imoveis","WHERE id = :id","id={$postId}");
        if(!$read->getResult()):
            header('Location: painel.php?exe=imoveis/imoveis&imoveis-create=true');
        else:
            $post = $read->getResult()[0];            
            $post['data'] = date('d/m/Y', strtotime($post['data']));
            $post['expira'] = date('d/m/Y', strtotime($post['expira']));
        endif;
    endif;
    
    if(!empty($_SESSION['errCapa'])):
        RMErro($_SESSION['errCapa'], E_USER_WARNING);
        unset($_SESSION['errCapa']);
    endif;
    
    $checkCreate = filter_input(INPUT_GET, 'imoveis-create', FILTER_VALIDATE_BOOLEAN);
    if ($checkCreate && empty($cadastra)):
        RMErro("O Imóvel <b>{$post['nome']}</b> foi cadastrado com sucesso no sistema!", RM_ACCEPT);
    endif;
?>

<form method="post" action="" enctype="multipart/form-data">

<div class="row">
    <div class="col-md-12">

        <div class="panel">                    

            <div class="panel-body">
            
            <div class="row">                               
                <div class="col-md-6 form-group">
                    <label><strong>Título do Imóvel</strong></label>
                    <input name="data" type="hidden" value="<?php if(isset($post['data'])): echo $post['data']; else: echo date('d/m/Y'); endif;?>"/>
                    <input type="text" class="form-control input-lg" name="nome" value="<?php if(isset($post['nome'])) echo $post['nome'];?>" />
                </div> 
                <div class="col-md-3 form-group">
                    <label><strong>Vende ou Aluga?</strong></label>
                    <select name="tipo" class="form-control input-lg m-bot15">
                        <option value=""> Selecione </option>
                        <option <?php if(isset($post['tipo']) && $post['tipo'] == '1') echo 'selected="selected"';?>  value="1">Aluga</option>
                        <option <?php if(!isset($post['tipo']) || $post['tipo'] == '0') echo 'selected="selected"';?>  value="0">Vende</option>	
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label><strong>Status</strong></label>
                    <select name="status" class="form-control input-lg m-bot15">
                        <option value=""> Selecione </option>
                        <option <?php if(isset($post['status']) && $post['status'] == '1') echo 'selected="selected"';?>  value="1">Publicado</option>
                        <option <?php if(!isset($post['status']) || $post['status'] == '0') echo 'selected="selected"';?>  value="0">Rascunho</option>	
                    </select>
                </div>
            </div>
            
            <hr>    
            
            <div class="row">
                <div class="col-md-12"> 
                    <label><strong>Acessórios</strong></label>
                    <div class="form-group">
                        <?php
                            $readAcessorios = new Read;
                            $readAcessorios->ExeRead("imoveis_atributos", "WHERE status = '1'");
                            if($readAcessorios->getResult()): 
                                
                                echo '<div class="col-sm-3 icheck">';
                                $i = 0; 
                                foreach($readAcessorios->getResult() as $acessorio):
                                
                                //Fechar div e abrir nova a cada 4 itens
                                if($i % 4 == 0 && $i != 0):            
                                    echo '</div><div class="col-sm-3 icheck">';               
                                endif;
                            ?>                        
                        
                            <div class="minimal-green single-row">
                                <div class="checkbox ">
                                    <input tabindex="<?= $acessorio['id'];?>" type="checkbox" name="acbanco[]" value="<?= $acessorio['id'];?>"
                                    <?php
                                        if(isset($post['acessorios'])):
                                            $checkExplode = $post['acessorios'];
                                            $checkValor = explode(',',$checkExplode);
                                            foreach($checkValor as $valorC):
                                                if($valorC == $acessorio['id']): echo 'checked'; endif;
                                            endforeach;
                                        endif;                                         
                                    ?>
                                    >       
                                    <label><?= $acessorio['nome'];?> </label>
                                </div>
                            </div>
                            <?php    
                                $i++;
                                endforeach; 
                                echo '</div>';
                            endif;
                            ?>                   
                    </div>                    
                </div>                
            </div>
            
            <hr>            
             
            <div class="row">
                <div class="col-md-6 form-group">
                        <div class="col-md-12 form-group">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 100%; height: 350px;">
                                <?php 
                                    $read1 = new Read;
                                    $read1->ExeRead("imoveis","WHERE id = :id","id={$postId}");
                                    if(!$read1->getResult()):
                                      echo '<img src="images/image.jpg">';  
                                    else:

                                     foreach($read1->getResult() as $capa1);
                                     if($capa1['img'] == ''):
                                        echo '<img src="images/image.jpg">';
                                     else:                                                
                                        echo '<img src="../uploads/'.$capa1['img'].'" />';
                                     endif; 

                                    endif;                    
                                ?>                                        
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; height: 350px; line-height: 20px;"></div>
                            <div>
                                <span class="btn btn-default btn-file">
                                <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                                <input type="file" name="img" class="default" value="" />
                                </span>
                                <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                            </div>
                        </div>
                        </div>
                </div>

                <div class="col-md-6 form-group"> 
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label><strong>Referência</strong></label>
                            <input type="text" class="form-control input-lg" name="referencia" value="<?php if(isset($post['referencia'])) echo $post['referencia'];?>" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label><strong>Data de Expiração</strong></label>
                            <input class="form-control form-control-inline input-lg default-date-picker" name="expira" size="16" type="text" value="<?php if(isset($post['expira'])): echo $post['expira']; else: echo date('d/m/Y'); endif;?>"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label><strong>Categoria</strong></label>
                            <select name="categoria" class="form-control input-lg m-bot15">
                                <option value=""> Selecione a Categoria </option>
                                <?php
                                    $readCatPai = new Read;
                                    $readCatPai->ExeRead("cat_imoveis","WHERE tipo = 'imovel' AND id_pai IS NULL ORDER BY nome ASC");
                                    if($readCatPai->getRowCount() >= 1):
                                        foreach($readCatPai->getResult() as $catpai):
                                        echo "<option value=\"\" disabled=\"disabled\">{$catpai['nome']}</option>";
                                            $readCat = new Read;
                                            $readCat->ExeRead("cat_imoveis","WHERE tipo = 'imovel' AND id_pai = :catpai ORDER BY nome ASC","catpai={$catpai['id']}");
                                            if($readCat->getRowCount() >= 1):
                                             foreach($readCat->getResult() as $cat):
                                             echo "<option ";

                                             if($post['categoria'] == $cat['id']):
                                             echo "selected=\"selected\" ";
                                             endif;

                                             echo "value=\"{$cat['id']}\">&raquo;&raquo; {$cat['nome']}</option>";
                                             endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                ?>	
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label><strong>Valor</strong></label>
                            <input type="text" class="form-control input-lg" name="valor" placeholder="Ex: 29,90" value="<?php if(isset($post['valor'])) echo str_replace('.', ',',$post['valor']);?>" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label><strong>Valor IPTU</strong></label>
                            <input type="text" class="form-control input-lg" name="valor_iptu" placeholder="Ex: 29,90" value="<?php if(isset($post['valor_iptu'])) echo str_replace('.', ',',$post['valor_iptu']);?>" />
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label><strong>Valor Condomínio</strong></label>
                            <input type="text" class="form-control input-lg" name="valor_condominio" placeholder="Ex: 29,90" value="<?php if(isset($post['valor_condominio'])) echo str_replace('.', ',',$post['valor_condominio']);?>" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label><strong>Exibir Valores?</strong></label>
                            <select name="exibirvalores" class="form-control input-lg m-bot15">
                                <option value=""> Selecione </option>
                                <option <?php if(isset($post['exibirvalores']) && $post['exibirvalores'] == '1') echo 'selected="selected"';?>  value="1">Sim</option>
                                <option <?php if(!isset($post['exibirvalores']) || $post['exibirvalores'] == '0') echo 'selected="selected"';?>  value="0">Não</option>	
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label><strong>Marca D'agua?</strong></label>
                            <select name="marcadagua" class="form-control input-lg m-bot15">
                                <option value=""> Selecione </option>
                                <option <?php if(isset($post['marcadagua']) && $post['marcadagua'] == '1') echo 'selected="selected"';?>  value="1">Sim</option>
                                <option <?php if(!isset($post['marcadagua']) || $post['marcadagua'] == '0') echo 'selected="selected"';?>  value="0">Não</option>	
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label><strong>Legenda da Imagem</strong></label>
                            <input type="text" class="form-control input-lg" name="legendaimagem" value="<?php if(isset($post['legendaimagem'])) echo $post['legendaimagem'];?>" />
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-md-12 form-group">
                    <label><strong>Mapa do Google <a href="javascript:;" data-toggle="modal" data-target="#1"><i style="color: blue;" class="fa fa-info-circle"></i></a></strong></label>                                    
                    <textarea class="form-control" rows="6" name="mapa" ><?php if(isset($post['mapa'])) echo htmlspecialchars(stripslashes($post['mapa']));?></textarea>                     
                </div>                
            </div>
            
            <div class="row">
                <div class="col-md-2 form-group">
                    <label><strong>Área Construída</strong></label>
                    <input type="text" class="form-control input-lg" name="areaconstruida" value="<?php if(isset($post['areaconstruida'])) echo $post['areaconstruida'];?>" />
                </div>
                <div class="col-md-2 form-group">
                    <label><strong>Área Total</strong></label>
                    <input type="text" class="form-control input-lg" name="areatotal" value="<?php if(isset($post['areatotal'])) echo $post['areatotal'];?>" />
                </div>
                <div class="col-md-2 form-group">
                    <label><strong>Medidas</strong></label>
                    <select name="medidas" class="form-control input-lg m-bot15">
                        <option value=""> Selecione </option>
                        <option <?php if(isset($post['medidas']) && $post['medidas'] == '1') echo 'selected="selected"';?>  value="1">m²</option>
                        <option <?php if(!isset($post['medidas']) || $post['medidas'] == '0') echo 'selected="selected"';?>  value="0">km²</option>
                        <option <?php if(!isset($post['medidas']) || $post['medidas'] == '2') echo 'selected="selected"';?>  value="2">hectare</option>
                        <option <?php if(!isset($post['medidas']) || $post['medidas'] == '3') echo 'selected="selected"';?>  value="3">alqueire</option>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label><strong>Dormitórios</strong></label>
                    <input type="text" class="form-control input-lg" name="dormitorios" value="<?php if(isset($post['dormitorios'])) echo $post['dormitorios'];?>" />
                </div>
                <div class="col-md-2 form-group">
                    <label><strong>Banheiros</strong></label>
                    <input type="text" class="form-control input-lg" name="banheiro" value="<?php if(isset($post['banheiro'])) echo $post['banheiro'];?>" />
                </div>
                <div class="col-md-2 form-group">
                    <label><strong>Suítes</strong></label>
                    <input type="text" class="form-control input-lg" name="suites" value="<?php if(isset($post['suites'])) echo $post['suites'];?>" />
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 form-group">
                    <label><strong>Ano de Construção</strong></label>
                    <input type="text" class="form-control input-lg" name="anodeconstrucao" value="<?php if(isset($post['anodeconstrucao'])) echo $post['anodeconstrucao'];?>" />
                </div>
                <div class="col-md-2 form-group">
                    <label><strong>Garagem</strong></label>
                    <select name="garagem" class="form-control input-lg m-bot15">
                        <option value=""> Selecione </option>
                        <option <?php if(isset($post['garagem']) && $post['garagem'] == '1') echo 'selected="selected"';?>  value="1">Sim</option>
                        <option <?php if(!isset($post['garagem']) || $post['garagem'] == '0') echo 'selected="selected"';?>  value="0">Não</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label><strong>Vagas na Garagem</strong></label>
                    <input type="text" class="form-control input-lg" name="vagas_garagem" value="<?php if(isset($post['vagas_garagem'])) echo $post['vagas_garagem'];?>" />
                </div>
                <div class="col-md-2 form-group">
                    <label><strong>Latitude</strong></label>
                    <input type="text" class="form-control input-lg" name="latitude" value="<?php if(isset($post['latitude'])) echo $post['latitude'];?>" />
                </div>
                <div class="col-md-2 form-group">
                    <label><strong>Longitude</strong></label>
                    <input type="text" class="form-control input-lg" name="longitude" value="<?php if(isset($post['longitude'])) echo $post['longitude'];?>" />
                </div>
            </div>
            
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
                                if (isset($post['uf_id']) && $post['uf_id'] == $estado_id): echo 'selected';
                                endif;
                                echo "> {$estado_uf} </option>";
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
                                    if (isset($post['cidade_id']) && $post['cidade_id'] == $cidade_id):
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
                    <label><strong>Bairro</strong></label>
                    <select class="form-control input-lg j_loadbairros" name="bairro_id">
                        <?php if (!isset($post['bairro_id'])): ?>
                            <option selected disabled> Selecione antes uma Cidade </option>
                            <?php
                        else:
                            $readBairros = new Read;
                            $readBairros->ExeRead("bairros", "WHERE status = '1' AND cidade = :cidadeId ORDER BY nome ASC", "cidadeId={$post['cidade_id']}");
                            if ($readBairros->getRowCount()):
                                foreach ($readBairros->getResult() as $bairros):
                                    echo "<option value=\"{$bairros['id']}\" ";
                                    if (isset($post['bairro_id']) && $post['bairro_id'] == $bairros['id']):
                                        echo "selected";
                                    endif;
                                    echo "> {$bairros['nome']} </option>";
                                endforeach;
                            endif;
                        endif;
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 form-group">
                    <label><strong>Rua</strong></label>
                    <input type="text" class="form-control input-lg" name="rua" value="<?php if(isset($post['rua'])) echo $post['rua'];?>" />
                </div>
                <div class="col-md-2 form-group">
                    <label><strong>Número</strong></label>
                    <input type="text" class="form-control input-lg" name="num" value="<?php if(isset($post['num'])) echo $post['num'];?>" />
                </div>
                <div class="col-md-2 form-group">
                    <label><strong>Complemento</strong></label>
                    <input type="text" class="form-control input-lg" name="complemento" value="<?php if(isset($post['complemento'])) echo $post['complemento'];?>" />
                </div>
                <div class="col-md-2 form-group">
                    <label><strong>CEP</strong></label>
                    <input type="text" class="form-control input-lg" name="cep" data-mask="99.999-999" value="<?php if(isset($post['cep'])) echo $post['cep'];?>" />
                </div>
            </div>
             
            <div class="row">
                <div class="col-md-12 form-group">
                    <label><strong>Meta Tags</strong></label>                                    
                    <input id="tags_1" type="text" class="tags" name="tags" value="<?php if(isset($post['tags'])) echo $post['tags'];?>" />                      
                </div>
                <div class="col-md-12 form-group">
                    <textarea class="form-control editor" rows="6" name="descricao" ><?php if(isset($post['descricao'])) echo htmlspecialchars($post['descricao']);?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label><strong>Notas Adicionais</strong></label> 
                    <textarea class="form-control" rows="6" name="notas" style="overflow-x: hidden;"><?php if(isset($post['notas'])) echo htmlspecialchars($post['notas']);?></textarea>
                </div>
            </div>
   
            
<div class="clear"></div>        
<div class="row">
    <div class="col-md-12 form-group">
        <h3>Galeria</h3>            
        <div class="upload-btn-wrapper" id="gbfoco">
            <button class="btnup">Enviar Imagens da Galeria</button>
            <input name="gallery_covers[]" type="file" multiple  />
        </div>
    </div>
</div>


    <div class="clear"></div>
    <?php
    $delGb = filter_input(INPUT_GET, 'gbdel', FILTER_VALIDATE_INT);
    if($delGb):
        require_once('models/AdminImoveis.class.php');
        $DelGallery = new AdminImoveis;
        $DelGallery->gbRemove($delGb);

        RMErro($DelGallery->getError()[0], $DelGallery->getError()[1]);

    endif;
    
    $gbi = 0;
    $Gallery = new Read;
    $Gallery->ExeRead("imovel_gb", "WHERE id_imovel = :post", "post={$postId}");
    if ($Gallery->getResult()):
        foreach ($Gallery->getResult() as $gb):
        $gbi++;
    ?>
        <div style="text-align: center;margin-bottom: 10px;" class="col-lg-3">		
    		<?= Check::Image('../uploads/' . $gb['img'], $post['nome'], 150, 150); ?>
            <br />
            <a href="painel.php?exe=imoveis/imoveis-edit&postid=<?= $postId; ?>&gbdel=<?= $gb['id']; ?>#gbfoco" title="Exluir imagem"><button class="btn btn-danger btn-xs" style="margin-top: 5px;" type="button">Excluir</button></a>
            <a href="../uploads/<?= $gb['img'];?>" rel="ShadowBox" title="Imagem <?= $gbi; ?> da galeria de: <?= $post['nome'];?>">
            <button class="btn btn-info btn-xs" style="margin-top: 5px;" type="button">Visualizar</button>
    		</a>
        </div>
    <?php
        endforeach;
	endif;
    ?>
    
    <div class="clear"></div>
    <div class="row">
        <div class="col-md-3 form-group">
            <label><strong>&nbsp;</strong></label>
            <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Atualizar">Atualizar</button>
        </div>
    </div>
</div>            
            
            
            </div>
        </div>
    </div>
</div>
             

</form>	
    
    
<!-- MODAL AJUDA MAPA -->
<div class="modal fade" id="1">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">				
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Ajuda</strong></h4>
            </div>              
            <div class="modal-body">
                <h4>Copie do Google Maps e cole somente o que está em vermelho como no exemplo abaixo:</h4>
                <p style="word-wrap: break-word;">iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="<b>http://maps.google.com.br/maps?q=padang+itamambuca&hl=pt&cd=5&ei=WKRATO_oD5qYyASLp8G8BA&sig2=w3RAiseOdsO7C12FC844Wg&sll=-23.40367,-45.013046&sspn=3.598987,4.938354&ie=UTF8&view=map&cid=3882811409949230167&ved=0CBgQpQY&hq=padang+itamambuca&hnear=&ll=-23.391737,-45.009162&spn=0.006893,0.00912&z=16&iwloc=A&output=embed</b>">iframe</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>                
            </div>
         </div>
    </div>
</div>


</div>
    
</section>
</div>
</div>
</div>