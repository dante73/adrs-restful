<?php
require_once 'lib/core/Rest.php';

try {
    $api = new Rest();
    $api->serve();
}
catch (Exception $e) {
    /*
     * Se houver qualquer falha em qualquer parte do processo, ela é tratada e reportada aqui
     */
    exit(json_encode(array('status' => 'error', 'data' => $e->getMessage())));
}