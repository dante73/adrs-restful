<?php
require_once 'lib/Support.php';
require_once 'lib/Collection.php';

class Pessoa extends Collection
{
    private $collection = 'pessoa';

    /*
     * Table Fields
     */
    private $nome;
    private $genero;
    private $nascimento;

    public function __construct()
    {
        parent::__construct($this->collection);
    }

    /*
     * Getters and Setters
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setGenero($genero) {
        $this->genero = $genero;
    }

    public function getGenero() {
        return $this->genero;
    }

    public function setNascimento($nascimento) {
        $this->nascimento = $nascimento;
    }

    public function getNascimento() {
        return $this->nascimento;
    }

    /*
     * Sanitize and cleans data before any SQL command
     *
     * @param   array   data to be sanitized
     */
    protected function sanitize($data) {
        if (isset($data->nome)) {
            $this->setNome($data->nome);
        }
        if (isset($data->genero)) {
            $this->setGenero($data->genero);
        }
        if (isset($data->nascimento)) {
            $this->setNascimento($data->nascimento);
        }
    }

    /*
     * Build an array with valued object to be used after by insert or update SQL method
     */
    protected function dataVO() {
        return array(
            'id'            => $this->getId(),
            'nome'          => $this->getNome(),
            'genero'        => $this->getGenero(),
            'nascimento'    => Support::DateToDb($this->getNascimento())
        );
    }

    /*
     * Set local valued object data using fetched from database
     */
    protected function setDataVO() {
        $data = $this->getRawData();

        $this->setNome($data->nome);
        $this->setGenero($data->genero);
        $this->setNascimento($data->nascimento);
    }

    /*
     * Main method to write current statement on database
     */
    public function do($method = 'POST', $id = 0, $data = array()) {
        /*
         * If is ID set, load him and set local value object
         */
        if ($id !== 0) {
            $this->setId($id);
            $this->load();
            $this->setDataVO();

            /*
             * Return single record data
             */
            if ($method === 'GET') {
                return $this->dataVO();
            }
        }
        else {
            /*
             * Return all records
             */
            if ($method === 'GET') {
                return $this->loadAll();
            }
        }

        /*
         * Do requested Rest operation
         */
        if ($method === 'POST') {
            $this->sanitize($data);

            if ($id === 0) {
                // Create
                $this->insert($this->dataVO());

                return array('code' => 201, 'text' => 'Record created successfully.');
            }
            else {
                // Update
                $this->update($this->dataVO());

                return array('code' => 200, 'text' => 'Record updated successfully.');
            }
        }
        else if ($method === 'DELETE') {
            // Delete
            $this->delete();

            return array('code' => 200, 'text' => 'Record deleted successfully.');
        }
    }

    protected function create_table() {
        $sqlcmd = [
            "DROP TABLE pessoa IF EXISTS;",
            "CREATE TABLE IF NOT EXISTS pessoa ("
            . "id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,"
            . "nome VARCHAR(255) NULL,"
            . "genero CHARACTER(1) NULL,"
            . "nascimento DATE NULL"
            . ") COMMENT 'Cadastro de Pessoas';",
            "CREATE UNIQUE INDEX pessoa_id_uindex ON pessoa (id);"
        ];
    }
}