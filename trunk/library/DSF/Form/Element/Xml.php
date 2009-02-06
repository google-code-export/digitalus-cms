<?php

require_once ('Zend/Form/Element.php');

class DSF_Form_Element_Xml extends Zend_Form_Element {

    public function getValue($toString = true)
    {
        $value = parent::getValue();
        if (is_array($value)) {
            $xml = new SimpleXMLElement('<elementData />');
            foreach ($value as $k => $v) {
                $xml->$k = $v;
            }
        } else {
            $xml = simplexml_load_string($value);
        }
        if ($toString) {
            return $xml->asXML();
        } else {
            return $xml;
        }
    }

    public function getXml()
    {
        return $this->getValue(false);
    }
}

?>