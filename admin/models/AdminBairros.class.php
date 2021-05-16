<?php

/**
 * AdminBairros.class [ MODEL ADMIN ]
 * Respnsável por gerenciar os bairros no Admin do sistema!
 * 
 * @copyright (c) 2020, Renato Montanari - Informática Livre
 */
class AdminBairros {

    private $Data;
    private $Post;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'bairros';

    /**
     * <b>Cadastrar o Bairro:</b> Envelope os dados do imóvel em um array atribuitivo e execute esse método
     * para cadastrar o imóvel. Envia a capa automaticamente!
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;

        if ($this->Data['nome'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Nome do Bairro</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();

            if ($this->Data['img']):
            
                $pasta 	= 'bairros';
                $upload = new Upload;
                $upload->ImageSemData($this->Data['img'], $this->Data['url'], '960', $pasta);
            endif;

            if (isset($upload) && $upload->getResult()):
                $this->Data['img'] = $upload->getResult();
                $this->Create();
            else:
                $this->Data['img'] = null;
                    $_SESSION['errCapa'] = "<strong>Você não enviou uma Capa</strong> ou o <strong>Tipo de arquivo é inválido</strong>, envie imagens JPG ou PNG!";
                $this->Create();
            endif;
            var_dump($this->Data);
        endif;
    }

    /**
     * <b>Atualizar Bairro:</b> Envelope os dados em uma array atribuitivo e informe o id de um 
     * post para atualiza-lo na tabela!
     * @param INT $PostId = Id do bairro
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($PostId, array $Data) {
        $this->Post = (int) $PostId;
        $this->Data = $Data;

        if ($this->Data['nome'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Nome do Bairro</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();
            $this->Data['uppdate'] = date('Y-m-d H:i:s');

            if (is_array($this->Data['img'])):
                $readCapa = new Read;
                $readCapa->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");
                $capa = '../uploads/' . $readCapa->getResult()[0]['img'];
                if (file_exists($capa) && !is_dir($capa)):
                    unlink($capa);
                endif;
                
                $pasta 	= 'bairros';
                
                $uploadCapa = new Upload;
                $uploadCapa->ImageSemData($this->Data['img'], $this->Data['url'], '960', $pasta);
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
     * <b>Deleta Bairro:</b> Informe o ID do post a ser removido para que esse método realize uma checagem de
     * pastas e galerias excluinto todos os dados nessesários!
     * @param INT $PostId = Id do bairro
     */
    public function ExeDelete($PostId) {
        $this->Post = (int) $PostId;

        $ReadPost = new Read;
        $ReadPost->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");

        if (!$ReadPost->getResult()):
            $this->Error = ["O bairro que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];
            if (file_exists('../uploads/' . $PostDelete['img']) && !is_dir('../uploads/' . $PostDelete['img'])):
                unlink('../uploads/' . $PostDelete['img']);
            endif;

            $deleta = new Delete;
            $deleta->ExeDelete(self::Entity, "WHERE id = :postid", "postid={$this->Post}");

            $this->Error = ["O bairro <b>{$PostDelete['nome']}</b> foi removido com sucesso do sistema!", RM_ACCEPT];
            $this->Result = true;

        endif;
    }

    /**
     * <b>Ativa/Inativa Bairro:</b> Informe o ID do bairro e o status e um status sendo 1 para ativo e 0 para
     * rascunho. Esse méto ativa e inativa os bairros!
     * @param INT $PostId = Id do bairro
     * @param STRING $PostStatus = 1 para ativo, 0 para inativo
     */
    public function ExeStatus($PostId, $PostStatus) {
        $this->Post = (int) $PostId;
        $this->Data['status'] = (string) $PostStatus;
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
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
        unset($this->Data['img']);
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data); 
        $this->Data['url']    = Check::Name($this->Data['nome']);
        $this->Data['data']   = Check::Data($this->Data['data']);
        $this->Data['img']    = $Cover;
    }

    //Verifica o NAME imóvel. Se existir adiciona um pós-fix -Count
    private function setName() {
        $Where = (isset($this->Post) ? "id != {$this->Post} AND" : '');
        $readName = new Read;
        $readName->ExeRead(self::Entity, "WHERE {$Where} nome = :t", "t={$this->Data['nome']}");
        if ($readName->getResult()):
            $this->Data['url'] = $this->Data['url'] . '-' . $readName->getRowCount();
        endif;
    }

    //Cadastra o imóvel no banco!
    private function Create() {
        $cadastra = new Create;
        $cadastra->ExeCreate(self::Entity, $this->Data);
        if ($cadastra->getResult()):
            $this->Error = ["O Bairro {$this->Data['nome']} foi cadastrado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $cadastra->getResult();
        endif;
    }

    //Atualiza o imóvel no banco!
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["O Bairro <b>{$this->Data['nome']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

}
