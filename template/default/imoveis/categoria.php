<?php
$Cat = $Link->getData();
//var_dump($Link->getData());
$getPage = (!empty($Link->getLocal()[3]) ? $Link->getLocal()[3] : 1);
$Pager = new Pager(BASE . '/imoveis/categoria/' . $Cat . '/');
$Pager->ExePager($getPage, 33);

$readCat = new Read;
$readCat->ExeRead("cat_imoveis","WHERE url = :nomeCat","nomeCat={$Cat}");
if($readCat->getResult()):
    $nomeCat = $readCat->getResult()['0']; 
else:
   header('Location: ' . BASE . DIRECTORY_SEPARATOR . '404');    
endif;

$readCatPosts = new Read;
$readCatPosts->ExeRead("imoveis","WHERE status = '1' AND categoria = :cat ORDER BY id DESC, referencia DESC LIMIT :limit OFFSET :offset","cat={$nomeCat['id']}&limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");    
if(!$readCatPosts->getResult()):
    require(REQUIRE_PATCH . '/include/search.inc.php');
    echo '<div class="content-area featured-properties">';
    echo '<div class="container">';
    echo '<div class="alert alert-info wow fadeInRight delay-03s" role="alert" style="visibility: visible; animation-name: fadeInRight;">';
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
    echo '<strong>Desculpe</strong> não foram encontrados resultados!';
    echo '</div>'; 
    echo '</div>';
    echo '</div>';
else:
?>
<?php require(REQUIRE_PATCH . '/include/header.inc.php'); ?>
<!-- Sub-Título -->
<div class="sub-banner overview-bgi" style="background: rgba(0, 0, 0, 0.04) url(<?= BASE.'/uploads/'.IMGTOPO;?>) top left repeat;">
    <div class="overlay">
        <div class="container">
            <div class="breadcrumb-area">
                <h1><?= Check::Categoria("cat_imoveis",$nomeCat['id_pai'], 'nome');?> - <?= $nomeCat['nome'];?></h1>
                <ul class="breadcrumbs">
                    <li><a href="<?= BASE;?>">Início</a></li>
                    <li class="active">Página <?= Check::Categoria("cat_imoveis",$nomeCat['id_pai'], 'nome');?> - <?= $nomeCat['nome'];?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Sub-Título end -->

<!-- MAIN -->
<div class="properties-section-body content-area">

<!-- Lista start -->
<div class="properties-section-body content-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                               

                <!-- Listagem start -->
                <div class="row">
                <?php
                    foreach($readCatPosts->getResult() as $imovel):
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
                        $Pager->ExePaginator("imoveis","WHERE status = '1' AND categoria = :cat ORDER BY id DESC, referencia DESC","&cat={$nomeCat['id']}"); 
                        echo $Pager->getPaginator();
                    ?>
                </nav>
                <!-- Page navigation end-->
            </div>
        </div>
    </div>
</div>
<!-- Lista End -->
</div><!-- MAIN -->
<?php
endif;
?>




<?php require(REQUIRE_PATCH . '/include/footer.inc.php'); ?>