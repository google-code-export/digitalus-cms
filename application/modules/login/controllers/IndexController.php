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
 * @author      LowTower - lowtower@gmx.de
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Module_Login
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: PublicController.php Mon Dec 24 20:38:38 EST 2007 20:38:38 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/**
 * @see Login_Challenge
 */
require_once APPLICATION_PATH . '/modules/login/models/Challenge.php';

/**
 * Index Controller
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */
class Mod_Login_IndexController extends Digitalus_Controller_Action
{
    /**
     * Called from __construct() as final step of object instantiation.
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Modules') => $this->baseUrl . '/admin/module',
           $this->view->getTranslation('Login')   => $this->baseUrl . '/mod_login'
        );
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->baseUrl . '/admin/index/bookmark/url/mod_login';
    }

    /**
     * The default backend action
     *
     * @return void
     */
    public function indexAction()
    {
        if (!Login_Challenge::isDbInstalled() && !$this->createChallengeTable()) {
            $sql = "CREATE TABLE `" . Digitalus_Db_Table::getTableName(Login_Challenge::DB_NAME) . "` (" . PHP_EOL
                 . "    `challenge_id` VARCHAR(50) NOT NULL," . PHP_EOL
                 . "    `user_name` VARCHAR(30) NOT NULL," . PHP_EOL
                 . "    `valid` TINYINT(1) NOT NULL DEFAULT 1," . PHP_EOL
                 . "    `timestamp` INT(11) NOT NULL," . PHP_EOL
                 . "    PRIMARY KEY (`challenge_id`)," . PHP_EOL
                 . "    INDEX (`user_name`)," . PHP_EOL
                 . "    FOREIGN KEY (`user_name`) REFERENCES `" . Digitalus_Db_Table::getTableName('users') . "`(`name`) ON DELETE CASCADE ON UPDATE CASCADE" . PHP_EOL
                 . ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $errorMessage   = array();
            $errorMessage[] = 'For the login module to work properly, the challenge database must be installed first.';
            $errorMessage[] = 'An attempt to create the database automatically failed (probably because of missing rights).';
            $errorMessage[] = 'Please create it manually with the following SQL statement (mind Your table prefix):';
            $errorMessage[] = '<br /><code>' . nl2br($sql) . '</code>';
            $this->view->errorMessage = $errorMessage;
        }
    }

    /**
     * Creates the challenge table
     *
     * @return bool True for success, false if table already exists
     */
    public function createChallengeTable()
    {
        if (!Login_Challenge::isDbInstalled()) {
            $db = Zend_Registry::get('database');
            $sql = "CREATE TABLE `?` (
                        `challenge_id` VARCHAR(50) NOT NULL,
                        `user_name` VARCHAR(30) NOT NULL,
                        `valid` TINYINT(1) NOT NULL DEFAULT 1,
                        `timestamp` INT(11) NOT NULL,
                        PRIMARY KEY (`challenge_id`),
                        INDEX (`user_name`),
                        FOREIGN KEY (`user_name`) REFERENCES `?`(`name`) ON DELETE CASCADE ON UPDATE CASCADE
                    ) ENGINE = InnoDB DEFAULT CHARSET=utf8";
            $stmtClass = $db->getStatementClass();
            $stmt      = new $stmtClass($db, $sql);
            try {
                $stmt->execute(array(Digitalus_Db_Table::getTableName(Login_Challenge::DB_NAME), Digitalus_Db_Table::getTableName('users')));
            } catch (Exception $e) {
                return false;
            }
            return true;
        }
        return false;
    }
}