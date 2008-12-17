<?php

class DSF_Toolbox_Data {
    static function getValueOrNull($value)
    {
        if(empty($value)) {
            return null;
        }else{
            return $value;
        }
    }
}

?>