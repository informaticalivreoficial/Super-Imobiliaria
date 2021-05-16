<?php
if ($Link->getData()):
    extract($Link->getData());
else:
    header('Location: ' . BASE . DIRECTORY_SEPARATOR . '404');
endif;
?>	
<?php require(REQUIRE_PATCH . '/include/header.inc.php'); ?>
<!-- Blog body start -->
<div class="blog-body content-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-xs-12 col-md-push-4">
                <!-- Blog box start -->
                <div class="thumbnail blog-box clearfix">                
                    <?php
                    $pasta = 'uploads/';
                    if(file_exists($pasta.$thumb) && $thumb != null):
                        echo '<img src="'.BASE.'/uploads/'.$thumb.'" alt="'.$titulo.'" class="img-responsive"/>';
                    else:
                        echo '<img src="'.REQUIRE_PATCH.'/images/image.jpg" alt="'.$titulo.'" class="img-responsive"/>';
                    endif;
                    ?>
                    <div class="caption detail">
                        <!--Main title -->
                        <h3 class="title">
                            <?= $titulo;?>
                        </h3>
                        <!-- Post meta -->
                        <div class="post-meta">
                            <span><a href="#"><i class="fa fa-user"></i><?= Check::getUser("usuario",$autor,'nome');?></a></span>
                            <span><a><i class="fa fa-calendar "></i><?= strftime('%d %b, %Y', strtotime($data));?></a></span>
                            <span><a href="#"><i class="fa fa-bars"></i> <?= Check::Categoria("cat_posts",$categoria,'nome');?></a></span>                        
                            <span><a href="#"><i class="fa fa-comments"></i><?= Check::getComentariosCount($id);?></a></span>
                        </div>
                        <!-- Social list -->
                        <div id="shareIcons"></div>
                        <?= $content;?>
                        
                        <?php
                            $readPostGb = new Read;
                            $readPostGb->ExeRead("posts_gb","WHERE post_id = :artId","artId={$id} ORDER BY data DESC");
                            if($readPostGb->getResult()):
                        ?>
                            <div class="row clearfix t-s">                            
                        <?php foreach($readPostGb->getResult() as $gb): ?>
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
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
                            <?php
                                $readTags = new Read;
                                $readTags->ExeRead("posts", "WHERE tipo = 'artigo' AND status = '1' AND tags != '' AND id != :artId ORDER BY data DESC LIMIT 4","artId={$id}");
                                if($readTags->getResult()):                                        
                                    echo '<div class="tags-box">';
                                    echo '<h2>Tags</h2>';                        
                                        echo '<ul class="tags">';                            
                                        foreach($readTags->getResult() as $tags):
                                        $tag = $tags['tags'];  
                                        $array = explode(",", $tags['tags']);                            
                                        foreach($array as $tag){
                                        $tag = trim($tag);                                                       
                                        echo '<li><a href="'.BASE.'/blog/artigo/'.$tags['url'].'">'.$tag.'</a></li>';
                                        }
                                        endforeach;
                                        echo '</ul>';
                                    echo '</div>';                                        
                                else:
                                    echo '';
                                endif;
                            ?>                               
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
                </div>
                <!-- Blog box end -->

                  <?php
                  // SE PERMITIR COMENTARIOS NO POST
                   if($comentarios == '1'):
                  
                  
                    $readComentarios = new Read;
                    $readComentarios->ExeRead("comentarios", "WHERE resp_id = '0' AND resp_resp_id = '0' AND post_id = :postId AND status = '1'", "postId={$id}");
                    if($readComentarios->getResult()):
                    ?>
                    <div class="comments-section sidebar-widget">
                    <div class="main-title-2"><h1>Comentários</h1></div>
                    <ul class="comments">
                    <?php
                        foreach($readComentarios->getResult() as $coment):                    
                    ?>                        
                    <li>
                            <div class="comment">
                                <div class="comment-author"> 
                                    <?php
                                        $readUser = new Read;
                                        $readUser->ExeRead("usuario", "WHERE email = :emailId", "emailId={$coment['email']}");
                                        if($readUser->getResult()):
                                            $userComent = $readUser->getResult()['0'];
                                            echo '<a href="#">';
                                            echo '<img src="'.BASE.'/uploads/'.$userComent['avatar'].'" alt="'.$coment['nome'].'" />';
                                            echo '<a/>';
                                        else:
                                            $nomeComent = $coment['nome'];
                                            echo '<a href="#">';
                                            echo '<img src="'.PATCH.'/images/avatar.png" alt="'.$coment['nome'].'" />';
                                            echo '<a/>';
                                        endif;
                                    ?>                                
                                </div>
                                <div class="comment-content">
                                    <div class="comment-meta">
                                        <div class="comment-meta-author">
                                            <?= $coment['nome'];?>
                                        </div>
                                        <div class="comment-meta-reply">
                                            <a href="javascript:;" data-toggle="modal" data-target="#1">Responder</a>
                                        </div>
                                        <div class="comment-meta-date">
                                            <span class="hidden-xs"><?= date('d/m/Y', strtotime($coment['data']));?></span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="comment-body">
                                        <p><?= $coment['comentario'];?></p>
                                    </div>
                                </div>
                            </div>

