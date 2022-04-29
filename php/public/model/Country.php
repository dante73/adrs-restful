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

class Country extends Collection
{
    /**
     * Nome da tabela do banco de dados que esta classe irá gerenciar
     */
    private $collection_name = 'country';

    /**
     * Campos da tabela que esta classe representa e que irá gerenciar
     */
    private $name;
    private $country_code;
    private $country_iso_alpha2;
    private $country_iso_alpha3;

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

    private function getCountry_code() {
        return $this->country_code;
    }

    private function setCountry_code($country_code) {
        if ($this->country_code !== $country_code) {
            $this->country_code = $country_code;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getCountry_iso_alpha2() {
        return $this->country_iso_alpha2;
    }

    private function setCountry_iso_alpha2($country_iso_alpha2) {
        if ($this->country_iso_alpha2 !== $country_iso_alpha2) {
            $this->country_iso_alpha2 = $country_iso_alpha2;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getCountry_iso_alpha3() {
        return $this->country_iso_alpha3;
    }

    private function setCountry_iso_alpha3($country_iso_alpha3) {
        if ($this->country_iso_alpha3 !== $country_iso_alpha3) {
            $this->country_iso_alpha3 = $country_iso_alpha3;
            $this->flag |= DATA_MODIFIED;
        }
    }

    /**
     * Higieniza os dados para gravação no banco de dados
     *
     * @param   array   @data   Dados recebidos do usuário
     */
    protected function sanitize($data) {
        if (isset($data->name)) {
            $this->setName($data->name);
        }
        if (isset($data->country_code)) {
            $this->setCountry_code($data->country_code);
        }
        if (isset($data->country_iso_alpha2)) {
            $this->setCountry_iso_alpha2($data->country_iso_alpha2);
        }
        if (isset($data->country_iso_alpha3)) {
            $this->setCountry_iso_alpha3($data->country_iso_alpha3);
        }
    }

    /**
     * Monta uma lista com os dados armazenados no objeto (Value Object)
     */
    protected function dataVO() {
        return array(
            'id'                    => $this->getId(),
            'name'                  => $this->getName(),
            'country_id'            => $this->getCountry_id(),
            'country_iso_alpha2'    => $this->getCountry_iso_alpha2(),
            'country_iso_alpha3'    => $this->getCountry_iso_alpha3(),
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
        $this->setName($data->name);
        $this->setCountry_code($data->country_code);
        $this->setCountry_iso_alpha2($data->country_iso_alpha2);
        $this->setCountry_iso_alpha3($data->country_iso_alpha3);
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
                "CREATE TABLE country ("
                . "id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,"
                . "name VARCHAR(255) NULL,"
                . "country_code SMALLINT NULL,"
                . "country_iso_alpha2 VARCHAR(2) NULL,"
                . "country_iso_alpha3 VARCHAR(3) NULL"
                . ") COMMENT 'Cadastro de Países';"
                ."CREATE UNIQUE INDEX country_id_uindex ON country (id);";

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