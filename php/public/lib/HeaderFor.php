<?php
/**
 *  This php script is the HTTP Header setter on local rest api.
 *
 * PHP version 7.3
 *
 * @category REST_API
 * @package  Restapi
 * @author   Daniel <daniel@antunesbr.com>
 * @license  Mine http://adrs.mine.nu/
 * @link     http://adrs.mine.nu/
 */
class HeaderFor
{
    protected $method;
    protected $header;

    function __construct($method) {
        $this->setMethod($method);
        $this->setHeaders();
    }

    protected function setMethod($method) {
        $this->method = $method;
    }

    protected function getMethod() {
        return $this->method;
    }

    protected function setHeaders() {
        $this->header = [
            "Access-Control-Allow-Origin: *",
            "Content-Type: application/json; charset=UTF-8",
        ];

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

    public function putHeaders() {
        foreach ($this->header as $h) {
            header($h);
        }
    }
}