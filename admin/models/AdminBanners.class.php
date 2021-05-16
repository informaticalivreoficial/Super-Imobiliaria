<?php

/**
 * AdminPost.class [ MODEL ADMIN ]
 * Respnsável por gerenciar os posts no Admin do sistema!
 * 
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class AdminBanners {

    private $Data;
    private $Post;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'banners';

    /**
     * <b>Cadastrar o Post:</b> Envelope os dados do post em um array atribuitivo e execute esse método
     * para cadastrar o post. Envia a capa automaticamente!
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;

        if ($this->Data['titulo'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Título</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();

            if ($this->Data['imagem']):            
                $pasta 	= 'banners';
                $upload = new Upload;
                $upload->ImageSemData($this->Data['imagem'], $this->Data['url'], '2200', $pasta);
            endif;

            if (isset($upload) && $upload->getResult()):
                $this->Data['imagem'] = $upload->getResult();
                $this->Create();
            else:
                $this->Data['imagem'] = null;
                    $_SESSION['errCapa'] = "<strong>Você não enviou uma Imagem</strong> ou o <strong>Tipo de arquivo é inválido</strong>, envie imagens JPG ou PNG!";
                $this->Create();
            endif;
        endif;
    }

    /**
     * <b>Atualizar Banner:</b> Envelope os dados em uma array atribuitivo e informe o id de um 
     * post para atualiza-lo na tabela!
     * @param INT $PostId = Id do post
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($PostId, array $Data) {
        $this->Post = (int) $PostId;
        $this->Data = $Data;

        if ($this->Data['titulo'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Título</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();
            $this->Data['uppdate'] = date('Y-m-d H:i:s');

            if (is_array($this->Data['imagem'])):
                $readCapa = new Read;
                $readCapa->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");
                $capa = '../uploads/' . $readCapa->getResult()[0]['imagem'];
                if (file_exists($capa) && !is_dir($capa)):
                    unlink($capa);
                endif;
                
                $pasta 	= 'banners';
                $uploadCapa = new Upload;
                $uploadCapa->ImageSemData($this->Data['imagem'], $this->Data['url'], '2200', $pasta);
            endif; 

            if (isset($uploadCapa) && $uploadCapa->getResult()):
                $this->Data['imagem'] = $uploadCapa->getResult();
                $this->Update();
            else:
                unset($this->Data['imagem']);
                if(!empty($uploadCapa) && $uploadCapa->getError()):
                    RMErro("<strong>Erro ao enviar Imagem</strong>: " .$uploadCapa->getError(), E_USER_WARNING);
                endif;
                $this->Update();
            endif;
        endif;
    }

    /**
     * <b>Deleta Post:</b> Informe o ID do post a ser removido para que esse método realize uma checagem de
     * pastas e galerias excluinto todos os dados nessesários!
     * @param INT $PostId = Id do post
     */
    public function ExeDelete($PostId) {
        $this->Post = (int) $PostId;

        $ReadPost = new Read;
        $ReadPost->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");

        if (!$ReadPost->getResult()):
            $this->Error = ["O banner que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];
            if (file_exists('../uploads/' . $PostDelete['imagem']) && !is_dir('../uploads/' . $PostDelete['imagem'])):
                unlink('../uploads/' . $PostDelete['imagem']);
            endif;
            
            $deleta = new Delete;
            $deleta->ExeDelete(self::Entity, "WHERE id = :postid", "postid={$this->Post}");

            $this->Error = ["O banner <b>{$PostDelete['titulo']}</b> foi removido com sucesso do sistema!", RM_ACCEPT];
            $this->Result = true;

        endif;
    }

    /**
     * <b>Ativa/Inativa Post:</b> Informe o ID do post e o status e um status sendo 1 para ativo e 0 para
     * rascunho. Esse méto ativa e inativa os posts!
     * @param INT $PostId = Id do post
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
        $Cover = $this->Data['imagem'];
        $Content = $this->Data['content'];
        unset($this->Data['imagem'], $this->Data['content']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        $this->Data['url']  = Check::Name($this->Data['titulo']);
        $this->Data['data'] = Check::Data($this->Data['data']);
        $this->Data['expira'] = Check::Data($this->Data['expira']);
        $this->Data['imagem'] = $Cover;
        $this->Data['content'] = $Content;
    }

    //Verifica o NAME post. Se existir adiciona um pós-fix -Count
    private function setName() {
        $Where = (isset($this->Post) ? "id != {$this->Post} AND" : '');
        $readName = new Read;
        $readName->ExeRead(self::Entity, "WHERE {$Where} titulo = :t", "t={$this->Data['titulo']}");
        if ($readName->getResult()):
            $this->Data['url'] = $this->Data['url'] . '-' . $readName->getRowCount();
        endif;
    }

    //Cadastra o post no banco!
    private function Create() {
        $cadastra = new Create;
        $cadastra->ExeCreate(self::Entity, $this->Data);
        if ($cadastra->getResult()):
            $this->Error = ["O banner {$this->Data['titulo']} foi cadastrado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $cadastra->getResult();
        endif;
    }

    //Atualiza o post no banco!
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["O banner <b>{$this->Data['titulo']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

}
