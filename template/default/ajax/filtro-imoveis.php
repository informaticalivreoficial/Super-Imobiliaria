<?php
require('../../../vendor/autoload.php');
require('../../../app/config.inc.php');

$CidadeFiltro = filter_input(INPUT_GET, 'cidade', FILTER_VALIDATE_INT);
$BairroFiltro = filter_input(INPUT_GET, 'bairro', FILTER_VALIDATE_INT);
$CategoriaFiltro = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT);
$SubCategoriaFiltro = filter_input(INPUT_GET, 'subcategoria', FILTER_VALIDATE_INT);
$Tipo = filter_input(INPUT_GET, 'tipo', FILTER_VALIDATE_INT);
$Dormitorios = filter_input(INPUT_GET, 'dormitorios', FILTER_VALIDATE_INT);

$readBairro = new Read;
$readBairro->ExeRead("bairros", "WHERE id = '$BairroFiltro'");
if($readBairro->getResult()):
    $filtroBairro = 'bairro_id = '.$BairroFiltro.' AND';
else:
    $filtroBairro = '';
endif;

$readCategoria = new Read;
$readCategoria->ExeRead("cat_imoveis", "WHERE id = '$CategoriaFiltro'");
if($readCategoria->getResult()):
    $filtroCategoria = 'cat_pai = '.$CategoriaFiltro.' AND';
else:
    $filtroCategoria = '';
endif;

$readSubCategoria = new Read;
$readSubCategoria->ExeRead("cat_imoveis", "WHERE id = '$SubCategoriaFiltro'");
if($readSubCategoria->getResult()):
    $filtroSubCategoria = 'categoria = '.$SubCategoriaFiltro.' AND';
else:
    $filtroSubCategoria = '';
endif;

if($Tipo):
    $filtroTipo = 'tipo = '.$Tipo.' AND';
else:
    $filtroTipo = '';
endif;

if($Dormitorios):
    $filtroDormitorios = 'dormitorios = '.$Dormitorios.' AND';
else:
    $filtroDormitorios = '';
endif;

$readImoveisSearch = new Read;
$readImoveisSearch->ExeRead("imoveis", "WHERE {$filtroDormitorios} {$filtroTipo} {$filtroSubCategoria} {$filtroCategoria} {$filtroBairro} cidade_id = :idCidade ORDER BY id DESC, referencia DESC LIMIT 20", "idCidade={$CidadeFiltro}");
if($readImoveisSearch->getResult()):
?>

<div class="properties-section-body content-area hideR">
    <div class="container">
        <!-- TÍTULO  -->
        <div class="main-title">
        <div class="row">
        <?php        
            foreach($readImoveisSearch->getResult() as $imovel):
            
        ?>

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 wow fadeInUp delay-03s">
                <div class="property" style="min-height: 440px !important;">
                    <!-- Property img -->
                    <div class="property-img">
                        <div class="property-tag button alt featured">Referência: <?= $imovel['referencia'];?></div>
                        <?php $objetivo = ($imovel['tipo'] == '1' ? 'Aluga' : 'Vende');?>
                        <div class="property-tag button sale"><?= $objetivo;?></div>
                        <div class="property-price">R$<?= number_format($imovel['valor'], 0 , ',' , '.');?></div>
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
        <?php endforeach;?>
        <a class="button-md button-theme" href="<?= BASE;?>/imoveis/index" data-hover="Ver mais Imóveis"> Ver mais Imóveis </a>
    </div>
    </div>
    </div>
</div>  

<?php 
else:
?>   
<div class="properties-section-body content-area hideR">
    <div class="container">
        <div class="row">
            <div class="alert alert-info">Desculpe Nenhum resultado encontrado!</div>
        </div>
    </div>        
</div>
<?php

endif; // Fim Lista de Imóveis Recentes?>
<?php require('../include/head-js.inc.php'); ?>
