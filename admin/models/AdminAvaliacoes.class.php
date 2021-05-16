<?php
/**
 * AdminAvaliações.class [ MODEL ADMIN ]
 * Responsável por gerenciar as Avaliações no Admin do sistema!
 * 
 * @copyright (c) 2020, Renato Montanari INFORMÁTICA LIVRE
 */
class AdminAvaliacoes {

    private $Data;
    private $Post;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'avaliacoes';
    
    /**
     * <b>Cadastrar Avaliação:</b> Envelope os dados de uma avaliação em um array atribuitivo e execute esse método
     * para cadastrar o mesmo no sistema. Validações serão feitas!
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;
        $this->checkData();
        
        if($this->Result):
            $this->setData();  
        
            if($this->Data['avatar']): 
                $ImageName  = Check::Name($this->Data['nome']);               
                $ImgName    = "{$ImageName}-av-" . (substr(md5(time() + $i), 0, 5));
                $upload     = new Upload;
                $upload->ImageSemData($this->Data['avatar'], $ImgName, '200', 'avaliacoes');
            endif;

            if (isset($upload) && $upload->getResult()):
                $this->Data['avatar'] = $upload->getResult();
                $this->Create();
            else:
                $this->Data['avatar'] = null;
                    $_SESSION['errCapa'] = "<strong>Erro ao enviar a Foto:</strong> Tipo de arquivo inválido, envie imagens JPG ou PNG!";
                $this->Create();
            endif;
        endif;        
    }

    /**
     * <b>Atualizar Avaliação:</b> Envelope os dados em uma array atribuitivo e informe o id de um 
     * post para atualiza-lo na tabela!
     * @param INT $PostId = Id do post
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($PostId, array $Data) {
        $this->Post = (int) $PostId;
        $this->Data = $Data;
        $this->checkData();
        $this->Data['uppdate'] = date('Y-m-d H:i:s');

        if ($this->Result):
            $this->setData();
        if (is_array($this->Data['avatar'])):
            $readCapa = new Read;
            $readCapa->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");
            $capa = '../uploads/' . $readCapa->getResult()[0]['avatar'];
            if (file_exists($capa) && !is_dir($capa)):
                unlink($capa);
            endif;
            $ImageName  = Check::Name($this->Data['nome']);
            $uploadCapa = new Upload;
            $uploadCapa->ImageSemData($this->Data['avatar'], $ImageName, '200', 'avaliacoes');               
        endif;

        if (isset($uploadCapa) && $uploadCapa->getResult()):
            $this->Data['avatar'] = $uploadCapa->getResult();
            $this->Update();
        else:
            unset($this->Data['avatar']);
            if(!empty($uploadCapa) && $uploadCapa->getError()):
                RMErro("<strong>Erro ao enviar a Capa</strong>: " .$uploadCapa->getError(), E_USER_WARNING);
            endif;
            $this->Update();
        endif;
        endif;
    }

    /**
     * <b>Deleta Avaliação:</b> Informe o ID da Avaliação a ser removida para que esse método realize uma checagem de
     * excluindo todos os dados nessesários!
     * @param INT $PostId = Id do post
     */
    public function ExeDelete($PostId) {
        $this->Post = (int) $PostId;
        $ReadPost = new Read;
        $ReadPost->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");
        if (!$ReadPost->getResult()):
            $this->Error = ["A Avaliação que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];        
            if (file_exists('../uploads/' . $PostDelete['avatar']) && !is_dir('../uploads/' . $PostDelete['avatar'])):
                unlink('../uploads/' . $PostDelete['avatar']);
            endif;
            $deleta = new Delete;
            $deleta->ExeDelete(self::Entity, "WHERE id = :postid", "postid={$this->Post}");
            $this->Error = ["A Avaliação de <b>{$PostDelete['nome']}</b> foi removida com sucesso do sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

    /**
     * <b>Ativa/Inativa Comentário:</b> Informe o ID do comentário e o status sendo 1 para Aprovado e 0 para
     * Pendente. Esse méto ativa e inativa os comentários!
     * @param INT $PostId = Id do comentário
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
        $Avatar = $this->Data['avatar'];
        $Depoimento = $this->Data['depoimento'];
        unset($this->Data['avatar'], $this->Data['depoimento']);
        
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
        
        $this->Data['data'] = Check::Data($this->Data['data']); 
        $this->Data['avatar'] = $Avatar;
        $this->Data['depoimento'] = $Depoimento;
    } 
    
    //Verifica os dados digitados no formulário
    private function checkData() {
        if ($this->Data['nome'] == ''):
            $this->Error = ["Por favor, preencha o campo <strong>Nome</strong>!", RM_ERROR];
            $this->Result = false;
        elseif (!Check::Email($this->Data['email'])):
            $this->Error = ["O <b>E-mail</b> informado não parece ter um formato válido!", RM_ERROR];
            $this->Result = false;
        elseif ($this->Data['avaliacao'] == ''):
            $this->Error = ["Por favor, Selecione uma <strong>Avaliação</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->checkEmail();
        endif;
    }
    
    //Verifica usuário pelo e-mail, Impede cadastro duplicado!
    private function checkEmail() {
        $Where = ( isset($this->Post) ? "id != {$this->Post} AND" : '');
        $readUser = new Read;
        $readUser->ExeRead(self::Entity, "WHERE {$Where} email = :email", "email={$this->Data['email']}");
        if ($readUser->getRowCount()):
            $this->Error = ["O <b>E-email</b> informado foi cadastrado no sistema por outro usuário! Informe outro e-mail!", RM_ERROR];
            $this->Result = false;
        else:
            $this->Result = true;
        endif;
    }
    
    //Cadastra Avaliação no banco!
    private function Create() {
        $Create = new Create;
        $Create->ExeCreate(self::Entity, $this->Data);
        if ($Create->getResult()):
            $this->Error = ["A Avaliação de <b>{$this->Data['nome']}</b> foi cadastrada com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $Create->getResult();
        endif;        
    }

    //Atualiza Avaliação no banco!
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["A Avaliação de <b>{$this->Data['nome']}</b> foi atualizada com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }
    
    //Remove Avaliação
    private function Delete() {
        $Delete = new Delete;
        $Delete->ExeDelete(self::Entity, "WHERE id = :id", "id={$this->Post}");
        if ($Delete->getResult()):
            $this->Error = ["A Avaliação foi removida com sucesso do sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

}

