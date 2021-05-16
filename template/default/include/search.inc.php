<form action="<?= BASE;?>/imoveis/busca-avancada" method="post">
<!-- Search area start -->
<div class="search-area">
    <div class="container">
        <div class="search-area-inner">
            <div class="search-contents ">
                    <div class="row">
                        <div class="alertas"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">

                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Alugar ou Comprar?</label>
                                <select class="search-fields loadtipo" name="tipo" id="tipo">
                                    <option <?php if(isset($post['tipo']) && $post['tipo'] == '1') echo 'selected="selected"';?>  value="1">Alugar</option>
                                    <option <?php if(!isset($post['tipo']) || $post['tipo'] == '0') echo 'selected="selected"';?>  value="0">Comprar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">

                        </div>
                    </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Escolha a Cidade</label>
                            <select class="search-fields loadcidadeFiltro" name="cidade" id="cidade">
                                <?php
                                $readCidade = new Read;
                                $readCidade->ExeRead("cidades", "WHERE cidade_id ORDER BY cidade_nome ASC");
                                if($readCidade->getResult()):
                                    echo '<option value="">Selecione</option>';
                                    foreach($readCidade->getResult() as $cidade):
                                        $readImoveisFiltro = new Read;
                                        $readImoveisFiltro->ExeRead("imoveis", "WHERE cidade_id = :cidadeId","cidadeId={$cidade['cidade_id']}");
                                        if($readImoveisFiltro->getResult()):
                                            echo '<option ';
                                            if(isset($post['cidade']) && $post['cidade'] == $cidade['cidade_id']):
                                                echo 'selected="selected" ';
                                            endif;
                                            echo 'value="'.$cidade['cidade_id'].'">'.$cidade['cidade_nome'];
                                            echo '</option>';
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Bairro</label>
                                <div class="selectBairro">
                                    <select class="search-fields j_loadbairros" name="bairro" id="bairro">
                                        <?php
                                        if(isset($post['cidade'])):
                                            $cidade = $post['cidade'];
                                            $readBairros = new Read;
                                            $readBairros->ExeRead("bairros", "WHERE status =  '1' AND cidade = :cidadeId", "cidadeId={$cidade}");
                                            if($readBairros->getResult()):
                                                foreach($readBairros->getResult() as $bairo):
                                                    $readImoveisFiltro = new Read;
                                                    $readImoveisFiltro->ExeRead("imoveis", "WHERE bairro_id = :cidadeId","cidadeId={$bairo['id']}");
                                                    if($readImoveisFiltro->getResult()):
                                                        echo '<option ';
                                                        if(isset($post['bairro']) && $post['bairro'] == $bairo['id']):
                                                            echo 'selected="selected" ';
                                                        endif;
                                                        echo 'value="'.$bairo['id'].'">'.$bairo['nome'];
                                                        echo '</option>';
                                                    endif;
                                                endforeach;
                                                echo '<option value="">Todos</option>';
                                            else:
                                                echo '<option>Selecione uma Cidade</option>';
                                            endif;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Valores</label>
                                <div class="selectValores">
                                    <select class="search-fields loadvalores" name="valores" id="valores">
                                        <option value="" selected>Imóvel até</option>
                                        <option <?php if(isset($post['valores']) && $post['valores'] == '300000') echo 'selected="selected"';?>  value="300000">R$300.000</option>
                                        <option <?php if(!isset($post['valores']) || $post['valores'] == '450000') echo 'selected="selected"';?>  value="450000">R$450.000</option>
                                        <option <?php if(!isset($post['valores']) || $post['valores'] == '600000') echo 'selected="selected"';?>  value="600000">R$600.000</option>
                                        <option <?php if(!isset($post['valores']) || $post['valores'] == '750000') echo 'selected="selected"';?>  value="750000">R$750.000</option>
                                        <option <?php if(!isset($post['valores']) || $post['valores'] == '900000') echo 'selected="selected"';?>  value="900000">R$900.000</option>
                                        <option <?php if(!isset($post['valores']) || $post['valores'] == '1500000') echo 'selected="selected"';?>  value="1500000">R$1.500.000</option>
                                        <option <?php if(!isset($post['valores']) || $post['valores'] == '2000000') echo 'selected="selected"';?>  value="2000000">R$2.000.000</option>
                                        <option <?php if(!isset($post['valores']) || $post['valores'] == '2500000') echo 'selected="selected"';?>  value="2500000">R$2.500.000</option>
                                        <option <?php if(!isset($post['valores']) || $post['valores'] == '3000000') echo 'selected="selected"';?>  value="3000000">R$3.000.000</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Quartos</label>
                                <select class="search-fields loaddormitorios" name="dormitorios" id="dormitorios">
                                    <option <?php if(isset($post['dormitorios']) && $post['dormitorios'] == '1') echo 'selected="selected"';?>  value="1">1+</option>
                                    <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '2') echo 'selected="selected"';?>  value="2">2+</option>
                                    <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '3') echo 'selected="selected"';?>  value="3">3+</option>
                                    <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '4') echo 'selected="selected"';?>  value="4">4+</option>
                                    <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '5') echo 'selected="selected"';?>  value="5">5+</option>
                                    <option <?php if(!isset($post['dormitorios']) || $post['dormitorios'] == '') echo 'selected="selected"';?>  value="">Todos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label></label>
                                <button style="width: 100%;margin-top: 2%;" type="submit" id="b_nome" class="button-md button-theme">Pesquisar</button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Search area start -->
</form>