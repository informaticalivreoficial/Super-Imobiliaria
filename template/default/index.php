<?php require(REQUIRE_PATCH . '/include/header.inc.php'); ?>
<?php require(REQUIRE_PATCH . '/include/slide.inc.php'); ?>
<?php require(REQUIRE_PATCH . '/include/search.inc.php'); ?>

<!-- RESULTADO DO FILTRO DE BUSCA  -->  
<div class="resultado"></div>

<?php
    $readIm = new Read;
    $readIm->ExeRead("imoveis", "WHERE status = '1' ORDER BY id DESC, referencia DESC LIMIT 42");
    if($readIm->getResult()):
?>
<div class="properties-section-body content-area">
<div class="container">
<!-- TÍTULO  -->
<div class="main-title">
<h1>Imóveis Recentes</h1>
<div class="row">
<?php        
        foreach($readIm->getResult() as $imovel):
    ?>
    
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 wow fadeInUp delay-03s">
            <div class="property" style="min-height: 440px !important;">
                <!-- Property img -->
                <div class="property-img">
                    <div class="property-tag button alt featured">Referência: <?= $imovel['referencia'];?></div>
                    <?php $objetivo = ($imovel['tipo'] == '1' ? 'Aluga' : 'Vende');?>
                    <div class="property-tag button sale"><?= $objetivo;?></div>
                    <div class="property-price"><?php if($imovel['exibirvalores'] == '1'): echo 'R$'.number_format($imovel['valor'], 0 , ',' , '.'); else: echo ''; endif;?></div>
                    <?php
                           if($imovel['img'] == null):
                    ?> 
                        <img src="tim.php?src=<?= PATCH.'/images/images.jpg';?>&w=360&h=240&q=100&zc=1" alt="<?= $imovel['nome'];?>" class="img-responsive"/>
                    <?php  
                       else:
                    ?> 
                        <img src="tim.php?src=<?= BASE.'/uploads/'.$imovel['img'];?>&w=360&h=240&q=100&zc=1" alt="<?= $imovel['nome'];?>" class="img-responsive"/>
                    <?php   
                       endif;
                    ?>                    
                    <div class="property-overlay">
                        <a href="imoveis/imovel/<?= $imovel['url'];?>" class="overlay-link">
                            <i class="fa fa-link"></i>
                        </a>                                                
                    <?php
                      $readImovelGbi = new Read;  
                      $readImovelGbi->ExeRead("imovel_gb", "WHERE id_imovel = :imovelId", "imovelId={$imovel['id']}");
                      if($readImovelGbi->getResult()):
                        echo '<div class="property-magnify-gallery">'; 
                        echo '<a href="'.BASE.'/uploads/'.$imovel['img'].'" class="overlay-link"><i class="fa fa-expand"></i></a>';
                          foreach($readImovelGbi->getResult() as $imovelgbii):                                        
                            echo '<a href="'.BASE.'/uploads/'.$imovelgbii['img'].'" class="hidden"></a>';                                      
                          endforeach;
                        echo '</div>';
                      endif;
                    ?> 
                    </div>
                </div>
                <!-- Property content -->
                <div class="property-content">
                    <!-- title -->
                    <h1 class="title">
                        <a href="imoveis/imovel/<?= $imovel['url'];?>"><?= $imovel['nome'];?></a>
                    </h1>
                    <!-- Property address -->
                    <h3 class="property-address">
                        <a href="imoveis/imovel/<?= $imovel['url'];?>">
                            <i class="fa fa-map-marker"></i><?= Check::getBairro($imovel['bairro_id'],'nome');?>, <?= Check::getCidade($imovel['cidade_id'],'cidade_nome');?>/<?= Check::getUf($imovel['uf_id'],'estado_uf');?>
                        </a>
                    </h3>
                    <!-- Facilities List -->
                    <ul class="facilities-list clearfix">
                        <?php
                           if($imovel['areatotal']):
                                echo '<li>';
                                echo '<i class="flaticon-square-layouting-with-black-square-in-east-area"></i>';
                                echo '<span>'.$imovel['areatotal'].' '.Check::getSigla($imovel['medidas']).'</span>';
                                echo '</li>';
                           endif;
                           if($imovel['dormitorios']):
                                echo '<li>';
                                echo '<i class="flaticon-bed"></i>';
                                echo '<span>'.$imovel['dormitorios'].' Dorm.</span>';
                                echo '</li>';
                           endif;
                           if($imovel['banheiro']):
                                echo '<li>';
                                echo '<i class="flaticon-holidays"></i>';
                                echo '<span>'.$imovel['banheiro'].' Banh.</span>';
                                echo '</li>';
                           endif;
                           if($imovel['garagem']):
                                echo '<li>';
                                echo '<i class="flaticon-vehicle"></i>';
                                echo '<span>'.$imovel['garagem'].' Garag.</span>';
                                echo '</li>';
                           endif;
                        ?>
                    </ul>

                </div>
            </div>
        </div>    
    <?php
        endforeach;
