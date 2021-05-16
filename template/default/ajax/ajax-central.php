<?php
    $getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $setPost = array_map('strip_tags', $getPost);
    $Post = array_map('trim', $setPost);
    
    $Action = $Post['action'];
    $jSon = array();
    unset($Post['action']);
    //sleep(5);
    
    if($Action):
        require('../../../vendor/autoload.php');
        require('../../../app/config.inc.php');        
    endif; 
    
    switch($Action):
    
    case 'simulador': 
        if($Post['nome'] == ''):
            $jSon['error'] = "Por favor preencha o campo <strong>Nome</strong>";
        elseif($Post['nasc'] == ''): 
            $jSon['error'] = "Por favor informe <strong>sua data de nascimento</strong>";
        elseif($Post['tempo'] == ''): 
            $jSon['error'] = "Por favor selecione <strong>quando pretende se mudar?</strong>";
        elseif($Post['tipo_financiamento'] == ''): 
            $jSon['error'] = "Por favor escolha <strong>o Tipo de Financiamento</strong>";
        else:
            require('../../../app/Library/PHPMailer/class.phpmailer.php');
            require('../../../app/Models/Email.class.php');

            if($Post['tipo_financiamento'] == '0'):
                if($Post['valor'] == ''):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." Por favor informe o <strong>valor do imóvel</strong>";
                elseif($Post['valor_entrada'] == ''):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." Por favor informe o <strong>valor da entrada</strong>";
                elseif($Post['uf'] == '0'):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." Por favor selecione o <strong>Estado</strong>";
                elseif($Post['cidade'] == ''):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." Por favor selecione a <strong>Cidade</strong>";
                elseif($Post['bairro'] == ''):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." Por favor informe o <strong>Bairro</strong>";
                elseif(!Check::Email($Post['email'])):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." o campo <strong>E-mail</strong> está vazio ou tem um formato inválido!";
                elseif($Post['celular'] == ''):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." o campo <strong>Celular</strong> precisa ser preechido";
                elseif($Post['renda'] == ''):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." o campo <strong>Renda</strong> precisa ser preechido";
                elseif(!empty($Post['bairro1']) || !empty($Post['cidade1'])):
                    $jSon['error'] = "<strong>ERRO</strong> Você está praticando SPAM!";
                else:
                    // DECLARA O QUE VAI PRO BANCO
                    $f['nome']      = $Post['nome'];
                    $f['nasc']      = Check::Data($Post['nasc']);
                    $f['email']     = $Post['email'];
                    $f['uf']        = $Post['uf'];
                    $f['cidade']    = $Post['cidade'];
                    $f['telefone1'] = $Post['celular'];
                    $f['bairro']    = $Post['bairro'];
                    $f['data']      = date('Y-m-d H:i:s');
                    $f['status']            = '1';
                    $f['tipo']              = '2';
                    $f['nivel']             = '4';
                    $f['senha'] = $Post['email'].'0981';
                    $f['code'] = md5($f['senha']);
                    
                    //CADASTRA CLIENTE
                    $readCliente = new Read;
                    $readCliente->ExeRead("usuario", "WHERE email = :emailPost","emailPost={$Post['email']}");
                    if(!$readCliente->getResult()):
                        $idlast = new Create;
                        $idlast->ExeCreate("clientes", $f);
                    endif; 
                    
                    //SOMA 1 VISITA A TABELA ESTATÍSTICA      
                    Set::SetEstatisticas(3);

                    $data       = date('d/m/Y');    
                    $Contato['RemetenteNome']  = $Post['nome'];
                    $Contato['RemetenteEmail'] = MAILUSER;
                    $Contato['Assunto']        = '#Simulação de financiamento enviada pelo site';
                    $Contato['DestinoNome']    = '#Sistema '.SITENAME.'';
                    $Contato['DestinoEmail']   = MAILUSER;
                    $Contato['DestinoCopia']    = 'wlanmoras@gmail.com,felipedemarcos@hotmail.com';
                    $Contato['DestinoCCopia']    = 'atendimento@ubatubatimes.com.br';
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
                                    <strong>Nome: </strong><strong style="color:#09F;">'.$Post['nome'].'</strong>
                                    / <strong>Data de nascimento: </strong><strong style="color:#09F;">'.$Post['nasc'].'</strong> 
                                    <br />
                                    <strong>E-mail: </strong><strong style="color:#09F;">'.$Post['email'].'</strong>
                                    <br />
                                    <strong>Celular: </strong><strong style="color:#09F;">'.$Post['celular'].'</strong> 
                                    <br />
                                    <strong>Cidade: </strong><strong style="color:#09F;">'.Check::getCidade($Post['cidade'],'cidade_nome').'/'. Check::getUf($Post['uf'],'estado_uf').'</strong> 
                                    / <strong>Bairro: </strong><strong style="color:#09F;">'.$Post['bairro'].'</strong> 
                                    <br />
                                    <strong>Pretenção de mudança: </strong><strong style="color:#09F;">'.$Post['tempo'].'</strong> 
                                    <br />
                                    <strong>Valor do Imóvel: </strong><strong style="color:#09F;">'.$Post['valor'].'</strong> 
                                    / <strong>Valor da Entrada: </strong><strong style="color:#09F;">'.$Post['valor_entrada'].'</strong> 
                                    <br />
                                    <strong>Natureza do Imóvel: </strong><strong style="color:#09F;">'.$Post['natureza'].'</strong> 
                                    / <strong>Tipo do Imóvel: </strong><strong style="color:#09F;">'.$Post['tipoimovel'].'</strong> 
                                    <br />
                                    <strong>Renda aproximada: </strong><strong style="color:#09F;">'.$Post['renda'].'</strong>
                                    <br />
                                    <strong>Intenção do Cliente: </strong><strong style="color:#09F;">'.$Post['oqueprecisa'].'</strong>
                                    </p>
                                </div> 
                            </div>
                            <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por Informática Livre - <a href="mailto:suporte@informaticalivre.com">suporte@informaticalivre.com</a></pre></div>
                        </body>                  
                        ';
                    
                    $Resposta['RemetenteNome']  = '#Atendimento '.SITENAME.'';
                    $Resposta['RemetenteEmail'] = MAILUSER;
                    $Resposta['Assunto']        = '#Simulação de financiamento';
                    $Resposta['DestinoNome']    = $Post['nome'];
                    $Resposta['DestinoEmail']   = $Post['email'];
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
                                                    Recebemos sua mensagem dia '.$data.'
                                                </div>                        
                                            </div>

                                            <div id="box-content" style="background:#FFF; padding:10px; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
                                                <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Olá <strong style="color:#09F;">'.Check::getPrimeiroNome($Post['nome']).'</strong>!</h1>
                                                <p>Recebemos sua Simulação de financiamento de Imóveis pelo nosso site.</p>
                                                <p>Enviaremos o mais rápido possível a melhor proposta para você.</p>
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
                    $jSon['sucess'] = 'Obrigado <strong>'.Check::getPrimeiroNome($Post['nome']).'</strong>, nosso sistema recebeu sua simulação de financiamento de Imóveis!<br /> Nossos corretores vão lhe enviar a melhor proposta em instantes.';
                endif;
            else:
                if(!Check::Email($Post['email'])):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." o campo <strong>E-mail</strong> está vazio ou tem um formato inválido!";
                elseif($Post['celular'] == ''):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." o campo <strong>Celular</strong> precisa ser preechido";
                elseif($Post['uf'] == '0'):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." Por favor selecione o <strong>Estado</strong>";
                elseif($Post['cidade'] == ''):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." Por favor selecione a <strong>Cidade</strong>";
                elseif($Post['bairro'] == ''):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." Por favor informe o <strong>Bairro</strong>";
                elseif($Post['valorcarta'] == ''):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." Por favor informe o <strong>Valor da Carta de Crédito</strong>";
                elseif($Post['prazocarta'] == ''):
                    $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." Por favor selecione o <strong>Prazo</strong>";
                elseif(!empty($Post['bairro1']) || !empty($Post['cidade1'])):
                    $jSon['error'] = "<strong>ERRO</strong> Você está praticando SPAM!";
                else:
                    // DECLARA O QUE VAI PRO BANCO
                    $f['nome']      = $Post['nome'];
                    $f['nasc']      = Check::Data($Post['nasc']);
                    $f['email']     = $Post['email'];
                    $f['uf']        = $Post['uf'];
                    $f['cidade']    = $Post['cidade'];
                    $f['telefone1'] = $Post['celular'];
                    $f['bairro']    = $Post['bairro'];
                    $f['data']      = date('Y-m-d H:i:s');
                    $f['status']            = '1';
                    $f['tipo']              = '2';
                    $f['nivel']             = '4';
                    $f['senha'] = $Post['email'].'0981';
                    $f['code'] = md5($f['senha']);
                    
                    //CADASTRA CLIENTE
                    $readCliente = new Read;
                    $readCliente->ExeRead("usuario", "WHERE email = :emailPost","emailPost={$Post['email']}");
                    if(!$readCliente->getResult()):
                        $idlast = new Create;
                        $idlast->ExeCreate("clientes", $f);
                    endif;                   
                    
                    //SOMA 1 VISITA A TABELA ESTATÍSTICA      
                    Set::SetEstatisticas(3);

                    $data       = date('d/m/Y');    
                    $Contato['RemetenteNome']  = $Post['nome'];
                    $Contato['RemetenteEmail'] = MAILUSER;
                    $Contato['Assunto']        = '#Simulação de financiamento enviada pelo site';
                    $Contato['DestinoNome']    = '#Sistema '.SITENAME.'';
                    $Contato['DestinoEmail']   = MAILUSER;
                    $Contato['DestinoCopia']    = 'wlanmoras@gmail.com,felipedemarcos@hotmail.com';
                    $Contato['DestinoCCopia']    = 'atendimento@ubatubatimes.com.br';
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
                                    <strong>Nome: </strong><strong style="color:#09F;">'.$Post['nome'].'</strong>
                                    / <strong>Data de nascimento: </strong><strong style="color:#09F;">'.$Post['nasc'].'</strong> 
                                    <br />
                                    <strong>E-mail: </strong><strong style="color:#09F;">'.$Post['email'].'</strong>
                                    <br />
                                    <strong>Celular: </strong><strong style="color:#09F;">'.$Post['celular'].'</strong> 
                                    <br />
                                    <strong>Cidade: </strong><strong style="color:#09F;">'.Check::getCidade($Post['cidade'],'cidade_nome').'/'. Check::getUf($Post['uf'],'estado_uf').'</strong> 
                                    / <strong>Bairro: </strong><strong style="color:#09F;">'.$Post['bairro'].'</strong> 
                                    <br />
                                    <strong>Pretenção de mudança: </strong><strong style="color:#09F;">'.$Post['tempo'].'</strong>
                                    <br />
                                    <strong>Valor da Carta: </strong><strong style="color:#09F;">'.$Post['valorcarta'].'</strong> 
                                    <br />
                                    <strong>Prazo: </strong><strong style="color:#09F;">'.$Post['prazocarta'].'</strong> 
                                    <br />
                                    </p>
                                </div> 
                            </div>
                            <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por Informática Livre - <a href="mailto:suporte@informaticalivre.com">suporte@informaticalivre.com</a></pre></div>
                        </body>                  
                        ';
                    $Resposta['RemetenteNome']  = '#Atendimento '.SITENAME.'';
                    $Resposta['RemetenteEmail'] = MAILUSER;
                    $Resposta['Assunto']        = '#Simulação de financiamento';
                    $Resposta['DestinoNome']    = $Post['nome'];
                    $Resposta['DestinoEmail']   = $Post['email'];
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
                                                    Recebemos sua mensagem dia '.$data.'
                                                </div>                        
                                            </div>

                                            <div id="box-content" style="background:#FFF; padding:10px; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
                                                <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Olá <strong style="color:#09F;">'.Check::getPrimeiroNome($Post['nome']).'</strong>!</h1>
                                                <p>Recebemos sua Simulação de financiamento de Imóveis pelo nosso site.</p>
                                                <p>Enviaremos o mais rápido possível a melhor proposta para você.</p>
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
                    $jSon['sucess'] = 'Obrigado <strong>'.Check::getPrimeiroNome($Post['nome']).'</strong>, nosso sistema recebeu sua simulação de Consórcio de Imóveis!<br /> Nossos corretores vão lhe enviar a melhor proposta em instantes.';
                endif;
            endif;
        endif;
    break;
        
    case 'comentario':
        if($Post['nome'] == ''):
            $jSon['error'] = "O campo <strong>Nome</strong> está vazio por favor preencha!";
        elseif(!Check::Email($Post['email'])):
            $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." o campo <strong>E-mail</strong> está vazio ou tem um formato inválido!";
        elseif($Post['comentario'] == ''):
            $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." o campo <strong>Mensagem</strong> está vazio por favor preencha com seu comentário!";
        elseif(!empty($Post['bairro']) || !empty($Post['cidade'])):
            $jSon['error'] = "<strong>ERRO</strong> Você está praticando SPAM!";
        else:  
            require('../../../app/Library/PHPMailer/class.phpmailer.php');
            require('../../../app/Models/Email.class.php');
            
            $data  = date('d/m/Y');                
            //CADASTRA NA TABELA COMENTÁRIOS
            $comentarioCreate = new Create;
            $c['email'] =  $Post['email'];

            // verifica se é usuário cadastrado
            $readUser = new Read;
            $readUser->ExeRead("usuario", "WHERE email = :cEmail", "cEmail={$c['email']}");
            if($readUser->getResult()):
                $userId = $readUser->getResult()['0'];
                if($userId['nivel'] == '1'):
                    $c['isadmin']  =  '1';
                endif;
                $c['user_id']  =  $userId['id']; 
            endif;
            
            if(isset($Post['resp_id']) && !isset($Post['resp_resp_id'])):
                $c['resp_id']  =  $Post['resp_id'];
                $c['resp_resp_id']  =  '0';
            elseif(isset($Post['resp_id']) && isset($Post['resp_resp_id'])):
                $c['resp_id']       =  $Post['resp_id'];
                $c['resp_resp_id']  =  $Post['resp_resp_id'];
            else:
                $c['resp_id']  =  '0';
                $c['resp_resp_id']  =  '0';
            endif;

            $c['nome']  =  $Post['nome'];
            $c['comentario']  = strip_tags($Post['comentario']);
            $c['status']      = '0';
            $c['data'] 	  = date('Y-m-d H:i:s');
            $c['post_id']     = $Post['post_id'];
            $comentarioCreate->ExeCreate("comentarios", $c);
            
            
            $Contato['RemetenteNome']  = $Post['nome'];
            $Contato['RemetenteEmail'] = MAILUSER;
            $Contato['Assunto']        = '#Novo comentário cadastrado no site';
            $Contato['DestinoNome']    = '#Sistema '.SITENAME.'';
            $Contato['DestinoEmail']   = MAILUSER;
            $Contato['DestinoCopia']    = 'wlanmoras@gmail.com,felipedemarcos@hotmail.com';
            $Contato['DestinoCCopia']    = 'atendimento@ubatubatimes.com.br';
            $Contato['DestinoArquivo']    = null;
            $Contato['DestinoArquivoNome']    = null;

            $readPost = new Read;
            $readPost->ExeRead("posts", "WHERE id = :postId", "postId={$Post['post_id']}");
            if($readPost->getResult()):
                $post = $readPost->getResult()['0'];
            endif;
            if($post['tipo'] == 'noticia'):
                $post['tipo'] = 'noticia/'.$post['url'];
            elseif($post['tipo'] == 'artigo'):
                $post['tipo'] = 'blog/artigo/'.$post['url'];
            else:
                $post['tipo'] = 'sessao/'.$post['url']; 
            endif;
            
            $Contato['Mensagem'] = '<body style="background:#E9ECEF; margin:0; padding:0;">
                            <div style="width:100%;">        
                                <div id="box-header" style="background:#ffefa4; overflow:hidden; padding:15px;">                        
                                    <div style="float:left; font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold; text-align:right;">
                                        Novo comentário cadastrado no site
                                    </div>

                                    <div style="float:right; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold;">
                                        Data '.$data.'
                                    </div>                        
                                </div>

                                <div id="box-content" style="background:#FFF; padding:10px; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
                                    <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Post</h1>
                                    <p><strong>'.$post['titulo'].'</strong><br />
                                        '. Check::Words($post['content'], 35).' <a href="'.BASE.'/'.$post['tipo'].'">Leia Mais</a>
                                    </p>
                                    <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Comentário</h1>
                                    <p>
                                    <strong>Nome: </strong><strong style="color:#09F;">'.$Post['nome'].'</strong>
                                    <br />
                                    <strong>E-mail: </strong><strong style="color:#09F;">'.$Post['email'].'</strong>
                                    <br />
                                    <strong>Comentário: </strong>
                                    <p style="font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#09F;">'.nl2br($Post['comentario']).'</p>
                                    </p>
                                </div> 
                            </div>
                            <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por Informática Livre - <a href="mailto:suporte@informaticalivre.com">suporte@informaticalivre.com</a></pre></div>
                        </body>                  
                        ';
            $SendMail = new Email;
            $SendMail->Enviar($Contato);

            //CADASTRA NA TABELA NEWSLETTER
            $newsRead = new Read;
            $newsRead->ExeRead("newsletter","WHERE email = '$Post[email]'");
            if(!$newsRead->getResult()):
                $newsCreate = new Create;
                $h['email'] = $Post['email'];
                $h['nome']  =  $Post['nome'];
                $h['autorizacao'] = '1';
                $h['status']      = '1';
                $h['data'] 	      = date('Y-m-d H:i:s');
                $h['cat_id']      = '1';
                $newsCreate->ExeCreate("newsletter", $h);
            endif;
            $jSon['sucess'] = 'Obrigado <strong>'.Check::getPrimeiroNome($Post['nome']).'</strong>, seu comentário foi enviado com sucesso!<br>Nossa equipe de moderação irá analizá-lo antes da publicação!';               
        endif;
    break;
        
    case 'consulta_imovel': 
            if($Post['nome'] == ''):
            $jSon['error'] = "<strong>Erro:</strong> Por favor preencha o campo <strong>Nome</strong>";
            elseif(!Check::Email($Post['email'])):
                $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." o campo <strong>E-mail</strong> está vazio ou tem um formato inválido!";
            elseif($Post['mensagem'] == ''):
                $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." para que possamos enviar o formulário você deve preencher o campo <strong>Mensagem</strong>!";
            elseif(!empty($Post['bairro']) || !empty($Post['cidade'])):
                $jSon['error'] = "<strong>ERRO</strong> Você está praticando SPAM!";
            else:
                
                $readImovelajax = new Read;
                $readImovelajax->ExeRead("imoveis", "WHERE id = :imovelId", "imovelId={$Post['idimovel']}");
                if($readImovelajax->getResult()):
                    $imovelAjax = $readImovelajax->getResult()['0'];
                endif;
                
                require('../../../app/Library/PHPMailer/class.phpmailer.php');
                require('../../../app/Models/Email.class.php');

                $data       = date('d/m/Y');    

                $Contato['RemetenteNome']  = $Post['nome'];
                $Contato['RemetenteEmail'] = $Post['email'];
                $Contato['Assunto']        = '#Consulta Imóvel '.$imovelAjax['referencia'].'';
                $Contato['DestinoNome']    = '#Atendimento '.SITENAME.'';
                $Contato['DestinoEmail']   = MAILUSER;
                $Contato['DestinoCopia']    = 'wlanmoras@gmail.com,felipedemarcos@hotmail.com';
                $Contato['DestinoCCopia']    = 'atendimento@ubatubatimes.com.br';
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
                                    <strong>Nome: </strong><strong style="color:#09F;">'.$Post['nome'].'</strong>
                                    <br />
                                    <strong>E-mail: </strong><strong style="color:#09F;">'.$Post['email'].'</strong>
                                    <br />
                                    <strong>Telefone: </strong><strong style="color:#09F;">'.$Post['telefone'].'</strong>
                                    <br />
                                    <strong>Imóvel: </strong><strong style="color:#09F;">'.$imovelAjax['nome'].'</strong>
                                    <br />
                                    <strong>Referência: </strong><strong style="color:#09F;">'.$imovelAjax['referencia'].'</strong>
                                    <br />
                                    <strong>Mensagem: </strong>
                                    <p style="font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#09F;">'.nl2br($Post['mensagem']).'</p>
                                    <br />
                                    <strong>Link do Imóvel: <a href="'.BASE.'/imoveis/imovel/'.$imovelAjax['url'].'">> Clique Aqui!< </a></strong>                                        
                                    </p>
                                </div> 
                            </div>
                            <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por '.DESENVOLVEDOR.' - <a href="mailto:'.DESENVOLVEDOREMAIL.'">'.DESENVOLVEDOREMAIL.'</a></pre></div>
                        </body>                  
                        ';

                $Resposta['RemetenteNome']  = '#Atendimento '.SITENAME.'';
                $Resposta['RemetenteEmail'] = MAILUSER;
                $Resposta['Assunto']        = '#Informações do Imóvel '.$imovelAjax['referencia'].'';
                $Resposta['DestinoNome']    = $Post['nome'];
                $Resposta['DestinoEmail']   = $Post['email'];
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
                                        Recebemos sua consulta dia '.$data.'
                                    </div>                        
                                </div>

                                <div id="box-content" style="background:#FFF; padding:10px; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
                                    <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Olá <strong style="color:#09F;">'.Check::getPrimeiroNome($Post['nome']).'</strong>!</h1>
                                    <p>Recebemos sua consulta pelo nosso site.</p>
                                    <p>Enviaremos o mais rápido possível uma resposta a sua Consulta.</p>
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
            
            //SOMA 1 VISITA A TABELA ESTATÍSTICA      
            Set::SetEstatisticas(2);
            //CADASTRA NA TABELA NEWSLETTER
            $newsRead = new Read;
            $newsRead->ExeRead("newsletter","WHERE email = '$Post[email]'");
            if(!$newsRead->getResult()):
                $newsCreate = new Create;
                $h['email'] = $Post['email'];
                $h['nome']  =  $Post['nome'];
                $h['autorizacao'] = '1';
                $h['status']      = '1';
                $h['data'] 	      = date('Y-m-d H:i:s');
                $h['cat_id']      = '1';
                $newsCreate->ExeCreate("newsletter", $h);
            endif;
            // MENSAGEM DE SUCESSO!
            $jSon['sucess'] = '<strong>Sucesso:</strong> Obrigado '.Check::getPrimeiroNome($Post['nome']).', sua consulta foi enviada.Em alguns instantes nossa equipe entrará em contato!';   
            endif;                   
    break;
    
    case 'busca-avancada':
        if($Post['cidade'] == ''):
            $jSon['error'] = "Por favor selecione a <strong>Cidade</strong>!";
        elseif(empty($Post['bairro_id'])):
            $jSon['error'] = "Por favor selecione o <strong>Bairro</strong>!";
        else:
            $jSon['sucess'] = "Carregando resultado...";
        endif;            
    break;
        
    case 'atendimento':
        if($Post['nome'] == ''):
            $jSon['error'] = "<strong>Erro:</strong> Por favor preencha o campo <strong>Nome</strong>";
        elseif(!Check::Email($Post['email'])):
            $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." o campo <strong>E-mail</strong> está vazio ou tem um formato inválido!";
        elseif($Post['mensagem'] == ''):
            $jSon['error'] = Check::getPrimeiroNome($Post['nome'])." para que possamos enviar o formulário você deve preencher o campo <strong>Mensagem</strong>!";
        elseif(!empty($Post['bairro']) || !empty($Post['cidade'])):
            $jSon['error'] = "<strong>ERRO</strong> Você está praticando SPAM!";
        else:
            require('../../../app/Library/PHPMailer/class.phpmailer.php');
            require('../../../app/Models/Email.class.php');

            $data       = date('d/m/Y');    

            $Contato['RemetenteNome']  = $Post['nome'];
            $Contato['RemetenteEmail'] = $Post['email'];
            $Contato['Assunto']        = '#Solicitação de atendimento enviada pelo site';
            $Contato['DestinoNome']    = '#Atendimento '.SITENAME.'';
            $Contato['DestinoEmail']   = MAILUSER;
            $Contato['DestinoCopia']    = 'wlanmoras@gmail.com,felipedemarcos@hotmail.com';
            $Contato['DestinoCCopia']    = 'atendimento@ubatubatimes.com.br';
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
                                    <strong>Nome: </strong><strong style="color:#09F;">'.$Post['nome'].'</strong>
                                    <br />
                                    <strong>E-mail: </strong><strong style="color:#09F;">'.$Post['email'].'</strong>
                                    <br />
                                    <strong>Telefone: </strong><strong style="color:#09F;">'.$Post['telefone'].'</strong>
                                    <br />
                                    <strong>Mensagem: </strong>
                                    <p style="font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#09F;">'.nl2br($Post['mensagem']).'</p>
                                    </p>
                                </div> 
                            </div>
                            <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por '.DESENVOLVEDOR.' - <a href="mailto:'.DESENVOLVEDOREMAIL.'">'.DESENVOLVEDOREMAIL.'</a></pre></div>
                        </body>                  
                        ';


            $Resposta['RemetenteNome']  = '#Atendimento '.SITENAME.'';
            $Resposta['RemetenteEmail'] = MAILUSER;
            $Resposta['Assunto']        = '#Solicitação de atendimento';
            $Resposta['DestinoNome']    = $Post['nome'];
            $Resposta['DestinoEmail']   = $Post['email'];
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
                                            Recebemos sua mensagem dia '.$data.'
                                        </div>                        
                                    </div>

                                    <div id="box-content" style="background:#FFF; padding:10px; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
                                        <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Olá <strong style="color:#09F;">'.Check::getPrimeiroNome($Post['nome']).'</strong>!</h1>
                                        <p>Recebemos sua mensagem pelo nosso site.</p>
                                        <p>Enviaremos o mais rápido possível uma resposta a sua solicitação.</p>
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

            //SOMA 1 VISITA A TABELA ESTATÍSTICA      
            Set::SetEstatisticas(1); 

            //CADASTRA NA TABELA NEWSLETTER
            $newsRead = new Read;
            $newsRead->ExeRead("newsletter","WHERE email = '$Post[email]'");
            if(!$newsRead->getResult()):
                $newsCreate = new Create;
                $h['email'] = $Post['email'];
                $h['nome']  =  $Post['nome'];
                $h['autorizacao'] = '1';
                $h['status']      = '1';
                $h['data'] 	      = date('Y-m-d H:i:s');
                $h['cat_id']      = '1';
                $newsCreate->ExeCreate("newsletter", $h);
            endif;                
            // MENSAGEM DE SUCESSO!
            $jSon['sucess'] = 'Obrigado <strong>'.Check::getPrimeiroNome($Post['nome']).'</strong>, sua mensagem foi enviada com sucesso!';
        endif;

    break;
        
    case 'newsletter':
        if(!Check::Email($Post['email'])):
            $jSon['error'] = "O campo <strong>E-mail</strong> está vazio ou tem um formato inválido!";
        elseif(!empty($Post['bairro']) || !empty($Post['cidade'])):
            $jSon['error'] = "<strong>ERRO</strong> Você está praticando SPAM!";
        else:
            $readNewsletter = new Read;
            $readNewsletter->ExeRead("newsletter","WHERE email = :emailCad","emailCad={$Post['email']}");
            if($readNewsletter->getResult()):
                //ATUALIZA A TABELA NEWSLETTER
                $emailNewsletter = extract($readNewsletter->getResult()['0']);
                $h['autorizacao'] = '1';
                $h['status']      = '1';
                $h['uppdate']     = date('Y-m-d H:i:s');
                $updateNewsletter = new Update;
                $updateNewsletter->ExeUpdate("newsletter", $h, "WHERE id = :idNews", "idNews={$id}");
                $jSon['sucess'] = 'Seu e-mail foi cadastrado com sucesso!';
            else:
                //CADASTRA NA TABELA NEWSLETTER                    
                $h['email']       = $Post['email'];
                $h['nome']        = '(#Cadastrado pelo Site)';
                $h['autorizacao'] = '1';
                $h['status']      = '1';
                $h['data'] 	      = date('Y-m-d H:i:s');
                $h['cat_id']      = '1';
                $newsCreate = new Create;
                $newsCreate->ExeCreate("newsletter", $h);
                $jSon['sucess'] = 'Seu e-mail foi cadastrado com sucesso!';
            endif;                
        endif;
    break;    
        
    endswitch;
    
    echo json_encode($jSon);