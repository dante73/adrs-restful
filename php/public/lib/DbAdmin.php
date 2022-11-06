<?php
/**
 * Gerencia a comunicação com o servidor de dados do sistema
 *
 * @category REST_API
 * @package  adrs-restful
 * @author   Daniel <daniel@antunesbr.com>
 */
require_once 'lib/Constants.php';

class DbAdmin
{
    /**
     * Mantém a instância do objeto
     */
    private static $instance = null;

    /**
     * Dados para se conectar ao servidor de dados, estas informações estão configuradas no arquivo .ini
     */
    private $driver;
    private $charset;
    private $dbname;
    private $dbhost;
    private $dbport;
    private $dbuser;
    private $dbpass;

    /**
     * Tudo junto forma o nome da fonte de dados, ou DSN
     */
    private $dsn;

    /**
     * Opções da biblioteca PDO de PHP
     */
    private $options;

    /**
     * Conexão com o Banco de Dados
     */
    private $connection;

    /**
     * Construtor do objeto de comunicação
     *
     * @throws Exception
     */
    private function __construct() {
        /**
         * Efetua a operação com o banco de dados esperando por exceções
         */
        try {
            /**
             * Se conecta ao servidor de dados
             */
            $this->Connect();
        }
        /**
         * Se houver qualquer falha com o banco de dados, gera estado de exceção geral
         */
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage() . '.');
        }
    }

    /**
     * Força a classe a ser singleton
     *
     * @return DbAdmin|null
     */
    public static function getInstance() {
        if ( ! self::$instance) {
            self::$instance = new DbAdmin();
        }
        return self::$instance;
    }

    /**
     * Conecta ao banco de dados
     *
     * @throws Exception
     */
    private function Connect() {
        /**
         * Lê as configurações de conexão ao banco de dados no arquivo .ini
         */
        $this->takeSettings();

        /**
         * Informa/configura a fonte de dados utilizando as informações lidas no arquivo .ini
         */
        $this->setDsn(
            $this->getDriver()  . ':host=' .
            $this->getDbhost()  . ';dbname=' .
            $this->getDbname()  . ';charset=' .
            $this->getCharset() . ';port=' .
            $this->getDbport()
        );

        /**
         * Informa/configura as opções de acesso a biblioteca de conexão ao Banco de Dados
         */
        $this->setOptions(array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ));

        /**
         * Se conecta ao servidor de dados utilizando as informações configuradas
         */
        $this->setConnection(
            new PDO(
                $this->getDsn(),
                $this->getDbuser(),
                $this->getDbpass(),
                $this->getOptions()
            )
        );
    }

    /**
     * Lê as informações de acesso ao servidor de dados no arquivo .ini
     *
     * @throws Exception
     */
    private function takeSettings() {
        /**
         * Verifica se o arquivo .ini informado em Constants.php existe e é legível
         */
        if ( ! is_readable(Constants::INI_FILENAME)) {
            throw new Exception('System are not setted.');
        }

        /**
         * Lê as informações ali contidas e configura/informa estes dados localmente para a conexão
         */
        $this->setDbASettings(
            parse_ini_file(Constants::INI_FILENAME, true)
        );
    }

    /*
     * Getter e setter da fonte de dados
     */
    private function getDsn() {
        return $this->dsn;
    }

    private function setDsn($dsn) {
        $this->dsn = $dsn;
    }

    /**
     * Getter e setter do driver de acesso a dados
     */
    public function getDriver() {
        return $this->driver;
    }

    public function setDriver($driver) {
        $this->driver = $driver;
    }

    /**
     * Getter e setter da página de caracteres que é utilizado na comunicação com o servidor de dados
     */
    public function getCharset() {
        return $this->charset;
    }

    public function setCharset($charset) {
        $this->charset = $charset;
    }

    /**
     * Getter e setter do nome do banco de dados
     */
    public function getDbname() {
        return $this->dbname;
    }

    public function setDbname($dbname) {
        $this->dbname = $dbname;
    }

    /**
     * Getter e setter do @host (ou endereço) do servidor de dados
     */
    public function getDbhost() {
        return $this->dbhost;
    }

    public function setDbhost($dbhost) {
        $this->dbhost = $dbhost;
    }

    /**
     * Getter e setter da porta de comunicação com o @host do servidor de dados
     */
    public function getDbport() {
        return $this->dbport;
    }

    public function setDbport($dbport) {
        $this->dbport = $dbport;
    }

    /**
     * Getter e setter do usuário que será utilizado para autenticação no servidor de dados
     */
    public function getDbuser() {
        return $this->dbuser;
    }

    public function setDbuser($dbuser) {
        $this->dbuser = $dbuser;
    }

    /**
     * Getter e setter da senha que será utilizada para autenticação no servidor de dados
     */
    public function getDbpass() {
        return $this->dbpass;
    }

    public function setDbpass($dbpass) {
        $this->dbpass = $dbpass;
    }

    /**
     * Getter e setter das opções de comunicação com a interface de comunicação com o servidor de dados
     */
    private function getOptions() {
        return $this->options;
    }

    private function setOptions($options) {
        $this->options = $options;
    }

    /**
     * Getter e setter da conexão com o banco de dados
     */
    public function getConnection() {
        return $this->connection;
    }

    private function setConnection($connection) {
        $this->connection = $connection;
    }

    /**
     * Com as informações lidas do arquivo .ini configura o objeto de conexão ao banco de dados
     *
     * @param $config
     * @throws Exception
     */
    private function setDbASettings($config) {
        /**
         * Verifica se está tudo certo nos dados de configuração lidos no arquivo .ini
         */
        if ( ! $config || ! (
                isset($config['PDO']['driver']) &&
                isset($config['PDO']['charset']) &&
                isset($config['PDO']['dbname']) &&
                isset($config['PDO']['dbhost']) &&
                isset($config['PDO']['dbport']) &&
                isset($config['PDO']['dbuser']) &&
                isset($config['PDO']['dbpass'])
            ))
        {
            throw new Exception('Insufficient settings to connect.');
        }

        /**
         * Configura o driver (modelo) de banco de dados que será utilizado
         */
        $this->setDriver($config['PDO']['driver']);

        /**
         * Configura o mapa de caracteres que será utilizado na conexão
         */
        $this->setCharset($config['PDO']['charset']);

        /**
         * Configura o nome do banco de dados
         */
        $this->setDbname($config['PDO']['dbname']);

        /**
         * Configura o @host (endereço) de comunicação
         */
        $this->setDbhost($config['PDO']['dbhost']);

        /**
         * Configura a porta de comunicação
         */
        $this->setDbport($config['PDO']['dbport']);

        /**
         * Configura o usuário de autenticação
         */
        $this->setDbuser($config['PDO']['dbuser']);

        /**
         * Configura a senha de autenticação
         */
        $this->setDbpass($config['PDO']['dbpass']);
    }
}