?>

<a class="button-md button-theme" href="<?= BASE;?>/imoveis/index" data-hover="Ver mais Imóveis"> Ver mais Imóveis </a>
</div>
</div>
</div>
</div>       
<?php endif; // Fim Lista de Imóveis Recentes?> 



<!-- IMOVEIS MAIS VISUALIZADOS INICIO -->
<?php
   $readImoveisMais = new Read; 
   $readImoveisMais->ExeRead("imoveis", "WHERE status = '1' ORDER BY visitas DESC LIMIT 8");
   if($readImoveisMais->getResult()):
?>
<div class="mb-70 recently-properties chevron-icon">
<div class="container">
<!-- MAIN TITULO -->
<div class="main-title">
<h1>Mais Visualizados</h1>
</div>
<div class="row">
<div class="carousel our-partners slide" id="ourPartners2">
<div class="col-lg-12 mrg-btm-30">
<a class="right carousel-control" href="#ourPartners2" data-slide="prev"><i class="fa fa-chevron-left icon-prev"></i></a>
<a class="right carousel-control" href="#ourPartners2" data-slide="next"><i class="fa fa-chevron-right icon-next"></i></a>
</div>
<div class="carousel-inner">
<?php 
    foreach($readImoveisMais->getResult() as $key=>$imovelMais):
    $active = ($key == '0' ? ' active' : '');
 ?>
<div class="item<?= $active;?>">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        
        <div class="property-2" style="min-height: 350px !important;">
            
            <div class="property-img">
                
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
                    <img src="tim.php?src=<?= PATCH.'/images/image.jpg';?>&w=262&h=175&q=100&zc=1" alt="<?= $imovelMais['nome'];?>" class="img-responsive"/>
                <?php   
                   else:
                ?>
                    <img src="tim.php?src=<?= BASE.'/uploads/'.$imovelMais['img'];?>&w=262&h=175&q=100&zc=1" alt="<?= $imovelMais['nome'];?>" class="img-responsive"/>
                <?php                     
                   endif;
                ?>
                <div class="property-overlay">
                    <a href="imoveis/imovel/<?= $imovelMais['url'];?>" class="overlay-link">
                        <i class="fa fa-link"></i>
                    </a>                    
                    <?php
                        $readImovelGbi = new Read;
                        $readImovelGbi->ExeRead("imovel_gb", "WHERE id_imovel = :idImovel","idImovel={$imovelMais['id']}");
                        if($readImovelGbi->getResult()):
                            $imovelgbii = $readImovelGbi->getResult()['0'];
                            echo '<div class="property-magnify-gallery">'; 
                            echo '<a href="'.BASE.'/uploads/'.$imovelMais['img'].'" class="overlay-link"><i class="fa fa-expand"></i></a>';                                        
                            foreach($readImovelGbi as $imovelgbii):                                        
                            echo '<a href="'.BASE.'/uploads/'.$imovelgbii['img'].'" class="hidden"></a>';                                      
                            endforeach;
                            echo '</div>';
                        endif;                         
                    ?>
                </div>
            </div>
            <!-- CONTEUDO -->
            <div class="content">
                <!-- TITULO -->
                <h4 class="title">
                    <a href="imoveis/imovel/<?= $imovelMais['url'];?>"><?= $imovelMais['nome'];?></a>
                </h4>
                <!-- ENDEREÇO -->
                <h3 class="property-address">
                    <a href="imoveis/imovel/<?= $imovelMais['url'];?>">
                        <i class="fa fa-map-marker"></i><?= Check::getBairro($imovelMais['bairro_id'],'nome');?>, <?= Check::getCidade($imovelMais['cidade_id'],'cidade_nome');?>/<?= Check::getUf($imovelMais['uf_id'],'estado_uf');?>
                    </a>
                </h3>
            </div>
            
            <ul class="facilities-list clearfix">
                <li>
                    <i class="flaticon-square-layouting-with-black-square-in-east-area"></i>
                    <span><?= $imovelMais['areatotal'];?></span>
                </li>
                <li>
                    <i class="flaticon-bed"></i>
                    <span><?= $imovelMais['dormitorios'];?></span>
                </li>
                <li>
                    <i class="flaticon-holidays"></i>
                    <span><?= $imovelMais['banheiro'];?></span>
                </li>
                <li>
                    <i class="flaticon-vehicle"></i>
                    <span><?= $imovelMais['garagem'];?></span>
                </li>
            </ul>
        </div>        
    </div>
</div>
<?php    
   endforeach;
?>
</div>
</div>
</div>
</div>
</div>
<?php endif; ?>
<!-- IMOVEIS MAIS VISUALIZADOS FIM -->



<div class="clearfix"></div>

