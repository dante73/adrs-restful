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

use Route;
use Model;
use HeaderFor;

try {
    /**
     * Consiste informações necessárias do @_REQUEST para prosseguir.
     */
    $route = Route::takeRoute();

    /**
     * Com as informações obtidas do @_REQUEST, monta um objeto model para a operação.
     */
    $model = Model::takeModel($route['name']);

    /**
     * Flag de operação informada
     */
    $action = $route['op'];
    $hasAnAction = (boolean)($action !== '');

    /**
     * Verifica se foi especificada uma operação para ser executada e se esta operação existe.
     */
    if ($hasAnAction && ! method_exists($model, $action)) {
        http_response_code(404);
        throw new Exception('Invalid operation.');
    }

    /**
     * Verifica se a operação exige autenticação e se o usuário está autenticado.
     */
    if ($model->authNeeded($action) && ! $model->verifyServiceAuthorization()) {
        http_response_code(403);
        throw new Exception('No permission on index.');
    }

    /**
     * Configura o ambiente de comunicação HTTP de acordo com o método solicitado.
     */
    $method = $_SERVER['REQUEST_METHOD'];

    $header = new HeaderFor($method);
    $header->putHeaders();

    /**
     * Seta localmente todas os cabeçalhos enviados no request http.
     */
    $request_header = getallheaders();

    /**
     * Flag para métodos com envio de dados
     */
    $dataSent = (boolean)($method === 'POST' || $method === 'PUT');

    /**
     * Trata arquivos enviados pelo request.
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
     * Capta todos os dados passados por JSON e verifica (aqui superficialmente) se estão válidos.
     */
    $data = json_decode(file_get_contents("php://input", true));

    /**
     * Tanto no PUT quanto no POST é obrigatório o envio dos dados a serem processados.
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
         * Envia as informações reunidas para o model agir de acordo com o solicitado.
         */
        $result = $model->doIt($method, $route['id'], $data);
    }

    /*
     * Finaliza o processo REST com sucesso informando o resultado.
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