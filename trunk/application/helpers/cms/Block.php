<?php
/**
 * renders a cms block
 * the blocks are formatted as:
 * module_block_action
 *
 * note that in this case the module does not have the mod_ prefix
 *
 * @package    Zend_View
 * @subpackage Helpers
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class DSF_View_Helper_Cms_Block
{
    public function Block($request, $params = null)
    {
        //process the block request
        $requestArray = explode('_',$request);
        $module = $requestArray[0];
        $controller = $requestArray[1];
        $action = $requestArray[2];

        // set the block module path. note that this resolves differently than the standard modules
        $module = $module . '_blocks';

        //check to see if the module block has already been added
        $front = Zend_Controller_Front::getInstance();
        $modulePaths = $front->getControllerDirectory();
        if (!isset($modulePaths[$moduleBlock])) {
            $front->addControllerDirectory();
        }

    }
}