<!-- Categories strat -->
<div class="categories">
    <div class="container">
        <!-- Main title -->
        <div class="main-title">
            <h1>Locais Populares</h1>
        </div>
        <div class="clearfix"></div>
        <div class="row wow">
            <div class="col-lg-7 col-md-7 col-sm-12">
                <div class="row">
                <?php                    
                    $readBairros = new Read;
                    $readBairros->ExeRead("bairros", "WHERE status = '1' ORDER BY count_imoveis DESC LIMIT 2");
                    if($readBairros->getResult()):
                        foreach($readBairros->getResult() as $br):
                            echo '<div class="col-sm-6 col-pad wow fadeInLeft delay-04s">';
                            echo '<div class="category">';
                            echo '<div class="category_bg_box" style="background-image: url('.BASE.'/uploads/'.$br['img'].');">';
                            echo '<div class="category-overlay">';
                            echo '<div class="category-content">';
                            echo '<div class="category-subtitle">'.$br['count_imoveis'].' Imóveis</div>';
                            echo '<h3 class="category-title">';
                            echo '<a href="imoveis/busca-por-referencia&bairro='.$br['id'].'">'.$br['nome'].'</a>';
                            echo '</h3>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        endforeach;                        
                    endif;                                        
                    
                    $readBairros2 = new Read;
                    $readBairros2->ExeRead("bairros", "WHERE status = '1' ORDER BY count_imoveis DESC LIMIT 2,1");
                    if($readBairros2->getResult()):
                        foreach($readBairros2->getResult() as $br2):
                            echo '<div class="col-sm-12 col-pad wow fadeInUp delay-04s">';
                            echo '<div class="category">';
                            echo '<div class="category_bg_box" style="background-image: url('.BASE.'/uploads/'.$br2['img'].');">';
                            echo '<div class="category-overlay">';
                            echo '<div class="category-content">';
                            echo '<div class="category-subtitle">'.$br2['count_imoveis'].' Imóveis</div>';
                            echo '<h3 class="category-title">';
                            echo '<a href="imoveis/busca-por-referencia&bairro='.$br2['id'].'">'.$br2['nome'].'</a>';
                            echo '</h3>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        endforeach;
                    endif;
                    
 
                ?>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 col-pad wow fadeInRight delay-04s">
               <?php                    
                    $readBairros3 = new Read;
                    $readBairros3->ExeRead("bairros", "WHERE status = '1' ORDER BY count_imoveis DESC LIMIT 3,1");
                    if($readBairros3->getResult()):
                        foreach($readBairros3->getResult() as $br3):
                        echo '<div class="category">';
                        echo '<div class="category_bg_box category_long_bg" style="background-image: url('.BASE.'/uploads/'.$br3['img'].');">';
                        echo '<div class="category-overlay">';
                        echo '<div class="category-content">';
                        echo '<div class="category-subtitle">'.$br3['count_imoveis'].' Imóveis</div>';
                        echo '<h3 class="category-title">';
                        echo '<a href="imoveis/busca-por-referencia&bairro='.$br3['id'].'">'.$br3['nome'].'</a>';
                        echo '</h3>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        endforeach;
                    endif;                     
               ?>                
            </div>
            
        </div>
    </div>
</div>
<!-- Categories end-->     
    

<div class="clearfix"></div>

<?php
    $readBlog = new Read;
    $readBlog->ExeRead("posts", "WHERE status = '1' AND tipo = 'artigo' ORDER BY data DESC LIMIT 3");
    if($readBlog->getResult()):
?>
<div class="blog content-area">
<div class="container">
<div class="main-title"><h1>Acompanhe nosso Blog</h1></div>
<div class="row">   
<?php foreach($readBlog->getResult() as $blog): ?>
    <div class="col-lg-4 col-md-4 col-sm-6 wow fadeInLeft delay-04s">
        <div class="thumbnail blog-box-2 clearfix" style="min-height: 470px;">
            <div class="blog-photo">
                <?php
                if($blog['thumb'] == null):
                    echo '<img src="tim.php?src='.PATCH.'/images/image.jpg&w=360&h=200&q=100&zc=1" alt="'.$blog['titulo'].'" class="img-responsive">';
                else:
                    echo '<img src="tim.php?src=uploads/'.$blog['thumb'].'&w=360&h=200&q=100&zc=1" alt="'.$blog['titulo'].'" class="img-responsive">';
                endif;
                ?>                
            </div>
            <div class="caption detail">
                <h4><a href="blog/artigo/<?= $blog['url'];?>"><?= $blog['titulo'];?></a></h4>
                <?= Check::Words($blog['content'],26);?>
                <div class="clearfix"></div>
                <a href="blog/artigo/<?= $blog['url'];?>" class="read-more">Leia +</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div></div></div>    
<?php endif;?>

<?php require(REQUIRE_PATCH . '/include/footer.inc.php'); ?>
<?php include_once('chat/index.php');
 live_chat_facebook(FACEBOOK,BASE);
 ?>