<?php
class Model
{
    const modelPath = './model';

    const model = array(
        'pessoa'    => 'Pessoa',
        'cliente'   => 'Cliente',
        'endereco'  => 'Endereco',
        'documento' => 'Documento'
    );

    public static function takeModel($route) {
        if (isset(self::model[$route])) {
            $class = self::model[$route];
            $file = self::modelPath . '/' . $class . '.php';

            if ( ! file_exists($file)) {
                throw new Exception('Invalid model.');
            }

            require_once($file);
            return new $class();
        }
        else {
            throw new Exception('Unknown model.');
        }
    }

    public static function getReValidModelNames() {
        return '/^(?:' . implode('|', array_keys(self::model)) . ')$/';
    }
}