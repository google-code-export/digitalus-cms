<?php

class Digitalus_Toolbox_Data {
    public static function getValueOrNull($value)
    {
        if (empty($value)) {
            return null;
        } else {
            return $value;
        }
    }
}

?>