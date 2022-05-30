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

class State extends Collection
{
    /**
     * Nome da tabela do banco de dados que esta classe irá gerenciar
     */
    private $collection_name = 'state';

    /**
     * Campos da tabela que esta classe representa e que irá gerenciar
     */
    private $name;
    private $state_short;
    private $country_id;

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

    private function getState_short() {
        return $this->state_short;
    }

    private function setState_short($state_short) {
        if ($this->state_short !== $state_short) {
            $this->state_short = $state_short;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getCountry_id() {
        return $this->country_id;
    }

    private function setCountry_id($country_id) {
        if ($this->country_id !== $country_id) {
            $this->country_id = $country_id;
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
        if (isset($data->state_short)) {
            $this->setState_short($data->state_short);
        }
        if (isset($data->country_id)) {
            $this->setCountry_id($data->country_id);
        }
    }

    /**
     * Monta uma lista com os dados armazenados no objeto (Value Object)
     */
    protected function dataVO() {
        return array(
            'id'            => $this->getId(),
            'name'          => $this->getName(),
            'state_short'   => $this->getState_short(),
            'country_id'    => $this->getCountry_id()
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
        $this->setState_short($data->state_short);
        $this->setCountry_id($data->country_id);
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
                "CREATE TABLE state ("
                . "id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,"
                . "name VARCHAR(255) NULL,"
                . "state_short VARCHAR(2) NULL,"
                . "country_id INT NULL"
                . ") COMMENT 'Cadastro de Estados';"
                ."CREATE UNIQUE INDEX state_id_uindex ON state (id);";

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
}