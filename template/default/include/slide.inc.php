<!-- Banner start -->
<div class="banner">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
<?php
    $dt_atual  = date('Y-m-d');
    $readSlide = new Read;
    $readSlide->ExeRead("banners", "WHERE status = '1' AND expira >= '$dt_atual' ORDER BY data DESC");
    if($readSlide->getResult()):        
        foreach($readSlide->getResult() as $key=>$slide):
        $active = ($key == '0' ? ' active' : '');
        
        $dt_atual		        = date('Y-m-d'); // data atual
        $timestamp_dt_atual 	= strtotime($dt_atual); // converte para timestamp Unix
         
        $dt_expira		        = $slide['expira']; // data de expiração do anúncio
        $timestamp_dt_expira	= strtotime($dt_expira); // converte para timestamp Unix
        
        $target = ($slide['target'] == '1' ? 'target="_blank"' : '');
         
        // data atual é maior que a data de expiração
        //if($timestamp_dt_atual >= $timestamp_dt_expira): // true
//          echo  $timestamp_dt_atual.'<br />'.$timestamp_dt_expira;
//        else: // false
            echo '<div class="item banner-max-height'.$active.'">';
            echo '<img src="'.BASE.'/uploads/'.$slide['imagem'].'" alt="'.$slide['titulo'].'">';
            echo '<div class="carousel-caption banner-slider-inner">';
            echo '<div class="banner-content">';
            if($slide['exibir_titulo'] == '1'):
                echo '<h1 data-animation="animated fadeInDown delay-05s">'.$slide['titulo'].'</h1>';
            endif;
            
            if($slide['content'] != ''):
                echo '<p data-animation="animated fadeInUp delay-1s">'.$slide['content'].'</p>';
            endif;            
            if($slide['link'] == ''):
            
            else:
            echo '<a '.$target.' href="'.$slide['link'].'" title="'.$slide['titulo'].'" class="btn button-md button-theme" data-animation="animated fadeInUp delay-15s">Clique aqui!</a>';
            endif;
            //echo '<a href="#" class="btn button-md border-button-theme" data-animation="animated fadeInUp delay-15s">Learn More</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
        //endif;
               
        endforeach;
    else:
        echo '';
    endif;
?>
           
            
</div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="slider-mover-left" aria-hidden="true">
        <i class="fa fa-angle-left"></i>
        </span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="slider-mover-right" aria-hidden="true">
        <i class="fa fa-angle-right"></i>
        </span>
        <span class="sr-only">Next</span>
    </a>
</div>
</div>
<!-- Banner end -->