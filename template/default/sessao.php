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
                <h1><?= $titulo;?></h1>
                <ul class="breadcrumbs">
                    <li><a href="<?= BASE;?>">Início</a></li>
                    <li class="active"><?= $titulo;?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Sub Banner end -->


<!-- Blog body start -->
<div class="blog-body content-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Blog box start -->
                <div class="thumbnail blog-box clearfix">
                    <!-- Inside properties start  -->
                       <?php
            	           if($thumb == null):
                       ?>
                       <div class="caption detail">
                        <!--Main title -->
                        <h3 class="title">
                            <?= $titulo;?>
                        </h3>                        
                        <!-- paragraph -->
                        <p><?= $content;?></p>
                        
                        <?php
                            $readPostGb = new Read;
                            $readPostGb->ExeRead("posts_gb","WHERE post_id = :artId","artId={$id} ORDER BY data DESC");
                            if($readPostGb->getResult()):
                        ?>
                            <div class="row clearfix t-s">                            
                        <?php foreach($readPostGb->getResult() as $gb): ?>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="agent-1">
                                        <!-- Agent img -->
                                        <a rel="ShadowBox[galeria]" href="<?= BASE.'/uploads' . DIRECTORY_SEPARATOR . $gb['img'];?>" class="agent-img">
                                            <?= Check::Image('uploads' . DIRECTORY_SEPARATOR . $gb['img'], $titulo, 600, 460,'img-responsive');?>
                                        </a>
                                    </div>                                
                                </div>
                        <?php endforeach; ?>                            
                            </div>
                        <?php endif; ?>

                        <div class="row clearfix t-s">
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                <!-- Tags box start -->
                                <?php
                                    $readTags = new Read;
                                    $readTags->ExeRead("posts", "WHERE tipo = 'pagina' AND status = '1' AND tags != '' AND id != :artId ORDER BY data DESC LIMIT 4","artId={$id}");
                                    if($readTags->getResult()):
                                        echo '<div class="tags-box">';
                                        echo '<h2>Tags</h2>';                        
                                            echo '<ul class="tags">';                            
                                            foreach($readTags->getResult() as $tags):
                                            $tag = $tags['tags'];                        
                                           
                                            $array = explode(",", $tags['tags']);                            
                                            foreach($array as $tag){
                                            $tag = trim($tag);                                                       
                                            echo '<li><a href="'.BASE.'/sessao/'.$tags['url'].'">'.$tag.'</a></li>';
                                            }
                                                                    
                                            endforeach;
                                            echo '</ul>';
                                        echo '</div>';                                        
                                    endif;
                                ?>
                                <!-- Tags box end -->
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                <!-- Blog Share start -->
                                <div class="social-media clearfix blog-share">
                                    <h2>Compartilhe</h2>
                                    <!-- Social list -->
                                    <div class="shareIcons"></div>
                                </div>
                                <!-- Blog Share end -->
                            </div>
                        </div>
                    </div>
                       <?php    
                           else:
                       ?>
                       <div class="inside-properties">
                       <img src="<?= BASE.'/uploads/'.$thumb;?>" alt="<?= $titulo;?>" class="img-responsive"/>
                    </div>
                    <!-- detail -->
                    <div class="caption detail">
                        <!--Main title -->
                        <h3 class="title">
                            <?= $titulo;?>
                        </h3>                        
                        <!-- paragraph -->
                        <p><?= $content;?></p>
                        
                        
                        <?php
                            $readPostGb = new Read;
                            $readPostGb->ExeRead("posts_gb","WHERE post_id = :artId","artId={$id} ORDER BY data DESC");
                            if($readPostGb->getResult()):
                        ?>
                            <div class="row clearfix t-s">                            
                        <?php foreach($readPostGb->getResult() as $gb): ?>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="agent-1">
                                        <!-- Agent img -->
                                        <a rel="ShadowBox[galeria]" href="<?= BASE.'/uploads' . DIRECTORY_SEPARATOR . $gb['img'];?>" class="agent-img">
                                            <?= Check::Image('uploads' . DIRECTORY_SEPARATOR . $gb['img'], $titulo, 600, 460,'img-responsive');?>
                                        </a>
                                    </div>                                
                                </div>
                        <?php endforeach; ?>                            
                            </div>
                        <?php endif; ?>
                        
                                    
                        <div class="row clearfix t-s">
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                <!-- Tags box start -->
                                <?php
                                    $readTags = new Read;
                                    $readTags->ExeRead("posts", "WHERE tipo = 'pagina' AND status = '1' AND tags != '' AND id != :artId ORDER BY data DESC LIMIT 4","artId={$id}");
                                    if($readTags->getResult()):
                                        echo '<div class="tags-box">';
                                        echo '<h2>Tags</h2>';                        
                                            echo '<ul class="tags">';                            
                                            foreach($readTags->getResult() as $tags):
                                            $tag = $tags['tags'];                        
                                           
                                            $array = explode(",", $tags['tags']);                            
                                            foreach($array as $tag){
                                            $tag = trim($tag);                                                       
                                            echo '<li><a href="'.BASE.'/sessao/'.$tags['url'].'">'.$tag.'</a></li>';
                                            }
                                                                    
                                            endforeach;
                                            echo '</ul>';
                                        echo '</div>';                                        
                                    endif;
                                ?>
                                <!-- Tags box end -->
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                <!-- Blog Share start -->
                                <div class="social-media clearfix blog-share">
                                    <h2>Compartilhe</h2>
                                    <!-- Social list -->
                                    <div class="shareIcons"></div>
                                </div>
                                <!-- Blog Share end -->
                            </div>
                        </div>
                    </div>
                       <?php    
                           endif;
                        ?>
                    
                </div>
                <!-- Blog box end -->

               
            </div>
        </div>
    </div>
</div>
<!-- Blog body end -->

<?php require(REQUIRE_PATCH . '/include/footer.inc.php'); ?>