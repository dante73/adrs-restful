<?php
/**
 *   Controla alguns aspectos da comunicação com o banco de dados, que são as operações com as
 * tabelas (Collections).
 *
 * @category REST_API
 * @package  adrs-restful
 * @author   Daniel <daniel@antunesbr.com>
 */
require_once 'lib/DbAdmin.php';

/*
 * Estados do registro
 */
define("DATA_LOADED", 0x1);
define("DATA_MODIFIED", 0x2);
define("DATA_NEW", 0x4);

/**
 * Class Collection
 */
class Collection extends DbAdmin
{
    /*
     * Nome da collection para referência interna, será configurada pela classe descendente
     */
    private $collection_name;

    /*
     * Id do registro, é o único campo que é gerenciado aqui
     */
    private $id;

    /*
     * Controla os estados do registro, o estado inicial é zero
     */
    protected $flag = 0x0;

    /*
     * Dados do registro baixados diretamente da tabela
     */
    private $rawdata;

    /*
     * Lista de campos da collection
     */
    private $fields;

    /**
     * Construtor da classe, configura a lista de campos e o nome da collection que receberá da classe descendente
     *
     * @param $collection_name
     */
    function __construct($collection_name) {
        parent::__construct();

        $this->setCollection_name($collection_name);
        $this->setFields($this->getAllFieldsName());
    }

    /*
     * Getter e setter do @id
     */
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    /*
     * Getter e setter do nome da coleção/tabela
     */
    private function getCollection_name() {
        return $this->collection_name;
    }

    private function setCollection_name($collection_name) {
        $this->collection_name = $collection_name;
    }

    /*
     * Getter e setter dos dados da tabela
     */
    protected function getRawdata() {
        return $this->rawdata;
    }

    private function setRawdata($rawdata) {
        $this->rawdata = $rawdata;
    }

    /*
     * Getter e setter dos campos da coleção/tabela
     */
    private function getFields() {
        return $this->fields;
    }

    private function setFields($fields) {
        $this->fields = $fields;
    }

    /*
     * Monta e retorna uma lista com o nome dos campos da coleção/tabela
     *
     * @return array
     */
    private function getAllFieldsName() {
        $conn = $this->getConnection();

        return array_map(
            function($arr) { return $arr['Field']; },
            $conn->query('SHOW COLUMNS FROM ' . $this->getCollection_name())->fetchAll()
        );
    }

    /*
     *   Verifica se é um campo válido para gravação/edição e, no caso de edição, se o campo existe na tabela. Isto
     * para evitar falhas nas operações SQL; o campo é informado no JSON, mas não existe na coleção/tabela.
     *
     * @param $key      Nome do campo para ser verificado
     * @return bool     Sinaliza para o chamador se o campo é válido/existente na coleção/tabela
     */
    private function notAValidField($key) {
        return (boolean)($key === 'id' || ! in_array($key, $this->getFields()));
    }

