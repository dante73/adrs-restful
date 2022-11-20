<?php
/**
 * Abre automaticamente classes necessárias para o funcionamento da API REST.
 *
 * @category REST_API
 * @package  adrs-restful
 * @author   Daniel <daniel@antunesbr.com>
 *
 * @param $class_name
 */
function autoloader($class_name) {
    require_once "lib/$class_name.php";
}

/**
 * Registra no php a função autoloader.
 */
spl_autoload_register("autoloader");