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
        <h3>Cadastrar Cliente</strong></h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=clientes/clientes<?= $varPage;?>" title="Voltar e listar Clientes" class="btn btn-primary btn-lg" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e listar Clientes</a>	
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
    if($post && $post['sendFormPost']):    
        $post['avatar'] = ($_FILES['avatar']['tmp_name'] ? $_FILES['avatar'] : null);
        unset($post['sendFormPost']);
        if($post['uf'] == '0'):
            $post['cidade'] = '0';
        endif;
        require('models/AdminClientes.class.php');
        $cadastra = new AdminClientes;
        $cadastra->ExeCreate($post);
        if ($cadastra->getResult()):
            header("Location: painel.php?exe=clientes/clientes-edit&create=true&postid={$cadastra->getResult()}");
        else:
            RMErro($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;
    //var_dump($post);    
    endif;			
?>
<form method="post" action="" enctype="multipart/form-data">
<div class="row">
            <div class="col-md-12">
                <div class="panel">   
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2 form-group" style="text-align: center;">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 160px; height: 160px;">
                                        <img src="images/200x200.gif"/>                                        
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="width: 160px; height: 160px; line-height: 20px;"></div>
                                    <div>
                                           <span class="btn btn-default btn-file">
                                           <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                           <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                                           <input type="file" name="avatar" class="default" value="" />
                                           </span>
                                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-10 form-group"> 
                                <div class="row">                               
                                    <div class="col-md-6 form-group">
                                        <label><strong>Nome</strong></label>
                                        <input type="hidden" name="nivel" value="4"/>
                                        <input type="hidden" name="tipo" value="2"/>
                                        <input type="text" class="form-control input-lg" name="nome" value="<?php if(!empty($post['nome'])) echo $post['nome'];?>" />
                                    </div> 
                                    <div class="col-md-3 form-group">
                                        <label><strong>Status</strong></label>
                                        <select name="status" class="form-control input-lg">
                                            <option>Selecione</option>
                                            <option <?php if(isset($post['status']) && $post['status'] == '1') echo 'selected="selected"';?>  value="1">Ativo</option>
                                            <option <?php if(!isset($post['status']) || $post['status'] == '0') echo 'selected="selected"';?>  value="0">Inativo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label><strong>&nbsp;</strong></label>
                                        <button name="sendFormPost" type="submit" style="width:100%;" class="btn btn-success btn-lg" value="Cadastrar">Cadastrar</button>
                                    </div>
                                </div>                               
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label><strong>E-mail</strong></label>
                                        <input type="text" class="form-control input-lg" name="email" value="<?php if(!empty($post['email'])) echo $post['email'];?>" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label><strong>Data de Nascimento</strong></label>
                                        <input class="form-control form-control-inline input-lg default-date-picker" size="16" type="text" name="nasc" value="<?php if(!empty($post['nasc'])): echo $post['nasc']; else: echo date('d/m/Y'); endif;?>"/>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label><strong>Data do Cadastro</strong></label>
                                        <input class="form-control form-control-inline input-lg default-date-picker" size="16" type="text" name="data" value="<?php if(!empty($post['data'])): echo $post['data']; else: echo date('d/m/Y'); endif;?>"/>
                                    </div>
                                </div>                            
                                                    
                                <div class="row"> 
                                    <div class="col-md-3 form-group">
                                        <label><strong>CPF</strong></label>
                                        <input type="text" class="form-control input-lg" name="cpf" data-mask="999.999.999-99" value="<?php if(!empty($post['cpf'])) echo $post['cpf'];?>" />
                                    </div> 
                                    <div class="col-md-3 form-group">
                                        <label><strong>CNH</strong></label>
                                        <input type="text" class="form-control input-lg" name="cnh" value="<?php if(!empty($post['cnh'])) echo $post['cnh'];?>" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label><strong>RG</strong></label>
                                        <input type="text" class="form-control input-lg" name="rg" data-mask="99.999.999-9" value="<?php if(!empty($post['rg'])) echo $post['rg'];?>" />
                                    </div>  
                                    <div class="col-md-3 form-group">
                                        <label><strong>WhatsApp</strong></label>
                                        <input type="text" class="form-control input-lg" data-mask="(99) 99999-9999" name="whatsapp" value="<?php if(!empty($post['whatsapp'])) echo $post['whatsapp'];?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>                        
                        
                        <div class="row">                                    
                            <div class="col-md-4 form-group">
                                <label><strong>Telefone fixo</strong></label>
                                <input type="text" class="form-control input-lg" data-mask="(99) 9999-9999" name="telefone1" value="<?php if(!empty($post['telefone1'])) echo $post['telefone1'];?>" />
                            </div>
                            <div class="col-md-4 form-group">
                                <label><strong>Telefone móvel</strong></label>
                                <input type="text" class="form-control input-lg" data-mask="(99) 99999-9999" name="telefone2" value="<?php if(!empty($post['telefone2'])) echo $post['telefone2'];?>" />
                            </div>                              
                            <div class="col-md-4 form-group">
                                <label><strong>Categoria</strong></label>
                                <select name="categoria" class="form-control input-lg m-bot15">
                                    <option value=""> Selecione a Categoria </option>
                                    <?php
                                        $readCatPai = new Read;
                                        $readCatPai->ExeRead("cat_clientes","WHERE id_pai IS NULL ORDER BY nome ASC");
                                        if($readCatPai->getRowCount() >= 1):
                                            foreach($readCatPai->getResult() as $catpai):
                                            echo "<option value=\"\" disabled=\"disabled\">{$catpai['nome']}</option>";
                                                $readCat = new Read;
                                                $readCat->ExeRead("cat_clientes","WHERE id_pai = :catpai ORDER BY nome ASC","catpai={$catpai['id']}");
                                                if($readCat->getRowCount() >= 1):
                                                 foreach($readCat->getResult() as $cat):
                                                 echo "<option ";
                                                 if(!empty($post['categoria']) && $post['categoria'] == $cat['id']):
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
                            <div class="col-md-4 form-group">
                                <label><strong>Instagram</strong></label>
                                <input type="text" class="form-control input-lg" name="instagram" value="<?php if(!empty($post['instagram'])) echo $post['instagram'];?>" />
                            </div>
                            <div class="col-md-4 form-group">
                                <label><strong>Twitter</strong></label>
                                <input type="text" class="form-control input-lg" name="twitter" value="<?php if(!empty($post['twitter'])) echo $post['twitter'];?>" />
                            </div>
                            <div class="col-md-4 form-group">
                                <label><strong>Youtube</strong></label>
                                <input type="text" class="form-control input-lg" name="youtube" value="<?php if(!empty($post['youtube'])) echo $post['youtube'];?>" />
                            </div>                            
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label><strong>Facebook</strong></label>
                                <input type="text" class="form-control input-lg" name="facebook" value="<?php if(!empty($post['facebook'])) echo $post['facebook'];?>" />
                            </div>
                            <div class="col-md-4 form-group">
                                <label><strong>Linkedin</strong></label>
                                <input type="text" class="form-control input-lg" name="linkedin" value="<?php if(!empty($post['linkedin'])) echo $post['linkedin'];?>" />
                            </div>
                            <div class="col-md-4 form-group">
                                <label><strong>Skype</strong></label>
                                <input type="text" class="form-control input-lg" name="skype" value="<?php if(!empty($post['skype'])) echo $post['skype'];?>" />
                            </div>
                        </div>
                        
                         <hr />
                        
                        <div class="row"> 
                        <div class="col-md-5 form-group">
                            <label><strong>Rua</strong></label>
                            <input type="text" class="form-control input-lg" name="rua" value="<?php if(!empty($post['rua'])) echo $post['rua'];?>" />
                        </div>
                        <div class="col-md-2 form-group">
                            <label><strong>UF</strong></label>
                            <select name="uf" class="form-control input-lg j_loadstate">
                                    <option value="0" selected> Selecione o estado </option>
                                    <?php
                                    $readState = new Read;
                                    $readState->ExeRead("estados", "ORDER BY estado_nome ASC");
                                    foreach ($readState->getResult() as $estado):
                                        extract($estado);
                                        echo "<option value=\"{$estado_id}\" ";
                                        if (isset($post['uf']) && $post['uf'] == $estado_id): echo 'selected'; endif;
                                        echo "> {$estado_uf} / {$estado_nome} </option>";
                                    endforeach;
                                    ?>
                            </select>
                        </div>
                        <div class="col-md-5 form-group">
                            <label><strong>Cidade</strong></label>
                            <select class="form-control input-lg j_loadcity" name="cidade">
                                    <?php if (!isset($post['cidade'])): ?>
                                        <option value="0" selected disabled> Selecione antes um estado </option>
                                        <?php
                                    else:
                                        $City = new Read;
                                        $City->ExeRead("cidades", "WHERE estado_id = :uf ORDER BY cidade_nome ASC", "uf={$post['uf']}");
                                        if ($City->getRowCount()):
                                            foreach ($City->getResult() as $cidade):
                                                extract($cidade);
                                                echo "<option value=\"{$cidade_id}\" ";
                                                if (isset($post['cidade']) && $post['cidade'] == $cidade_id):
                                                    echo "selected";
                                                endif;
                                                echo "> {$cidade_nome} </option>";
                                            endforeach;
                                        endif;
                                    endif;
                                    ?>
                            </select>
                        </div>                        
                        
                        
                        </div> 
                        
                        <div class="row"> 
                            <div class="col-md-5 form-group">
                                <label><strong>Bairro</strong></label>
                                <input type="text" class="form-control input-lg" name="bairro" value="<?php if(!empty($post['bairro'])) echo $post['bairro'];?>" />
                            </div>                        
                            <div class="col-md-2 form-group">
                                <label><strong>Número</strong></label>
                                <input type="text" class="form-control input-lg" name="num" value="<?php if(!empty($post['num'])) echo $post['num'];?>" />
                            </div>
                            <div class="col-md-2 form-group">
                                <label><strong>Cep</strong></label>
                                <input type="text" class="form-control input-lg" data-mask="99.999-999" name="cep" value="<?php if(!empty($post['cep'])) echo $post['cep'];?>" />
                            </div>
                            <div class="col-md-3 form-group">
                                <label><strong>Complemento</strong></label>
                                <input type="text" class="form-control input-lg" name="complemento" value="<?php if(!empty($post['complemento'])) echo $post['complemento'];?>" />
                            </div>                        
                        </div> 
                        
                        <hr />
                        
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label><strong>Apresentação</strong></label>
                                <textarea class="form-control editor" name="descricao" rows="6"><?php if(!empty($post['descricao'])) echo htmlspecialchars($post['descricao']);?></textarea>                        	    
                            </div>
                            
                            <div class="col-md-12 form-group">
                                <button name="sendFormPost" type="submit" style="width:100%;" class="btn btn-success btn-lg" value="Cadastrar">Cadastrar Agora</button>
                            </div>
                        </div>            
                        
                    </div>
                </div>
            </div>
        </div>
</form>


</div>
</div>
</section>
</div>
</div>
</div>