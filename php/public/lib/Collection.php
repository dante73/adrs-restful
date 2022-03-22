<?php
require_once 'lib/DbAdmin.php';

class Collection extends DbAdmin
{
    /*
     * Collection name
     */
    private $collection_name;

    /*
     * Record Id
     */
    private $id;

    // Raw data fetched by load method
    private $rawdata;

    // Collection fields
    private $fields;

    /*
     * Construct
     *
     * @param   string  $table  Table name to be used
     */
    function __construct($collection_name) {
        try {
            parent::__construct();

            $this->setCollection_name($collection_name);
            $this->setFields($this->getAllFieldsName());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /*
     * $id getter and setter
     */
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    /*
     * Collection name getter and setter
     */
    private function getCollection_name() {
        return $this->collection_name;
    }

    private function setCollection_name($collection_name) {
        $this->collection_name = $collection_name;
    }

    /*
     * Rawdata getter and setter
     */
    protected function getRawdata() {
        return $this->rawdata;
    }

    private function setRawdata($rawdata) {
        $this->rawdata = $rawdata;
    }

    /*
     * Fields getter and setter
     */
    private function getFields() {
        return $this->fields;
    }

    private function setFields($fields) {
        $this->fields = $fields;
    }

    /*
     * Return an array with the name of all fields
     */
    private function getAllFieldsName() {
        $conn = $this->getConnection();

        return array_map(
            function($arr) { return $arr['Field']; },
            $conn->query('SHOW COLUMNS FROM ' . $this->getCollection_name())->fetchAll()
        );
    }

    /*
     * Protect some fields and check validity of keys before write commands
     */
    private function notAValidField($key) {
        return (boolean)($key === 'id' || ! in_array($key, $this->getFields()));
    }

    /*
     * Load record using setted $id
     */
    protected function load() {
        try {
            $sqlcmd = 'SELECT * FROM ' . $this->getCollection_name() . ' WHERE id = \'' . $this->getId() . '\';';

            $conn = $this->getConnection();

            $result = $conn->query($sqlcmd);

            if ($result === false) {
                throw new Exception('Error loading data.');
            }
            else if ($result->rowCount() === 0) {
                throw new Exception('Record not found.');
            }
            else {
                $this->setRawdata($result->fetchObject());
            }
        }
        catch (Exception $e) {
            throw new Exception('Error loading record : ' . $e->getMessage());
        }
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }

    /*
     * Delete already loaded record by Id
     */
    public function delete() {
        try {
            $sqlcmd = 'DELETE FROM ' . $this->getCollection_name() . ' WHERE id = \'' . $this->getId() . '\';';

            $conn = $this->getConnection();

            $result = $conn->query($sqlcmd);

            if ($result === false) {
                throw new Exception('Error deleting record.');
            }
            else if ($result->rowCount() === 0) {
                throw new Exception('Record not found.');
            }
        }
        catch (Exception $e) {
            throw new Exception('Error deleting record :' . $e->getMessage());
        }
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }

    /*
     * Load all data
     */
    function loadAll() {
        try {
            $sqlcmd = 'SELECT * FROM ' . $this->getCollection_name();

            $conn = $this->getConnection();

            $result = $conn->query($sqlcmd);

            if ($result === false) {
                throw new Exception('Error loading data.');
            }
            else if ($result->rowCount() === 0) {
                throw new Exception('Record not found.');
            }
            else {
                return $result->fetchAll();
            }
        }
        catch (Exception $e) {
            throw new Exception('Error loading records : ' . $e->getMessage());
        }
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }

    /*
     * Builds an part of a SQL command to concatenate on an insert statement
     *
     * @param   array   $data       Data to write on a new record
     * @return  string  $sqlcmd     Part of a SQL statement to be concatenate by another method
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
     * Builds an part of a SQL command to concatenate on an update statement
     *
     * @param   array   $data       Data to write on a new record
     * @return  string  $sqlcmd     Part of a SQL statement to be concatenate by another method
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

    /*
     * Insert Data
     */
    protected function insert($data) {
        try {
            $sqlcmd = "INSERT INTO " . $this->getCollection_name();
            $sqlcmd .= $this->getFieldsForInsertStatement($data);

            $conn = $this->getConnection();

            $rows = $conn->exec($sqlcmd);

            if ($rows === false) {
                throw new Exception('Error writing new record !');
            }
            elseif ($rows === 0) {
                throw new Exception('Error writing new record ! Zero row affected.');
            }
            else {
                return $rows;
            }
        }
        catch (Exception $e) {
            throw new Exception('Error writing new record ! ' . $e->getMessage());
        }
    }

    /*
     * Update Data
     */
    protected function update($data) {
        try {
            $sqlcmd = "UPDATE " . $this->getCollection_name() . " SET ";
            $sqlcmd .= $this->getFieldsForUpdateStatement($data);

            $conn = $this->getConnection();

            $rows = $conn->exec($sqlcmd);

            if ($rows === false) {
                throw new Exception('Error updating record !');
            }
            elseif ($rows === 0) {
                throw new Exception('Error updating record ! Zero row affected.');
            }
            else {
                return $rows;
            }
        }
        catch (Exception $e) {
            throw new Exception('Error updating record ! ' . $e->getMessage());
        }
        catch (PDOException $e) {
            throw new Exception('PDO Error : ' . $e->getMessage());
        }
    }
}