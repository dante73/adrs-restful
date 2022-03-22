<?php
/**
 *  This php script is the master index on local rest api.
 *
 * PHP version 8.0
 *
 * @category REST_API
 * @package  Restapi
 * @author   Daniel <daniel@antunesbr.com>
 * @license  Mine http://adrs.mine.nu/
 * @link     http://adrs.mine.nu/
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
    $route = Route::takeRoute();
    $model = Model::takeModel($route['name']);

    $method = $_SERVER['REQUEST_METHOD'];

    $header = new HeaderFor($method);
    $header->putHeaders();

    $data = json_decode(file_get_contents("php://input", true));

    $result = $model->do($method, $route['id'], $data);

    exit(json_encode(array('status' => 'success', 'data' => $result)));
}
catch (Exception $e) {
    exit(json_encode(array('status' => 'error', 'data' => $e->getMessage())));
}