<?php
require_once 'lib/Model.php';

class Route
{
    public static function takeRoute() {
        $data = $_REQUEST;

        if ( ! isset($data['r'])) {
            throw new Exception('Unknown route.');
        }

        if (str_contains($data['r'], '/')) {
            $arr = explode('/', $data['r']);

            $name = filter_var($arr[0], FILTER_SANITIZE_STRING);
            $id = filter_var($arr[1], FILTER_SANITIZE_NUMBER_INT);

            if ( ! $id) {
                throw new Exception('Invalid data.');
            }
        }
        else {
            $name = filter_var($data['r'], FILTER_SANITIZE_STRING);
            $id = 0;
        }

        if ( ! $name) {
            throw new Exception('Invalid data.');
        }

        if ( ! preg_match(Model::getReValidModelNames(), $name)) {
            throw new Exception('Invalid route.');
        }

        return array(
            'name' => $name,
            'id' => $id
        );
    }
}