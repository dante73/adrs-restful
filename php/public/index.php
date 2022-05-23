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
 * POST: http://localhost:8000/cliente/9/   -> U -> Modifica dados do cliente
 * DELETE: http://localhost:8000/cliente/8/ -> D -> Deleta dados do cliente
 */
require_once 'lib/Constants.php';
require_once 'lib/Route.php';
require_once 'lib/Model.php';
require_once 'lib/HeaderFor.php';

try {
    /*
     * Pega e consiste informações necessárias de @_REQUEST para prosseguir
     */
    $route = Route::takeRoute();

    /*
     * Com as informações obtidas de @_REQUEST, monta o objeto model para a persistência dos dados
     */
    $model = Model::takeModel($route['name']);

    /*
     * Configura o ambiente de comunicação HTTP, de acordo com o método solicitado
     */
    $method = $_SERVER['REQUEST_METHOD'];

    $header = new HeaderFor($method);
    $header->putHeaders();

    /*
     * Capta todos os dados passados por JSON e verifica (aqui superficialmente) se estão válidos
     */
    $data = json_decode(file_get_contents("php://input", true));

    if ( ! $data) {
        throw new Exception('Invalid JSON.');
    }

    /*
     * Envia as informações reunidas para o Model agir de acordo com o solicitado
     */
    $result = $model->doIt($method, $route['id'], $data);

    /*
     * Finaliza processo REST com sucesso informando o resultado
     */
    exit(json_encode(array('status' => 'success', 'data' => $result)));
}
catch (Exception $e) {
    /*
     * Se houver qualquer falha em qualquer parte do processo, ela é tratada e reportada aqui
     */
    exit(json_encode(array('status' => 'error', 'data' => $e->getMessage())));
}