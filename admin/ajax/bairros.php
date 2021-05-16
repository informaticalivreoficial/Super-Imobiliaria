<?php
require('../../vendor/autoload.php');
require('../../app/config.inc.php');

//$bairro = (int) strip_tags(trim($_POST['bairro']));
$bairro = filter_input(INPUT_GET, 'bairro', FILTER_VALIDATE_INT);
$readBairros = new Read;
$readBairros->ExeRead("bairros", "WHERE cidade = :cidadeId", "cidadeId={$bairro}");

echo "<option value=\"\" disabled selected> Selecione o bairro </option>";
foreach ($readBairros->getResult() as $bairros2):
    echo "<option value=\"{$bairros2['id']}\"> {$bairros2['nome']} </option>";
endforeach;