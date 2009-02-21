<?php
class Xml extends Data
{

    public function fileExists($tags)
    {
        $where[] = $this->_db->quoteInto('tags = ?', $tags);
        $row = $this->fetchRow($where);
        if ($row) {
            return true;
        }
    }

    /**
     * @todo cache is throwing a notice about a node not existing at E_STRICT
     *
     *
     * @param unknown_type $filename
     * @param unknown_type $useCache
     * @return unknown
     */
    public function open($filename, $useCache = false)
    {
        $cache = $this->_getCache();
        $cacheKey = $this->_getcacheKey($filename);
        if ($useCache && $xml = $cache->load($cacheKey)) {
            return $xml;
        } else {
            $where[] = $this->_db->quoteInto('tags = ?', $filename);
            $row = $this->fetchRow($where);
            if (!empty($row->data)) {
                $xml = simplexml_load_string($row->data);
                if ($useCache) {
                    $cache->save($xml, $cacheKey);
                }
                return $xml;
            }
        }
    }

    public function saveXml($filename, $xml)
    {
        //clear cache
        $cache = $this->_getCache();
        $tags = $this->_getcacheKey($filename);
        $cache->remove($tags);

        if (is_object($xml)) {
            $xml = $xml->asXml();
        }

        $where[] = $this->_db->quoteInto('tags = ?', $filename);
        $row = $this->fetchRow($where);
        if ($row) {
            $row->data = $xml;
            $row->save();
        } else {
            $data = array(
                'tags' => $filename,
                'data' => $xml
            );
            $this->insert($data);
        }

    }

    private function _getCache()
    {
        return Zend_Registry::get('cache');
    }

    private function _getcacheKey($filename)
    {
        return 'xml_file_' . $filename;
    }
}