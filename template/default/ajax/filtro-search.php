<?php
    require('../../../vendor/autoload.php');
    require('../../../app/config.inc.php');
    
    $tipo = (int) strip_tags(trim($_GET['categoria']));
     
    $seleciona = new Read;
    $seleciona->ExeRead("cat_imoveis", "WHERE id_pai = :idPai AND exibir = '1' ORDER BY nome ASC", "idPai={$tipo}");
    
    foreach($seleciona->getResult() as $select):
        echo '<option value="'.$select['id'].'" ';
            if($select['id'] == $tipo):
                echo 'selected="selected"';	
            endif;
        echo '>'.$select['nome'].'</option>';                                
    endforeach; 
    echo '<option value="00"> -- Todos -- </option>';   
?>
                                                
                    