<?php
$getPage = (!empty($Link->getLocal()[2]) ? $Link->getLocal()[2] : 1);
$Pager = new Pager(BASE . '/blog/artigos/');
$Pager->ExePager($getPage, 30);    

?>
<?php require(REQUIRE_PATCH . '/include/header.inc.php'); ?>
<?php
$readPosts = new Read;
$readPosts->ExeRead("posts","WHERE status = '1' AND tipo = 'artigo' ORDER BY data DESC LIMIT :limit OFFSET :offset","limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
if(!$readPosts->getResult()):
    header('Location: ' . BASE . DIRECTORY_SEPARATOR . '404');
else:
?>
<!-- Banner start -->
<div class="blog-banner">
    <div class="container">
        <div class="breadcrumb-area">
            <h1>Blog</h1>
            <ul class="breadcrumbs">
                <li><a href="<?= BASE;?>">Início</a></li>
                <li class="active">Blog - Listagem de Artigos</li>
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
                if(!$readPosts->getResult()): 
             ?>             
             <div class="row mb-40">
                <div class="col-lg-12">                
                    <div class="alert alert-info wow fadeInRight delay-03s" role="alert" style="visibility: visible; animation-name: fadeInRight;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <strong>Desculpe!</strong> Não encontramos nenhum artigo publicado!
                    </div>                
                </div>
            </div>
             <?php   
                else:        
                   foreach($readPosts->getResult() as $art):
                   extract($art);
             ?>        
            <div class="col-lg-4 col-md-4 col-sm-6 ">
                <div class="thumbnail blog-box-2 clearfix" style="min-height: 470px;">
                    <div class="blog-photo">
                        <?php
	                       if($thumb == ''):
                                echo '<img src="'.BASE.'/tim.php?src='.PATCH.'/images/image.jpg&w=360&h=200&q=100&zc=1" alt="'.$titulo.'" class="img-responsive">';
                            else:
                                echo '<img src="'.BASE.'/tim.php?src='.BASE.'/uploads/'.$thumb.'&w=360&h=200&q=100&zc=1" alt="'.$titulo.'" class="img-responsive">';
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
                endif;
             ?>
            
            <div class="col-lg-12">
                <!-- Page navigation start -->
                <nav aria-label="Page navigation">
                    <?php                            
                        $Pager->ExePaginator("posts","WHERE status = '1' AND tipo = 'artigo' ORDER BY data DESC"); 
                        echo $Pager->getPaginator();
                    ?>
                </nav>
                <!-- Page navigation end -->
            </div>            
        </div>
    </div>
</div>
<!-- Blog body end -->



<?php endif;?>

    
<?php require(REQUIRE_PATCH . '/include/footer.inc.php'); ?>
