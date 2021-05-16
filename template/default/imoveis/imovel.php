<?php
if ($Link->getData()):
    extract($Link->getData());
else:
    header('Location: ' . BASE . DIRECTORY_SEPARATOR . '404');
endif;
?>

<?php require(REQUIRE_PATCH . '/include/header.inc.php'); ?>

<!-- Sub banner start -->
<div class="sub-banner overview-bgi" style="background: rgba(0, 0, 0, 0.04) url(<?= BASE.'/uploads/'.IMGTOPO;?>) top left repeat;">
    <div class="overlay">
        <div class="container">
            <div class="breadcrumb-area">
                <h1><?= $nome;?></h1>
                <ul class="breadcrumbs">
                    <li><a href="<?= BASE;?>">Início</a></li>
                    <li class="active">Imóveis -  <?= $nome;?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Sub Banner end -->


<!-- Properties details page start -->
<div class="properties-details-page content-area">
    <div class="container">
        <div class="row mb-50">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <!-- Header -->
                <div class="heading-properties clearfix sidebar-widget">
                    <div class="pull-left">
                        <h3><?= $nome;?></h3>
                        <p>
                            <i class="fa fa-map-marker"></i><?= Check::getBairro($bairro_id,'nome');?>, <?= Check::getCidade($cidade_id,'cidade_nome');?>/<?= Check::getUf($uf_id,'estado_uf');?>
                        </p>
                    </div>
                    <div class="pull-right">
                        <h3><span><?php if($exibirvalores == '1'): echo 'R$'.number_format($valor, 0 , ',' , '.'); else: echo 'Sob Consulta'; endif;?></span></h3>
                        <?php $objetivo = ($tipo == '1' ? 'Aluga' : 'Vende');?>
                        <h5>
                            Referência: <?= $referencia?> - <?= $objetivo;?>
                        </h5>
                    </div>
                </div>
                <!-- Properties details section start -->
                <div class="sidebar-widget mb-40">
                    <!-- Properties description start -->
                    <div class="properties-description mb-40 ">
                        <div class="property-img">
                            <?php
                                if($img):
                                    echo '<img class="imgimovel" src="'.BASE.'/uploads/'.$img.'" alt="'.$nome.'">';  
                                else:
                                    echo '<img class="imgimovel" src="'.PATCH.'/images/image.jpg" alt="'.$nome.'">';
                                endif;                                       
                            ?>
                            <div class="property-overlay">
                                <?php
                                  $readImovelGbi = new Read; 
                                  $readImovelGbi->ExeRead("imovel_gb", "WHERE id_imovel = :imovelId", "imovelId={$id}");
                                  if($readImovelGbi->getResult()):
                                        echo '<div class="property-magnify-gallery">'; 
                                        echo '<a style="font-size:22px;line-height: 46px;width: 56px; height: 56px;" href="'.BASE.'/uploads/'.$img.'" class="overlay-link"><i class="fa fa-expand"></i></a>';
                                        foreach($readImovelGbi->getResult() as $imovelgbii):                                        
                                            echo '<a href="'.BASE.'/uploads/'.$imovelgbii['img'].'" class="hidden"></a>';                                      
                                        endforeach;
                                        echo '</div>';
                                  endif;
                                ?>
                            </div>
                            <?php
                            if($legendaimagem != null):
                                echo '<p>'.$legendaimagem.'</p>';
                            endif;
                            ?>
                        </div>
                        <div class="main-title-2">
                            <h1 style="margin-top:10px;"><span>Informações</span></h1>
                            <br />
                        <!-- Social list -->
                        <div id="shareIcons"></div>
                    
                        </div>                        
                    </div>
                    <!-- Properties description end -->
                    
                    <div class="panel-box properties-panel-box Property-description">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default" data-toggle="tab" aria-expanded="true">Descrição</a></li>
                            <li class=""><a href="#tab2default" data-toggle="tab" aria-expanded="false">Condições do Imóvel</a></li>
                            <li class=""><a href="#tab3default" data-toggle="tab" aria-expanded="false">Acessórios</a></li>                                                    
                        </ul>
                        <div class="panel with-nav-tabs panel-default">
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="tab1default">                                        
                                        <?= $descricao;?>
                                    </div>
                                    <div class="tab-pane fade features" id="tab2default">
                                        <!-- Properties condition start -->
                                        <div class="properties-condition">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <ul class="condition">
                                                        <?php                   
                                                           if($dormitorios):
                                                                echo '<li>';
                                                                echo '<i class="fa fa-check-square"></i>';
                                                                echo '<span>Dormitórios: '.$dormitorios.'</span>';
                                                                echo '</li>';
                                                           endif;
                                                           if($banheiro):
                                                                echo '<li>';
                                                                echo '<i class="fa fa-check-square"></i>';
                                                                echo '<span>Banheiros: '.$banheiro.'</span>';
                                                                echo '</li>';
                                                           endif;
                                                           if($valor_iptu != '0' && $exibirvalores == '1'):
                                                                echo '<li>';
                                                                echo '<i class="fa fa-check-square"></i>';
                                                                echo '<span>Valor IPTU: '.$valor_iptu.'</span>';
                                                                echo '</li>';
                                                           endif;                                                    
                                                        ?>                
                                                    </ul>
                                                </div>
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <ul class="condition">
                                                        <?php
                                                           if($areatotal):
                                                                echo '<li>';
                                                                echo '<i class="fa fa-check-square"></i>';
                                                                echo '<span>Área Total: '.$areatotal.' '.Check::getSigla($medidas).'</span>';
                                                                echo '</li>';
                                                           endif;
                                                           if($areaconstruida):
                                                                echo '<li>';
                                                                echo '<i class="fa fa-check-square"></i>';
                                                                echo '<span>Área Construída: '.$areaconstruida.' '.Check::getSigla($medidas).'</span>';
                                                                echo '</li>';
                                                           endif;
                                                           if($valor_condominio != '0' && $exibirvalores == '1'):
                                                                echo '<li>';
                                                                echo '<i class="fa fa-check-square"></i>';
                                                                echo '<span>Condomínio: '.$valor_condominio.'</span>';
                                                                echo '</li>';
                                                           endif; 
                                                        ?>                
                                                    </ul>
                                                </div>
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <ul class="condition">
                                                        <?php
                                                           if($garagem):
                                                                echo '<li>';
                                                                echo '<i class="fa fa-check-square"></i>';
                                                                echo '<span>Garagem: '.$garagem.'</span>';
                                                                echo '</li>';
                                                           endif;
                                                           if($vagas_garagem):
                                                                echo '<li>';
                                                                echo '<i class="fa fa-check-square"></i>';
                                                                echo '<span>Vagas na Garagem: '.$vagas_garagem.'</span>';
                                                                echo '</li>';
                                                           endif;
                                                        ?>                                   
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Properties condition end -->
                                    </div>
                                    <div class="tab-pane fade technical" id="tab3default">
                                        <!-- Properties amenities start -->
                                        <div class="properties-amenities">                                            
                                            <div class="row">
                                                <?php
                                                    $checkExplode = $acessorios;            
                                                    $checkAcessorios = explode(',',$checkExplode);

                                                   echo '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">';
                                                   echo '<ul class="list-2">';
                                                   $i = 0;
                                                   foreach($checkAcessorios as $acessorio):
                                                   if($i % 5 == 0 && $i != 0){echo '</ul></div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><ul class="list-2">';} 
                                                    $readAcessorios = new Read;
                                                    $readAcessorios->ExeRead("imoveis_atributos", "WHERE id = :acessorioId", "acessorioId={$acessorio}");                                                     
                                                      if($readAcessorios->getResult()){                                
                                                        foreach($readAcessorios->getResult() as $ass):                                    
                                                        echo '<li> '.$ass['nome'].'</li>';
                                                        endforeach;
                                                      }
                                                    $i++;                                                                                                  
                                                    endforeach;
                                                    echo '</ul></div>';
                                                ?> 
                                            </div>
                                        </div>
                                        <!-- Properties amenities end -->
                                    </div>                                                                        
                                </div>
                            </div>
                        </div>                      
                        
                    </div>
                    
                    
                    <?php
                    if($notas != null):
                        echo '<hr /><p style="font-size: 12px;text-align:center;width:100%;">'.$notas.'</p>';
                    endif;
                    ?>                    
                    
                    <?php
                    if($mapa != null):
                    ?>
                    <hr />
                    <div class="location sidebar-widget">
                        <div class="map">
                            <!-- Main Title 2 -->
                            <div class="main-title-2">
                                <h1><span>Localização</span></h1>
                            </div>
                            <div id="map" class="contact-map" style="position: relative; overflow: hidden;">
                                <div style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px;">
                                    <iframe class="resp-iframe" src="<?= $mapa;?>" gesture="media"  allow="encrypted-media" allowfullscreen></iframe>
                                </div>
                            </div>    
                        </div>    
                    </div>
                    <?php
                    endif;
                    ?>
                    <hr />
                    <!-- Contact 1 start -->
                    <div class="contact-1">
                        <div class="contact-form">
                            <!-- Main Title 2 -->
                            <div class="main-title-2">
                                <h1><span>Consultar este imóvel</span></h1>
                            </div>
                            <form id="contact_form" action="" method="post" class="j_formsubmitconsulta">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
                                        <div class="alertas"></div>
                                        <input class="noclear" type="hidden" name="action" value="consulta_imovel" />
                                        <input class="noclear" type="hidden" class="noclear" name="idimovel" value="<?= $id?>" />
                                        <!-- HONEYPOT -->
                                        <input type="hidden" class="noclear" name="bairro" value="" />
                                        <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                                    </div>
                                </div>
                                <div class="row form_hide">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group fullname">                                            
                                            <input type="text" name="nome" class="input-text" placeholder="Nome"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group enter-email">
                                            <input type="email" name="email" class="input-text"  placeholder="E-mail"/>
                                        </div>
                                    </div>                                  
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group number">
                                            <input type="text" name="telefone" class="input-text" placeholder="Telefone"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group message">
                                            <textarea class="input-text" name="mensagem" placeholder="Mensagem">
                                                
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group send-btn mb-0">
                                            <button type="submit" class="button-md button-theme b_nome">Consultar Agora</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Contact 1 end -->
                </div>
                <!-- Properties details section end -->              

              
            </div>
            
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <!-- Sidebar start -->
                <div class="sidebar right">
                    <!-- ABUSCA AVANÇADA -->
                    <div class="advabced-search hidden-lg hidden-md" style="margin-bottom: 30px;">    
                    <a href="<?= BASE.'/imoveis/busca-avancada';?>"><button class="search-button">Buscar Imóveis</button></a>    
                    </div>
                    <!-- ABUSCA AVANÇADA -->
                    
                    
                    <!-- Search contents sidebar start -->
                    <div class="sidebar-widget hidden-sm hidden-xs">
                        <div class="main-title-2">
                            <h1><span>Busca Avançada</span></h1>
                        </div>

                        <form method="post" action="<?= BASE;?>/imoveis/busca-avancada"> 
                           
                        <div class="form-group">
                            <label>Alugar ou Comprar?</label>
                            <select class="search-fields loadtipo" name="tipo" id="tipo">
                                <option <?php if(isset($post['tipo']) && $post['tipo'] == '1') echo 'selected="selected"';?>  value="1">Alugar</option>
                                <option <?php if(!isset($post['tipo']) || $post['tipo'] == '0') echo 'selected="selected"';?>  value="0">Comprar</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Escolha a Cidade</label>
                            <select class="search-fields loadcidadeFiltro" name="cidade" id="cidade">
                            <option selected>Escolha a Cidade</option> 
                            <?php
                                $readCidade = new Read;
                                $readCidade->ExeRead("cidades", "WHERE cidade_id ORDER BY cidade_nome ASC");
                                if($readCidade->getResult()):
                                    foreach($readCidade->getResult() as $cidade):
                                    $readImoveisFiltro = new Read;
                                    $readImoveisFiltro->ExeRead("imoveis", "WHERE cidade_id = :cidadeId","cidadeId={$cidade['cidade_id']}");
                                    if($readImoveisFiltro->getResult()):
                                        echo '<option value="'.$cidade['cidade_id'].'">'.$cidade['cidade_nome'].'</option>';
                                    endif;                                            
                                endforeach;
                                endif;                                        
                            ?>
                        </select>
                        </div>  
                        <div class="form-group">
                            <div class="selectBairro">
                                <select class="search-fields j_loadbairros" name="bairro_id" id="bairro" disabled>
                                    <option value="" selected>Selecione o Bairro</option>
                                </select>
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label>Valores</label>
                            <div class="selectValores">
                                <select class="search-fields loadvalores" name="valores" id="valores">
                                    <option value="" selected>Imóvel até</option>
                                    <option <?php if(isset($post['valores']) && $post['valores'] == '300000') echo 'selected="selected"';?>  value="300000">R$300.000</option>
                                    <option <?php if(!isset($post['valores']) || $post['valores'] == '450000') echo 'selected="selected"';?>  value="450000">R$450.000</option>
                                    <option <?php if(!isset($post['valores']) || $post['valores'] == '600000') echo 'selected="selected"';?>  value="600000">R$600.000</option>
                                    <option <?php if(!isset($post['valores']) || $post['valores'] == '750000') echo 'selected="selected"';?>  value="750000">R$750.000</option>
                                    <option <?php if(!isset($post['valores']) || $post['valores'] == '900000') echo 'selected="selected"';?>  value="900000">R$900.000</option>
                                    <option <?php if(!isset($post['valores']) || $post['valores'] == '1500000') echo 'selected="selected"';?>  value="1500000">R$1.500.000</option>
                                    <option <?php if(!isset($post['valores']) || $post['valores'] == '2000000') echo 'selected="selected"';?>  value="2000000">R$2.000.000</option>
                                    <option <?php if(!isset($post['valores']) || $post['valores'] == '2500000') echo 'selected="selected"';?>  value="2500000">R$2.500.000</option>
                                    <option <?php if(!isset($post['valores']) || $post['valores'] == '3000000') echo 'selected="selected"';?>  value="3000000">R$3.000.000</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Quartos</label>
                            <select class="search-fields loaddormitorios" name="dormitorios" id="dormitorios">
                                <option <?php if(isset($post['dormitorios']) && $post['dormitorios'] == '1') echo 'selected="selected"';?>  value="1">1+</option>
                                <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '2') echo 'selected="selected"';?>  value="2">2+</option>
                                <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '3') echo 'selected="selected"';?>  value="3">3+</option>
                                <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '4') echo 'selected="selected"';?>  value="4">4+</option>
                                <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '5') echo 'selected="selected"';?>  value="5">5+</option>
                                <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '') echo 'selected="selected"';?>  value="">Todos</option>
                            </select>
                        </div>
        
                        <div class="form-group mb-0">                
                            <button class="search-button">Buscar Imóveis</button>
                        </div>
                    </form>
                    </div>
                    <!-- Search contents sidebar end -->

                    <!-- Social media start -->
                    <div class="social-media sidebar-widget clearfix">
                        <!-- Main Title 2 -->
                        <div class="main-title-2">
                            <h1><span>Redes Sociais</span></h1>
                        </div>
                        <!-- Social list -->
                        <ul class="social-list">
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
                    </div>

                    <div class="sidebar-widget helping-box clearfix">
                        <div class="main-title-2">
                            <h1><span>Atendimento</span></h1>
                        </div>                        
                        <?php
                           if(TELEFONE1):
                                echo '<div class="helping-center">';
                                echo '<div class="icon"><i class="fa fa-phone"></i></div>';
                                echo '<h4>Telefone</h4>';
                                echo '<p><a href="tel:'.TELEFONE1.'">'.TELEFONE1.'</a> </p>';
                                echo '</div>';
                           endif; 
                           if(TELEFONE2):
                                echo '<div class="helping-center">';
                                echo '<div class="icon"><i class="fa fa-phone"></i></div>';
                                echo '<h4>Telefone</h4>';
                                echo '<p><a href="tel:'.TELEFONE2.'">'.TELEFONE2.'</a> </p>';
                                echo '</div>';
                           endif;                        
                           if(WATSAPP):
                                echo '<div class="helping-center">';
                                echo '<div class="icon"><i class="fa fa-whatsapp"></i></div>';
                                echo '<h4>WhatsApp</h4>';
                                echo '<p><a target="_blank" href="'.Check::getNumZap(WATSAPP ,'Atendimento Imóveis em Ubatuba').'">'.WATSAPP.'</a> </p>';
                                echo '</div>';
                           endif;
                        ?>                        
                    </div>
                    <div class="clearfix"></div>                  
                </div>
                <!-- Sidebar end -->
            </div>
        </div>

       
       
       
