<?php
/**
 * Índice de adrs-restful, todas as requisições na url passam por aqui (veja .htaccess)
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
require_once 'lib/core/Autoloader.php';

try {
    /**
     * Consiste informações necessárias do @_REQUEST utilizando a classe Route, que retornará um array associativo
     * com informações da requisição a ser feita pelo processo.
     */
    $route = Route::takeRoute();

    /**
     * Com as informações obtidas do @_REQUEST e classe Route, monta o objeto model necessário para a operação.
     */
    $model = Model::takeModel($route['name']);

    /**
     * Seta "flags" da operação informada.
     */
    $action = $route['op'];
    $hasAnAction = (boolean)($action !== '');

    /**
     * Verifica se foi especificada uma operação para ser executada e se esta operação existe no "model".
     */
    if ($hasAnAction && ! method_exists($model, $action)) {
        http_response_code(404);
        throw new Exception('Invalid operation.');
    }

    /**
     * Seta localmente todas os cabeçalhos enviados pelo "request" http.
     */
    $request_header = apache_request_headers();

    if ($request_header === false) {
        http_response_code(401);
        throw new Exception('No headers.');
    }

    /**
     * Verifica se a operação exige autenticação, se o usuário está autenticado com um "token" válido.
     */
    if ($model->authNeeded($action) && ! $model->verifyServiceAuthorization($request_header)) {
        http_response_code(403);
        throw new Exception('No permission on index.');
    }

    /**
     * Configura o ambiente de comunicação (cabeçalhos) HTTP de acordo com o método solicitado.
     */
    $method = $_SERVER['REQUEST_METHOD'];

    $header = new HeaderFor($method);
    $header->putHeaders();

    /**
     * Seta "flag" @dataSent para métodos que fazem envio de dados.
     */
    $dataSent = (boolean)($method === 'POST' || $method === 'PUT');

    /**
     * Trata arquivos enviados pelo "request" como imagens de avatar.
     */
    if ($dataSent && isset($request_header['Content-Type']) &&
        strpos($request_header['Content-Type'], 'multipart/form-data') !== false) {

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
        $model->saveFile();
    }

    /*
     * Capta todos os dados passados por JSON.
     */
    $data = json_decode(file_get_contents("php://input", true));

    /**
     * Tanto no PUT quanto no POST é obrigatório o envio de dados para serem processados.
     */
    if ($dataSent && ! $data) {
        http_response_code(400);
        throw new Exception('Invalid JSON.');
    }

    /**
     * Verifica se foi especificado uma operação a ser executada.
     */
    if ($hasAnAction) {
        /**
         * Executa a operação no model enviando os parâmetros e os dados passados.
         */
        $result = $model->$action($route['params'], $data);
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
        $result = $model->doIt($method, $route['id'], $data);
    }

    /*
     * Se tudo deu certo, finaliza o processo REST com sucesso informando o resultado.
     */
    http_response_code(200);
    exit(json_encode(array('status' => 'success', 'data' => $result)));
}
catch (Exception $e) {
    /*
     * Se houver qualquer falha em qualquer parte do processo, ela é tratada e reportada aqui
     */
    exit(json_encode(array('status' => 'error', 'data' => $e->getMessage())));
}