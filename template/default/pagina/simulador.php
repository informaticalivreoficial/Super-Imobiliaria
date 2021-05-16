<div class="blog-body content-area">
    <div class="container" style="margin-bottom: 100px;">
        <div class="row">
            <div class="col-lg-12">
                <div class="logosimulador">
                    <img src="<?= BASE; ?>/uploads/<?= LOGOMARCA;?>" alt="<?= SITENAME; ?>"/>
                    <h1>Bem-vindo ao simulador de financiamento</h1>
                    <h3>Escolha uma das opções de crédito imobiliário.</h3>
                </div>                
            </div>            
            <div class="col-lg-12">
                <div class="submit-address">
                    <form method="post" class="j_submitsimulador">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alertas"></div>                                
                            </div>
                        </div>
                        <div class="form_hide"><!-- FORM HIDE START -->
                            <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Preencha com seu nome completo*</label>
                                    <input title="Preencha com seu nome completo" type="text" class="input-text" name="nome">
                                    <input class="noclear" type="hidden" name="action" value="simulador" />
                                    <!-- HONEYPOT -->
                                    <input type="hidden" class="noclear" name="bairro1" value="" />
                                    <input type="text" class="noclear" style="display: none;" name="cidade1" value="" />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Data de nascimento*</label>
                                    <input title="Data de nascimento" type="text" class="input-text" id="nascimento" name="nasc">
                                </div>
                            </div>
                        </div>                                                
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                 <div class="form-group">
                                     <label>Quando pretende se mudar?*</label>
                                     <select title="Quando pretende se mudar?" class="selectpicker search-fields" name="tempo"> 
                                         <option value="">Selecione</option>
                                         <option value="Quanto antes">Quanto antes</option>
                                         <option value="Até 3 meses">Até 3 meses</option>
                                         <option value="Entre 3 e 6 meses">Entre 3 e 6 meses</option>
                                         <option value="Entre 6 meses e 1 ano">Entre 6 meses e 1 ano</option>
                                         <option value="Mais de 1 ano">Mais de 1 ano</option>
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6 col-sm-6">
                                 <div class="form-group">
                                     <label>Escolha um tipo de Financiamento*</label>
                                     <select title="Escolha um tipo de Financiamento" class="selectpicker search-fields loadtipo_s" name="tipo_financiamento">    
                                        <option value="">Selecione</option>
                                        <option value="0">Financiamento Imobiliário</option>
                                        <option value="1">Consórcio Imobiliário</option>                        
                                     </select>
                                 </div>
                             </div>
                         </div>
                        
                         <!-- SE FOR FINANCIAMENTO DE IMÓVEL -->
                         <div class="financiamento">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label>Valor do Imóvel*</label>
                                        <input title="Valor do Imóvel" type="text" class="input-text" data-prefix="R$ " id="dinheiroComZero" name="valor">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label>Valor da Entrada*</label>
                                        <input title="Valor da Entrada" type="text" data-prefix="R$ " class="input-text dinheiroComZero" name="valor_entrada">
                                    </div>
                                </div>
                            </div>
                             
                            <div class="row">
                                    <div class="col-lg-6">
                                     <label class="margin-t-10">Natureza do imóvel</label>
                                     <div class="row">
                                         <div class="col-lg-4 col-sm-4 col-xs-12">
                                             <div class="radio checkbox-theme checkbox-circle">
                                                 <input id="radio1" name="natureza" value="Indiferente" type="radio" checked>
                                                 <label for="radio1">
                                                     Indiferente
                                                 </label>
                                             </div>                                        
                                         </div>                                    
                                         <div class="col-lg-4 col-sm-4 col-xs-12">
                                             <div class="radio checkbox-theme checkbox-circle">
                                                 <input id="radio2" name="natureza" value="Novo" type="radio">
                                                 <label for="radio2">
                                                     Novo
                                                 </label>
                                             </div>                                        
                                         </div>
                                         <div class="col-lg-4 col-sm-4 col-xs-12">
                                             <div class="radio checkbox-theme checkbox-circle">
                                                 <input id="radio3" name="natureza" value="Usado" type="radio">
                                                 <label for="radio3">
                                                     Usado
                                                 </label>
                                             </div>                                        
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-lg-6">
                                     <label class="margin-t-10">Tipo do imóvel</label>
                                     <div class="row">
                                         <div class="col-lg-4 col-sm-4 col-xs-12">
                                             <div class="radio checkbox-theme checkbox-circle">
                                                 <input id="radio4" name="tipoimovel" value="Residencial" type="radio" checked>
                                                 <label for="radio4">
                                                     Residencial
                                                 </label>
                                             </div>                                        
                                         </div>                                    
                                         <div class="col-lg-4 col-sm-4 col-xs-12">
                                             <div class="radio checkbox-theme checkbox-circle">
                                                 <input id="radio5" name="tipoimovel" value="Comercial" type="radio">
                                                 <label for="radio5">
                                                     Comercial
                                                 </label>
                                             </div>                                        
                                         </div>
                                         <div class="col-lg-4 col-sm-4 col-xs-12">
                                             <div class="radio checkbox-theme checkbox-circle">
                                                 <input id="radio6" name="tipoimovel" value="Rural" type="radio">
                                                 <label for="radio6">
                                                     Rural
                                                 </label>
                                             </div>                                        
                                         </div>
                                     </div>
                                 </div> 
                            </div> 

                           <div class="row">                            
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label>UF*</label>
                                        <select title="Estado" class="selectpicker search-fields j_loadstate opcaofinanciamento" name="uf">    
                                            <option value="0" selected> Selecione o estado </option>
                                            <?php
                                                $readState = new Read;
                                                $readState->ExeRead("estados", "ORDER BY estado_nome ASC");
                                                foreach ($readState->getResult() as $estado):
                                                    extract($estado);
                                                    echo "<option value=\"{$estado_id}\"> {$estado_uf} </option>";
                                                endforeach;
                                            ?>                      
                                        </select>
                                    </div>
                                </div>
                               <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label>Cidade*</label>
                                        <select title="Cidade" class="search-fields j_loadcity" name="cidade" disabled>    
                                            <option value="" selected disabled> Selecione antes um estado </option>                                            
                                        </select>
                                    </div>
                                </div>
                               <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                       <label>Bairro*</label>
                                       <input title="Bairro" type="text" class="input-text" name="bairro">
                                    </div>
                                </div>
                           </div>

                           <div class="row">                            
                               <div class="col-md-4 col-sm-4">
                                   <div class="form-group">
                                      <label>E-mail*</label>
                                      <input title="E-mail" type="text" class="input-text" name="email">
                                   </div>
                               </div>
                               <div class="col-md-4 col-sm-4">
                                   <div class="form-group">
                                      <label>Celular*</label>
                                      <input title="Celular" type="text" id="whatsapp" class="input-text" name="celular">
                                   </div>
                               </div>
                               <div class="col-md-4 col-sm-4">
                                   <div class="form-group">
                                      <label>Renda aproximada*</label>
                                      <input title="Renda aproximada" type="text" class="input-text" data-prefix="R$ " id="dinheiroComZero2" name="renda">
                                   </div>
                               </div>
                           </div> 
                           
                            <div class="row">  
                               <div class="col-md-12 col-sm-12">
                                 <h4>Gostaria de pré-aprovar o seu crédito?</h4> 
                               </div>
                               <div class="col-lg-12">
                               <label class="margin-t-10">O que você precisa agora?</label>
                               <div class="row">
                                   <div class="col-lg-4 col-sm-4 col-xs-12">
                                       <div class="radio checkbox-theme checkbox-circle">
                                           <input id="radio7" name="oqueprecisa" value="Pré aprovar meu crédito" type="radio" checked>
                                           <label for="radio7">
                                               Pré aprovar meu crédito
                                           </label>
                                       </div>                                        
                                   </div>                                    
                                   <div class="col-lg-4 col-sm-4 col-xs-12">
                                       <div class="radio checkbox-theme checkbox-circle">
                                           <input id="radio8" name="oqueprecisa" value="Refinanciamento Imobiliário" type="radio">
                                           <label for="radio8">
                                               Refinanciamento Imobiliário
                                           </label>
                                       </div>                                        
                                   </div>
                                   <div class="col-lg-4 col-sm-4 col-xs-12">
                                       <div class="radio checkbox-theme checkbox-circle">
                                           <input id="radio9" name="oqueprecisa" value="Receber opções de Imóveis" type="radio">
                                           <label for="radio9">
                                               Receber opções de Imóveis
                                           </label>
                                       </div>                                        
                                   </div>
                               </div>
                           </div>
                            </div>
                        </div>
                        <!-- SE FOR FINANCIAMENTO DE IMÓVEL FIM --> 
                        
                        <!-- SE FOR CONSÓRCIO -->
                        <div class="row consorcio">                            
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                   <label>E-mail*</label>
                                   <input title="E-mail" type="text" class="input-text opcaoconsorcio" name="email">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                   <label>Celular*</label>
                                   <input title="Celular" type="text" id="whatsapp" class="input-text opcaoconsorcio" name="celular">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label>UF*</label>
                                    <select title="Estado" class="selectpicker search-fields j_loadstate opcaoconsorcio" name="uf">
                                        <option value="0" selected> Selecione o estado </option>
                                        <?php
                                            $readState = new Read;
                                            $readState->ExeRead("estados", "ORDER BY estado_nome ASC");
                                            foreach ($readState->getResult() as $estado):
                                                extract($estado);
                                                echo "<option value=\"{$estado_id}\"> {$estado_nome} - {$estado_uf} </option>";
                                            endforeach;
                                        ?>                      
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label>Cidade*</label>
                                    <select title="Cidade" class="search-fields j_loadcity" name="cidade" disabled>    
                                        <option value="" selected>Selecione o Estado Primeiro</option>                     
                                    </select>
                                </div>
                             </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="form-group">
                                   <label>Bairro*</label>
                                   <input type="text" title="Bairro" class="input-text opcaoconsorcio" name="bairro">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row consorcio">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                   <label>Valor da Carta de Crédito*</label>
                                   <input type="text" title="Valor da Carta de Crédito" class="input-text opcaoconsorcio" data-prefix="R$ " id="dinheiroComZero1" name="valorcarta">
                                </div>
                              </div>
                              <div class="col-md-6 col-sm-6">
                                  <div class="form-group">
                                     <label>Prazo*</label>
                                     <select class="selectpicker search-fields opcaoconsorcio" title="Prazo" name="prazocarta"> 
                                         <option value="">Selecione</option>
                                         <option value="36">36</option>
                                         <option value="48">48</option>
                                         <option value="60">60</option>
                                         <option value="72">72</option>
                                         <option value="120">120</option>
                                         <option value="180">180</option>
                                     </select>
                                 </div>
                              </div>
                        </div>
                        
                        <!-- SE FOR CONSÓRCIO FIM -->
                        
                        <div class="row"> 
                            <div class="col-md-12">
                                <button type="submit" class="btn button-md button-theme" title="Enviar Agora">Enviar Agora</button>
                            </div>
                        </div>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>    
    </div>
</div>

<div class="seguro">
    <img width="100" src="<?= PATCH;?>/images/site-seguro.png" alt="Site 100% Seguro" title="Site 100% Seguro" /></a>
</div>