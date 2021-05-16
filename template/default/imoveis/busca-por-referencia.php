<?php //var_dump($Link->getLocal());?>
<?php require(REQUIRE_PATCH . '/include/header.inc.php'); ?>
<!-- Sub banner start -->
<div class="sub-banner overview-bgi" style="background: rgba(0, 0, 0, 0.04) url(<?= BASE.'/uploads/'.IMGTOPO;?>) top left repeat;">
    <div class="overlay">
        <div class="container">
            <div class="breadcrumb-area">
                <h1>Busca por referência</h1>
                <ul class="breadcrumbs">
                    <li><a href="<?= BASE;?>">Início</a></li>
                    <li class="active">Página - Busca por referência</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Sub Banner end -->
<?php
    if(isset($_POST['search'])):        
        $pesquisa = $_POST['search'];
        $pesquisa = Check::Name($pesquisa);
        header('Location: '.BASE.'/imoveis/busca-por-referencia/'.$pesquisa);
    endif;
?>
<!-- view section start -->
<div class="view-section content-area-7">
    <div class="container">
        <div class="row">            
            <div class="col-lg-12">
                <form class="form-search view-search" action="" method="post">
                    <div class="form-group mb-0">
                        <input type="text" style="height: 60px;border-radius: 8px;font-size: 22px;" class="form-control" name="search" placeholder="Pesquisar Imóveis"/>
                    </div>
                    <button type="submit" class="btn" style="font-size: 22px;"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- view section end -->

<!-- MAIN -->
<div class="properties-section-body content-area">
<!-- Lista start -->
<div class="properties-section-body content-area">
<div class="container">
<?php
$bairroSearch = filter_input(INPUT_GET, 'bairro', FILTER_VALIDATE_INT);
if($bairroSearch && !empty($bairroSearch)):
    
    $getPage = (!empty($Link->getLocal()[2]) ? $Link->getLocal()[2] : 1);
    $Pager = new Pager(BASE . '/imoveis/busca-por-referencia/'.$bairroSearch.'/');
    $Pager->ExePager($getPage, 33);
    $readImoveis = new Read;
    $readImoveis->ExeRead("imoveis", "WHERE bairro_id = :bairroId AND status = '1' ORDER BY id DESC, referencia DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}&bairroId={$bairroSearch}");
