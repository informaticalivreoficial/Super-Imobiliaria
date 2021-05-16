<?php require(REQUIRE_PATCH . '/include/header.inc.php'); ?>
<?php
//var_dump($Link);
?>
<!-- Sub banner start -->
<div class="sub-banner overview-bgi" style="background: rgba(0, 0, 0, 0.04) url(<?= BASE.'/uploads/'.IMGTOPO;?>) top left repeat;">
    <div class="overlay">
        <div class="container">
            <div class="breadcrumb-area">
                <h1>Cadastrar Imóvel</h1>
                <ul class="breadcrumbs">
                    <li><a href="<?= BASE;?>">Início</a></li>
                    <li class="active">Imóveis -  Cadastrar Imóvel</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Sub Banner end -->
<!-- Submit Property start -->
<div class="content-area-7 submit-property">
    <div class="container">
        <div class="row">            
            <div class="col-md-12">
                <div class="submit-address">
                    <?php
                        if (isset($Link->getLocal()[2]) && $Link->getLocal()[2] == 'create'):
                            RMErro("Obrigado <strong></strong>, seu imóvel foi cadastrado com sucesso em nosso sistema!<br /> ", RM_ACCEPT);
                            RMErro("Agora os dados de seu imóvel passarão por uma análise de nossos corretores, em instantes ele será publicado.", RM_ALERT);
                            unset($post);
                        else:
                          
                        $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                        if(isset($post) && $post['SendPostForm']):                            
                            
                        if($post['categoria'] == '0'):
                            RMErro("Por favor selecione o <strong>Tipo do imóvel</strong>", RM_ERROR);
                        elseif($post['tipo'] == '2'): 
                            RMErro("Por favor selecione se vai <strong>Alugar ou Vender?</strong>", RM_ERROR);
                        elseif($post['dormitorios'] == '0'): 
                            RMErro("Por favor selecione a quantidade de <strong>Dormitórios</strong>", RM_ERROR);
                        elseif($post['banheiro'] == ''): 
                            RMErro("Por informe a quantidade de <strong>Banheiros</strong>", RM_ERROR);
                        elseif($post['garagem'] == ''): 
                            RMErro("Por favor selecione se possui <strong>Garagem</strong>", RM_ERROR);
                        elseif($post['valor'] == ''): 
                            RMErro("Por informe o valor do <strong>Imóvel</strong>", RM_ERROR);
                        elseif($post['uf_id'] == '0'):
                            RMErro("Por favor selecione o <strong>Estado</strong>", RM_ERROR);
                        elseif($post['cidade_id'] == '0'):
                            RMErro("Por favor selecione a <strong>Cidade</strong>", RM_ERROR);
                        elseif($post['bairroRascunho'] == ''):
                            RMErro("Por informe o <strong>Bairro</strong>", RM_ERROR);
                        elseif($post['rua'] == ''):
                            RMErro("Por informe a <strong>Rua</strong>", RM_ERROR);
                        elseif($post['cliente_nome'] == ''):
                            RMErro("Por favor preencha o campo <strong>Nome</strong>", RM_ERROR);
                        elseif(!Check::Email($post['email'])):
                            RMErro(Check::getPrimeiroNome($post['cliente_nome'])." o campo <strong>E-mail</strong> está vazio ou tem um formato inválido!", RM_ERROR);
                        elseif($post['telefone'] == ''):
                            RMErro(Check::getPrimeiroNome($post['cliente_nome'])." por favor informe o seu <strong>Telefone</strong>", RM_ERROR);
                        elseif(!empty($post['bairro']) || !empty($post['cidade'])):
                            RMErro("<strong>ERRO</strong> Você está praticando SPAM!", RM_ERROR);
                        else:
                            
                            $post['img'] = ($_FILES['img']['tmp_name'] ? $_FILES['img'] : null);
                            
                            $post['img1'] = ($_FILES['img1']['tmp_name'] ? $_FILES['img1'] : null);
                            $post['img2'] = ($_FILES['img2']['tmp_name'] ? $_FILES['img2'] : null);
                            $post['img3'] = ($_FILES['img3']['tmp_name'] ? $_FILES['img3'] : null);
                            $post['img4'] = ($_FILES['img4']['tmp_name'] ? $_FILES['img4'] : null);
                            $post['img5'] = ($_FILES['img5']['tmp_name'] ? $_FILES['img5'] : null);
                            //unset($post['SendPostForm']);

                            $readClientes = new Read;
                            $readClientes->ExeRead("clientes", "WHERE email = :cliEmail","cliEmail={$post['email']}");
                            if(!$readClientes->getResult()):
                                $cli['nome']       = $post['cliente_nome'];
                                $cli['email']      = $post['email'];
                                $cli['telefone']   = $post['telefone'];
                                $cli['status']     = '1';
                                $cli['indicacao']  = 'Cadastro pelo Site';
                                $cli['data']       = date('Y-m-d H:i:s');
                                $cli['code1']      = md5(date('Y-m-d H:i:s').$cli['email'].date('Y'));
                                $cadCli = new Create;
                                $cadCli->ExeCreate("clientes", $cli);
                            endif;
                            
                            
                            $imovel['nome']            = Check::Categoria("cat_imoveis", $post['categoria'], 'nome').' em '.Check::getCidade($post['cidade_id'], 'cidade_nome').'/'.Check::getUf($post['uf_id'], 'estado_nome');
                            $imovel['data']            = date('Y-m-d H:i:s');
                            $imovel['status']          = '0';
                            $imovel['url']             = Check::Name($imovel['nome']);
                            $imovel['tipo']            = $post['tipo'];
                            $imovel['dormitorios']     = $post['dormitorios'];
                            $imovel['banheiro']        = $post['banheiro'];
                            $imovel['garagem']         = $post['garagem'];
                            $imovel['vagas_garagem']   = $post['vagas_garagem'];
                            $imovel['anodeconstrucao'] = $post['anodeconstrucao'];
                            $imovel['uf_id']           = $post['uf_id'];
                            $imovel['cli_email']       = $post['email'];
                            $imovel['cidade_id']       = $post['cidade_id'];
                            $imovel['rua']             = $post['rua'];
                            $imovel['expira']          = Check::getDataExpiraDias('90');                            
                            $imovel['num']             = $post['num'];
                            $imovel['categoria']       = $post['categoria'];
                            $imovel['cat_pai']         = Check::Categoria("cat_imoveis", $post['categoria'], 'id_pai');
                            $imovel['descricao']       = 'Bairro: '.$post['bairroRascunho'].'<br />'.$post['descricao'];

                            $str = str_replace('R', '', $post['valor']); // remove o R
                            $str1 = str_replace('$', '', $str); // remove o $
                            $str2 = str_replace('.', '', $str1); // remove o ponto
                            $str3 = explode(',', $str2); // remove a , e os 00
                            $imovel['valor'] = $str3['0'];
                            
                            $readImoveis = new Read;
                            $readImoveis->ExeRead("imoveis", "WHERE url = :urlImovel","urlImovel={$imovel['url']}");
                            if($readImoveis->getResult()):
                                $imovel['url'] = $imovel['url'].'-'.$readImoveis->getRowCount();
                            endif;
                            
                            $imovel['referencia'] = 'CS'.rand(1,100);
                            
                            $readImoveis->ExeRead("imoveis", "WHERE url = :referenciaImovel","referenciaImovel={$imovel['referencia']}");
                            if($readImoveis->getResult()):
                                $imovel['referencia'] = 'CS'.rand(1,100) + 1;
                            endif;
                            
                            // PEGA OS VALORES DE CHECKBOX
                            if(isset($post['acbanco'])):
                                $Acessorio = $post['acbanco'];
                                $opcoes_text = implode(", ", $Acessorio);            
                                $imovel['acessorios'] = $opcoes_text;
                                $post['acessorios'] = $opcoes_text;
                                unset($post['acbanco']);
                            endif;
                            
                            $pasta 	= 'imoveis';
                            if($post['img']):
                                $upload = new Upload('uploads/');
                                $upload->ImageSetPasta($post['img'], $imovel['url'], '960', $pasta.'/capas');
                            endif; 

                            $cadIm = new Create;                            
                            if (isset($upload) && $upload->getResult()):
                                $imovel['img'] = $upload->getResult();
                                $cadIm->ExeCreate("imoveis", $imovel);                
                            else:
                                $imovel['img'] = null;
                                RMErro("<strong>Você não enviou uma Capa</strong> ou o <strong>Tipo de arquivo é inválido</strong>, envie imagens JPG ou PNG!",RM_ALERT);
                                $cadIm->ExeCreate("imoveis", $imovel);                
                            endif;
                            
                            $ImgName1 = "{$imovel['url']}-gb-{$cadIm->getResult()}-" . (substr(md5(time() + 1), 0, 5));
                            if($post['img1']):
                                $upload1 = new Upload('uploads/');
                                $upload1->ImageSetPasta($post['img1'], $ImgName1, '960', $pasta.'/imagens');
                                
                                $gbCreate1 = ['id_imovel' => $cadIm->getResult(), "img" => $upload1->getResult(), "data" => date('Y-m-d H:i:s')];
                                $insertGb1 = new Create;
                                $insertGb1->ExeCreate("imovel_gb", $gbCreate1);
                            endif;
                            
                            $ImgName2 = "{$imovel['url']}-gb-{$cadIm->getResult()}-" . (substr(md5(time() + 2), 0, 5));
                            if($post['img2']):
                                $upload2 = new Upload('uploads/');
                                $upload2->ImageSetPasta($post['img2'], $ImgName2, '960', $pasta.'/imagens');
                                
                                $gbCreate2 = ['id_imovel' => $cadIm->getResult(), "img" => $upload2->getResult(), "data" => date('Y-m-d H:i:s')];
                                $insertGb2 = new Create;
                                $insertGb2->ExeCreate("imovel_gb", $gbCreate2);
                            endif;
                            
                            $ImgName3 = "{$imovel['url']}-gb-{$cadIm->getResult()}-" . (substr(md5(time() + 3), 0, 5));
                            if($post['img3']):
                                $upload3 = new Upload('uploads/');
                                $upload3->ImageSetPasta($post['img3'], $ImgName3, '960', $pasta.'/imagens');
                                
                                $gbCreate3 = ['id_imovel' => $cadIm->getResult(), "img" => $upload3->getResult(), "data" => date('Y-m-d H:i:s')];
                                $insertGb3 = new Create;
                                $insertGb3->ExeCreate("imovel_gb", $gbCreate3);
                            endif;
                            
                            $ImgName4 = "{$imovel['url']}-gb-{$cadIm->getResult()}-" . (substr(md5(time() + 4), 0, 5));
                            if($post['img4']):
                                $upload4 = new Upload('uploads/');
                                $upload4->ImageSetPasta($post['img4'], $ImgName4, '960', $pasta.'/imagens');
                                
                                $gbCreate4 = ['id_imovel' => $cadIm->getResult(), "img" => $upload4->getResult(), "data" => date('Y-m-d H:i:s')];
                                $insertGb4 = new Create;
                                $insertGb4->ExeCreate("imovel_gb", $gbCreate4);
                            endif;
                            
                            $ImgName5 = "{$imovel['url']}-gb-{$cadIm->getResult()}-" . (substr(md5(time() + 5), 0, 5));
                            if($post['img5']):
                                $upload5 = new Upload('uploads/');
                                $upload5->ImageSetPasta($post['img5'], $ImgName5, '960', $pasta.'/imagens');
                                
                                $gbCreate5 = ['id_imovel' => $cadIm->getResult(), "img" => $upload5->getResult(), "data" => date('Y-m-d H:i:s')];
                                $insertGb5 = new Create;
                                $insertGb5->ExeCreate("imovel_gb", $gbCreate5);
                            endif;
                            
                            //SOMA 1 VISITA A TABELA ESTATÍSTICA      
                            Set::SetEstatisticas(4);
                            //CADASTRA NA TABELA NEWSLETTER
                            $newsRead = new Read;
                            $newsRead->ExeRead("newsletter","WHERE email = :emaiUser","emaiUser={$post['email']}");
                            if(!$newsRead->getResult()):
                                $newsCreate = new Create;
                                $h['email'] = $post['email'];
                                $h['nome']  = $post['cliente_nome'];
                                $h['autorizacao'] = '1';
                                $h['status']      = '1';
                                $h['data'] 	      = date('Y-m-d H:i:s');
                                $h['cat_id']      = '1';
                                $newsCreate->ExeCreate("newsletter", $h);
                            endif;
                            
                            require('app/Library/PHPMailer/class.phpmailer.php');
                            require('app/Models/Email.class.php');                            
                            
                            $data       = date('d/m/Y');    
                            $Contato['RemetenteNome']  = $post['cliente_nome'];
                            $Contato['RemetenteEmail'] = MAILUSER;
                            $Contato['Assunto']        = '#Cadastro de imóvel pelo site';
                            $Contato['DestinoNome']    = '#Sistema '.SITENAME.'';
                            $Contato['DestinoEmail']   = MAILUSER;
                            $Contato['DestinoCopia']    = null;
                            $Contato['DestinoCCopia']    = null;
                            $Contato['DestinoArquivo']    = null;
                            $Contato['DestinoArquivoNome']    = null;
        
                            $Contato['Mensagem'] = '<body style="background:#E9ECEF; margin:0; padding:0;">
                                    <div style="width:100%;">        
                                        <div id="box-header" style="background:#ffefa4; overflow:hidden; padding:15px;">                        
                                            <div style="float:left; font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold; text-align:right;">
                                                Mensagem enviada pelo site
                                            </div>
        
                                            <div style="float:right; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold;">
                                                Data '.$data.'
                                            </div>                        
                                        </div>
        
                                        <div id="box-content" style="background:#FFF; padding:10px; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
                                            <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Dados da Mensagem</h1>
                                            <p>
                                            <strong>Nome: </strong><strong style="color:#09F;">'.$post['cliente_nome'].'</strong>
                                            <br />
                                            <strong>E-mail: </strong><strong style="color:#09F;">'.$post['email'].'</strong>
                                            <br />
                                            <strong>Celular: </strong><strong style="color:#09F;">'.$post['telefone'].'</strong> 
                                            <br />
                                            <strong>Cidade: </strong><strong style="color:#09F;">'.Check::getCidade($post['cidade_id'],'cidade_nome').'/'. Check::getUf($post['uf_id'],'estado_uf').'</strong> 
                                            / <strong>Bairro: </strong><strong style="color:#09F;">'.$post['bairroRascunho'].'</strong> 
                                            <br />
                                            <strong>Valor do Imóvel: </strong><strong style="color:#09F;">'.$post['valor'].'</strong>
                                            <br />
                                            <strong>Descrição: </strong><strong style="color:#09F;">'.$post['descricao'].'</strong>
                                            </p>
                                        </div> 
                                    </div>
                                    <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por Informática Livre - <a href="mailto:suporte@informaticalivre.com">suporte@informaticalivre.com</a></pre></div>
                                </body>                  
                                ';
                            
                            $Resposta['RemetenteNome']  = SITENAME;
                            $Resposta['RemetenteEmail'] = MAILUSER;
                            $Resposta['Assunto']        = '#Cadastro de imóvel';
                            $Resposta['DestinoNome']    = $post['cliente_nome'];
                            $Resposta['DestinoEmail']   = $post['email'];
                            $Resposta['DestinoCopia']    = null;
                            $Resposta['DestinoCCopia']    = null;
                            $Resposta['DestinoArquivo']    = null;
                            $Resposta['DestinoArquivoNome']    = null;
                            
                            $Resposta['Mensagem'] = '<body style="background:#E9ECEF; margin:0; padding:0;">
                                                <div style="width:100%;">        
                                                    <div id="box-header" style="background:#ffefa4; overflow:hidden; padding:15px;">                        
                                                        <div style="float:left; font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold; text-align:right;">
                                                            '.SITENAME.' - Ubatuba/SP
                                                        </div>
        
                                                        <div style="float:right; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold;">
                                                            Recebemos seu cadastro dia '.$data.'
                                                        </div>                        
                                                    </div>
        
                                                    <div id="box-content" style="background:#FFF; padding:10px; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
                                                        <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Olá <strong style="color:#09F;">'.Check::getPrimeiroNome($post['cliente_nome']).'</strong>!</h1>
                                                        <p>Recebemos o cadastro do seu imóvel pelo nosso site.</p>
                                                        <p>Ele será publicado após a análise de nossos corretores, ah '.Check::getPrimeiroNome($post['cliente_nome']).' fique tranquilo é rápido.</p>
                                                        <div style="background:#DFEFFF; padding:15px;">
                                                            <p>Este e-mail foi enviado automaticamente pelo nosso sistema. Por favor, não responder.</p>        
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por Informática Livre - <a href="mailto:suporte@informaticalivre.com">suporte@informaticalivre.com</a></pre></div>
                                            </body>  
                                            ';
                            $SendMail = new Email;
                            $SendMail->Enviar($Contato);
                            $SendMail1 = new Email;
                            $SendMail1->Enviar($Resposta);
                            
                            
                            if ($cadIm->getResult()):
                                header('Location: ' . BASE . '/imoveis/cadastrar/create');
                            endif;                            

                        endif;
                        
                        endif;
                        ?>
                    
                        <form method="post" action="" enctype="multipart/form-data" class="j_formcadastro">
                        <div class="main-title-2">
                            <h1><span>Informações do seu imóvel</span></h1>
                            <!-- HONEYPOT -->
                            <input type="hidden" class="noclear" name="bairro" value="" />
                            <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                        </div>
                        <div class="search-contents-sidebar mb-30">
                            
                            <div class="row">                                
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>Tipo do imóvel<span style="color: #E83737;">*</span></label>
                                        <select class="selectpicker search-fields" name="categoria">
                                            <option value="0">Selecione</option>
                                            <?php
                                                $readCategoria = new Read;
                                                $readCategoria->ExeRead("cat_imoveis", "WHERE exibir = '1' AND id_pai IS NULL ORDER BY nome ASC");
                                                if($readCategoria->getResult()):
                                                    foreach($readCategoria->getResult() as $cat):   
                                                        echo "<option value=\"\" disabled=\"disabled\">>> {$cat['nome']}</option>";
                                                        $readCategoriSub = new Read;  
                                                        $readCategoriSub->ExeRead("cat_imoveis", "WHERE exibir = '1' AND id_pai = :idPai","idPai={$cat['id']}");
                                                        if($readCategoriSub->getResult()):                                                             
                                                           foreach($readCategoriSub->getResult() as $catSub):                                                                                        
                                                               echo '<option value="'.$catSub['id'].'"';
                                                                if(isset($post['categoria']) && $post['categoria'] == $catSub['id']):
                                                                    echo "selected=\"selected\" ";
                                                                endif;                                                                
                                                                echo '>'.$catSub['nome'].'</option>';                                            
                                                           endforeach;
                                                        endif;
                                                    endforeach;
                                                endif;                                        
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>Alugar ou Vender?<span style="color: #E83737;">*</span></label>
                                        <select class="selectpicker search-fields" name="tipo">
                                            <option value="2">Selecione</option>
                                            <option <?php if(isset($post['tipo']) && $post['tipo'] == '1') echo 'selected="selected"';?>  value="1">Aluga</option>
                                            <option <?php if(!isset($post['tipo']) || $post['tipo'] == '0') echo 'selected="selected"';?>  value="0">Vende</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>Dormitórios<span style="color: #E83737;">*</span></label>
                                        <select class="selectpicker search-fields" name="dormitorios">
                                            <option value="0">Selecione</option>
                                            <option <?php if(isset($post['dormitorios']) && $post['dormitorios'] == '1') echo 'selected="selected"';?>  value="1">1</option>
                                            <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '2') echo 'selected="selected"';?>  value="2">2</option>
                                            <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '3') echo 'selected="selected"';?>  value="3">3</option>
                                            <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '4') echo 'selected="selected"';?>  value="4">4</option>
                                            <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '5') echo 'selected="selected"';?>  value="5">5</option>
                                            <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '6') echo 'selected="selected"';?>  value="6">6</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>Banheiros<span style="color: #E83737;">*</span></label>
                                        <input type="text" class="input-text" name="banheiro" value="<?php if(isset($post['banheiro'])) echo $post['banheiro'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Garagem<span style="color: #E83737;">*</span></label>
                                        <select class="selectpicker search-fields" name="garagem">
                                            <option value="">Selecione</option>
                                            <option <?php if(isset($post['garagem']) && $post['garagem'] == '1') echo 'selected="selected"';?>  value="1">Sim</option>
                                            <option <?php if(!isset($post['garagem']) || $post['garagem'] == '0') echo 'selected="selected"';?>  value="0">Não</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Vagas na Garagem</label>
                                        <input type="text" class="input-text" name="vagas_garagem" value="<?php if(isset($post['vagas_garagem'])) echo $post['vagas_garagem'];?>">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Ano de Construção</label>
                                        <input type="text" class="input-text" name="anodeconstrucao" value="<?php if(isset($post['anodeconstrucao'])) echo $post['anodeconstrucao'];?>">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Valor<span style="color: #E83737;">*</span></label>
                                        <input type="text" class="input-text" name="valor" data-prefix="R$" id="dinheiroComZero2" value="<?php if(isset($post['valor'])) echo $post['valor'];?>">
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="main-title-2">
                            <h1><span>Localização do Imóvel</span></h1>
                        </div>

                        <div class="row mb-30 ">
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label>Estado<span style="color: #E83737;">*</span></label>
                                    <select class="selectpicker search-fields j_loadstate" name="uf_id">
                                        <option value="0" selected> Selecione o estado </option>
                                        <?php
                                            $readState = new Read;
                                            $readState->ExeRead("estados", "ORDER BY estado_nome ASC");
                                            foreach ($readState->getResult() as $estado):
                                                extract($estado);
                                                echo "<option value=\"{$estado_id}\" ";
                                                if (isset($post['uf_id']) && $post['uf_id'] == $estado_id): echo 'selected';
                                                endif;
                                                echo "> {$estado_nome} - {$estado_uf} </option>";
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label>Cidade<span style="color: #E83737;">*</span></label>
                                    <select class="search-fields  j_loadcity" name="cidade_id">
                                        <?php if (!isset($post['cidade_id'])): ?>
                                        <option value="0" selected> Selecione antes um estado </option>
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
                            </div>
                            <div class="col-md-4 col-sm-8 col-lg-4">
                                <div class="form-group">
                                    <label>Rua<span style="color: #E83737;">*</span></label>
                                    <input type="text" class="input-text" name="rua" value="<?php if(isset($post['rua'])) echo $post['rua'];?>">
                                </div>
                            </div> 
                            <div class="col-md-2 col-sm-4 col-lg-2">
                                <div class="form-group">
                                    <label>Número</label>
                                    <input type="text" class="input-text" name="num" value="<?php if(isset($post['num'])) echo $post['num'];?>">
                                </div>
                            </div>                          
                        </div>
                        
                        <div class="row mb-30 ">
                        <div class="col-md-6 col-sm-8">
                                <div class="form-group">
                                    <label>Bairro<span style="color: #E83737;">*</span></label>
                                    <input type="text" class="input-text" name="bairroRascunho" value="<?php if(isset($post['bairroRascunho'])) echo $post['bairroRascunho'];?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4">
                                <div class="form-group">
                                    <label>Complemento</label>
                                    <input type="text" class="input-text" name="complemento" value="<?php if(isset($post['complemento'])) echo $post['complemento'];?>">
                                </div>
                            </div>
                            
                        </div>

                        
                        <div class="main-title-2">
                            <h1><span>Selecione Imagens</span></h1>
                        </div>

                        <div class="row mb-30">
                            <div class="col-md-4 col-xs-12 col-sm-6 col-lg-4" style="margin-bottom:10px;">                                
                                <input type="file" name="img" id="arquivo1" class="arquivo1" value="<?php if(isset($post['img']['name'])) echo $post['img']['name'];?>"/>
                                <input type="text" name="file" id="file1" class="file" placeholder="Capa" readonly="readonly"/>
                                <input type="button" class="btn1" value="SELECIONAR"/>                                
                                </div> 
                            <div class="col-md-4 col-xs-12 col-sm-6 col-lg-4"style="margin-bottom:10px;">                                
                                <input type="file" name="img1" id="arquivo2" class="arquivo2"/>
                                <input type="text" name="file" id="file2" class="file" placeholder="Foto 1" readonly="readonly"/>
                                <input type="button" class="btn2" value="SELECIONAR"/>                                
                            </div>
                            <div class="col-md-4 col-xs-12 col-sm-6 col-lg-4" style="margin-bottom:10px;">                                
                                <input type="file" name="img2" id="arquivo3" class="arquivo3"/>
                                <input type="text" name="file" id="file3" class="file" placeholder="Foto 2" readonly="readonly"/>
                                <input type="button" class="btn3" value="SELECIONAR"/>                                
                            </div>                                            
                        </div>
                            
                        <div class="row mb-30">                             
                            <div class="col-md-4 col-xs-12 col-sm-6 col-lg-4"style="margin-bottom:10px;">                                
                                <input type="file" name="img3" id="arquivo4" class="arquivo4"/>
                                <input type="text" name="file" id="file4" class="file" placeholder="Foto 3" readonly="readonly"/>
                                <input type="button" class="btn4" value="SELECIONAR"/>                                
                            </div>
                            <div class="col-md-4 col-xs-12 col-sm-6 col-lg-4" style="margin-bottom:10px;">                                
                                <input type="file" name="img4" id="arquivo5" class="arquivo5"/>
                                <input type="text" name="file" id="file5" class="file" placeholder="Foto 4" readonly="readonly"/>
                                <input type="button" class="btn5" value="SELECIONAR"/>                                
                            </div>
                            <div class="col-md-4 col-xs-12 col-sm-6 col-lg-4" style="margin-bottom:10px;">                                
                                <input type="file" name="img5" id="arquivo6" class="arquivo6"/>
                                <input type="text" name="file" id="file6" class="file" placeholder="Foto 5" readonly="readonly"/>
                                <input type="button" class="btn6" value="SELECIONAR"/>                                
                            </div>
                        </div>
                                               

                        <div class="main-title-2">
                            <h1><span>Informações Adicionais</span></h1>
                        </div>

                        <div class="row mb-30">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea class="input-text" name="descricao"><?php if(isset($post['descricao'])) echo htmlspecialchars($post['descricao']);?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-30">
                            <div class="col-lg-12">
                                <label class="margin-t-10">Acessórios (opcional)</label>
                                <div class="row">
                                    <?php
                                    $readAcessorios = new Read;
                                    $readAcessorios->ExeRead("imoveis_atributos", "WHERE status = '1'");
                                    if($readAcessorios->getResult()): 

                                        echo '<div class="col-lg-4 col-sm-4 col-xs-12">';
                                        $i = 0; 
                                        foreach($readAcessorios->getResult() as $acessorio):

                                        //Fechar div e abrir nova a cada 4 itens
                                        if($i % 4 == 0 && $i != 0):            
                                            echo '</div><div class="col-lg-4 col-sm-4 col-xs-12">';               
                                        endif;
                                    ?>                                    
                                    <div class="checkbox checkbox-theme checkbox-circle">                                        
                                        <input id="checkbox<?= $acessorio['id'];?>" type="checkbox" name="acbanco[]" value="<?= $acessorio['id'];?>"
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
                                        <label for="checkbox<?= $acessorio['id'];?>"><?= $acessorio['nome'];?> </label>                                        
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
                        <div class="main-title-2">
                            <h1><span>Informações para Contato</span></h1>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label>Seu Nome<span style="color: #E83737;">*</span></label>
                                    <input type="text" class="input-text" name="cliente_nome" value="<?php if(isset($post['cliente_nome'])) echo $post['cliente_nome'];?>">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label>E-mail<span style="color: #E83737;">*</span></label>
                                    <input type="text" class="input-text" name="email" value="<?php if(isset($post['email'])) echo $post['email'];?>">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label>Telefone(móvel)<span style="color: #E83737;">*</span></label>
                                    <input type="text" class="input-text" id="whatsapp" name="telefone" value="<?php if(isset($post['telefone'])) echo $post['telefone'];?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn button-md button-theme b_cadastro" name="SendPostForm" title="Cadastrar" value="Cadastrar">Cadastrar</button>
                            </div>
                        </div>
                    </form>
                </div>
                         <?php    
                        endif;
                    ?>
                    
            </div>
        </div>
    </div>
</div>
<!-- Submit Property end -->


<?php require(REQUIRE_PATCH . '/include/footer.inc.php'); ?>