<?php
    $readComentariosResposta = new Read;
    $readComentariosResposta->ExeRead("comentarios", "WHERE resp_id = '$coment[id]' AND resp_resp_id = '0' AND post_id = :postId AND status = '1'", "postId={$id}");
    if($readComentariosResposta->getResult()):
        $comentarioresp = $readComentariosResposta->getResult()['0'];    
?>
<ul>
    <li>
        <div class="comment">
            <div class="comment-author">
                <?php
                   $readUser = new Read;
                   $readUser->ExeRead("usuario", "WHERE email = :emailUser", "emailUser={$comentarioresp['email']}");
                   if($readUser->getResult()):
                        $userComent = $readUser->getResult()['0'];
                        echo '<a href="#">';
                        echo '<img src="'.BASE.'/uploads/'.$userComent['avatar'].'" alt="'.$userComent['nome'].'"/>';
                        echo '<a/>';
                   else:
                        echo '<a href="#">';
                        echo '<img src="'.PATCH.'/images/avatar.png" alt="'.$comentarioresp['nome'].'"/>';
                        echo '<a/>';
                   endif;
                 ?> 
            </div>

            <div class="comment-content">
                <div class="comment-meta">
                    <div class="comment-meta-author">
                        <?= $comentarioresp['nome'];?>
                    </div>
                    
                    <div class="comment-meta-date">
                        <span class="hidden-xs"><?= date('d/m/Y', strtotime($comentarioresp['data']));?></span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="comment-body">                    
                    <p><?= $comentarioresp['comentario'];?></p>
                </div>
            </div>
        </div>
        
    </li>
</ul>
<?php endif;?> 
                        
