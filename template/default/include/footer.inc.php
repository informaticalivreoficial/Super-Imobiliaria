<!-- Footer start -->
<footer class="main-footer clearfix">
    <div class="container">
        <!-- Footer info-->
        <div class="footer-info">
            <div class="row">
                <!-- About us -->
                <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
                    <div class="footer-item">
                        <div class="main-title-2">
                            <h1>Atendimento</h1>
                        </div>
                        <?= SITEDESC;?>
                        <ul class="personal-info"><br />                            
                            <?php
                               if(RUA):
                                    echo '<li>';
                                    echo '<i class="fa fa-map-marker"></i>';
                                    echo RUA;
                               if(RUA && NUMERO):
                                    echo ', '.NUMERO;
                               endif;
                               if(RUA && NUMERO && BAIRRO):
                                    echo ', '.BAIRRO;
                               endif;
                               if(BAIRRO && CIDADE):
                                    echo ' - '.CIDADE;
                               endif;
                               if(CIDADE && UF):
                                    echo '/'.UF;
                               endif;                                    
                                    echo '</li>';
                               endif;
                                
	                           if(EMAIL):
                                    echo '<li>';
                                    echo '<i class="fa fa-envelope"></i>';
                                    echo 'Email: <a href="mailto:'.EMAIL.'">'.EMAIL.'</a>';
                                    echo '</li>';
                               endif;
                               
                               if(EMAIL1):
                                    echo '<li>';
                                    echo '<i class="fa fa-envelope"></i>';
                                    echo 'Email: <a href="mailto:'.EMAIL1.'">'.EMAIL1.'</a>';
                                    echo '</li>';
                               endif;
                               
                               if(TELEFONE1):
                                    echo '<li>';
                                    echo '<i class="fa fa-phone"></i>';
                                    echo 'Telefone: <a href="tel:'.TELEFONE1.'">'.TELEFONE1.'</a>';
                                    echo '</li>';
                               endif;
                               
                               if(TELEFONE2):
                                    echo '<li>';
                                    echo '<i class="fa fa-phone"></i>';
                                    echo 'Telefone: <a href="tel:'.TELEFONE2.'">'.TELEFONE2.'</a>';
                                    echo '</li>';
                               endif;
                               
                               if(WATSAPP):
                                    echo '<li>';
                                    echo '<i class="fa fa-whatsapp"></i>';
                                    echo 'WhatsApp: <a target="_blank" href="'.Check::getNumZap(WATSAPP ,'Atendimento Imóveis em Ubatuba').'">'.WATSAPP.'</a>';
                                    echo '</li>';
                               endif;
                            ?>                            
                        </ul>
                    </div>
                </div>
                <!-- Links -->
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="footer-item">
                        <div class="main-title-2">
                            <h1>Links</h1>
                        </div>
                        <ul class="links">
                            <li><a href="<?= BASE;?>">Início </a></li>
                            <li><a href="<?= BASE;?>/blog/artigos">Blog</a></li>
                            <li><a href="<?= BASE;?>/imoveis/index">Imóveis</a></li>
                            <li><a target="_blank" href="<?= BASE;?>/pagina/simulador">Financiamento</a></li>
                            <li><a href="<?= BASE;?>/imoveis/busca-por-referencia">Busca</a></li>
                            <li><a href="<?= BASE;?>/imoveis/cadastrar">Cadastrar Imóvel</a></li>
                            <li><a href="<?= BASE;?>/pagina/atendimento">Atendimento</a></li>                            
                        </ul>
                    </div>
                </div>
                <!-- Recent cars -->
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="footer-item popular-posts">
                        <div class="main-title-2">
                            <h1>Blog</h1>
                        </div>
                        <?php
                            $readBlog = new Read;
                            $readBlog->ExeRead("posts", "WHERE status = '1' AND tipo = 'artigo' ORDER BY data DESC LIMIT 3");
                            if($readBlog->getResult()):
                                foreach($readBlog->getResult() as $blog):
                                    echo '<div class="media">';
                                    echo '<div class="media-left">';
                                    if($blog['thumb'] == ''):
                                        echo '<img class="media-object" src="'.BASE.'/tim.php?src='.PATCH.'/images/image.jpg&w=90&h=63&q=100&zc=1" alt="'.$blog['titulo'].'">';
                                    else:
                                        echo '<img class="media-object" src="'.BASE.'/tim.php?src='.BASE.'/uploads/'.$blog['thumb'].'&w=90&h=63&q=100&zc=1" alt="'.$blog['titulo'].'">';
                                    endif;                                    
                                    echo '</div>';
                                    echo '<div class="media-body">';
                                    echo '<h3 class="media-heading">';
                                    echo '<a href="'.BASE.'/blog/artigo/'.$blog['url'].'">'.$blog['titulo'].'</a>';
                                    echo '</h3>';
                                    echo '<p>'.strftime('%d %b, %Y', strtotime($blog['data'])).'</p>';                                    
                                    echo '</div>';
                                    echo '</div>';
                                endforeach;
                            endif;
                        ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="footer-item">
                        <div class="main-title-2">
                            <h1>Inscreva-se</h1>
                        </div>
                        <div class="newsletter clearfix">
                            <p>
                                Receba direto no seu e-mail, nossas dicas e novidades sobre compra, venda e locação de imóveis!
                            </p>

                            <form action="" method="post" class="j_formNewsletter">
                                <div class="alertas"></div>
                                <div class="form-group form_hide">
                                    <!-- HONEYPOT -->
                                    <input type="hidden" class="noclear" name="bairro" value="" />
                                    <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                                    <input class="noclear" type="hidden" name="action" value="newsletter" />
                                    <input class="nsu-field btn-block" id="nsu-email-0" type="text" name="email" placeholder="Digite seu e-mail"/>
                                </div>
                                <div class="form-group mb-0 form_hide">
                                    <button type="submit" class="button-sm button-theme btn-block b_cadastro">
                                        Cadastrar
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="main-title-2" style="margin-top: 20px;margin-bottom: 10px;">
                            <h1>Cotação</h1>
                        </div>
                        <?php
                            // PEGA COTAÇÃO DO DOLAR VIA JSON
                            $url = file_get_contents('https://economia.awesomeapi.com.br/json/USD-BRL/1');
                            $json = json_decode($url, true);
                            $imprime = end($json);
                            $cor = ($imprime['pctChange'] < '0' ? 'pos' :
                                   ($imprime['pctChange'] == '0' ? 'neutro' : 
                                   ($imprime['pctChange'] > '0' ? 'neg' : 'neg')));
                            $sinal = ($imprime['pctChange'] < '0' ? '' :
                                   ($imprime['pctChange'] == '0' ? '' : 
                                   ($imprime['pctChange'] > '0' ? '+' : '+')));
                            echo '<div class="numbers">';                    
                            echo '<span class="value bra"> '.$imprime['name'].' R$'.number_format($imprime['ask'],'3',',','').'</span>';
                            echo '<span class="data '.$cor.'">'.$sinal.' '.number_format($imprime['pctChange'],'2',',','').'% </span>';
                            echo '</div>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer end -->

