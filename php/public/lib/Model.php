<?php
/**
 *   Esta classe vai conectar a requisição HTTP ao banco de dados, de acordo com a rota informada em @_REQUEST
 *
 * @category REST_API
 * @package  adrs-restful
 * @author   Daniel <daniel@antunesbr.com>
 */
class Model
{
    /*
     * Pasta onde ficam as classes de persistência da REST
     */
    const modelPath = './model';

    /*
     * Rotas válidas e suas respectivas classes de persistência na REST
     */
    const model = array(
        'acesso'    => 'Acesso',
        'city'      => 'City',
        'cliente'   => 'Cliente',
        'contato'   => 'Contato',
        'country'   => 'Country',
        'documento' => 'Documento',
        'endereco'  => 'Endereco',
        'pessoa'    => 'Pessoa',
        'state'     => 'State'
    );

    /**
     * De acordo com a rota informada conecta a classe e retorna um objeto instanciado para a persistência dos dados
     *
     * @param $route        Informa qual a rota a ser utilizada
     * @return mixed        Retornará um objeto instanciado caso a rota seja válida
     * @throws Exception    Gera uma exception em caso de falha
     */
    public static function takeModel($route) {
        /*
         * Verifica se a rota informada existe
         */
        if (isset(self::model[$route])) {
            /*
             * Associa a classe correspondente
             */
            $class = self::model[$route];

            /*
             * Verifica se o arquivo de classe está no lugar
             */
            $file = self::modelPath . '/' . $class . '.php';

            if ( ! file_exists($file)) {
                http_response_code(404);
                throw new Exception('Invalid model.');
            }

            /*
             * Carrega o arquivo com a classe correspondente a rota
             */
            require_once $file;

            /*
             * Retorna um objecto instanciado, pronto para ser utilizado pelo processo chamador
             */
            return new $class();
        }
        else {
            /*
             * Pára o processo e informa que não há model para a rota
             */
            http_response_code(404);
            throw new Exception('Unknown model.');
        }
    }

    /**
     * Monta uma expressão regular para a verificação de model disponíveis
     *
     * @return string   String contendo uma expressão regular
     */
    public static function getReValidModelRoutes() {
        return '/^(?:' . implode('|', array_keys(self::model)) . ')$/';
    }

    /**
     * Monta uma expressão regular para a verificação de classes disponíveis
     *
     * @return string   String contendo uma expressão regular
     */
    public static function getReValidModelNames() {
        return '/^(?:' . implode('|', array_values(self::model)) . ')$/';
    }
}