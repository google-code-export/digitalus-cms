<?php
class Digitalus_View_Helper_DigitalusTag extends Zend_View_Helper_Abstract
{
    public function digitalusTag($tag, $id, $attr = array())
    {
        $xhtml = '<digitalus' . ucfirst(strip_tags($tag));
        $attr['id'] = $id;
        foreach ($attr as $key => $value) {
            $xhtml .= ' ' . strip_tags($key) . "='" . strip_tags($value) . "'";
        }
        $xhtml .= ' />';
        return  $xhtml;
    }
}