<?php

/**
 * AdminImoveis.class [ MODEL ADMIN ]
 * Respnsável por gerenciar os imóveis no Admin do sistema!
 * 
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class AdminImoveis {

    private $Data;
    private $Post;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'imoveis';

    /**
     * <b>Cadastrar o Imóvel:</b> Envelope os dados do imóvel em um array atribuitivo e execute esse método
     * para cadastrar o imóvel. Envia a capa automaticamente!
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;
        $this->checkData();
        
        if($this->Result):
            $this->setData();
            $this->setName();
            $this->setUrl();

            if($this->Data['img']):            
                $pasta 	= 'imoveis';
                $upload = new Upload;
                if($this->Data['marcadagua'] == '1'):
                    $upload->ImageComMarca($this->Data['img'], $this->Data['url'], '960', $pasta.'/capas', true);
                else:
                    $upload->ImageComMarca($this->Data['img'], $this->Data['url'], '960', $pasta.'/capas', false);
                endif;                
            endif;
            
            //CONTADOR DOS BAIRROS
            $readBairrosCreate = new Read;
            $readBairrosCreate->ExeRead("bairros", "WHERE id = :bId","bId={$this->Data['bairro_id']}");
            if($readBairrosCreate->getResult()):
                $updatebairro = $readBairrosCreate->getResult()['0'];
                $updateArr = ['count_imoveis' => $updatebairro['count_imoveis'] + 1];
                $Update = new Update();
                $Update->ExeUpdate("bairros", $updateArr, "WHERE id = :postid", "postid={$updatebairro['id']}");
            endif;

            if (isset($upload) && $upload->getResult()):
                $this->Data['img'] = $upload->getResult();
                $this->Create();                
            else:
                $this->Data['img'] = null;
                    $_SESSION['errCapa'] = "<strong>Você não enviou uma Capa</strong> ou o <strong>Tipo de arquivo é inválido</strong>, envie imagens JPG ou PNG!";
                $this->Create();                
            endif;
        endif;
    }

    /**
     * <b>Atualizar Imóvel:</b> Envelope os dados em uma array atribuitivo e informe o id de um 
     * post para atualiza-lo na tabela!
     * @param INT $PostId = Id do imóvel
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($PostId, array $Data) {
        $this->Post = (int) $PostId;
        $this->Data = $Data;
        $this->checkData();
        
        if($this->Result):  
            $this->setData();
            $this->setName();
            $this->setUrl();
            $this->Data['uppdate'] = date('Y-m-d H:i:s');

            if (is_array($this->Data['img'])):
                $readCapa = new Read;
                $readCapa->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");
                $capa = '../uploads/' . $readCapa->getResult()[0]['img'];
                if (file_exists($capa) && !is_dir($capa)):
                    unlink($capa);
                endif;                
                $pasta 	= 'imoveis';                
                $uploadCapa = new Upload;
                if($this->Data['marcadagua'] == '1'):
                    $uploadCapa->ImageComMarca($this->Data['img'], $this->Data['url'], '960', $pasta.'/capas', true);
                else:
                    $uploadCapa->ImageComMarca($this->Data['img'], $this->Data['url'], '960', $pasta.'/capas', false);
                endif;
            endif;             
            
            if (isset($uploadCapa) && $uploadCapa->getResult()):
                $this->Data['img'] = $uploadCapa->getResult();
                $this->Update();
            else:
                unset($this->Data['img']);
                if(!empty($uploadCapa) && $uploadCapa->getError()):
                    RMErro("<strong>Erro ao enviar a Capa</strong>: " .$uploadCapa->getError(), E_USER_WARNING);
                endif;
                $this->Update();
            endif;
        endif;
    }

    /**
     * <b>Deleta Imóvel:</b> Informe o ID do post a ser removido para que esse método realize uma checagem de
     * pastas e galerias excluinto todos os dados nessesários!
     * @param INT $PostId = Id do imóvel
     */
    public function ExeDelete($PostId) {
        $this->Post = (int) $PostId;

        $ReadPost = new Read;
        $ReadPost->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");

        if (!$ReadPost->getResult()):
            $this->Error = ["O imóvel que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];
            if (file_exists('../uploads/' . $PostDelete['img']) && !is_dir('../uploads/' . $PostDelete['img'])):
                unlink('../uploads/' . $PostDelete['img']);
            endif;

            $readGallery = new Read;
            $readGallery->ExeRead("imovel_gb", "WHERE id_imovel = :id", "id={$this->Post}");
            if ($readGallery->getResult()):
                foreach ($readGallery->getResult() as $gbdel):
                    if (file_exists('../uploads/' . $gbdel['img']) && !is_dir('../uploads/' . $gbdel['img'])):
                        unlink('../uploads/' . $gbdel['img']);
                    endif;
                endforeach;
            endif;
            
            //CONTADOR DOS BAIRROS
            $readBairrosCreate = new Read;
            $readBairrosCreate->ExeRead("bairros", "WHERE id = :bId","bId={$PostDelete['bairro_id']}");
            if($readBairrosCreate->getResult()):
                $updatebairro = $readBairrosCreate->getResult()['0'];
                $updateArr = ['count_imoveis' => $updatebairro['count_imoveis'] - 1];
                $Update = new Update();
                $Update->ExeUpdate("bairros", $updateArr, "WHERE id = :postid", "postid={$updatebairro['id']}");
            endif;

            $deleta = new Delete;
            $deleta->ExeDelete("imovel_gb", "WHERE id_imovel = :gbpost", "gbpost={$this->Post}");
            $deleta->ExeDelete(self::Entity, "WHERE id = :postid", "postid={$this->Post}");

            $this->Error = ["O imóvel <b>{$PostDelete['nome']}</b> foi removido com sucesso do sistema!", RM_ACCEPT];
            $this->Result = true;

        endif;
    }

    /**
     * <b>Ativa/Inativa Imóvel:</b> Informe o ID do imovel e o status e um status sendo 1 para ativo e 0 para
     * rascunho. Esse méto ativa e inativa os imóveis!
     * @param INT $PostId = Id do imóvel
     * @param STRING $PostStatus = 1 para ativo, 0 para inativo
     */
    public function ExeStatus($PostId, $PostStatus) {
        $this->Post = (int) $PostId;
        $this->Data['status'] = (string) $PostStatus;
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
    }

    /**
     * <b>Enviar Galeria:</b> Envelope um $_FILES de um input multiple e envie junto a um postID para executar
     * o upload e o cadastro de galerias do imóvel!
     * @param ARRAY $Files = Envie um $_FILES multiple
     * @param INT $PostId = Informe o ID do imóvel
     */
    public function gbSend(array $Images, $PostId, $Pasta) {
        $this->Post = (int) $PostId;
        $this->Data = $Images;
        $this->Pasta = $Pasta;

        $ImageName = new Read;
        $ImageName->ExeRead(self::Entity, "WHERE id = :id", "id={$this->Post}");

        if (!$ImageName->getResult()):
            $this->Error = ["Erro ao enviar galeria. O índice {$this->Post} não foi encontrado no banco!", RM_ERROR];
            $this->Result = false;
        else:
            $ImageUrl = $ImageName->getResult()[0]['url'];

            $gbFiles = array();
            $gbCount = count($this->Data['tmp_name']);
            $gbKeys = array_keys($this->Data);

            for ($gb = 0; $gb < $gbCount; $gb++):
                foreach ($gbKeys as $Keys):
                    $gbFiles[$gb][$Keys] = $this->Data[$Keys][$gb];
                endforeach;
            endfor;

            $gbSend = new Upload;
            $i = 0;
            $u = 0;

            foreach ($gbFiles as $gbUpload):
                $i++;
                $ImgName = "{$ImageUrl}-gb-{$this->Post}-" . (substr(md5(time() + $i), 0, 5));
                if($ImageName->getResult()[0]['marcadagua'] == '1'):
                    $gbSend->ImageComMarca($gbUpload, $ImageUrl, '960', $this->Pasta, true);
                else:
                    $gbSend->ImageComMarca($gbUpload, $ImageUrl, '960', $this->Pasta, false);
                endif;

                if ($gbSend->getResult()):
                    $gbImage = $gbSend->getResult();
                    list($y, $m) = explode('/', date('Y/m'));
                    $gbCreate = ['id_imovel' => $this->Post, "img" => $gbImage, "data" => date('Y-m-d H:i:s')];
                    $insertGb = new Create;
                    $insertGb->ExeCreate("imovel_gb", $gbCreate);
                    $u++;
                endif;

            endforeach;

            if ($u > 1):
                $this->Error = ["Galeria Atualizada: Foram enviadas {$u} imagens para galeria deste imóvel!", RM_ACCEPT];
                $this->Result = true;
            endif;
        endif;
    }

    /**
     * <b>Deletar Imagem da galeria:</b> Informe apenas o id da imagem na galeria para que esse método leia e remova
     * a imagem da pasta e delete o registro do banco!
     * @param INT $GbImageId = Id da imagem da galleria
     */
    public function gbRemove($GbImageId) {
        $this->Post = (int) $GbImageId;
        $readGb = new Read;
        $readGb->ExeRead("imovel_gb", "WHERE id = :gb", "gb={$this->Post}");
        if ($readGb->getResult()):

            $Imagem = '../uploads/' . $readGb->getResult()[0]['img'];

            if (file_exists($Imagem) && !is_dir($Imagem)):
                unlink($Imagem);
            endif;

            $Deleta = new Delete;
            $Deleta->ExeDelete("imovel_gb", "WHERE id = :id", "id={$this->Post}");
            if ($Deleta->getResult()):
                $this->Error = ["A imagem foi removida com sucesso da galeria!", RM_ACCEPT];
                $this->Result = true;
            endif;

        endif;
    }

    /**
     * <b>Verificar Cadastro:</b> Retorna ID do registro se o cadastro for efetuado ou FALSE se não.
     * Para verificar erros execute um getError();
     * @return BOOL $Var = InsertID or False
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com uma mensagem e o tipo de erro.
     * @return ARRAY $Error = Array associatico com o erro
     */
    public function getError() {
        return $this->Error;
    }

    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    //Valida e cria os dados para realizar o cadastro
    private function setData() {
        $Cover     = $this->Data['img'];
        $Content   = $this->Data['descricao']; 
        $Notas     = $this->Data['notas']; 
        unset($this->Data['img'], $this->Data['descricao'],$this->Data['notas']);        
        
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
        $this->Data['valor']  = str_replace(',','.', str_replace('.','', $this->Data['valor']));
        $this->Data['valor_iptu']  = str_replace(',','.', str_replace('.','', $this->Data['valor_iptu']));
        $this->Data['valor_condominio']  = str_replace(',','.', str_replace('.','', $this->Data['valor_condominio']));        

        $this->Data['url']    = Check::Name($this->Data['nome']);
        $this->Data['data']   = Check::Data($this->Data['data']);
        $this->Data['expira'] = Check::Data($this->Data['expira']);
        $this->Data['tipo']   = Check::Name($this->Data['tipo']);
        $this->Data['img']    = $Cover;
        $this->Data['descricao'] = $Content;
        $this->Data['notas'] = $Notas;
        $this->Data['cat_pai'] = $this->getCatParent();
    }
    
    private function checkData(){
        if ($this->Data['nome'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Título do Imóvel</strong>!", RM_ERROR];
            $this->Result = false;
        elseif($this->Data['uf_id'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor, selecione o <strong>Estado</strong>!", RM_ERROR];
            $this->Result = false;
        elseif(!isset($this->Data['cidade_id'])):
            $this->Error = ["Erro ao cadastrar: Por favor, selecione a <strong>Cidade</strong>!", RM_ERROR];
            $this->Result = false;
        elseif(!isset($this->Data['bairro_id'])):
            $this->Error = ["Erro ao cadastrar: Por favor, selecione o <strong>Bairro</strong>!", RM_ERROR];
            $this->Result = false;
        elseif($this->Data['categoria'] == '0'):
            $this->Error = ["Erro ao cadastrar: Por favor selecione uma <strong>Categoria</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->Result = true;
        endif;
    }

    //Obtem o ID da categoria PAI
    private function getCatParent() {
        $rCat = new Read;
        $rCat->ExeRead("cat_imoveis", "WHERE id = :id", "id={$this->Data['categoria']}");
        if ($rCat->getResult()):
            return $rCat->getResult()[0]['id_pai'];
        else:
            return null;
        endif;
    }

    //Verifica o NOME imóvel. Se existir adiciona um pós-fix -Count
    private function setName() {
        $Where = (isset($this->Post) ? "id != {$this->Post} AND" : '');
        $readName = new Read;
        $readName->ExeRead(self::Entity, "WHERE {$Where} nome = :t", "t={$this->Data['nome']}");
        if ($readName->getResult()):
            $this->Data['nome'] = $this->Data['nome'] . '-' . $readName->getRowCount();
        endif;
    }
    //Verifica o URL imóvel. Se existir adiciona um pós-fix -Count
    private function setUrl() {
        $Where = (isset($this->Post) ? "id != {$this->Post} AND" : '');
        $readName = new Read;
        $readName->ExeRead(self::Entity, "WHERE {$Where} url = :u", "u={$this->Data['url']}");
        if ($readName->getResult()):
            $this->Data['url'] = $this->Data['url'] . '-' . $readName->getRowCount();
        endif;
    }

    //Cadastra o imóvel no banco!
    private function Create() {
        $cadastra = new Create;
        $cadastra->ExeCreate(self::Entity, $this->Data);
        if ($cadastra->getResult()):
            $this->Error = ["O Imóvel {$this->Data['nome']} foi cadastrado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $cadastra->getResult();
        endif;
    }

    //Atualiza o imóvel no banco!
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["O Imóvel <b>{$this->Data['nome']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

}
