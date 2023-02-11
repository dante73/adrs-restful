<?php
/**
 * Classe de gerenciamento da tabela "contato", serve de modelo para replicar e gerenciar outras tabelas
 *
 * @category REST_API
 * @package  adrs-restful
 * @author   Daniel <daniel@antunesbr.com>
 */
require_once 'lib/Support.php';
require_once 'lib/Collection.php';

class Contato extends Collection
{
    /**
     * Nome da tabela do banco de dados que esta classe irá gerenciar
     */
    private $collection_name = 'contato';

    /**
     * Campos da tabela que esta classe representa e que irá gerenciar
     */
    private $pessoa;
    private $tipo;
    private $valor;

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
     * Getter e setters dos campos
     */
    private function getPessoa() {
        return $this->pessoa;
    }

    private function setPessoa($pessoa) {
        if ($this->pessoa !== $pessoa) {
            $this->pessoa = $pessoa;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getTipo() {
        return $this->tipo;
    }

    private function setTipo($tipo) {
        if ($this->tipo !== $tipo) {
            $this->tipo = $tipo;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getValor() {
        return $this->valor;
    }

    private function setValor($valor) {
        if ($this->valor !== $valor) {
            $this->valor = $valor;
            $this->flag |= DATA_MODIFIED;
        }
    }

    /**
     * Higieniza os dados para gravação no banco de dados
     *
     * @param   array   @data   Dados recebidos do usuário
     */
    protected function sanitize($data) {
        if (isset($data->pessoa)) {
            $this->setPessoa($data->pessoa);
        }
        if (isset($data->tipo)) {
            $this->setTipo($data->tipo);
        }
        if (isset($data->valor)) {
            $this->setValor($data->valor);
        }
    }

    /**
     * Monta uma lista com os dados armazenados no objeto (Value Object)
     */
    protected function dataVO() {
        return array(
            'id'     => $this->getId(),
            'pessoa' => $this->getPessoa(),
            'tipo'   => $this->getTipo(),
            'valor'  => $this->getValor(),
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
        $this->setPessoa($data->pessoa);
        $this->setTipo($data->tipo);
        $this->setValor($data->valor);
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
                "CREATE TABLE contato ("
                . "id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,"
                . "pessoa INT UNSIGNED NOT NULL,"
                . "tipo CHARACTER(1) NULL,"
                . "valor VARCHAR(255) NULL"
                . ") COMMENT 'Cadastro de Dados de Contato de Pessoas';"
                ."CREATE UNIQUE INDEX contato_id_uindex ON contato (id);";

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

    /**
     * Abre o objeto pai (Pessoa) pelo ID informado
     */
    public function loadParentById($id) {
        /**
         * Cria um objeto com a classe pai
         */
        $parent = new Pessoa();
        $parent->setId($id);
        $parent->load();

        /**
         * Seta o objeto pai
         */
        $this->setParent($parent);
    }

    public function loadAllByPersonId($params, $data = []) {
        /*
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /*
             * Monta o comando SQL
             */
            $sqlcmd = 'SELECT * FROM ' . $this->getCollection_name()
                . ' WHERE pessoa = \'' . $this->getParent()->getId(). '\' ORDER BY tipo;';

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
            }
            else if ($result->rowCount() === 0) {
                throw new Exception('Record not found.');
            }
            else {
                /*
                 * Se tudo correu bem, retorna as informações obtidas para o chamador
                 */
                return $result->fetchAll();
            }
        }
            /*
             * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
             */
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }

    }
}