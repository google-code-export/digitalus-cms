<?php
class Model_MetaData extends Model_Xml
{
    protected $_namespace = 'meta_data';

    public function set($data, $pageId)
    {
        $xml = $this->get($pageId);
        if (!$xml) {
            $xml = new SimpleXMLElement('<meta_data />');
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $xml->$key = strip_tags($value);
            }
        }

        $this->saveXml($this->_getKey($pageId), $xml);
    }

    public function asArray($pageId)
    {
        $xml = $this->get($pageId);
        if ($xml) {
            foreach ($xml as $key => $value) {
                $data[$key] = $value;
            }
            if (is_array($data)) {
                return $data;
            }
        }
    }

    public function get($pageId)
    {
        return $this->open($this->_getKey($pageId));
    }

    public function deleteByPageId($pageId)
    {
        $where = $this->_db->quoteInto('tags = ?', $this->_getKey($pageId));
        $this->delete($where);
    }

    protected function _getKey($pageId)
    {
        return $this->_namespace . '_' . $pageId;
    }
}