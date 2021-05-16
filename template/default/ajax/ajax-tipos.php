<?php    
    require('../../../vendor/autoload.php');
    require('../../../app/config.inc.php');
    
    // Parte que preenche as combobox
    $Filtro = filter_input(INPUT_GET, 'tipo', FILTER_VALIDATE_INT);

    if($Filtro == '1'):
        echo "<select class=\"search-fields loadvalores\" name=\"valor\" id=\"valor\">";
        echo "<option value=\"\" selected>Aluguel até</option>";
        echo "<option value=\"1000\">R$1.000</option>";
        echo "<option value=\"2000\">R$2.000</option>";
        echo "<option value=\"3000\">R$3.000</option>";
        echo "<option value=\"4000\">R$4.000</option>";
        echo "<option value=\"5000\">R$5.000</option>";
        echo "<option value=\"10000\">R$10.000</option>";
        echo "<option value=\"20000\">R$20.000</option>";
        echo "</select>";
    elseif($Filtro == '0'):
        echo "<select class=\"search-fields loadvalores\" name=\"valor\" id=\"valor\">";
        echo "<option value=\"\" selected>Imóvel até</option>";
        echo "<option value=\"300000\">R$300.000</option>";
        echo "<option value=\"450000\">R$450.000</option>";
        echo "<option value=\"600000\">R$600.000</option>";
        echo "<option value=\"750000\">R$750.000</option>";
        echo "<option value=\"900000\">R$900.000</option>";
        echo "<option value=\"1500000\">R$1.500.000</option>";
        echo "<option value=\"2000000\">R$2.000.000</option>";
        echo "<option value=\"2500000\">R$2.500.000</option>";
        echo "<option value=\"3000000\">R$3.000.000</option>";
        echo "</select>";
    endif;
?>