<!-- Top header start -->
<header class="top-header hidden-xs" id="top">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="list-inline">
                    <?php
                    if(TELEFONE1):
                         echo '<a href="tel:'.TELEFONE1.'"><i class="fa fa-phone"></i>'.TELEFONE1.'</a>';
                    endif;
                    if(WATSAPP):
                         echo '<a target="_blank" href="'.Check::getNumZap(WATSAPP ,'Atendimento Imóveis em Ubatuba').'"><i class="fa fa-whatsapp"></i>'.WATSAPP.'</a>';
                    endif;
                    if(EMAIL):
                         echo '<a href="mailto:'.EMAIL.'"><i class="fa fa-envelope"></i>'.EMAIL.'</a>';
                    endif;                       
                    ?>
                    
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align: right;">
                <a target="_blank" style="margin-top: 5px;margin-bottom: 5px;" href="<?= BASE.'/pagina/simulador';?>" class="btn button-sm border-button-theme">financiamento</a>
                <a style="margin-top: 5px;margin-bottom: 5px;margin-left: 10px;" href="<?= BASE.'/imoveis/busca-por-referencia';?>" class="btn button-sm border-button-theme">Busca por referência</a>
                 
                
                <ul class="top-social-media pull-right" style="margin-left: 10px;">
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
</header>
<!-- Top header end -->

<!-- Main header start -->
<header class="main-header">
    <div class="container">
        <nav class="navbar navbar-default">
            <div class="navbar-header">                
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navigation" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="<?= BASE;?>" class="logo" title="<?= SITENAME;?>">
                    <img src="<?= BASE; ?>/uploads/<?= LOGOMARCA;?>" alt="<?= SITENAME; ?>"/>
                </a>
                <ul class="top-social-media mobile">
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
            <!-- MENU -->
            <div class="navbar-collapse collapse" role="navigation" aria-expanded="true" id="app-navigation">
                <ul class="nav navbar-nav">
                    <?php
                    $readMenu = new Read;
                    $readMenu->ExeRead("menu_topo","WHERE status = '1' AND id_pai IS NULL ORDER BY nome ASC");
                    if($readMenu->getResult()):
                        foreach($readMenu->getResult() as $link):

                        //Verifica se abre na mesma janela ou não
                        $target = ($link['target'] == '1' ? '_blank' : '_self');

                        // Verifica se é link externo ou interno
                        $Url = ($link['url'] != '' ? $link['url'] : BASE.'/'.$link['link']);

                        // Consulta se é submenu
                        $readSubMenu = new Read;
                        $readSubMenu->ExeRead("menu_topo","WHERE status = '1' AND id_pai = :id ORDER BY nome ASC","id={$link['id']}");
                        if($readSubMenu->getResult()):
                    ?>
                        <li class="dropdown"><a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false"><?= $link['nome'];?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                    <?php
                        foreach($readSubMenu->getResult() as $sublink):
                        //Verifica se abre na mesma janela ou não
                        $target = ($sublink['target'] == '1' ? '_blank' : '_self');

                        // Verifica se é link externo ou interno
                        $Url = ($sublink['url'] != '' ? $sublink['url'] : BASE.'/'.$sublink['link']);
                    ?>        
                            <li><a target="<?= $target;?>" href="<?= $Url;?>"><?= $sublink['nome'];?></a></li>
                    <?php
                        endforeach;
                    ?>
                        </ul> 

                        </li>
                    <?php
                        else:
                    ?>
                       <li><a target="<?= $target;?>" href="<?= $Url;?>"><?= $link['nome'];?></a></li>    
                    <?php
                        endif;

                        endforeach;
                    endif;
                    ?>
                       
                    <?php
                        $readCatBlogPai = new Read;
                        $readCatBlogPai->ExeRead("cat_posts", "WHERE id_pai IS NULL AND exibir = '1' ORDER BY nome ASC");                            
                        if($readCatBlogPai->getResult()):
                            $readPostsCat = new Read;
                            $readPostsCat->ExeRead("posts", "WHERE status = '1' AND tipo = 'artigo' AND cat_pai = :catPaiId", "catPaiId={$readCatBlogPai->getResult()['0']['id']}");
                            if($readPostsCat->getResult()):
                                echo '<li class="dropdown">';
                                echo '<a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">Blog<span class="caret"></span></a>';
                                echo '<ul class="dropdown-menu">';
                                foreach($readCatBlogPai->getResult() as $blogpai):
                                    echo '<li class="dropdown-submenu">';
                                        echo '<a tabindex="0">'.$blogpai['nome'].'</a>';
                                        $readCatBolgFilho = new Read;
                                        $readCatBolgFilho->ExeRead("cat_posts", "WHERE id_pai = :idPai AND exibir = '1' ORDER BY nome ASC", "idPai={$blogpai['id']}");
                                        if($readCatBolgFilho->getResult()):
                                            echo '<ul class="dropdown-menu">';
                                                foreach($readCatBolgFilho->getResult() as $catblogfilho):
                                                echo '<li><a href="'.BASE.'/blog/categoria/'.$catblogfilho['url'].'">'.$catblogfilho['nome'].'</a></li>';
                                                endforeach;
                                            echo '</ul>';
                                        endif;                                 
                                    echo '</li>';
                                endforeach;
                                echo '</ul>';
                                echo '</li>';
                            endif;
                        endif;                   
            	        
                    ?>
                       
                       
                    <?php
                        $readCatImoveis = new Read;
                        $readCatImoveis->ExeRead("cat_imoveis", "WHERE id_pai IS NULL AND exibir = '1'");
                        if($readCatImoveis->getResult()):
                            foreach($readCatImoveis->getResult() as $catImovel):
                                echo '<li class="dropdown">';
                                echo '<a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">'.$catImovel['nome'].'<span class="caret"></span></a>';
                                $readCatImoveisSub = new Read;
                                $readCatImoveisSub->ExeRead("cat_imoveis", "WHERE id_pai = :idPai AND exibir = '1'", "idPai={$catImovel['id']}");
                                if($readCatImoveisSub->getResult()):
                                    echo '<ul class="dropdown-menu">';
                                    foreach($readCatImoveisSub->getResult() as $catImovelSub):
                                    echo '<li><a href="'.BASE.'/imoveis/categoria/'.$catImovelSub['url'].'">'.$catImovelSub['nome'].'</a></li>';
                                    endforeach;
                                    echo '</ul>'; 
                                endif;
                                echo '</li>';
                            endforeach;
                        endif;
                    ?>                    
                    <li><a href="<?= BASE;?>/pagina/atendimento">Atendimento</a></li>
                </ul>
                
                <ul class="nav navbar-nav navbar-right rightside-navbar">
                    <li>
                        <a href="<?= BASE;?>/imoveis/cadastrar" class="button">
                            Cadastre seu Imóvel
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<!-- Main header end -->