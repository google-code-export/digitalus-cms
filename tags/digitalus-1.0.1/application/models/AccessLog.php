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
 * @package    DSF_CMS_Models
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: AccessLog.php Sun Dec 23 09:17:31 EST 2007 09:17:31 forrest lyman $
 */

class AccessLog extends Zend_Db_Table
{
	/**
	 * table name
	 *
	 * @var string
	 */
	protected $_name='access_log';
	
	/**
	 * inserts the currenta user id, uri, ip address, and date to the cms access log
	 *
	 * @param int $userId
	 */
	function log($userId)
	{
		$data['user_id'] = $userId;
		$data['uri'] = $_SERVER['REQUEST_URI'];
		$data['ip'] = $_SERVER['REMOTE_ADDR'];
		$data['date_time'] = time();
		
		$this->insert($data);		
	}
	
	/**
	 * this method returns the access for the last
	 * 2 weeks.  it automatically cleans itself (drops any records older than
	 * 2 weeks.)
	 *
	 */
	function getLog()
	{
		$date = new Zend_Date();
		$date->sub('2',Zend_Date::WEEK);
		$this->delete("date_time < " . $date->get(Zend_Date::TIMESTAMP));
		return $this->fetchAll(null, 'date_time DESC');
	}
	
	/**
	 * i dont want any updates or deletes to be performed on 
	 * the application.  all log maintance should be performed
	 * on the db.  override these methods
	 *
	 */
	function update()
	{
		return false;		
	}
	
	function delete()
	{
		return false;
	}
	
}