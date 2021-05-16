<?php
    $textoZap = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
    if($textoZap && !empty($textoZap)):
       header('Location: '.Check::getNumZap(WATSAPP, $textoZap['texto']).'');
    else:
       header('Location: '.BASE.''); 
    endif;
?>