</li>                        
                        
    <!-- MODAL RESPOSTA -->
    <div class="modal fade" id="1">
		<div class="modal-dialog">
			<div class="modal-content">				
				<div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h5 class="modal-title"><strong>Responder Comentário de <strong><?= $coment['nome'];?></strong></strong></h5>
				</div>              
                <div class="modal-body">    				
      			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <div class="formContatoResposta">
                        <form action="" method="post" class="j_formsubmitcomentario">
                            <div class="form-group">
                                <div class="alertas"></div>
                                <input class="noclear" type="hidden" name="action" value="comentario" />
                                <input class="noclear" type="hidden" name="post_id" value="<?= $id;?>" />
                                <input class="noclear" type="hidden" name="resp_id" value="<?= $coment['id'];?>" />
                                <input type="hidden" class="noclear" name="bairro" value="" />
                                <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                            </div>
                            <div class="form_hide">
                            <div class="form-group">
                                <input type="text" name="nome" class="input-text" placeholder="Seu Nome"/>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="input-text" placeholder="Seu E-mail"/>
                            </div>
                            <div class="form-group">
                                <textarea class="input-text" name="comentario" style="min-height: 120px;"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="button-md button-theme btn-block" id="b_nome">Enviar Comentário</button>
                            </div>                            
                            </div>
                        
                    </div> 
                </div> 
		       
                </div>
        		<div class="modal-footer" style="border-top: 0px!important;">
        			                    
        		</div>
                </form>
		 </div>
	  </div>
   </div>
   <!-- MODAL RESPOSTA -->
                                         
        <?php endforeach;?>
        </ul>
       </div>
        <?php endif;?>
                
                <div class="contact-1 sidebar-widget">
                    <div class="main-title-2">
                        <h1>Deixe seu Comentário</h1>
                    </div>
                    <div class="contact-form">
                        <form  action="" method="post" class="j_formsubmitcomentario">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="alertas"></div>
                                </div>                                
                                <div class="form_hide">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group fullname">
                                        <input class="noclear" type="hidden" name="action" value="comentario" />
                                        <input class="noclear" type="hidden" name="post_id" value="<?= $id;?>" />
                                        <input type="hidden" class="noclear" name="bairro" value="" />
                                        <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                                        <input type="text" name="nome" class="input-text" placeholder="Seu Nome"/>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group enter-email">
                                        <input type="email" name="email" class="input-text" placeholder="Seu E-mail"/>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
                                    <div class="form-group message">
                                        <textarea class="input-text" name="comentario" placeholder="Comentário"></textarea>
                                    </div>
                                </div>                                
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group enter-email" style="text-align: right;">
                                        <button style="width: 100%;" type="submit" class="button-md button-theme" id="b_nome">Enviar Comentário</button>
                                    </div>
                                </div>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Contact end -->
                <?php endif;// FIM SE PERMITIR COMENTARIOS NO POST?>
                
            </div>
            
            
            

            <div class="col-lg-4 col-md-4 col-xs-12 col-md-pull-8">
                <div class="sidebar">                    
                    <?php
                        $readCategorias = new Read;
                        $readCategorias->ExeRead("cat_posts", "WHERE id_pai IS NOT NULL");
                        if($readCategorias->getResult()):                          
                            echo '<div class="sidebar-widget category-posts">';
                            echo '<div class="main-title-2">';
                            echo '<h1>Categorias</h1>';
                            echo '</div>';
                            echo '<ul class="list-unstyled list-cat">';
                            foreach($readCategorias->getResult() as $categoria):
                                $readPostCount = new Read;
                                $readPostCount->ExeRead("posts", "WHERE categoria = :catPai", "catPai={$categoria['id']}");
                                echo '<li><a href="'.BASE.'/blog/categoria/'.$categoria['url'].'">'.$categoria['nome'].' </a> <span>('.$readPostCount->getRowCount().')  </span></li>';                   
                            endforeach;
                            echo '</ul>';
                            echo '</div>';
                        endif;
                     ?>
                    
                    
                    <?php
                        $readMaisLidos = new Read;
                        $readMaisLidos->ExeRead("posts", "WHERE status = '1' AND tipo = 'artigo' ORDER BY visitas DESC LIMIT 3");
                        if($readMaisLidos->getResult()):
                                echo '<div class="sidebar-widget popular-posts">';
                                echo '<div class="main-title-2">';
                                echo '<h1>Mais Lidos</h1>';
                                echo '</div>';
                            foreach($readMaisLidos->getResult() as $maislidos):
                                echo '<div class="media">';
                                echo '<div class="media-left">';
                                echo '<img class="media-object" src="'.BASE.'/tim.php?src='.BASE.'/uploads/'.$maislidos['thumb'].'&w=90&h=63&q=100&zc=1" alt="'.$maislidos['titulo'].'">';
                                echo '</div>';
                                echo '<div class="media-body">';
                                echo '<h3 class="media-heading">';
                                echo '<a href="'.BASE.'/blog/artigo/'.$maislidos['url'].'">'.$maislidos['titulo'].'</a>';
                                echo '</h3>';
                                echo '<p>'.strftime('%d %b, %Y', strtotime($maislidos['data'])).'</p>';                                
                                echo '</div>';
                                echo '</div>'; 
                            endforeach;
                                echo '</div>';                       
                       endif; 
                    ?>
                    
                    <?php
                        $readTags1 = new Read;
                        $readTags1->ExeRead("posts", "WHERE tipo = 'artigo' AND status = '1' AND tags != '' AND id != :artId ORDER BY data DESC LIMIT 9","artId={$id}");
                        if($readTags1->getResult()):
                            echo '<div class="sidebar-widget tags-box">';
                            echo '<div class="main-title-2"><h1>Tags</h1></div>';                        
                                echo '<ul class="tags">';                            
                                foreach($readTags1->getResult() as $tags2):
                                $tag1 = $tags2['tags'];                        
                               
                                $array = explode(",", $tags2['tags']);                            
                                foreach($array as $tag1){
                                $tag1 = trim($tag1);                                                       
                                echo '<li><a href="'.BASE.'/blog/artigo/'.$tags2['url'].'">'.$tag1.'</a></li>';
                                }
                                                        
                                endforeach;
                                echo '</ul>';
                            echo '</div>';                                        
                        else:
                            echo '';
                        endif;
                    ?>
                    
                    
                    <!-- Social media -->
                    <div class="social-media sidebar-widget clearfix">
                        <!-- Main Title 2 -->
                        <div class="main-title-2">
                            <h1>Redes Sociais</h1>
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
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Blog body end -->

<?php require(REQUIRE_PATCH . '/include/footer.inc.php'); ?>v>