    /*
     * Carrega registro com dados da tabela a partir do @id configurado
     *
     * @throws Exception
     */
    protected function load() {
        /*
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /*
             * Monta o comando SQL
             */
            $sqlcmd = 'SELECT * FROM ' . $this->getCollection_name() . ' WHERE id = \'' . $this->getId() . '\';';

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
                 * Se tudo correu bem, configura a propriedade com os dados e sinaliza o flag com o estado loaded
                 */
                $this->setRawdata($result->fetchObject());

                $this->flag |= DATA_LOADED;
            }
        }
        /*
         * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
         */
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }

    /*
     * Deleta registro da tabela a partir do @id configurado
     *
     * @throws Exception
     */
    public function delete() {
        /*
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /*
             * Monta o comando SQL
             */
            $sqlcmd = 'DELETE FROM ' . $this->getCollection_name() . ' WHERE id = \'' . $this->getId() . '\';';

            /*
             * Emite comando no servidor conectado em DbAdmin e trata o retorno
             */
            $conn = $this->getConnection();

            $result = $conn->query($sqlcmd);

            /*
             * Se houver qualquer falha, gera um estado de exceção
             */
            if ($result === false) {
                throw new Exception('Error deleting record.');
            }
            else if ($result->rowCount() === 0) {
                throw new Exception('Record not found.');
            }
        }
        /*
         * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
         */
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }

    /*
     * Carrega todos os dados da coleção/tabela e retorna estas informações para o chamador
     *
     * @throws Exception
     */
    function loadAll() {
        /*
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /*
             * Monta o comando SQL
             */
            $sqlcmd = 'SELECT * FROM ' . $this->getCollection_name();

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

    /*
     * Insere registro com dados na tabela a partir de dados/informações fornecidas na lista @data
     *
     * @throws Exception
     */
    protected function insert($data) {
        /*
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /*
             * Monta o comando SQL para inserção
             */
            $sqlcmd = "INSERT INTO " . $this->getCollection_name();
            $sqlcmd .= $this->getFieldsForInsertStatement($data);

            /*
             * Emite comando no servidor conectado em DbAdmin e trata o retorno
             */
            $conn = $this->getConnection();

            $result = $conn->exec($sqlcmd);

            /*
             * Se houver qualquer falha, gera um estado de exceção geral
             */
            if ($result === false) {
                throw new Exception('Error writing new record.');
            }
            elseif ($result === 0) {
                throw new Exception('Error writing new record. Zero row affected.');
            }
            else {
                /*
                 * Capta o @id gerado para o novo registro
                 */
                $id = $conn->lastInsertId();

                /*
                 * Configura objeto com este @id
                 */
                $this->setId($id);

                /*
                 * Envia ao servidor comando para efetivar dados informados no banco de dados
                 * Só funciona quando estiver trabalhando com transactions
                $conn->commit();
                 */

                /*
                 * Carrega os novos dados a partir do banco de dados
                 */
                $this->load();
            }
        }
        /*
         * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
         */
        catch (Exception $e) {
            throw new Exception('Error writing new record. ' . $e->getMessage());
        }
    }

    /*
     * Modifica registro na tabela a partir de dados/informações fornecidas na lista @data
     *
     * @throws Exception
     */
    protected function update($data) {
        /*
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /*
             * Monta o comando SQL para modificações
             */
            $sqlcmd = "UPDATE " . $this->getCollection_name() . " SET ";
            $sqlcmd .= $this->getFieldsForUpdateStatement($data);

            /*
             * Emite comando no servidor conectado em DbAdmin e trata o retorno
             */
            $conn = $this->getConnection();

            $result = $conn->exec($sqlcmd);

            /*
             * Se houver qualquer falha, gera um estado de exceção geral
             */
            if ($result === false) {
                throw new Exception('Error updating record.');
            }
            elseif ($result === 0) {
                throw new Exception('Error updating record. Zero row affected.');
            }
            else {
                /*
                 * Recarrega os dados a partir do banco de dados
                 */
                $this->load();
            }
        }
        /*
         * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
         */
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }

    /*
     *   Com a lista de dados informada em @data, monta parte do comando SQL que será concatenado ao comando
     * completo para inserir informações na coleção/tabela.
     *
     * @param   array   $data   Lista de dados com informações a serem gravadas na coleção/tabela
     * @return  string  $sqlcmd Parte do comando SQL com informações a serem concatenadas ao comando completo
     */
    private function getFieldsForInsertStatement($data) {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if ($this->notAValidField($key)) {
                continue;
            }

            array_push($fields, $key);
            array_push($values, $value);
        }

        $sqlcmd = " (`";
        $sqlcmd .= implode("`, `", $fields);
        $sqlcmd .= "`) VALUES ('";
        $sqlcmd .= implode("', '", $values);
        $sqlcmd .= "')";

        return $sqlcmd;
    }

    /*
     *   Com a lista de dados informada em @data, monta parte do comando SQL que será concatenado ao comando
     * completo para modificar informações na coleção/tabela.
     *
     * @param   array   $data   Lista de dados com informações a serem gravadas na coleção/tabela
     * @return  string  $sqlcmd Parte do comando SQL com informações a serem concatenadas ao comando completo
     */
    private function getFieldsForUpdateStatement($data) {
        $setStatement = [];

        foreach ($data as $key => $value) {
            if ($this->notAValidField($key)) {
                continue;
            }

            array_push($setStatement, "`${key}` = '${value}'");
        }

        $sqlcmd = implode(", ", $setStatement);
        $sqlcmd .= " WHERE `id` = '" . $this->getId() . "'";

        return $sqlcmd;
    }
}