<?php 

/**
 * DSF CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @category   DSF CMS
 * @package   DSF_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: Command.php Tue Dec 25 21:46:51 EST 2007 21:46:51 forrest lyman $
 */

class DSF_Command
{
    const PATH_TO_COMMANDS = './library/DSF/Command';
    
    /**
     * load the command
     *
     * @param string $command
     * @return mixed
     */
    static function run($command)
    {
        //get params if they exist
        $parts = explode('?', $command);
        if(is_array($parts) && count($parts) == 2)
        {
            $command = $parts[0];
            $params = DSF_Toolbox_Array::arrayFromGet($parts[1]);
        }
        $cmd = self::loadCommand($command);
        if(is_object($cmd))
        {
            $cmd->run($params);
            return $cmd->getResponse();
        }else{
            return $cmd;
        }
    }
    
    /**
     * run the info method of the command
     *
     * @param string $command
     * @return string
     */
    static function info($command)
    {
        $cmd = self::loadCommand($command);
        if(is_object($cmd))
        {
            $cmd->info();
            return $cmd->getResponse();
        }else{
            return $cmd;
        }
    }
    
    /**
     * load the requested command
     *
     * @param string $command
     * @return mixed
     */
    function loadCommand($command)
    {
        $class = "DSF_Command_" . $command;
        try {
            Zend_Loader::loadClass($class);
        } catch (Zend_Exception $e) {
            return array('Error loading command');
        }
        return new $class();
    }
    
}