<!-- Copy right start -->
<div class="copy-right">
    <div class="container">
        <div class="row clearfix">
            <div class="col-md-8 col-sm-12">
                &copy;  <?= ANODEINICIO;?> <?= SITENAME;?> - Todos os direitos reservados.
            </div>
            <div class="col-md-4 col-sm-12">
                <ul class="social-list clearfix">
                    <?php
                        if(FACEBOOK):
                         echo '<li><a target="_blank" href="'.FACEBOOK.'" class="facebook"><i class="fa fa-facebook"></i></a></li>';
                         endif;
                         if(TWITTER):
                              echo '<li><a target="_blank" href="'.TWITTER.'" class="twitter"><i class="fa fa-twitter"></i></a></li>';
                         endif;
                         if(LINKEDIN):
                              echo '<li><a target="_blank" href="'.LINKEDIN.'" class="linkedin"><i class="fa fa-linkedin"></i></a></li>';
                         endif;
                         if(GOOGLE):
                              echo '<li><a target="_blank" href="'.GOOGLE.'" class="google"><i class="fa fa-google-plus"></i></a></li>';
                         endif;
                         if(INSTAGRAN):
                              echo '<li><a target="_blank" href="'.INSTAGRAN.'" class="instagram"><i class="fa fa-instagram"></i></a></li>';
                         endif;
                    ?>
                </ul>
                <a style="margin-right:30px;float: right;" href="<?= DESENVOLVEDORURL;?>" target="_blank"><img src="<?= DESENVOLVEDORLOGO;?>" alt="<?= DESENVOLVEDOR;?>" title="<?= DESENVOLVEDOR;?>" width="72" height="26" /></a>
            </div>
        </div>
    </div>
</div>
<!-- Copy end right-->

<form class="btn-wats" action="<?= BASE;?>/pagina/zapchat" method="post" target="_blank">
<div class="balao">
<textarea name="texto" placeholder="Digite Aqui" name="texto"></textarea>
<button name="sendwhats" type="submit">Enviar</button>
</div>
</form>

<div class="whatsapp-footer j_btnwhats">
<a><img src="<?= PATCH;?>/images/zap-topo.png" alt="<?php echo PATCH;?>/images/zap-topo.png" /></a>
</div>