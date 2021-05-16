<?php
    //$Tipo = filter_input(INPUT_GET, 'tipo', FILTER_VALIDATE_INT);
    //$CidadeFiltro = filter_input(INPUT_GET, 'cidade', FILTER_VALIDATE_INT);
    //$BairroFiltro = filter_input(INPUT_GET, 'bairro', FILTER_VALIDATE_INT);
    //$CategoriaFiltro = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT);
    //$SubCategoriaFiltro = filter_input(INPUT_GET, 'subcategoria', FILTER_VALIDATE_INT);
    //$Valor = filter_input(INPUT_GET, 'dormitorios', FILTER_VALIDATE_INT);
    //$Dormitorios = filter_input(INPUT_GET, 'dormitorios', FILTER_VALIDATE_INT);

    $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    //var_dump($post);
    if ($Link->getData()):
       extract($Link->getData());
    else:

    if(isset($post['bairro']) && $post['bairro'] == ''):
        $Bairro = "";
    else:
        $Bairro = " AND bairro_id = '{$post['bairro']}'";
    endif;

    if(isset($post['dormitorios']) && $post['dormitorios'] == ''):
        $Dormitorios = "";
    else:
        $Dormitorios = " AND dormitorios = '{$post['dormitorios']}'";
    endif;

    if(isset($post['cidade']) && $post['cidade'] == ''):
        $Cidade = "";
    else:
        $Cidade = " AND cidade_id = '{$post['cidade']}'";
    endif;

    if(isset($post['valores']) && $post['valores'] == ''):
        $Valor = "";
    else:
        $Valor = " AND valor < '{$post['valores']}'";
    endif;
?>

<?php require(REQUIRE_PATCH . '/include/header.inc.php'); ?>

<?php
$getPage = (!empty($Link->getLocal()[2]) ? $Link->getLocal()[2] : 1);
$Pager = new Pager(BASE . '/imoveis/busca-avancada/');
$Pager->ExePager($getPage, 40);

$readImoveisSearch = new Read;
$readImoveisSearch->ExeRead("imoveis", "WHERE status = '1' AND tipo = :Tipo {$Cidade} {$Bairro} {$Dormitorios} {$Valor} ORDER BY id DESC, referencia DESC LIMIT :limit OFFSET :offset", "Tipo={$post['tipo']}&limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");

if(!$readImoveisSearch->getResult()):
    echo '<div class="content-area featured-properties">';
    echo '<div class="container">';
    echo '<div class="alert alert-info wow fadeInRight delay-03s" role="alert" style="visibility: visible; animation-name: fadeInRight;">';
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
    echo '<strong>Desculpe</strong> não foram encontrados resultados para sua Busca!';
    echo '</div>'; 
    echo '</div>';
    echo '</div>';
endif;
?>

<?php require(REQUIRE_PATCH . '/include/search.inc.php'); ?>

<!-- MAIN -->
<div class="properties-details-page content-area">

<!-- Busca Avançada Início -->
<div class="content-area featured-properties">
    <div class="container">
        <!-- Main title -->
        <div class="main-title">
            <h1>Resultado da Pesquisa</h1>
        </div>
        
        <div class="row">
            <div class="filtr-container">
                <?php
                    foreach($readImoveisSearch->getResult() as $imovel):
                    extract($imovel);
                ?>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="property" style="min-height: 440px !important;">
                        <!-- Property img -->
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
                                <img src="<?= BASE?>/tim.php?src=<?= BASE.'/uploads/'.$img;?>&w=360&h=240&q=100&zc=1" alt="<?= $nome;?>" class="img-responsive"/>
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
                        <!-- Property content -->
                        <div class="property-content">
                            <!-- title -->
                            <h1 class="title">
                                <a href="<?= BASE?>/imoveis/imovel/<?= $url;?>"><?= $nome;?></a>
                            </h1>
                            <!-- Property address -->
                            <h3 class="property-address">
                                <a href="<?= BASE?>/imoveis/imovel/<?= $url;?>">
                                    <i class="fa fa-map-marker"></i><?= Check::getBairro($bairro_id,'nome');?>, <?= Check::getCidade($cidade_id,'cidade_nome');?>/<?= Check::getUf($uf_id,'estado_uf');?>
                                </a>
                            </h3>
                            <!-- Facilities List -->
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
                </div>
                <?php
	               endforeach;
                ?>
               
            </div>            
        </div>
    </div>
</div>
<!-- Busca Avançada Fim -->    
    
  
</div>
</div><!-- MAIN -->
<?php endif; ?>
<?php require(REQUIRE_PATCH . '/include/footer.inc.php'); ?>