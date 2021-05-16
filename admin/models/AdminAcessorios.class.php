<?php

/**
 * AdminImoveis.class [ MODEL ADMIN ]
 * Respnsável por gerenciar os imóveis no Admin do sistema!
 * 
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class AdminAcessorios {

    private $Data;
    private $Post;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'imoveis_atributos';

    /**
     * <b>Cadastrar o Imóvel:</b> Envelope os dados do imóvel em um array atribuitivo e execute esse método
     * para cadastrar o imóvel. Envia a capa automaticamente!
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;

        if ($this->Data['nome'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Nome do Acessório</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();
            $this->Create();
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

        if ($this->Data['nome'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Nome do Acessório</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();
            $this->Data['uppdate'] = date('Y-m-d H:i:s');  
            $this->Update();
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
            $this->Error = ["O Acessório que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];
            $deleta = new Delete;
            $deleta->ExeDelete(self::Entity, "WHERE id = :postid", "postid={$this->Post}");

            $this->Error = ["O Acessório <b>{$PostDelete['nome']}</b> foi removido com sucesso do sistema!", RM_ACCEPT];
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
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
        $this->Data['data']   = Check::Data($this->Data['data']);     
    }    

    //Verifica o NAME imóvel. Se existir adiciona um pós-fix -Count
    private function setName() {
        $Where = (isset($this->Post) ? "id != {$this->Post} AND" : '');
        $readName = new Read;
        $readName->ExeRead(self::Entity, "WHERE {$Where} nome = :t", "t={$this->Data['nome']}");
        if ($readName->getResult()):
            $this->Data['nome'] = $this->Data['nome'] . '-' . $readName->getRowCount();
        endif;
    }

    //Cadastra o imóvel no banco!
    private function Create() {
        $cadastra = new Create;
        $cadastra->ExeCreate(self::Entity, $this->Data);
        if ($cadastra->getResult()):
            $this->Error = ["O Acessório {$this->Data['nome']} foi cadastrado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $cadastra->getResult();
        endif;
    }

    //Atualiza o imóvel no banco!
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["O Acessório <b>{$this->Data['nome']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

}
