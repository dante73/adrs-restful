<?php
/**
 * Classe de gerenciamento da tabela "acessos", serve de modelo para replicar e gerenciar outras tabelas
 *
 * @category REST_API
 * @package  adrs-restful
 * @author   Daniel <daniel@antunesbr.com>
 */
require_once 'vendor/autoload.php';
require_once 'lib/Support.php';
require_once 'lib/Collection.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Acesso extends Collection
{
    /**
     * Nome da tabela do banco de dados que esta classe irá gerenciar
     */
    private $collection_name = 'acesso';

    /**
     * Campos da tabela que esta classe representa e que irá gerenciar
     */
    private $pessoa;
    private $chave;
    private $senha;

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
     * Sinaliza que o método solicitado necessita de autenticação, o padrão é verdadeiro, sendo falso somente no
     * caso do usuário estar se autenticando no método de autenticação especificado pela condição.
     *
     * @param $op
     * @return bool
     */
    public function authNeeded($op) {
        /**
         * Somente o método authenticate não exigirá que o usuário esteja logado.
         */
        if ($op === 'authenticate') {
            return false;
        }

        /**
         * Todos os outros procede normalmente, exigindo a chave de autenticação.
         */
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

    private function getChave() {
        return $this->chave;
    }

    private function setChave($chave) {
        if ($this->chave !== $chave) {
            $this->chave = $chave;
            $this->flag |= DATA_MODIFIED;
        }
    }

    private function getSenha() {
        return $this->senha;
    }

    private function setSenha($senha) {
        if ($this->senha !== $senha) {
            $this->senha = $senha;
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
        if (isset($data->chave)) {
            $this->setChave($data->chave);
        }
        if (isset($data->senha)) {
            $this->setSenha($data->senha);
        }
    }

    /**
     * Monta uma lista com os dados armazenados no objeto (Value Object)
     */
    protected function dataVO() {
        return array(
            'id'     => $this->getId(),
            'pessoa' => $this->getPessoa(),
            'chave'  => $this->getChave(),
            'senha'  => $this->getSenha(),
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
        $this->setChave($data->chave);
        $this->setSenha($data->senha);
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
                "CREATE TABLE acesso ("
                . "id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,"
                . "pessoa INT UNSIGNED NOT NULL,"
                . "chave VARCHAR(255) NULL,"
                . "senha VARCHAR(255) NULL"
                . ") COMMENT 'Cadastro de Dados de Acesso de Pessoas';"
                ."CREATE UNIQUE INDEX acesso_id_uindex ON acesso (id);";

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
                . ' WHERE pessoa = \'' . $this->getParent()->getId() . '\' ORDER BY chave;';

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

    public function authenticate($params, $data = []) {
        /* Verify required data */
        if ( ! isset($data->login) || ! isset($data->password)) {
            throw new Exception('Insufficient data to authenticate.');
        }

        /*
        print '"' . $data->encrypted . '"';

        $decrypted = openssl_decrypt($data->encrypted, 'aes128', 'Chave secreta 1');

        if ($decrypted === false) {
            print 'Error!';
        }
        else {
            print '->';
            print openssl_decrypt($data->encrypted, 'aes256', 'Chave Secreta 1');
            print '<-';
        }
         */

        /*
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /*
             * Monta o comando SQL
             */
            $sqlcmd = 'SELECT ' .
                '1 AS authenticated,' .
                'pessoa.id AS id,' .
                'chave,' .
                'nome,' .
                'genero,' .
                'nascimento,' .
                'imagem ' .
                ' FROM ' . $this->getCollection_name() .
                ' LEFT JOIN pessoa ON pessoa.id = acesso.pessoa' .
                ' WHERE chave = ? AND senha = ?';

            /*
             * Emite comando no servidor conectado em DbAdmin e trata o retorno
             */
            $conn = $this->getConnection();

            /**
             * Monta no servidor o statement com o comando preparado com "placeholders"
             */
            $stmt = $conn->prepare($sqlcmd);

            /**
             * Executa comando preparado informando os valores diretamente na chamada
             */
            $result = $stmt->execute([$data->login, $data->password]);

            /*
             * Se houver qualquer falha, gera um estado de exceção
             */
            if ($result === false) {
                throw new Exception('Error loading data.');
            }
            else if ($stmt->rowCount() === 0) {
                throw new Exception('Dados de login inválidos.');
            }
            else {
                $data = $stmt->fetchAll();

                /*
                 * Se tudo correu bem retorna as informações obtidas para o chamador
                 */
                return array_merge($data[0], [ 'token' => $this->getJWTToken($data[0]) ]);
            }
        }
        /*
         * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
         */
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }

    private function getJWTToken($data) {
        $config = parse_ini_file(Constants::INI_FILENAME, true);

        $payload = [
            'id' => $data['id'],
            'login' => $data['chave'],
            'nome' => $data['nome'],
            'timeout' => time() + ($config['JWT']['timeout'] * 60 * 60)
        ];
        $key = $config['JWT']['key'];

        return JWT::encode($payload, $key, 'HS256');
    }
}