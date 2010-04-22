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
 * @version     $Id: Challenge.php Mon Dec 24 20:38:38 EST 2007 20:38:38 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/**
 * Challenge model
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */
class Login_Challenge extends Digitalus_Db_Table
{
    const validTimePeriod = 172800;        // the challenge is valid for 2 days: 2d = 2*24*3600 = 172800s

    const DB_NAME = 'challenge';

    /**
     * The table name.
     *
     * @var string
     */
    protected $_name = 'challenge';

    /**
     * The user name.
     *
     * @var string
     */
    protected $_userName;

    /**
     * The challenge id.
     *
     * @var string
     */
    protected $_challengeId;

    /**
     * The challenge url.
     *
     * @var string
     */
    protected $_challengeUrl;

    /**
     * Constructor
     *
     * @param  mixed $config Array of user-specified config options, or just the Db Adapter.
     * @return void
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->_createChallengeId();
        $this->_cleanUpDb();
    }

    public static function isDbInstalled()
    {
        if (Digitalus_Db_Table::tableExists(Digitalus_Db_Table::getTableName(self::DB_NAME))) {
            return true;
        }
        return false;
    }

    /**
     * Creates the challenge table
     *
     * @return bool True for success, false if table already exists
     */
    public function createTable()
    {
        if (!$this->isDbInstalled()) {
            $sql = "CREATE TABLE `" . $this->_name . "` (
                    `challenge_id` VARCHAR(50) NOT NULL,
                    `user_name` VARCHAR(30) NOT NULL,
                    `valid` TINYINT(1) NOT NULL DEFAULT 1,
                    `timestamp` INT(11) NOT NULL,
                    PRIMARY KEY (`challenge_id`),
                    INDEX (`user_name`),
                    FOREIGN KEY (`user_name`) REFERENCES `" . Digitalus_Db_Table::getTableName('users') . "`(`name`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE = InnoDB DEFAULT CHARSET=utf8";
            return true;
        }
        return false;
    }

    /**
     * Returns a challenge Url
     *
     * @param  bool   $html  Return a html or a plain text Challenge Url
     * @return string Challenge Url
     */
    public function getChallengeUrl($html = false, $action = null)
    {
        $challengeUrl = $this->_createChallengeUrl($action);
        if (true == $html) {
            $challengeUrl = '<a href="' . $this->_challengeUrl . '">' . $challengeUrl . '</a>';
        }
        return urldecode($challengeUrl);
    }

    /**
     * Creates a challenge Url
     *
     * @return string Challenge Url
     */
    protected function _createChallengeUrl($action = 'challenge')
    {
        if (empty($this->_challengeUrl) || '' == $this->_challengeUrl) {
            $this->_challengeUrl = urlencode(
                  'http://' . $_SERVER['HTTP_HOST'] . $this->view->getBaseUrl() . '/' . Digitalus_Toolbox_Page::getCurrentPageName() . '/p'
                . '/a/' . strtolower($action)           // action
                . '/u/' . $this->_userName              // username
                . '/c/' . $this->getChallengeId()       // challenge
            );
        }
        return $this->_challengeUrl;
    }

    /**
     * Returns the challenge id
     *
     * @return string Challenge Id
     */
    public function getChallengeId()
    {
        return $this->_challengeId;
    }

    /**
     * Creates a new challenge Id
     *
     * @return void
     */
    protected function _createChallengeId()
    {
        // create challengeId (double mersenne twister)
        $this->_challengeId = md5(mt_rand() . mt_rand());
    }

    protected function _setUserName($userName)
    {
        $this->_userName = $userName;
        return $this->_userName;
    }

    /**
     * Inserts a new challenge into the database
     *
     * @param  string $challengeId The challenge id
     * @param  string $userName    The corresponding username for the given challenge
     * @param  id     $valid       Validity of new challenge
     * @return int    The primary key of the row inserted.
     */
    public function insertChallenge($challengeId, $userName, $valid = 1)
    {
        $this->_setUserName($userName);
        $data = array(
            'challenge_id' => $challengeId,
            'user_name'    => $userName,
            'valid'        => $valid,
            'timestamp'    => time()
        );
        return $this->insert($data);
    }

    /**
     * Makes a given challenge valid
     *
     * @param  string $challengeId The challenge id to validate
     * @return int Number of rows updated
     */
    public function validate($challengeId)
    {
        $data = array(
            'valid'     => 1,
            'timestamp' => time()
        );
        $where[] = $this->_db->quoteInto('challenge_id = ?', $challengeId);
        return $this->update($data, $where);
    }

    /**
     * Makes a given challenge invalid
     *
     * @param  string $challengeId The challenge id to invalidate
     * @return int Number of rows updated
     */
    public function invalidate($challengeId)
    {
        $data['valid'] = 0;
        $where[] = $this->_db->quoteInto('challenge_id = ?', $challengeId);
        return $this->update($data, $where);
    }

    /**
     * Checks whether a given challenge is valid
     *
     * @param  string $challengeId The challenge id to check against
     * @param  string $userName    The corresponding username for the given challenge
     * @return int Number of rows deleted
     */
    public function isValid($challengeId, $userName)
    {
        $select = $this->select();
        $select->where($this->_db->quoteInto('challenge_id = ?', $challengeId))
               ->where($this->_db->quoteInto('user_name = ?', $userName))
               ->where($this->_db->quoteInto('valid = ?', 1))
               ->where($this->_db->quoteInto('timestamp > ?', time() - self::validTimePeriod));
        $result = $this->_db->fetchRow($select);
        if (!empty($result)) {
            return true;
        }
        return false;
    }

    /**
     * Cleans up the database from old, unused challenges
     *
     * @return int Number of rows deleted
     */
    protected function _cleanUpDb()
    {
        $where[] = $this->_db->quoteInto('timestamp < ?', time() - (self::validTimePeriod + 1));
        return $this->delete($where);
    }
}