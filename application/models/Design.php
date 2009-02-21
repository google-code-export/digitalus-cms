<?php
class Design extends Zend_Db_Table
{
    protected $_name = 'designs';
    protected $_design = null;

    public function createDesign($name, $notes)
    {
        $row = $this->createRow();
        $row->name = $name;
        $row->notes = $notes;
        $row->save();

        return $this->_db->lastInsertId();
    }

    public function updateDesign($id, $name, $notes, $layout, $stylesheets, $inlineStyles, $isDefault)
    {
        $row = $this->find($id)->current();
        $row->name = $name;
        $row->notes = $notes;
        $row->layout = $layout;


        $xml = new SimpleXMLElement('<styles />');
        if (is_array($stylesheets)) {
            foreach ($stylesheets as $skin => $styles) {
                if (is_array($styles)) {
                    $currentSkin = $xml->addChild($skin);
                    foreach ($styles as $stylesheet) {
                        $currentSkin->addChild('stylesheet', $stylesheet);
                    }
                }
            }
        }
        $strXml = $xml->asXML();

        $row->styles =  $strXml;
        $row->inline_styles = $inlineStyles;
        if ($isDefault == 1) {
            $this->_resetDefault();
        }
        $row->is_default = $isDefault;
        return $row->save();
    }

    public function deleteDesign($id)
    {
        $row = $this->find($id)->current();
        if ($row) {
            $row->delete();
        }
    }

    public function getDefaultDesign()
    {
        $select = $this->select();
        $select->where('is_default = 1');
        $row = $this->fetchRow($select);
        if ($row) {
            return $row;
        } else {
            $defaultSelect = $this->select();
            $defaultSelect->order('id');
            return $this->fetchRow($defaultSelect);
        }
    }

    protected  function _resetDefault()
    {
        //reset all to null
        $data['is_default'] = 0;
        $this->update($data, null);
    }

    public function listDesigns()
    {
        $select = $this->select();
        $select->order('name');
        return $this->fetchAll($select);
    }

    public function getDesign($designId)
    {
        return $this->_design;
    }

    public function getValue($key)
    {
        if (isset($this->_design->$key)) {
            return $this->_design->$key;
        }
    }

    public function getLayout()
    {
        return $this->_design->layout;
    }

    public function setDesign($designId)
    {
        $design = $this->find($designId)->current();
        if ($design != null) {
            $this->_design = $design;
            return true;
        } else {
            return false;
        }
    }

    public function getStylesheets()
    {
        if (!empty($this->_design->styles)) {
            $stylesArray = array();
            $xml = simplexml_load_string($this->_design->styles);
            foreach ($xml as $skin => $styles) {
                $strSkin = (string)$skin;
                foreach ($styles as $stylesheet) {
                    $strStylesheet = (string)$stylesheet;
                    $stylesArray[$strSkin][] = $strStylesheet;
                }
            }
            return $stylesArray;
        }
    }

    public function getInlineStyles()
    {
        if (!empty($this->_design->inline_styles)) {
            return $this->_design->inline_styles;
        }
    }

    /**
     * this wont be implemented in 1.5
     *
     * @return unknown
     */
    public function getScripts()
    {
        if (!empty($this->_design->scripts)) {
            $scripts = simplexml_load_string($this->_design->scripts);
            foreach ($scripts as $script) {
                $scriptsArray[] = (string)$script;
            }
            if (is_array($scriptsArray)) {
                return $scriptsArray;
            }
        }

    }


    /**
     * this wont be implemented in 1.5
     *
     * @return unknown
     */
    public function getPlaceholders()
    {
        if (!empty($this->_design->placeholders)) {
            $placeholders = simplexml_load_string($this->_design->placeholders);
            foreach ($placeholders as $placeholder) {
                $placeholdersArray[] = (string)$placeholder;
            }
            if (is_array($placeholdersArray)) {
                return $placeholdersArray;
            }
        }
    }
}