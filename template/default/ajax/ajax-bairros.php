<?php
require('../../../vendor/autoload.php');
require('../../../app/config.inc.php');
//sleep(4);

$bairro = filter_input(INPUT_GET, 'cidade', FILTER_VALIDATE_INT);
$readBairros = new Read;
$readBairros->ExeRead("bairros", "WHERE status =  '1' AND cidade = :cidadeId", "cidadeId={$bairro}");
if($readBairros->getResult()):
    echo "<select class=\"search-fields j_loadbairros\" name=\"bairro\" id=\"bairro\">";
    foreach ($readBairros->getResult() as $bairros2):
        $readImoveisFiltro = new Read;
        $readImoveisFiltro->ExeRead("imoveis", "WHERE bairro_id = :bairroId","bairroId={$bairros2['id']}");
        if($readImoveisFiltro->getResult()):
            echo "<option value=\"{$bairros2['id']}\"> {$bairros2['nome']} </option>";
        endif;        
    endforeach;
    echo '<option value="">Todos</option>';
    echo "</select>";
else:
    echo "<option value=\"\" disabled selected> Bairro não encontrado </option>";
endif;
