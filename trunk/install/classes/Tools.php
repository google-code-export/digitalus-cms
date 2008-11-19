<?php 
class Tools
{
    static function dump($value){
        echo "<pre>";
        print_r($value);
        echo "</pre>";
    }
    
    static function listItems($array) {
        if(is_array($array)){
            $xhtml = "<ul>";
            foreach ($array as $item){
                $xhtml .= '<li>' . $item . '</li>';
            }
            $xhtml .= "</ul>";
        }
        return $xhtml;
    }
    
    static function message($message){
        echo $message . '<br />';
    }
    
    /**
     * extracts a zipfile, saving the structure
     *
     * @param unknown_type $zipfile
     * @return unknown
     */
    function unzip($zipfile)
    {
        $zip = zip_open($zipfile);
        while ($zip_entry = zip_read($zip))    {
            zip_entry_open($zip, $zip_entry);
            if (substr(zip_entry_name($zip_entry), -1) == '/') {
                $zdir = substr(zip_entry_name($zip_entry), 0, -1);
                if (file_exists($zdir)) {
                    trigger_error('Directory "<b>' . $zdir . '</b>" exists', E_USER_ERROR);
                    return false;
                }
                mkdir($zdir);
            }
            else {
                $name = zip_entry_name($zip_entry);
                if (file_exists($name)) {
                    trigger_error('File "<b>' . $name . '</b>" exists', E_USER_ERROR);
                    return false;
                }
                $fopen = fopen($name, "w");
                fwrite($fopen, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)), zip_entry_filesize($zip_entry));
            }
            zip_entry_close($zip_entry);
        }
        zip_close($zip);
        return true;
    }
}