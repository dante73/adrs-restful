<?php
/**
 *   Esta classe php verifica na URL informada se há informações suficientes para o REST prosseguir.
 *   Se houver verifica estas informações e consiste para serem utilizadas pelos processos que virão
 * na sequência.
 *
 * @category REST_API
 * @package  adrs-restful
 * @author   Daniel <daniel@antunesbr.com>
 */
require_once 'lib/core/Autoloader.php';

use Model;

/**
 * Class Route
 */
class Route
{
    /**
     * Com base nas informações contidas na request retorna um @array com direções a serem seguidas em sequência
     *
     * @return array
     * @throws Exception
     */
    public static function takeRoute() {
        /*
         * Coloca as informações de request na variável @data
         */
        $data = $_REQUEST;

        /*
         * Se não encontrar os parâmetros (na variável @r) não prossegue
         */
        if ( ! isset($data['r'])) {
            http_response_code(404);
            throw new Exception('Unknown route.');
        }

        /**
         * Array de parâmetros
         */
        $arr = [];

        /*
         * Verifica se informou mais que um parâmetro, separados por "/".
         */
        if (strpos($data['r'], '/') !== false) {
            /*
             * Se informou mais valores, separa para verificação no array @arr (decladado fora do if)
             */
            $arr = explode('/', $data['r']);

            /*
             * Filtra o primeiro valor que deve ser o nome da rota
             */
            $name = filter_var(array_shift($arr), FILTER_SANITIZE_STRING);

            /**
             * Próximo parâmetro
             */
            $next = array_shift($arr);

            /**
             * Verifica se o segundo parâmetro tem somente dígitos com o ID
             */
            if (preg_match('/^\d+$/', $next)) {
                $id = filter_var($next, FILTER_SANITIZE_NUMBER_INT);
                $op = '';

                /*
                 * Verifica se o @id é um número inteiro válido
                 */
                if ( ! $id) {
                    http_response_code(400);
                    throw new Exception('Invalid data.');
                }
            }
            else {
                /**
                 * Se não é um ID, uma operação foi solicitada
                 */
                $id = 0;
                $op = $next;
            }
        }
        else {
            /*
             * Se não houver dois valores trata como um só, filtra a string @name, fixa o @id em zero e a @op em vazio
             */
            $name = filter_var($data['r'], FILTER_SANITIZE_STRING);
            $id = 0;
            $op = '';
        }

        /*
         * Verifica se @name é válido (não vazio) e não contém caracteres estranhos para um(a) rota/model
         */
        if ( ! $name || preg_match('/\W/', $name)) {
            http_response_code(400);
            throw new Exception('Invalid data.');
        }

        /*
         * Verifica se a rota especificada em @name é valida, tem um model definido em Model
         */
        if ( ! preg_match(Model::getReValidModelNames(), $name)) {
            http_response_code(404);
            throw new Exception('Invalid route.');
        }

        /*
         * Se tudo estiver ok retorna array com o @name da rota, o @id ou a @op (operação) a ser executada
         */
        return array(
            'name' => $name,
            'id' => $id,
            'op' => $op,
            /**
             * O restante dos parâmetros é atribuído a @params e será passado ao método chamado
             */
            'params' => $arr
        );
    }
}