<?php
class Support
{
    public static function DateToDb($date) {
        return preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', "$3-$2-$1", $date);
    }
}