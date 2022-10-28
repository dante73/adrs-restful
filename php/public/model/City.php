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

class City extends Collection
{
    /**
     * Nome da tabela do banco de dados que esta classe irá gerenciar
     */
    private $collection_name = 'city';

    /**
     * Campos da tabela que esta classe representa e que irá gerenciar
     */
    private $name;
    private $state_id;

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
    private function getName() {
        return $this->name;
    }

    private function setName($name) {
        if ($this->name !== $name) {
            $this->name = $name;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getState_id() {
        return $this->state_id;
    }

    private function setState_id($state_id) {
        if ($this->state_id !== $state_id) {
            $this->state_id = $state_id;
            $this->flag |= DATA_MODIFIED;
        }
    }

    /**
     * Higieniza os dados para gravação no banco de dados
     *
     * @param   array   @data   Dados recebidos do usuário
     */
    protected function sanitize($data) {
        if (isset($data->id)) {
            $this->setId($data->id);
        }
        if (isset($data->name)) {
            $this->setName($data->name);
        }
        if (isset($data->state_id)) {
            $this->setState_id($data->state_id);
        }
    }

    /**
     * Monta uma lista com os dados armazenados no objeto (Value Object)
     */
    protected function dataVO() {
        return array(
            'id'            => $this->getId(),
            'name'          => $this->getName(),
            'state_id'      => $this->getState_id()
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
        $this->setId($data->id);
        $this->setName($data->name);
        $this->setState_id($data->state_id);
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
                "CREATE TABLE city ("
                . "id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,"
                . "name VARCHAR(255) NULL,"
                . "state_id INT NULL"
                . ") COMMENT 'Cadastro de Cidades';"
                ."CREATE UNIQUE INDEX city_id_uindex ON city (id);";

            /**
             * Conexão com o servidor de dados
             */
            $conn = $this->getConnection();

            /**
             * Executa comando no servidor conectado e retorna o resultado
             */
            return $conn->exec($sqlcmd);
        }
        /*
         * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
         */
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }

    public function loadAllByStateId($params, $data = [])
    {
        /**
         * O primeiro parâmetro após o nome do método deve ser o id do estado
         */
        $pid = $params[0];

        if (!preg_match('/^\d+$/', $pid)) {
            throw new Exception('Invalid parameter!');
        }

        $pid = filter_var($pid, FILTER_SANITIZE_NUMBER_INT);

        if (!$pid) {
            throw new Exception('Invalid parameter!');
        }

        /*
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /*
             * Monta o comando SQL
             */
            $sqlcmd = 'SELECT * FROM ' . $this->getCollection_name() . ' WHERE state_id = \'' . $pid . '\' ORDER BY name;';

            /*
             * Emite comando no servidor conectado em DbAdmin e trata o retorno
             */
            $conn = $this->getConnection();

            $result = $conn->query($sqlcmd);

            /*
             * Se houver qualquer falha, gera um estado de exceção
             */
            if ($result === false) {
                throw new Exception('Error loading data.');
            } else if ($result->rowCount() === 0) {
                throw new Exception('Record not found.');
            } else {
                /*
                 * Se tudo correu bem, retorna as informações obtidas para o chamador
                 */
                return $result->fetchAll();
            }
        } /*
             * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
             */
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }
}