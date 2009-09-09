<?php
/**
 * Digitalus CMS
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
 * @category   Digitalus CMS
 * @package    Digitalus_CMS_Models
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: ErrorLog.php Sun Dec 23 11:00:10 EST 2007 11:00:10 forrest lyman $
 */

class Model_ErrorLog extends Zend_Db_Table_Abstract
{
    /**
     * content type
     *
     * @var string
     */
    protected $_name = 'error_log';

    public function __construct()
    {
        parent::__construct();
        $this->_name = Digitalus_Db_Table::getTableName($this->_name);
    }

    /**
     * logs the current request in the 404 error log
     *
     */
    public function log404()
    {
        $config = Zend_Registry::get('config');
        $date = new Zend_Date();
        $data['referer'] = $_SERVER['HTTP_REFERER'];
        $data['uri'] = $_SERVER['REQUEST_URI'];
        $data['date_time'] = $date->get();

        //validate uri so it is only logging valid requests
        $uriParts = explode('/', $data['uri']);
        if (is_array($uriParts)) {
            $pos = count($uriParts) - 1;
            $last = $uriParts[$pos];
            $parts = explode('.', $last);
            if (is_array($parts)) {
                $partsPos = count($parts) - 1;
                $ignoreList = explode(',', $config->errors->ignore);
                if (!in_array($parts[$partsPos], $ignoreList)) {
                    //log the error
                    $this->insert($data);
                }
            }
        }
    }

    /**
     * returns the current 404 error log
     *
     * @return zend_db_rowset
     */
    public function get404Log()
    {
        $sql = "
            SELECT id, referer, uri, FROM_UNIXTIME(date_time) AS date_time
            FROM error_log
            ORDER BY date_time DESC";
        return $this->_db->fetchAll($sql);
    }
}