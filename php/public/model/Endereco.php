<?php
/**
 * Classe de gerenciamento da tabela "endereco", serve de modelo para replicar e gerenciar outras tabelas
 *
 * @category REST_API
 * @package  adrs-restful
 * @author   Daniel <daniel@antunesbr.com>
 */
require_once 'lib/Support.php';
require_once 'lib/Collection.php';

class Endereco extends Collection
{
    /**
     * Nome da tabela do banco de dados que esta classe irá gerenciar
     */
    private $collection_name = 'endereco';

    /**
     * Campos da tabela que esta classe representa e que irá gerenciar
     */
    private $pessoa;
    private $cep;
    private $rua;
    private $numero;
    private $bairro;
    private $cidade;
    private $principal;

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
    private function getPessoa() {
        return $this->pessoa;
    }

    private function setPessoa($pessoa) {
        if ($this->pessoa !== $pessoa) {
            $this->pessoa = $pessoa;
            $this->flag |= DATA_MODIFIED;
        }
    }
    private function getCep() {
        return $this->cep;
    }

    private function setCep($cep) {
        if ($this->cep !== $cep) {
            $this->cep = $cep;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getRua() {
        return $this->rua;
    }

    private function setRua($rua) {
        if ($this->rua !== $rua) {
            $this->rua = $rua;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getNumero() {
        return $this->numero;
    }

    private function setNumero($numero) {
        if ($this->numero !== $numero) {
            $this->numero = $numero;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getBairro() {
        return $this->bairro;
    }

    private function setBairro($bairro) {
        if ($this->bairro !== $bairro) {
            $this->bairro = $bairro;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getCidade() {
        return $this->cidade;
    }

    private function setCidade($cidade) {
        if ($this->cidade !== $cidade) {
            $this->cidade = $cidade;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getPrincipal() {
        return $this->principal;
    }

    private function setPrincipal($principal) {
        if ($this->principal !== $principal) {
            $this->principal = $principal;
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
        if (isset($data->cep)) {
            $this->setCep($data->cep);
        }
        if (isset($data->rua)) {
            $this->setRua($data->rua);
        }
        if (isset($data->numero)) {
            $this->setNumero($data->numero);
        }
        if (isset($data->bairro)) {
            $this->setBairro($data->bairro);
        }
        if (isset($data->cidade)) {
            $this->setCidade($data->cidade);
        }
        if (isset($data->principal)) {
            $this->setPrincipal($data->principal);
        }
    }

    /**
     * Monta uma lista com os dados armazenados no objeto (Value Object)
     */
    protected function dataVO() {
        return array(
            'id'        => $this->getId(),
            'pessoa'    => $this->getPessoa(),
            'cep'       => $this->getCep(),
            'rua'       => $this->getRua(),
            'numero'    => $this->getNumero(),
            'bairro'    => $this->getBairro(),
            'cidade'    => $this->getCidade(),
            'principal' => $this->getPrincipal()
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
        $this->setCep($data->cep);
        $this->setRua($data->rua);
        $this->setNumero($data->numero);
        $this->setBairro($data->bairro);
        $this->setCidade($data->cidade);
        $this->setPrincipal($data->principal);
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
                "CREATE TABLE endereco ("
                . "id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,"
                . "pessoa INT UNSIGNED NOT NULL,"
                . "cep VARCHAR(10) NULL,"
                . "rua VARCHAR(255) NULL,"
                . "numero VARCHAR(65) NULL,"
                . "bairro VARCHAR(65) NULL,"
                . "cidade INT UNSIGNED NOT NULL,"
                . "principal BOOLEAN"
                . ") COMMENT 'Cadastro de Endereço de Pessoas';"
                ."CREATE UNIQUE INDEX endereco_id_uindex ON endereco (id);";

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

    public function loadAllByPersonId($params, $data = []) {
        /**
         * O primeiro parâmetro após o nome do método deve ser o id da pessoa
         */
        $pid = $params[0];

        if (! preg_match('/^\d+$/', $pid)) {
            throw new Exception('Invalid parameter!');
        }

        $pid = filter_var($pid, FILTER_SANITIZE_NUMBER_INT);

        if ( ! $pid) {
            throw new Exception('Invalid parameter!');
        }

        /*
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /*
             * Monta o comando SQL
             */
            $sqlcmd = 'SELECT *,'
                . 'endereco.id AS id,'
                . 'city.name AS cidade_nome,'
                . 'state.name AS estado_nome '
                . ' FROM ' . $this->getCollection_name()
                . ' LEFT JOIN city ON(endereco.cidade = city.id)'
                . ' LEFT JOIN state ON(city.state_id = state.id)'
                . ' WHERE pessoa = \'' . $pid . '\';';

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