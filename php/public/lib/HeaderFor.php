<?php
/**
 * Configura o ambiente de transmissão HTTP de acordo com o método utilizado na requisição em andamento
 *
 * @category REST_API
 * @package  adrs-restful
 * @author   Daniel <daniel@antunesbr.com>
 */
class HeaderFor
{
    /*
     * Propriedade @method contém o método utilizado na requisição
     */
    private $method;

    /*
     * Propriedade @headers conterá as headers necessárias para a comunicação HTTP
     */
    private $header;

    /*
     * Método de construção desta classe, método de comunicação HTTP da requisição deve ser informado
     */
    function __construct($method) {
        /*
         * Configura método da requisição na propriedade @method
         */
        $this->setMethod($method);

        /*
         * Configura headers necessárias na propriedade @headers
         */
        $this->setHeaders();
    }

    /*
     * Getter e setter da propriedade @method
     */
    private function getMethod() {
        return $this->method;
    }

    private function setMethod($method) {
        $this->method = $method;
    }

    /*
     * Carrega na propriedade @header todas as headers necessárias para a comunicação HTTP, de acordo
     * com o método utlizado na requisição e informado na propriedade @method
     */
    private function setHeaders() {
        /*
         * A propriedade @header inicializa com algumas headers padrões
         */
        $this->header = [
            "Access-Control-Allow-Origin: *",
            "Content-Type: application/json; charset=UTF-8",
        ];

        /*
         * Carrega a propriedade @headers com as headers necessárias para a comunicação HTTP em andamento,
         * de acordo com o que foi previamente configurado na propriedade @method
         */
        switch ($this->getMethod()) {
            case 'DELETE';
            case 'POST';
            case 'PUT';
                array_merge($this->header, array(
                    "Access-Control-Allow-Methods: "
                        . $this->getMethod(),
                    "Access-Control-Max-Age: 3600",
                    "Access-Control-Allow-Headers: " .
                    "Content-Type, " .
                    "Access-Control-Allow-Headers, " .
                    "Authorization, " .
                    "X-Requested-With"
                ));
                break;
            case 'GET';
            case 'LOAD':
            case 'READ':
                break;
            default:
                throw new Exception('Unknown Method.');
                break;
        }
    }

    /*
     * Configura no documento todas as headers necessárias
     */
    public function putHeaders() {
        foreach ($this->header as $h) {
            header($h);
        }
    }
}