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
 * @version    $Id: TrafficLog.php Mon Dec 24 20:35:48 EST 2007 20:35:48 forrest lyman $
 */

class TrafficLog extends DSF_Db_Table
{
    /**
     * the table name
     *
     * @var string
     */
    protected $_name = 'traffic_log';

    /**
     * add the current request to the traffic log
     *
     */
    public function logHit()
    {
        $date = new Zend_Date();
        $data['timestamp'] = $date->get(zend_date::TIMESTAMP);
        $data['day'] = $date->get(zend_date::WEEKDAY_DIGIT);
        $data['week'] = $date->get(zend_date::WEEK);
        $data['month'] = $date->get(zend_date::MONTH);
        $data['year'] = $date->get(zend_date::YEAR);
        $data['page'] = $_SERVER['REQUEST_URI'];
        $data['ip'] = $_SERVER['REMOTE_ADDR'];

        //get the admin identity
        $user = DSF_Auth::getIdentity();
        if ($user) {
          $data['user_id'] = $user->id;
        }

        $this->insert($data);
    }

    /**
     * this function returns the unique hits by week
     *
     * @return zend_db_rowset
     */
    public function getLogByWeek()
    {
        $sql = "SELECT
            COUNT(id) AS unique_hits,
            traffic_log.week,
            traffic_log.year
            FROM
            traffic_log
            WHERE
            page NOT LIKE '/admin%'
            AND
            page NOT LIKE '/module%'
            GROUP BY
            traffic_log.`year`,
            traffic_log.`week`,
            traffic_log.`ip`
            ORDER BY year DESC, week DESC
            ";

        return $this->_db->fetchAll($sql);
    }

    /**
     * this function returns the unique hits for the current week by the day
     *
     * @return zend_db_rowset
     */
    public function getLogByDay()
    {
        $date = new Zend_Date();
        $week = $date->get(zend_date::WEEK);
        $year = $date->get(zend_date::YEAR);

        $sql = "SELECT
            COUNT(id) AS unique_hits,
            traffic_log.day
            FROM
            traffic_log
            WHERE
            week = {$week}
            AND
            year = {$year}
            AND
            page NOT LIKE '/admin%'
            AND
            page NOT LIKE '/module%'
            GROUP BY
            traffic_log.`year`,
            traffic_log.`day`,
            traffic_log.`ip`
            ORDER BY year DESC, day DESC
            ";

        return $this->_db->fetchAll($sql);
    }

    /**
     * this function returns  all of the admin access
     *
     * @return zend_db_rowset
     */
    public function adminAccess($limit = 50)
    {
        $sql = "SELECT
            users.first_name,
            users.last_name,
            users.role,
            traffic_log.page,
            traffic_log.ip,
            FROM_UNIXTIME(traffic_log.timestamp) as date
            FROM
            traffic_log
            Inner Join users ON traffic_log.user_id = users.id
            ORDER BY timestamp DESC
            LIMIT {$limit}";
        return $this->_db->fetchAll($sql);
    }
}