<?php
/**
 * Classe de gerenciamento da tabela "pessoa", serve de modelo para replicar e gerenciar outras tabelas
 *
 * @category REST_API
 * @package  adrs-restful
 * @author   Daniel <daniel@antunesbr.com>
 */
require_once 'lib/Support.php';
require_once 'lib/Collection.php';

class Pessoa extends Collection
{
    /**
     * Nome da tabela do banco de dados que esta classe irá gerenciar
     */
    private $collection_name = 'pessoa';

    /**
     * Campos da tabela que esta classe representa e que irá gerenciar
     */
    private $nome;
    private $genero;
    private $nascimento;

    /**
     * Construtor da classe
     */
    public function __construct() {
        /**
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /**
             * Configura nome da tabela na classe pai
             */
            parent::__construct($this->collection_name);

            /**
             * Verifica se a coleção/tabela existe no servidor de banco de dados, se não existir cria
             */
            if ( ! $this->table_exists()) {
                $this->create_table();
            }

            /**
             * Monta a lista com todos os campos da coleção/tabela no banco de dados
             */
            $this->setFields($this->getAllFieldsName());
        }
        /**
         * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
         */
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }

    /**
     * Getter e setters dos campos
     */
    private function getNome() {
        return $this->nome;
    }

    private function setNome($nome) {
        if ($this->nome !== $nome) {
            $this->nome = $nome;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getGenero() {
        return $this->genero;
    }

    private function setGenero($genero) {
        if ($this->genero !== $genero) {
            $this->genero = $genero;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getNascimento() {
        return $this->nascimento;
    }

    private function setNascimento($nascimento) {
        if ($this->nascimento !== $nascimento) {
            $this->nascimento = $nascimento;
            $this->flag |= DATA_MODIFIED;
        }
    }

    /**
     * Higieniza os dados para gravação no banco de dados
     *
     * @param   array   @data   Dados recebidos do usuário
     */
    protected function sanitize($data) {
        if (isset($data->nome)) {
            $this->setNome($data->nome);
        }
        if (isset($data->genero)) {
            $this->setGenero($data->genero);
        }
        if (isset($data->nascimento)) {
            $this->setNascimento($data->nascimento);
        }
    }

    /**
     * Monta uma lista com os dados armazenados no objeto (Value Object)
     */
    protected function dataVO() {
        return array(
            'id'            => $this->getId(),
            'nome'          => $this->getNome(),
            'genero'        => $this->getGenero(),
            'nascimento'    => Support::DateToDb($this->getNascimento())
        );
    }

    /**
     * Coloca os valores obtidos da coleção/tabela do banco de dados localmente no Objeto (Value Object)
     */
    protected function setDataVO() {
        /**
         * Pega os dados obtidos da coleção/tabela do banco de dados
         */
        $data = $this->getRawData();

        /**
         * Seta individualmente os campos locais para refletirem os dados obtidos da coleção/tabela
         */
        $this->setNome($data->nome);
        $this->setGenero($data->genero);
        $this->setNascimento($data->nascimento);
    }

    /**
     * Executa a tarefa solicitada pelo chamador se baseando nas informações fornecidas : método, @id e @dados
     */
    public function do($method = 'POST', $id = 0, $data = array()) {
        /**
         * Se o @id foi informado, localiza os dados na tabela e seta o objeto local com estes dados
         */
        if ($id !== 0) {
            $this->setId($id);
            $this->load();
            $this->setDataVO();
        }

        /**
         * Faz a operação solicitada se baseando nas informações fornecidas (principalmente o método)
         */
        switch ($method) {
            case 'DELETE':
                /**
                 * Deletar um registro pelo @id (que foi carregado anteriormente no @load
                 */
                $this->delete();

                return array(
                    'code' => 200,
                    'text' => 'Record deleted successfully.'
                );
                break;
            case 'GET':
                /**
                 * Consulta simples, com o @id individual ou geral
                 */
                return ( $id !== 0 ? $this->dataVO() : $this->loadAll() );
                break;
            case 'POST':
                /**
                 * Higieniza os dados antes de qualquer operação no banco de dados
                 */
                $this->sanitize($data);

                /**
                 * Se informou o @id procede com a modificação
                 */
                if ($id !== 0) {
                    /**
                     * Só executa ação se houver mudança no conteúdo
                     */
                    if ($this->flag & DATA_MODIFIED) {
                        /**
                         * Edição de dados
                         */
                        $this->update($this->dataVO());

                        return array(
                            'code' => 200,
                            'text' => 'Record updated successfully.',
                            'data' => $this->dataVO()
                        );
                    }
                    else {
                        return array(
                            'code' => 200,
                            'text' => 'Nothing to do.',
                            'data' => $this->dataVO()
                        );
                    }
                }
                else {
                    /**
                     * Criação de um novo registro no banco de dados
                     */
                    $this->insert($this->dataVO());

                    return array(
                        'code' => 201,
                        'text' => 'Record created successfully.',
                        'data' => $this->dataVO()
                    );
                }
                break;
        }
    }

    /**
     * Cria a tabela no banco de dados
     */
    protected function create_table() {
        /**
         * Efetua a operação com o banco de dados esperando por exceção
         */
        try {
            /**
             * Comando SQL para criar a tabela e o índice no banco de dados
             */
            $sqlcmd =
                "CREATE TABLE pessoa ("
                . "id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,"
                . "nome VARCHAR(255) NULL,"
                . "genero CHARACTER(1) NULL,"
                . "nascimento DATE NULL"
                . ") COMMENT 'Cadastro de Pessoas';"
                ."CREATE UNIQUE INDEX pessoa_id_uindex ON pessoa (id);";

            /**
             * Executa comando no servidor conectado e trata o retorno
             */
            $conn = $this->getConnection();

            $result = $conn->exec($sqlcmd);

            /**
             * O retorno false indica que não foi possível criar a tabela
             */
            if ($result === false) {
                throw new Exception('Table does not exists.');
            }

            /**
             * Informa o chamador que criou a tabela e pode prosseguir
             */
            return true;
        }
        /*
         * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
         */
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }
}