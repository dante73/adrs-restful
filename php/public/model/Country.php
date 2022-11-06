<?php
/**
 * Classe de gerenciamento da tabela "country", serve de modelo para replicar e gerenciar outras tabelas.
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
     * Sinaliza se o método solicitado necessita de autenticação, o padrão é verdadeiro, sendo falso somente no
     * caso do usuário estar se autenticando no método de autenticação especificado pela condição.
     *
     * @param $op
     * @return bool
     */
    public function authNeeded($op) {
        return true;
    }

    /**
     * Getter e setters dos campos da tabela aqui gerenciada
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
     * Localiza e abre registro buscando pelo código de país de dois alfa : BR, US, etc...
     */
    protected function loadByCountry_iso_alpha2($country_iso_alpha2) {
        /**
         * Efetua a operação com o banco de dados esperando por exceção
         */
        try {
            /**
             * Conexão com o servidor de dados
             */
            $conn = $this->getConnection();

            /**
             * Comando SQL para procurar a informação no banco de dados
             */
            $sqlcmd = "SELECT * FROM country WHERE country_iso_alpha2 = :country_iso_alpha2;";

            /**
             * Prepara e executa o comando no servidor
             */
            $stmt = $conn->prepare($sqlcmd);
            $stmt->bindParam(':country_iso_alpha2', $country_iso_alpha2);
            $stmt->execute();

            /**
             * Baixa resultado na variável para retornar depois
             */
            $result = $stmt->fetchObject();

            /**
             * Se tudo correu bem, configura a propriedade com os dados
             */
            $this->setRawdata($result);

            /**
             * Sinaliza o @flag com o estado loaded
             */
            $this->flag |= DATA_LOADED;

            /**
             *  Retorna o resultado
             */
            return $result;
        }
        /**
         * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
         */
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }

    /**
     * Localiza e abre registro buscando pelo código de país de dois alfa : BRA, USA, etc...
     */
    protected function loadByCountry_iso_alpha3($country_iso_alpha3) {
        /**
         * Efetua a operação com o banco de dados esperando por exceção
         */
        try {
            /**
             * Conexão com o servidor de dados
             */
            $conn = $this->getConnection();

            /**
             * Comando SQL para procurar a informação no banco de dados
             */
            $sqlcmd = "SELECT * FROM country WHERE country_iso_alpha3 = :country_iso_alpha3;";

            /**
             * Prepara e executa o comando no servidor
             */
            $stmt = $conn->prepare($sqlcmd);
            $stmt->bindParam(':country_iso_alpha3', $country_iso_alpha3);
            $stmt->execute();

            /**
             * Baixa resultado na variável para retornar depois
             */
            $result = $stmt->fetchObject();

            /*
             * Se tudo correu bem, configura a propriedade com os dados
             */
            $this->setRawdata($result);

            /**
             * Sinaliza o @flag com o estado loaded
             */
            $this->flag |= DATA_LOADED;

            /**
             *  Retorna o resultado
             */
            return $result;
        }
        /*
         * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
         */
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }

    /**
     * Higieniza os dados para gravação no banco de dados
     *
     * @param   array   @data   Dados recebidos do usuário
     */
    protected function sanitize($data) {
        /**
         * Verifica se está faltando alguma coisa
         */
        if ( ! isset($data->name) ||
             ! isset($data->country_code) ||
             ! isset($data->country_iso_alpha2) ||
             ! isset($data->country_iso_alpha3))
        {
            throw new Exception("Missing data.");
        }

        /**
         * Se não, verifica se estão válidos
         */
        $this->setName($data->name);
        $this->setCountry_code($data->country_code);

        if ( ! preg_match('/^[A-Z]{2}$/', $data->country_iso_alpha2)) {
            throw new Exception("Invalid country code for alpha2.");
        }
        else {
            if ($this->loadByCountry_iso_alpha2($data->country_iso_alpha2)) {
                throw new Exception("Country code alpha2 already exists.");
            }
            else {
                $this->setCountry_iso_alpha2($data->country_iso_alpha2);
            }
        }

        if ( ! preg_match('/^[A-Z]{3}$/', $data->country_iso_alpha3)) {
            throw new Exception("Invalid country code for alpha3.");
        }
        else {
            if ($this->loadByCountry_iso_alpha3($data->country_iso_alpha3)) {
                throw new Exception("Country code alpha3 already exists.");
            }
            else {
                $this->setCountry_iso_alpha3($data->country_iso_alpha3);
            }
        }
    }

    /**
     * Monta um array com os dados armazenados no objeto (Value Object)
     */
    protected function dataVO() {
        return array(
            'id'                    => $this->getId(),
            'name'                  => $this->getName(),
            'country_code'          => $this->getCountry_code(),
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