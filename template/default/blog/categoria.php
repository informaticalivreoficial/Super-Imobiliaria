<?php
//var_dump($Link->getData());
$Cat = $Link->getData();

$getPage = (!empty($Link->getLocal()[3]) ? $Link->getLocal()[3] : 1);
$Pager = new Pager(BASE . '/blog/categoria/' . $Cat . '/');
$Pager->ExePager($getPage, 30);

$readCat = new Read;
$readCat->ExeRead("cat_posts","WHERE url = :nomeCat","nomeCat={$Cat}");
if($readCat->getResult()):
    $nomeCat = $readCat->getResult()['0']; 
else:
   header('Location: ' . BASE . DIRECTORY_SEPARATOR . '404');
endif;
$readCatPosts = new Read;
$readCatPosts->ExeRead("posts","WHERE status = '1' AND tipo = 'artigo' AND categoria = :cat ORDER BY data DESC LIMIT :limit OFFSET :offset","cat={$nomeCat['id']}&limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");    
?>
<?php require(REQUIRE_PATCH . '/include/header.inc.php'); ?>
<!-- Banner start -->
<div class="blog-banner">
    <div class="container">
        <div class="breadcrumb-area">
            <h1><?= $nomeCat['nome'];?></h1>
            <ul class="breadcrumbs">
                <li><a href="<?= BASE;?>">Início</a></li>
                <li class="active">Blog - <?= $nomeCat['nome'];?></li>
            </ul>
        </div>
    </div>
</div>
<!-- Banner end -->

<!-- Blog body start -->
<div class="blog-body content-area">
<div class="container">
<div class="row">
            <?php
                if(!$readCatPosts->getResult()): 
             ?>
             <div class="row mb-40">
                <div class="col-lg-12">                
                    <div class="alert alert-info wow fadeInRight delay-03s" role="alert" style="visibility: visible; animation-name: fadeInRight;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <strong>Desculpe!</strong> Não encontramos nenhum artigo publicado nesta categoria.
                    </div>                
                </div>
            </div>
             <?php   
                else:        
                   foreach($readCatPosts->getResult() as $art):
                   extract($art);
             ?>
            <div class="col-lg-4 col-md-4 col-sm-6 ">
                <div class="thumbnail blog-box-2 clearfix" style="min-height: 470px;">
                    <div class="blog-photo">
                        <?php
                            $pasta = 'uploads/';
                            if(file_exists($pasta.$thumb) && $thumb != null):
                                echo '<img title="'.$titulo.'" alt="'.$titulo.'" src="'.BASE.'/tim.php?src='.BASE.'/uploads/'.$thumb.'&w=360&h=200">';
                             else:
                                echo '<img title="'.$titulo.'" alt="'.$titulo.'" src="'.BASE.'/tim.php?src='.PATCH.'/images/image.jpg&w=360&h=200">';
                             endif;
                        ?>
                    </div>
                    <div class="post-meta">
                        <ul>
                            <li class="profile-user">
                             <?php
                            	if(Check::getUser("usuario",$autor,'avatar') == ''):
                                    echo '<img src="'.PATCH.'/images/avatar.png" alt="'.Check::getUser("usuario",$autor,'nome').'">';
                                else:
                                    echo '<img src="'.BASE.'/uploads/'.Check::getUser("usuario",$autor,'avatar').'" alt="'.Check::getUser("usuario",$autor,'nome').'">';
                                endif;
                            ?>
                            </li>
                            <li><span><?= Check::getUser("usuario",$autor,'nome');?></span></li>
                            <li><i class="fa fa-calendar"></i></li>
                            <li><i class="fa fa-comments"></i> <?= Check::getComentariosCount($id);?></li>
                        </ul>
                    </div>
                    <!-- Detail -->
                    <div class="caption detail">
                        <h4><a href="<?= BASE;?>/blog/artigo/<?= $url;?>"><?= $titulo;?></a></h4>
                        <!-- paragraph -->
                        <?= Check::Words($content,20);?>
                        <div class="clearfix"></div>
                        <!-- Btn -->
                        <a href="<?= BASE;?>/blog/artigo/<?= $url;?>" class="read-more">Leia +</a>
                    </div>
                </div>
            </div>
            <?php   
                endforeach;
             ?>
            <div class="col-lg-12">
                <!-- Page navigation start -->
                <nav aria-label="Page navigation">
                    <?php                            
                        $Pager->ExePaginator("posts","WHERE status = '1' AND tipo = 'artigo' AND categoria = :cat ORDER BY data DESC","cat={$nomeCat['id']}"); 
                        echo $Pager->getPaginator();
                    ?>
                </nav>
                <!-- Page navigation end -->                
            </div>
            <?php endif; ?>
</div>
</div>
</div>
<!-- Blog body end -->

<?php require(REQUIRE_PATCH . '/include/footer.inc.php'); ?>