<?php
ob_start();
require('vendor/autoload.php');
require('app/config.inc.php');
$Session = new Session;
$View = new View;
//var_dump($Session);
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8"/>        
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="copyright" content="<?= ANODEINICIO;?> <?= SITENAME;?>"/>       
        <meta name="author" content="<?= DESENVOLVEDOR;?>"/>
        <meta name="language" content="pt-br" />
        <meta name="robots" content="INDEX,FOLLOW" />  
        
        <!-- FAVICON -->
        <link rel="icon" type="image/png" href="<?= BASE;?>/uploads/<?= FAVICON;?>" />
        <link rel="shortcut icon" href="<?= BASE;?>/uploads/<?= FAVICON;?>" type="image/x-icon"/>        
        
        <?php
            $Link = new Link;
            $Link->getTags();
        ?>
        
        <?php require(REQUIRE_PATCH . '/include/head-css.inc.php'); ?>
        
        <div id="fb-root"></div>
        <script async defer src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v3.2&appId=1787040554899561&autoLogAppEvents=1"></script>
</head>
<body>
        <?php 
        if (!require($Link->getPatch())):
            RMErro('Erro ao incluir arquivo de navegação!', RM_ERROR, true);
        endif;        
        ?>
    
        <?php require(REQUIRE_PATCH . '/include/head-js.inc.php'); ?>
        <?php require(REQUIRE_PATCH . '/include/funcoes.php'); ?>
</body>
<?php   
 ob_end_flush();
?>
</html>