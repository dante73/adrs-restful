<?php
/**
 * Classe REST
 *
 * @category REST_API
 * @package  adrs-restful
 * @author   Daniel <daniel@antunesbr.com>
 *
 * POST: http://localhost:8000/cliente/     -> C -> Novo cliente
 * GET: http://localhost:8000/cliente/4/    -> R -> Consulta dados do cliente
 * PUT: http://localhost:8000/cliente/9/    -> U -> Modifica dados do cliente
 * DELETE: http://localhost:8000/cliente/8/ -> D -> Deleta dados do cliente
 */
require_once 'Autoloader.php';

class Rest
{
    private $route;
    private $model;
    private $header;
    private $action;
    private $method;
    private $dataNeeded;
    private $data;
    private $hasAnAction;

    public function __construct() {
        $this->route();
        $this->model();
        $this->header();
        $this->action();
        $this->auth();
        $this->environment();
        $this->dataSent();
        $this->file();
        $this->dataCheck();
    }

    public function serve() {
        try {
            $this->execute();
        }
        catch (Exception $e) {
            throw new Exception('REST Error : ' . $e->getMessage());
        }
    }

    /**
     * Consiste informações necessárias do @_REQUEST utilizando a classe Route, que retornará um array associativo
     * com informações da requisição a ser feita pelo objeto.
     */
    private function route() {
        $this->route = Route::takeRoute();
    }

    /**
     * Com as informações obtidas do @_REQUEST e classe Route, monta o objeto model necessário para a operação.
     */
    private function model() {
        $this->model = Model::takeModel($this->route['name']);
    }

    /**
     * Seta localmente todas os cabeçalhos enviados pelo "request" http.
     */
    private function header() {
        $this->header = apache_request_headers();

        if ($this->header === false) {
            http_response_code(401);
            throw new Exception('No headers.');
        }
    }

    /**
     * Seta propriedades da operação informada.
     */
    private function action() {
        $this->action = $this->route['op'];
        $this->hasAnAction = (boolean)($this->action !== '');

        /**
         * Verifica se foi especificada uma operação para ser executada e se esta operação existe no "model".
         */
        if ($this->hasAnAction && ! method_exists($this->model, $this->action)) {
            http_response_code(404);
            throw new Exception('Invalid operation.');
        }
    }

    /**
     * Verifica se a operação exige autenticação, se o usuário está autenticado com um "token" válido.
     */
    private function auth() {
        if ($this->model->authNeeded($this->action) && ! $this->model->verifyServiceAuthorization($this->header)) {
            http_response_code(403);
            throw new Exception('No permission on index.');
        }
    }

    /**
     * Configura o ambiente de comunicação (cabeçalhos) HTTP de acordo com o método solicitado.
     */
    private function environment() {
        $this->method = $_SERVER['REQUEST_METHOD'];

        $header = new HeaderFor($this->method);
        $header->putHeaders();
    }

    /**
     * Seta "flag" @dataNeeded para métodos que fazem envio de dados e os coloca na propriedade data.
     */
    private function dataSent() {
        $this->dataNeeded = (boolean)($this->method === 'POST' || $this->method === 'PUT');

        /*
         * Capta todos os dados passados por JSON.
         */
        $this->data = json_decode(file_get_contents("php://input", true));
    }

    /**
     * Trata arquivos enviados pelo "request" como imagens de avatar, por exemplo.
     */
    private function file() {
        if ($this->dataNeeded && isset($this->header['Content-Type']) &&
            strpos($this->header['Content-Type'], 'multipart/form-data') !== false) {

            /**
             * Verifica se o POST do arquivo está completo.
             */
            if ( ! isset($_FILES) || ! sizeof($_FILES) > 0) {
                http_response_code(400);
                throw new Exception('Invalid POST.');
            }

            /**
             * O model movimenta o arquivo enviado e finaliza o processo retornando o resultado por JSON.
             */
            $this->model->saveFile();
        }
    }

    /**
     * Última checagem de dados antes da gravação.
     * @throws Exception Caso haja a necessidade de dados e estes não foram postados.
     */
    private function dataCheck() {
        /**
         * Tanto no PUT quanto no POST é obrigatório o envio de dados para serem processados.
         */
        if ($this->dataNeeded && ! $this->data) {
            http_response_code(400);
            throw new Exception('Invalid JSON.');
        }
    }

    /**
     * Verifica se foi especificado uma operação a ser executada e a executa.
     */
    private function execute() {
        if ($this->hasAnAction) {
            /**
             * Se tiver informado um ID, valida a chave estrangeira no model.
             */
            if ($this->route['id'] !== 0) {
                $this->model->loadParentById($this->route['id']);
            }

            /**
             * Executa a operação no model enviando os parâmetros e os dados passados.
             */
            $action = $this->action;
            $result = $this->model->$action($this->route['params'], $this->data);
        }
        else {
            /**
             * Aqui é atendido o C R U D .
             *
             * Quando não há uma operação especificada, o sistema irá agir de acordo com o método "HTTP" :
             *
             * POST: http://localhost:8000/cliente/     -> C -> Novo cliente
             * GET: http://localhost:8000/cliente/4/    -> R -> Consulta dados do cliente
             * PUT: http://localhost:8000/cliente/9/    -> U -> Modifica dados do cliente
             * DELETE: http://localhost:8000/cliente/8/ -> D -> Deleta dados do cliente
             */
            $result = $this->model->doIt($this->method, $this->route['id'], $this->data);
        }

        /*
         * Se tudo deu certo, finaliza o processo REST com sucesso informando o resultado.
         */
        http_response_code(200);
        exit(json_encode(array('status' => 'success', 'data' => $result)));
    }
}