<?php require(REQUIRE_PATCH . '/include/header.inc.php'); ?>


<!-- SUB-TITULO START -->
<div class="sub-banner overview-bgi" style="background: rgba(0, 0, 0, 0.04) url(<?= BASE.'/uploads/'.IMGTOPO;?>) top left repeat;">
    <div class="overlay">
        <div class="container">
            <div class="breadcrumb-area">
                <h1>Atendimento</h1>
                <ul class="breadcrumbs">
                    <li><a href="<?= BASE;?>">Início</a></li>
                    <li class="active">Página - Atendimento</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- SUB-TITULO END -->

<!-- MAIN CONTATO START -->
<div class="contact-1 content-area-7">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                <!-- FORMULARIO START -->
                <div class="contact-form">
                    <form id="contact_form" class="j_formsubmitanuncioSend" action="" method="post">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
                                <div class="alertas"></div>
                                <input class="noclear" type="hidden" name="action" value="atendimento" />
                                <!-- HONEYPOT -->
                                <input type="hidden" class="noclear" name="bairro" value="" />
                                <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                            </div>
                        </div>
                        <div class="form_hide"><!-- FORM HIDE START -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group fullname">
                                    <input type="text" name="nome" class="input-text" placeholder="Seu Nome"/>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group enter-email">
                                    <input type="email" name="email" class="input-text" placeholder="Seu E-mail"/>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group subject">
                                    <input type="text" name="assunto" class="input-text" placeholder="Assunto"/>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group number">
                                    <input type="text" name="telefone" class="input-text" placeholder="Telefone"/>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
                                <div class="form-group message">
                                    <textarea class="input-text" name="mensagem" placeholder="Mensagem"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group send-btn mb-0">
                                    <button style="width: 100%;margin-top: 2%;" type="submit" id="b_nome" class="button-md button-theme">Enviar Mensagem</button>
                                </div>
                            </div>
                        </div>
                        </div> <!-- FORM HIDE END -->
                    </form>     
                </div>
                <!-- FORMULARIO END -->
            </div>
            <div class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-1 col-sm-6 col-xs-12">
                <!-- DADOS DE CONTATO START -->
                <div class="contact-details">
                    <div class="main-title-2">
                        <h3>Outros Canais</h3>
                    </div>
                    <?php
	                   if(RUA):
                            echo '<div class="media">';
                            echo '<div class="media-left">';
                            echo '<i class="fa fa-map-marker"></i>';
                            echo '</div>';
                            echo '<div class="media-body">';
                            echo '<h4>Escritório</h4>';
                            echo '<p>'.RUA.'';
                            if(NUMERO && BAIRRO):
                                echo ', '.NUMERO.', '.BAIRRO.'';
                            elseif(!NUMERO && BAIRRO):
                                echo ', '.BAIRRO.'';
                            else:
                                echo ', '.NUMERO.'';
                            endif;
                            if(CIDADE && UF):
                                echo ', '.CIDADE.'/'.UF.'';
                            elseif(!CIDADE && UF):
                                echo ', '.UF.'';
                            else:
                                echo ', '.CIDADE.'';
                            endif;
                            echo '</p>';
                            echo '</div>';
                            echo '</div>';
                       endif;
                    ?>                    
                    <div class="media">
                        <div class="media-left">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="media-body">
                            <h4>Telefone</h4>
                            <?php
	                           if(TELEFONE1):
                                    echo '<p><a href="tel:'.TELEFONE1.'">'.TELEFONE1.'</a></p>';                               
                               endif;
                               if(TELEFONE2):
                                    echo '<p><a href="tel:'.TELEFONE2.'">'.TELEFONE2.'</a></p>';                               
                               endif;
                               if(TELEFONE3):
                                    echo '<p><a href="tel:'.TELEFONE3.'">'.TELEFONE3.'</a></p>';                               
                               endif;
                            ?>
                        </div>
                    </div>
                    <?php
	                   if(WATSAPP):
                            echo '<div class="media">';
                            echo '<div class="media-left">';
                            echo '<i class="fa fa-whatsapp"></i>';
                            echo '</div>';
                            echo '<div class="media-body">';
                            echo '<h4>WhatsApp</h4>';
                            echo '<p><a target="_blank" href="'.Check::getNumZap(WATSAPP ,'Atendimento Imóveis em Ubatuba').'">'.WATSAPP.'</a></p>';    
                            echo '</div>';
                            echo '</div>';
                       endif;
                    ?>
                    <div class="media mb-0">
                        <div class="media-left">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="media-body">
                            <h4>E-mail</h4>
                            <?php
	                           if(EMAIL):
                                    echo '<p><a href="mailto:'.EMAIL.'">'.EMAIL.'</a></p>';                               
                               endif;
                               if(EMAIL1):
                                    echo '<p><a href="tel:'.EMAIL1.'">'.EMAIL1.'</a></p>';                               
                               endif;
                            ?>
                        </div>
                    </div>
                </div>
                <!-- DADOS DE CONTATO END -->
            </div>
        </div>
    </div>
</div>
<!-- MAIN CONTATO END -->

<?php require(REQUIRE_PATCH . '/include/footer.inc.php'); ?>