<div class="row">
    <?php
    $readImoveisMais = new Read;
    $readImoveisMais->ExeRead("imoveis", "WHERE status = '1' AND id != '$id' AND bairro_id = '$bairro_id' ORDER BY visitas DESC LIMIT 4");
    if($readImoveisMais->getResult()):
?>
<div class="main-title-2">
    <h1><span>Veja também</span></h1>
</div>
<div class="row">
<?php 
    foreach($readImoveisMais->getResult() as $key=>$imovelMais):
    $active = ($key == '0' ? ' active' : '');
 ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                
            <div class="property-2" style="min-height: 350px !important;">
            
            <div class="property-img">
                <div class="property-tag button alt featured">Referência: <?= $imovelMais['referencia'];?></div>
                <div class="price-ratings">
                    <div class="price"><?php if($imovelMais['exibirvalores'] == '1'): echo 'R$'.number_format($imovelMais['valor'], 0 , ',' , '.'); else: echo ''; endif;?></div>
                    <div class="ratings">
                    <?php    
                        $readStars = new Read; 
                        $readStars->FullRead("SELECT SUM(visitas) AS TotalViews FROM imoveis WHERE status = '1'");              
                        $TotalViews = $readStars->getResult()[0]['TotalViews']; 
                                             
                       if($imovelMais['visitas'] == '0'):
                                echo '<i class="fa fa-star-o"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                       else:
                            $percent = ($imovelMais['visitas'] * $TotalViews) / 100;
                            $percentPtag = substr($percent, 0, 2);
                            if($percentPtag <= 20):
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                            elseif($percentPtag >= 21 && $percentPtag <= 40):
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                            elseif($percentPtag >= 41 && $percentPtag <= 60):
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                            elseif($percentPtag >= 61 && $percentPtag <= 80):
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star-o"></i>';
                            elseif($percentPtag >= 81 && $percentPtag <= 100):
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star"></i>';
                                echo '<i class="fa fa-star"></i>';
                            endif;
                       endif;
                    ?>                   
                        
                    </div>
                </div>
                <?php
	               if($imovelMais['img'] == ''):
                ?>
                    <img src="<?= BASE;?>/tim.php?src=<?= PATCH.'/images/image.jpg';?>&w=262&h=175&q=100&zc=1" alt="<?= $imovelMais['nome'];?>" class="img-responsive"/>
                <?php   
                   else:
                ?>
                    <img src="<?= BASE;?>/tim.php?src=<?= BASE.'/uploads/'.$imovelMais['img'];?>&w=750&h=500&q=100&zc=1" alt="<?= $imovelMais['nome'];?>" class="img-responsive"/>
                <?php                     
                   endif;
                ?>                
                <div class="property-overlay">
                    <a href="<?= BASE;?>/imoveis/imovel/<?= $imovelMais['url'];?>" class="overlay-link">
                        <i class="fa fa-link"></i>
                    </a>                    
                    <?php
                        $readImovelGbi = new Read; 
                        $readImovelGbi->ExeRead("imovel_gb", "WHERE id_imovel = :imovelId", "imovelId={$imovelMais['id']}");
                        if($readImovelGbi->getResult()):
                            echo '<div class="property-magnify-gallery">'; 
                            echo '<a href="'.BASE.'/uploads/'.$imovelMais['img'].'" class="overlay-link"><i class="fa fa-expand"></i></a>';                                        
                                foreach($readImovelGbi->getResult() as $imovelgbii):                                        
                                echo '<a href="'.BASE.'/uploads/'.$imovelgbii['img'].'" class="hidden"></a>';                                      
                                endforeach;
                            echo '</div>';
                        endif; 
                    ?>                    
                </div>
            </div>
            <!-- content -->
            <div class="content">
                <!-- title -->
                <h4 class="title">
                    <a href="<?= BASE;?>/imoveis/imovel/<?= $imovelMais['url'];?>"><?= $imovelMais['nome'];?></a>
                </h4>
                <!-- Property address -->
                <h3 class="property-address">
                    <a href="<?= BASE;?>/imoveis/imovel/<?= $imovelMais['url'];?>">
                        <i class="fa fa-map-marker"></i><?= Check::getBairro($imovelMais['bairro_id'],'nome');?>, <?= Check::getCidade($imovelMais['cidade_id'],'cidade_nome');?>/<?= Check::getUf($imovelMais['uf_id'],'estado_uf');?>
                    </a>
                </h3>
            </div>
            
            <ul class="facilities-list clearfix">
                <?php
                   if($imovelMais['areatotal']):
                        echo '<li>';
                        echo '<i class="flaticon-square-layouting-with-black-square-in-east-area"></i>';
                        echo '<span> '.$imovelMais['areatotal'].'</span>';
                        echo '</li>';
                   endif;
                   if($imovelMais['dormitorios']):
                        echo '<li>';
                        echo '<i class="flaticon-bed"></i>';
                        echo '<span> '.$imovelMais['dormitorios'].'</span>';
                        echo '</li>';
                   endif;
                   if($imovelMais['banheiro']):
                        echo '<li>';
                        echo '<i class="flaticon-holidays"></i>';
                        echo '<span> '.$imovelMais['banheiro'].'</span>';
                        echo '</li>';
                   endif;
                   if($imovelMais['garagem']):
                        echo '<li>';
                        echo '<i class="flaticon-vehicle"></i>';
                        echo '<span> '.$imovelMais['garagem'].'</span>';
                        echo '</li>';
                   endif;
                ?>
            </ul>
        </div>
                
            </div>
<?php    
   endforeach;
?>
        </div>
        
<?php endif; ?>
</div>       
      
      
    </div>
</div>
<!-- Properties details page end -->


<?php require(REQUIRE_PATCH . '/include/footer.inc.php'); ?>