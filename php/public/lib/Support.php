<?php
require_once 'lib/Model.php';

class Support
{
    public static function autoloader($class_name) {
        if (preg_match(Model::getReValidModelNames(), $class_name)) {
            require_once "model/$class_name.php";
        }
        else {
            require_once "lib/$class_name.php";
        }
    }
    public static function DateToDb($date) {
        return preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', "$3-$2-$1", $date);
    }
}