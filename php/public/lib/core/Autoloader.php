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
require_once 'lib/Support.php';

/**
 * Registra no php a função autoloader.
 */
spl_autoload_register("Support::autoloader");