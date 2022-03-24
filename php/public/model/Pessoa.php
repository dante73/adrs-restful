<?php
require_once 'lib/Support.php';
require_once 'lib/Collection.php';

class Pessoa extends Collection
{
    /*
     * Local and database collection name
     */
    private $collection_name = 'pessoa';

    /*
     * Collection Fields
     */
    private $nome;
    private $genero;
    private $nascimento;

    /*
     * Construct
     */
    public function __construct() {
        parent::__construct($this->collection_name);
    }

    /*
     * Fields getters and setters
     */
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        if ($this->nome !== $nome) {
            $this->nome = $nome;
            $this->flag |= DATA_MODIFIED;
        }
    }

    public function getGenero() {
        return $this->genero;
    }

    public function setGenero($genero) {
        if ($this->genero !== $genero) {
            $this->genero = $genero;
            $this->flag |= DATA_MODIFIED;
        }
    }

    public function getNascimento() {
        return $this->nascimento;
    }

    public function setNascimento($nascimento) {
        if ($this->nascimento !== $nascimento) {
            $this->nascimento = $nascimento;
            $this->flag |= DATA_MODIFIED;
        }
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

        $this->flag |= DATA_LOADED;
    }

    /*
     * Main method to write current statement on database and answear the rest request
     */
    public function do($method = 'POST', $id = 0, $data = array()) {
        /*
         * If is ID set, load it and setup value object
         */
        if ($id !== 0) {
            $this->setId($id);
            $this->load();
            $this->setDataVO();
        }

        /*
         * Process sent data
         */
        switch ($method) {
            case 'DELETE':
                $this->delete();

                return array('code' => 200, 'text' => 'Record deleted successfully.');
                break;
            case 'GET':
                return ( $id !== 0 ? $this->dataVO() : $this->loadAll() );
                break;
            case 'POST':
                $this->sanitize($data);

                if ($this->flag & DATA_MODIFIED) {
                    if ($id !== 0) {
                        // Update
                        $this->update($this->dataVO());

                        return array('code' => 200, 'text' => 'Record updated successfully.');
                    }
                    else {
                        // Create
                        $this->insert($this->dataVO());

                        return array('code' => 201, 'text' => 'Record created successfully.');
                    }
                }
                else {
                    return array('code' => 200, 'text' => 'Nothing to do.');
                }
                break;
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