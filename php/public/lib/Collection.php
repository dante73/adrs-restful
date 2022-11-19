<?php
/**
 *   Controla alguns aspectos da comunicação com o banco de dados, que são as operações com as
 * tabelas (Collections).
 *
 * @category REST_API
 * @package  adrs-restful
 * @author   Daniel <daniel@antunesbr.com>
 */
require_once 'vendor/autoload.php';
require_once 'lib/DbAdmin.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/*
 * Estados do registro
 */
define("DATA_LOADED", 0x1);
define("DATA_MODIFIED", 0x2);
define("DATA_NEW", 0x4);

/**
 * Class Collection
 */
class Collection
{
    /*
     * Objeto de conexão com o banco de dados
     */
    private $dbAdmin;

    /*
     * Nome da collection para referência interna, será configurada pela classe descendente
     */
    private $collection_name;

    /*
     * Campo que configura a ordem do relatório
     */
    private $primary_order;

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
     * Método da operação
     */
    private $method;

    /**
     * Construtor da classe, configura a lista de campos e o nome da collection que receberá da classe descendente
     *
     * @param $collection_name
     */
    function __construct($collection_name) {
        /**
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /**
             * Configura a conexão com o banco de dados
             */
            $this->setDbAdmin();

            /**
             * Configura nome da tabela na classe pai
             */
            $this->setCollection_name($collection_name);

            /**
             * Verifica se a coleção/tabela existe no servidor de banco de dados, se não existir cria
             */
            if ( ! $this->table_exists() && ! $this->create_table() ) {
                throw new Exception('Table does not exists.');
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
     * Configura localmente uma instância de conexão com o banco de dados
     */
    protected function setDbAdmin() {
        $this->dbAdmin = DbAdmin::getInstance();
    }

    /**
     * Retorna a conexão local com o banco de dados
     */
    public function getConnection() {
        return $this->dbAdmin->getConnection();
    }

    /**
     * Verifica se a coleção/tabela existe no banco de dados conectado
     */
    protected function table_exists() {
        /**
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /*
             * Monta o comando SQL para testar a existência da tabela
             */
            $sqlcmd = 'SELECT 1 AS table_exists FROM ' . $this->getCollection_name() . ' LIMIT 1;';

            /*
             * Emite comando no servidor conectado em DbAdmin e trata o retorno
             */
            $conn = $this->getConnection();

            $result = $conn->query($sqlcmd);

            /*
             * Se houver qualquer falha, gera um estado de exceção
             */
            if ($result === false) {
                throw new Exception('Error checking table.');
            }

            return true;
        }
        /**
         * Se houve falha a tabela não existe, é necessário criar
         */
        catch (PDOException $e) {
            return false;
        }
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
    protected function getCollection_name() {
        return $this->collection_name;
    }

    private function setCollection_name($collection_name) {
        $this->collection_name = $collection_name;
    }

    /*
     * Getter e setter da ordem primária
     */
    protected function getPrimary_order() {
        return $this->primary_order;
    }

    protected function setPrimary_order($primary_order) {
        $this->primary_order = $primary_order;
    }

    /*
     * Getter e setter de dados brutos da coleção/tabela
     */
    protected function getRawdata() {
        return $this->rawdata;
    }

    protected function setRawdata($rawdata) {
        $this->rawdata = $rawdata;
    }

    /*
     * Getter e setter de campos da coleção/tabela
     */
    private function getFields() {
        return $this->fields;
    }

    protected function setFields($fields) {
        $this->fields = $fields;
    }

    /*
     * Getter e setter do método utilizado
     */
    private function getMethod() {
        return $this->method;
    }

    protected function setMethod($method) {
        $this->method = $method;
    }

    /*
     * Monta e retorna uma lista com o nome dos campos da coleção/tabela
     *
     * @return array
     */
    protected function getAllFieldsName() {
        /**
         * Conexão com o banco de dados
         */
        $conn = $this->getConnection();

        /**
         * Utiliza map para montar a lista de campos da tabela a partir do resultado captado no servidor de dados
         */
        return array_map(
            function($arr) { return $arr['Field']; },
            $conn->query('SHOW COLUMNS FROM ' . $this->getCollection_name())->fetchAll()
        );
    }

    /*
     *      Verifica se @key é um campo válido para gravação/edição e, no caso de edição, se o campo existe na tabela.
     * Isto para evitar falhas nas operações SQL; o campo é informado no JSON, mas não existe na coleção/tabela.
     *      É considerado somente o método "PUT", porque no caso de criação ("POST") deve ser possível fixar um ID,
     * isto para que a importação de estados e cidades absorva os IDs pré estabelecidos.
     *
     * @param $key      Nome do campo para ser verificado
     * @return bool     Sinaliza para o chamador se o campo é válido/existente na coleção/tabela
     */
    private function notAValidField($key) {
        return (boolean)(($key === 'id' && $this->getMethod() === 'PUT') || ! in_array($key, $this->getFields()));
    }

    /**
     *   Monta um array com os dados a serem usados nos statements preparados com "placeholders"
     *
     * @param $data
     * @return array
     */
    private function bindData($data) {
        $bindData = [];
        foreach ($data as $key => $value) {
            if ($this->notAValidField($key)) {
                continue;
            }

            $bindData[":{$key}"] = $value;
        }

        return $bindData;
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
                 * Se tudo correu bem, configura a propriedade com os dados e sinaliza o @flag com o estado loaded
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

            if (isset($this->primary_order)) {
                $sqlcmd .= ' ORDER BY ' . $this->getPrimary_order();
            }

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
            /**
             * Obtém o objeto de conexão com o banco de dados
             */
            $conn = $this->getConnection();

            /**
             * Monta uma string com o comando SQL preparado para inserções na collection/tabela
             */
            $sqlcmd = $this->getPreparedInsertStatement($data);

            /**
             * Monta no servidor o statement com o comando preparado com "placeholders"
             */
            $stmt = $conn->prepare($sqlcmd);

            /**
             * Monta o array com os "placeholders" e seus respectivos valores para serem gravados de forma segura
             */
            $bindData = $this->bindData($data);

            /**
             * Executa comando preparado usando os valores do array @bindData
             */
            $result = $stmt->execute($bindData);

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
                /**
                 * Capta o @id gerado para o novo registro
                 */
                $id = $conn->lastInsertId();

                /**
                 * Configura objeto com este @id
                 */
                $this->setId($id);

                /**
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
            /**
             * Obtém o objeto de conexão com o banco de dados
             */
            $conn = $this->getConnection();

            /**
             * Monta uma string com o comando SQL preparado para modificações na collection/tabela
             */
            $sqlcmd = $this->getPreparedUpdateStatement($data);

            /**
             * Monta no servidor o statement com o comando preparado com "placeholders"
             */
            $stmt = $conn->prepare($sqlcmd);

            /**
             * Monta o array com os "placeholders" e seus respectivos valores para serem gravados de forma segura
             */
            $bindData = $this->bindData($data);

            /**
             * Informa no statement qual o id será modificado
             */
            $bindData[":id"] = $this->getId();

            /**
             * Executa comando preparado usando os valores do array @bindData
             */
            $result = $stmt->execute($bindData);

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
    private function getPreparedInsertStatement($data) {
        $sqlcmd = "INSERT INTO " . $this->getCollection_name();

        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if ($this->notAValidField($key)) {
                continue;
            }

            array_push($fields, $key);
            array_push($values, ":{$key}");
        }

        $sqlcmd .= " (`";
        $sqlcmd .= implode("`, `", $fields);
        $sqlcmd .= "`) VALUES (";
        $sqlcmd .= implode(", ", $values);
        $sqlcmd .= ")";

        return $sqlcmd;
    }

    /**
     *   Com a lista de campos informada em @data, monta o comando SQL com os "fields" e "placeholders"
     * que serão executados para modificar informações na coleção/tabela.
     *
     * @param   array   $data   Lista de campos a serem gravadas na coleção/tabela
     * @return  string  $sqlcmd Comando SQL com campos e "placeholders" a serem executados no servidor.
     */
    private function getPreparedUpdateStatement($data) {
        /**
         * Inicializa o comando SQL para modificação
         */
        $sqlcmd = "UPDATE " . $this->getCollection_name() . " SET ";

        /**
         * Monta o comando SQL se baseando na variável @data passada como parâmetro
         */
        $setStatement = [];
        foreach ($data as $key => $value) {
            if ($this->notAValidField($key)) {
                continue;
            }

            array_push($setStatement, "`${key}` = :${key}");
        }

        $sqlcmd .= implode(", ", $setStatement);
        $sqlcmd .= " WHERE `id` = :id";

        return $sqlcmd;
    }

    /**
     * Executa a tarefa solicitada pelo chamador se baseando nas informações fornecidas : método, @id e @dados
     */
    public function doIt($method = 'POST', $id = 0, $data = array(), $op = '') {
        /**
         * Informa na classe o método que está sendo utilizado para a operação
         */
        $this->setMethod($method);

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
                if ($id === 0) {
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
                else {
                    throw new Exception('To change a record use PUT method instead POST.');
                }
                break;
            case 'PUT':
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
                    throw new Exception('To create new record use POST method instead PUT.');
                }
                break;
        }
    }

    /**
     * Grava localmente os arquivos enviados por formulário
     */
    public function saveSentFile($file) {
        if ( ! isset($_FILES[$file]) || ! $_FILES[$file]) {
            throw new Exception('File not found in POST.');
        }

        $fn = $_FILES[$file]['name'];
        $ext = strtolower(substr($fn, strrpos($fn, '.')));

        if ( ! $fn || ! $ext) {
            throw new Exception('Invalid filename.');
        }

        $fnc = substr($fn, 0, strpos($fn, $ext) - 1);
        $nfn = $file . '-' . $fnc . '-' . date("Y-m-d-His") . $ext;
        $dir = './assets/' . $this->collection_name . '/';
        $fullfn = $dir . $nfn;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $fullfn)) {
            http_response_code(200);
            exit(json_encode(array('filename' => $nfn)));
        }
        else {
            http_response_code(403);
            throw new Exception('Error while moving file to assets.');
        }
    }

    /**
     * Verifica os dados de autenticação para uso do serviço
     */
    public function verifyServiceAuthorization() {
        $config = parse_ini_file(Constants::INI_FILENAME, true);

        $request_header = apache_request_headers();

        if ($request_header === false) {
            throw new Exception('No headers.');
        }

        if ( ! isset($request_header['Authorization'])) {
            throw new Exception('No permission.');
        }

        $key = $config['JWT']['key'];
        $token = $request_header['Authorization'];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));

        if ( ! (
            isset($decoded->id) &&
            isset($decoded->login) &&
            isset($decoded->nome) &&
            isset($decoded->timeout))) {
            http_response_code(401);
            throw new Exception('Invalid authorization.');
        }

        if ($decoded->timeout < time()) {
            http_response_code(401);
            throw new Exception('Authorization expired.');
        }

        return true;
    }
}