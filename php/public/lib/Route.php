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
require_once 'lib/Model.php';

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
            throw new Exception('Unknown route.');
        }

        /*
         * Verifica se informou dois valores, separados por "/".
         */
        if (str_contains($data['r'], '/')) {
            /*
             * Se informou os dois valores, separa em duas variáveis : @nome e @id
             */
            $arr = explode('/', $data['r']);

            /*
             * Filtra os dois valores para checagem do conteúdo
             */
            $name = filter_var($arr[0], FILTER_SANITIZE_STRING);
            $id = filter_var($arr[1], FILTER_SANITIZE_NUMBER_INT);

            /*
             * Verifica se o @id é um número inteiro válido
             */
            if ( ! $id) {
                throw new Exception('Invalid data.');
            }
        }
        else {
            /*
             * Se não houver dois valores trata como um só, filtra a string @name e fixa o @id em zero
             */
            $name = filter_var($data['r'], FILTER_SANITIZE_STRING);
            $id = 0;
        }

        /*
         * Verifica se @name é válido (não vazio) e não contém caracteres estranhos para um(a) rota/model
         */
        if ( ! $name || preg_match('/\W/', $name)) {
            throw new Exception('Invalid data.');
        }

        /*
         * Verifica se a rota especificada em @name é valida, tem um model definido em Model
         */
        if ( ! preg_match(Model::getReValidModelNames(), $name)) {
            throw new Exception('Invalid route.');
        }

        /*
         * Se tudo estiver ok retorna array com o @name da rota e o @id
         */
        return array(
            'name' => $name,
            'id' => $id
        );
    }
}