?>
<!-- FORM VINDO DA INDEX INICIO -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <!-- Listagem start -->
            <div class="row">
            <?php
                foreach($readImoveis->getResult() as $imovel):
                extract($imovel);
            ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 wow fadeInUp delay-03s">
                <!-- Imóvel start -->
                <div class="property" style="min-height: 440px !important;">
                    <!-- IMG -->
                    <div class="property-img">
                        <div class="property-tag button alt featured">Referência: <?= $referencia;?></div>
                        <?php $objetivo = ($tipo == '1' ? 'Aluga' : 'Vende');?>
                        <div class="property-tag button sale"><?= $objetivo;?></div>
                        <div class="property-price"><?php if($exibirvalores == '1'): echo 'R$'.number_format($valor, 0 , ',' , '.'); else: echo ''; endif;?></div>                            
                        <?php
                               if($img == ''):
                        ?> 
                            <img src="<?= BASE?>/tim.php?src=<?= PATCH.'/images/images.jpg';?>&w=360&h=240&q=100&zc=1" alt="<?= $nome;?>" class="img-responsive"/>
                        <?php  
                           else:
                        ?> 
                            <img  src="<?= BASE?>/tim.php?src=<?= BASE.'/uploads/'.$img;?>&w=360&h=240&q=100&zc=1" alt="<?= $nome;?>" class="img-responsive"/>
                        <?php   
                           endif;
                        ?>
                        <div class="property-overlay">
                            <a href="<?= BASE?>/imoveis/imovel/<?= $url;?>" class="overlay-link">
                                <i class="fa fa-link"></i>
                            </a>
                            <?php
                                $readImovelGbi = new Read;
                                $readImovelGbi->ExeRead("imovel_gb", "WHERE id_imovel = :imovelId", "imovelId={$id}");                                    
                                if($readImovelGbi->getResult()):
                                        echo '<div class="property-magnify-gallery">'; 
                                        echo '<a href="'.BASE.'/uploads/'.$img.'" class="overlay-link"><i class="fa fa-expand"></i></a>';                                        
                                        foreach($readImovelGbi->getResult() as $imovelgbii):                                        
                                            echo '<a href="'.BASE.'/uploads/'.$imovelgbii['img'].'" class="hidden"></a>';                                      
                                        endforeach;
                                        echo '</div>';
                                 endif; 
                            ?>
                        </div>
                    </div>
                    <!-- Conteúdo -->
                    <div class="property-content">
                        <!-- Título -->
                        <h1 class="title">
                            <a href="<?= BASE?>/imoveis/imovel/<?= $url;?>"><?= $nome;?></a>
                        </h1>
                        <!-- Endereço -->
                        <h3 class="property-address">
                            <a href="<?= BASE?>/imoveis/imovel/<?= $url;?>">
                                <i class="fa fa-map-marker"></i><?= Check::getBairro($bairro_id,'nome');?>, <?= Check::getCidade($cidade_id,'cidade_nome');?>/<?= Check::getUf($uf_id,'estado_uf');?>
                            </a>
                        </h3>
                        <!-- Facilidades -->
                        <ul class="facilities-list clearfix">
                            <?php
                               if($areatotal):
                                    echo '<li>';
                                    echo '<i class="flaticon-square-layouting-with-black-square-in-east-area"></i>';
                                    echo '<span>'.$areatotal.' '.Check::getSigla($medidas).'</span>';
                                    echo '</li>';
                               endif;
                               if($dormitorios):
                                    echo '<li>';
                                    echo '<i class="flaticon-bed"></i>';
                                    echo '<span>'.$dormitorios.' Dorm.</span>';
                                    echo '</li>';
                               endif;
                               if($banheiro):
                                    echo '<li>';
                                    echo '<i class="flaticon-holidays"></i>';
                                    echo '<span>'.$banheiro.' Banh.</span>';
                                    echo '</li>';
                               endif;
                               if($garagem):
                                    echo '<li>';
                                    echo '<i class="flaticon-vehicle"></i>';
                                    echo '<span>'.$garagem.' Garag.</span>';
                                    echo '</li>';
                               endif;
                            ?>
                        </ul>                           
                    </div>
                </div>
                <!-- Imóvel end -->
            </div>
            <?php   
               endforeach;
            ?>


            </div>
            <!-- Listagem FIM -->

            <!-- Page navigation start -->
            <nav aria-label="Page navigation">
                <?php                            
                    $Pager->ExePaginator("imoveis","WHERE bairro_id = :bairroId AND status = '1' ORDER BY id DESC, referencia DESC","bairroId={$bairroSearch}"); 
                    echo $Pager->getPaginator();
                ?>
            </nav>
            <!-- Page navigation end-->
        </div>
    </div>    
<!-- Lista End -->
<!-- FORM VINDO DA INDEX FIM -->    
<?php    
// BUSCA NA REFERÊNCIA INICIO      
else:    

if(isset($Link->getLocal()[2])):
        
    // BUSCA NA REFERÊNCIA INICIO      
        
    $search = $Link->getLocal()[2];
    $search = str_replace('-',' ',$search);
    
    $getPage = (!empty($Link->getLocal()[2]) ? $Link->getLocal()[2] : 1);
    $Pager = new Pager(BASE . '/imoveis/busca-por-referencia/'.$search.'/');
    $Pager->ExePager($getPage, 33);
    
    $readPesquisa = new Read;
    $readPesquisa->ExeRead("imoveis", "WHERE status = '1' AND (nome LIKE '%' :link '%' OR referencia LIKE '%' :link '%' OR corretor_id LIKE '%' :link '%' OR corretor_id LIKE '%' :link '%') ORDER BY id DESC, referencia DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}&link={$search}");
    if($readPesquisa->getResult()):
