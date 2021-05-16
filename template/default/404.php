<?php require(REQUIRE_PATCH . '/include/header.inc.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <!-- Error404 -->
            <div class="error404-content">
                <h1>404</h1>
                <h2>Página não encontrada</h2>
                <p>Desculpe, a página que você está procurando não foi encontrada!</p>
                <form action="<?= BASE;?>" method="post">
                    <button type="submit" class="button-sm out-line-btn">Voltar ao Início</button>
                </form>
            </div>
            <!-- Error404 -->
        </div>
    </div>
</div>
<?php require(REQUIRE_PATCH . '/include/footer.inc.php'); ?>