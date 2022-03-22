<?php
class DbAdmin
{
    /*
     * Database access data
     */
    private $driver;
    private $charset;
    private $dbname;
    private $dbhost;
    private $dbport;
    private $dbuser;
    private $dbpass;

    /*
     * All together
     */
    private $dsn;

    /*
     * PDO Options
     */
    private $options;

    /*
     * Database connection handler
     */
    private $connection;

    /*
     * Database access construct method
     */
    function __construct() {
        try {
            $this->Connect();
        }
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }

    /*
     * Set DSN and connect to database
     */
    private function Connect() {
        $this->takeSettings();
        $this->setDsn(
            $this->getDriver()  . ':host=' .
            $this->getDbhost()  . ';dbname=' .
            $this->getDbname()  . ';charset=' .
            $this->getCharset() . ';port=' .
            $this->getDbport());

        $this->setOptions([
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ]);

        // Establish database connection
        $this->setConnection(new PDO($this->getDsn(), $this->getDbuser(), $this->getDbpass(), $this->getOptions()));
    }

    /*
     * Take and set database connect settings
     */
    private function takeSettings() {
        if ( ! is_readable(\Constants::INI_FILENAME)) {
            throw new Exception('System are not setted.');
        }

        $this->setDbASettings(parse_ini_file(\Constants::INI_FILENAME, true));
    }

    /*
     * DSN getter and setter
     */
    private function setDsn($dsn) {
        $this->dsn = $dsn;
    }

    private function getDsn() {
        return $this->dsn;
    }

    /**
     * @return mixed
     */
    public function getDriver() {
        return $this->driver;
    }

    /**
     * @param mixed $driver
     */
    public function setDriver($driver) {
        $this->driver = $driver;
    }

    /**
     * @return mixed
     */
    public function getCharset() {
        return $this->charset;
    }

    /**
     * @param mixed $charset
     */
    public function setCharset($charset) {
        $this->charset = $charset;
    }

    /**
     * @return mixed
     */
    public function getDbname() {
        return $this->dbname;
    }

    /**
     * @param mixed $dbname
     */
    public function setDbname($dbname) {
        $this->dbname = $dbname;
    }

    /**
     * @return mixed
     */
    public function getDbhost() {
        return $this->dbhost;
    }

    /**
     * @param mixed $dbhost
     */
    public function setDbhost($dbhost) {
        $this->dbhost = $dbhost;
    }

    /**
     * @return mixed
     */
    public function getDbport() {
        return $this->dbport;
    }

    /**
     * @param mixed $dbport
     */
    public function setDbport($dbport) {
        $this->dbport = $dbport;
    }

    /**
     * @return mixed
     */
    public function getDbuser() {
        return $this->dbuser;
    }

    /**
     * @param mixed $dbuser
     */
    public function setDbuser($dbuser) {
        $this->dbuser = $dbuser;
    }

    /**
     * @return mixed
     */
    public function getDbpass() {
        return $this->dbpass;
    }

    /**
     * @param mixed $dbpass
     */
    public function setDbpass($dbpass) {
        $this->dbpass = $dbpass;
    }

    /*
     * Options getter and setter
     */
    private function setOptions($options) {
        $this->options = $options;
    }

    private function getOptions() {
        return $this->options;
    }

    /*
     * Setup database access data using ini file
     */
    private function setDbASettings($config) {
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
            throw new Exception('Cannot connect PDO');
        }

        $this->setDriver($config['PDO']['driver']);
        $this->setCharset($config['PDO']['charset']);
        $this->setDbname($config['PDO']['dbname']);
        $this->setDbhost($config['PDO']['dbhost']);
        $this->setDbport($config['PDO']['dbport']);
        $this->setDbuser($config['PDO']['dbuser']);
        $this->setDbpass($config['PDO']['dbpass']);
    }

    /*
     * Connect database and setup connection handler
     */
    private function setConnection($connection) {
        $this->connection = $connection;
    }

    protected function getConnection() {
        return $this->connection;
    }
}