?>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                 
                 <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">  
                        <blockquote>O sistema encontrou <strong><?= $readPesquisa->getRowCount();?></strong> resultado(s) na sua pesquisa por <strong><?= $search;?></strong>.</blockquote>
                    </div>
                 </div>
                <!-- Listagem start -->
                <div class="row">
                <?php
	            foreach($readPesquisa->getResult() as $imovel):                   
                    extract($imovel);
                ?>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 wow fadeInUp delay-03s">
                    <!-- Imóvel start -->
                    <div class="property" style="min-height: 440px !important;">
                        <!-- IMG -->
                        <div class="property-img">
                            <div class="property-tag button alt featured">Referência: <?= $referencia;?></div>
                            <?php $objetivo = ($tipo == '1' ? 'Aluga' : 'Vende');?>
                            <div class="property-tag button sale"><?= $objetivo;?></div>
                            <div class="property-price"><?php if($exibirvalores == '1'): echo 'R$'.number_format($valor, 0 , ',' , '.'); else: echo ''; endif;?></div>                            
                            <?php
        	                   if($img == ''):
                            ?> 
                                <img src="<?= BASE?>/tim.php?src=<?= PATCH.'/images/images.jpg';?>&w=360&h=240&q=100&zc=1" alt="<?= $nome;?>" class="img-responsive"/>
                            <?php  
                               else:
                            ?> 
                                <img  src="<?= BASE?>/tim.php?src=<?= BASE.'/uploads/'.$img;?>&w=360&h=240&q=100&zc=1" alt="<?= $nome;?>" class="img-responsive"/>
                            <?php   
                               endif;
                            ?>
                            <div class="property-overlay">
                                <a href="<?= BASE?>/imoveis/imovel/<?= $url;?>" class="overlay-link">
                                    <i class="fa fa-link"></i>
                                </a>                                
                                <?php
                                    $readImovelGbi = new Read;
                                    $readImovelGbi->ExeRead("imovel_gb", "WHERE id_imovel = :imovelId", "imovelId={$id}");                                    
                                    if($readImovelGbi->getResult()):
                                            echo '<div class="property-magnify-gallery">'; 
                                            echo '<a href="'.BASE.'/uploads/'.$img.'" class="overlay-link"><i class="fa fa-expand"></i></a>';                                        
                                            foreach($readImovelGbi->getResult() as $imovelgbii):                                        
                                                echo '<a href="'.BASE.'/uploads/'.$imovelgbii['img'].'" class="hidden"></a>';                                      
                                            endforeach;
                                            echo '</div>';
                                     endif; 
                                ?>
                            </div>
                        </div>
                        <!-- Conteúdo -->
                        <div class="property-content">
                            <!-- Título -->
                            <h1 class="title">
                                <a href="<?= BASE?>/imoveis/imovel/<?= $url;?>"><?= $nome;?></a>
                            </h1>
                            <!-- Endereço -->
                            <h3 class="property-address">
                                <a href="<?= BASE?>/imoveis/imovel/<?= $url;?>">
                                    <i class="fa fa-map-marker"></i><?= Check::getBairro($bairro_id,'nome');?>, <?= Check::getCidade($cidade_id,'cidade_nome');?>/<?= Check::getUf($uf_id,'estado_uf');?>
                                </a>
                            </h3>
                            <!-- Facilidades -->
                            <ul class="facilities-list clearfix">
                                <?php
                                   if($areatotal):
                                        echo '<li>';
                                        echo '<i class="flaticon-square-layouting-with-black-square-in-east-area"></i>';
                                        echo '<span>'.$areatotal.' '.Check::getSigla($medidas).'</span>';
                                        echo '</li>';
                                   endif;
                                   if($dormitorios):
                                        echo '<li>';
                                        echo '<i class="flaticon-bed"></i>';
                                        echo '<span>'.$dormitorios.' Dorm.</span>';
                                        echo '</li>';
                                   endif;
                                   if($banheiro):
                                        echo '<li>';
                                        echo '<i class="flaticon-holidays"></i>';
                                        echo '<span>'.$banheiro.' Banh.</span>';
                                        echo '</li>';
                                   endif;
                                   if($garagem):
                                        echo '<li>';
                                        echo '<i class="flaticon-vehicle"></i>';
                                        echo '<span>'.$garagem.' Garag.</span>';
                                        echo '</li>';
                                   endif;
                                ?>
                            </ul>                           
                        </div>
                    </div>
                    <!-- Imóvel end -->
                </div>
                <?php   
                   endforeach;
                ?>
                                                           
                    
                </div>
                <!-- Listagem FIM -->

                <!-- Page navigation start -->
                <nav aria-label="Page navigation">
                    <?php                            
                        $Pager->ExePaginator("imoveis","WHERE status = '1' AND (nome LIKE '%' :link '%' OR referencia LIKE '%' :link '%' OR corretor_id LIKE '%' :link '%' OR corretor_id LIKE '%' :link '%') ORDER BY id DESC, referencia DESC","&link={$search}"); 
                        echo $Pager->getPaginator();
                    ?>
                </nav>
                <!-- Page navigation end-->
            </div>
        </div>    
<!-- Lista End -->



<?php  
    else:
?>
<div class="row mb-40">
    <div class="col-lg-12">                
        <div class="alert alert-info wow fadeInRight delay-03s" role="alert" style="visibility: visible; animation-name: fadeInRight;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <strong>Desculpe!</strong> Não encontramos nenhum resultado para sua pesquisa.
        </div>                
    </div>
</div>
<?php
    endif;
     
    endif;
?>
<?php

    
endif;
?>











</div>
</div>
</div><!-- MAIN -->



<?php require(REQUIRE_PATCH . '/include/footer.inc.php'); ?>