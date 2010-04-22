<?php
class Digitalus_Installer_Database
{
    const DB_PREFIX_REGEX = '#^[a-zA-Z0-9_]{0,12}$#';

    protected $_config = array();
    protected $_db;
    protected $_allowedAdapters = array();

    public function connect($name, $host, $username, $password, $prefix, $adapter = 'Pdo_Mysql')
    {
        $this->_config = array(
            'host'     => $host,
            'username' => $username,
            'password' => $password,
            'dbname'   => $name,
            'prefix'   => $prefix,
            'adapter'  => $adapter,
            'charset'  => 'utf8',
        );

        // have a try
        try {
            $this->_db = Zend_Db::factory($adapter, $this->_config);
            $this->_db->getConnection();
            $this->_db->query("SET NAMES 'utf8'");
            $this->_db->query("SET CHARACTER SET 'utf8'");
        } catch (Zend_Db_Adapter_Exception $e) {
            echo 'Connection could not be established! Please check Your database credentials!' . PHP_EOL;
            if ('production' != APPLICATION_ENV) {
                echo 'Caught exception:' . PHP_EOL, $e->getMessage() . ' in '. $e->getFile().', line: '. $e->getLine() . '.', PHP_EOL;
            }
        }
        return true;
    }

    public function isEmpty()
    {
        $tables = $this->_db->listTables();
        if (is_array($tables) && count($tables) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function isWritable()
    {
        //see if you can create a table
        $this->_db->query("CREATE TABLE `tmp` (`id` int(11) default null)");
        if (!$this->tableExists('tmp')) {
            return false;
        }

        //test insert
        $data['id'] = 1;
        $result = $this->_db->insert('tmp', $data);
        if (!$result) {
            return false;
        }

        //test update
        $data['id'] = 2;
        $where = "id = 1";
        $result = $this->_db->update('tmp', $data, $where);
        if (!$result) {
            return false;
        }

        //test delete
        $where = "id = 2";
        $result = $this->_db->delete('tmp', $where);
        if (!$result) {
            return false;
        }

        //drop table
        $this->_db->query("DROP TABLE `tmp`");
        return true;

    }

    public function installDatabase()
    {
        $this->_createData();
        $this->_createGroups();
        $this->_createPages();
        $this->_createPageNodes();
        $this->_createTrafficLog();
        $this->_createUsers();
        $this->_createUserBookmarks();
        $this->_createUserNotes();
        $this->_constraints();
        $this->_populate();
    }

    public function testInstallation()
    {
        $tables = array('data', 'page_nodes', 'user_bookmarks', 'user_notes', 'pages', 'traffic_log', 'users', 'groups');
        foreach ($tables as $table) {
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
            if (!$this->tableExists($table)) {
                return false;
            }
        }
        return true;
    }

    public function insertAdminUser($firstName, $lastName, $email, $password)
    {
        $data = array(
            'name'       => 'administrator',
            'active'     => 1,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'password'   => md5($password),
            'role'       => 'superadmin',
        );
        $table = 'users';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        return $this->_db->insert($table, $data);
    }

    private function _createData()
    {
        $table = 'data';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `tags` varchar(500) DEFAULT NULL,
                    `data` text,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
        return $this->_db->query($sql);
    }

    private function _createGroups()
    {
        $table = 'groups';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
                    `name` varchar(30) NOT NULL,
                    `parent` varchar(30) DEFAULT NULL,
                    `label` varchar(30) DEFAULT NULL,
                    `description` varchar(200) DEFAULT NULL,
                    `acl_resources` text,
                    PRIMARY KEY (`name`),
                    KEY `parent` (`parent`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        return $this->_db->query($sql);
    }

    private function _createPages()
    {
        $table = 'pages';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_name` varchar(30) DEFAULT NULL,
                    `create_date` int(11) DEFAULT NULL,
                    `publish_date` int(11) NOT NULL,
                    `archive_date` int(11) NOT NULL,
                    `last_update` int(11) NOT NULL,
                    `publish_level` enum('1','11','21') DEFAULT '11',
                    `name` varchar(250) DEFAULT NULL,
                    `label` varchar(250) DEFAULT NULL,
                    `namespace` varchar(100) DEFAULT NULL,
                    `content_template` varchar(100) DEFAULT NULL,
                    `parent_id` int(11) DEFAULT '0',
                    `position` int(11) DEFAULT NULL,
                    `show_on_menu` tinyint(1) DEFAULT '0',
                    PRIMARY KEY (`id`),
                    KEY `fk_parent_page` (`parent_id`),
                    KEY `fk_page_author` (`user_name`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
        return $this->_db->query($sql);
    }

    private function _createPageNodes()
    {
        $table = 'page_nodes';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
                    `page_id` int(11) NOT NULL,
                    `node_type` varchar(100) NOT NULL DEFAULT 'content',
                    `language` enum('en','de','es','fr','hu','it','pl','ru','se') NOT NULL DEFAULT 'en',
                    `label` varchar(100) DEFAULT NULL,
                    `headline` varchar(100) DEFAULT NULL,
                    `content` mediumtext NOT NULL,
                    PRIMARY KEY (`page_id`,`node_type`,`language`),
                    KEY `fk_page_nodes` (`page_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        return $this->_db->query($sql);
    }

    private function _createTrafficLog()
    {
        $table = 'traffic_log';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
              `id` int(11) NOT NULL auto_increment,
              `page` varchar(200) DEFAULT NULL,
              `ip` varchar(50) DEFAULT NULL,
              `user_id` int(2) DEFAULT NULL,
              `timestamp` int(11) DEFAULT NULL,
              `day` int(1) DEFAULT NULL,
              `week` int(2) DEFAULT NULL,
              `month` int(2) DEFAULT NULL,
              `year` int(4) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }

    private function _createUsers()
    {
        $table = 'users';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
                    `name` varchar(30) NOT NULL,
                    `first_name` varchar(50) DEFAULT '',
                    `last_name` varchar(50) DEFAULT '',
                    `email` varchar(100) NOT NULL,
                    `password` varchar(32) NOT NULL,
                    `active` tinyint(1) NOT NULL DEFAULT '0',
                    `role` varchar(30) DEFAULT 'guest',
                    `openid` varchar(100) DEFAULT NULL,
                    PRIMARY KEY (`name`),
                    KEY `fk_user_roles` (`role`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        return $this->_db->query($sql);
    }

    private function _createUserBookmarks()
    {
        $table = 'user_bookmarks';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_name` varchar(30) NOT NULL,
                    `label` varchar(50) NOT NULL,
                    `url` varchar(100) NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `fk_user_bookmarks` (`user_name`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;";
        return $this->_db->query($sql);
    }

    private function _createUserNotes()
    {
        $table = 'user_notes';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_name` varchar(30) NOT NULL,
                    `content` text,
                    PRIMARY KEY (`id`),
                    KEY `fk_user_notes` (`user_name`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
        return $this->_db->query($sql);
    }

    private function _constraints()
    {
        $pages          = Digitalus_Db_Table::getTableName('pages',           $this->_config['prefix']);
        $users          = Digitalus_Db_Table::getTableName('users',           $this->_config['prefix']);
        $userBookmarks  = Digitalus_Db_Table::getTableName('user_bookmarks',  $this->_config['prefix']);
        $userNotes      = Digitalus_Db_Table::getTableName('user_notes',      $this->_config['prefix']);
        $sql = "ALTER TABLE `" . $pages . "`
                    ADD CONSTRAINT `fk_page_author` FOREIGN KEY (`user_name`) REFERENCES `users` (`name`) ON DELETE NO ACTION ON UPDATE CASCADE;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `" . $users . "`
                    ADD CONSTRAINT `fk_user_roles` FOREIGN KEY (`role`) REFERENCES `groups` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `" . $userBookmarks . "`
                    ADD CONSTRAINT `fk_user_bookmarks` FOREIGN KEY (`user_name`) REFERENCES `users` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `" . $userNotes . "`
                    ADD CONSTRAINT `fk_user_notes` FOREIGN KEY (`user_name`) REFERENCES `users` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;";
        $this->_db->query($sql);
    }

    private function _populate()
    {
        $data       = Digitalus_Db_Table::getTableName('data',      $this->_config['prefix']);
        $groups     = Digitalus_Db_Table::getTableName('groups',    $this->_config['prefix']);
        $queries = array(
            'INSERT INTO `' . $data . '` VALUES (?, ?, ?)' => array(
                array(1, 'site_settings', "<?xml version=\"1.0\"?>\n<settings><name>Digitalus CMS Site</name><online>1</online><addMenuLinks>0</addMenuLinks><default_locale/><admin_language>en</admin_language><default_language>en</default_language><default_charset>utf-8</default_charset><default_timezone>Europe/Berlin</default_timezone><default_date_format/><default_currency_format/><default_email/><default_email_sender/><use_smtp_mail>0</use_smtp_mail><smtp_host/><smtp_username/><smtp_password/><google_tracking/><google_verify/><title_separator> - </title_separator><add_menu_links>1</add_menu_links><publish_pages>11</publish_pages><doc_type>XHTML1_TRANSITIONAL</doc_type><home_page>3</home_page><page_not_found>2</page_not_found><offline_page>1</offline_page><meta_description/><meta_keywords/><xml_declaration/></settings>\n"),
            ),
            'INSERT INTO `' . $groups . '` VALUES (?, ?, ?, ?, ?)' => array(
                array('superadmin', NULL,    'Super Administrator', NULL, NULL),
                array('admin',      'guest', 'Site Administrator',  NULL, NULL),
                array('guest',      NULL,    'Guest',               NULL, NULL),
            ),
        );
        foreach ($queries as $sql => $inserts) {
            $stmt = $this->_db->prepare($sql);
            foreach ($inserts as $data) {
                $stmt->execute($data);
            }
        }
    }

    public function insertPages()
    {
        $pages      = Digitalus_Db_Table::getTableName('pages',     $this->_config['prefix']);
        $pageNodes  = Digitalus_Db_Table::getTableName('pageNodes', $this->_config['prefix']);
        $queries = array(
            'INSERT INTO `' . $pages . '` VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' => array(
                array(1, 0, 'administrator', time(), time(), time(), time(), 1, 'Site Offline', 'Site Offline', 'content', 'default_default', 1, 0),
                array(2, 0, 'administrator', time(), time(), time(), time(), 1, '404 Page',     '404 Page',     'content', 'default_default', 0, 0),
                array(3, 0, 'administrator', time(), time(), time(), time(), 1, 'Home',         'Home',         'content', 'default_default', 2, 1),
            ),
            'INSERT INTO `' . $pageNodes . '` VALUES (?, ?, ?, ?, ?, ?)' => array(
                array(1, 'errorsite',  'en', 'Site Offline', 'Site Offline',           "Sorry, our site is currently offline for maintenance."),
                array(2, 'errorsite',  'en', '404 Page',     'HTTP/1.1 404 Not Found', "Sorry, the page you are looking for has moved or been renamed."),
                array(3, 'content',    'en', 'Home',         'Digitalus CMS',          "Congratulations! You have successfully installed Digitalus CMS.<br />To get started why don't you log in and change this page:<br /><ol><li>Log in to site administration with the username and password you set up in the installer.</li><li>Go to the pages section.</li><li>Click on the Home page on the left sidebar.</li><li>Now update it and click update page!</li></ol>If you have any questions here are some helpful links:<br /><ul><li><a href=\"http://forum.digitaluscms.com\">Digitalus Forum</a></li><li><a href=\"http://wiki.digitaluscms.com\">Digitalus Documentation</a><br /></li></ul>"),
            ),
        );
        foreach ($queries as $sql => $inserts) {
            $stmt = $this->_db->prepare($sql);
            foreach ($inserts as $data) {
                $stmt->execute($data);
            }
        }
    }

    private function _dropTable($table)
    {
        $sql = "DROP TABLE IF EXISTS `$table`";
        return $this->_db->query($sql);
    }

    public function tableExists($table)
    {
        $tables = $this->_db->listTables();
        if (in_array($table, $tables)) {
            return true;
        }
    }

    public static function getAllowedAdapters()
    {
        return array('Pdo_Ibm'   => 'Pdo_Ibm',   'Pdo_Mysql'  => 'Pdo_Mysql',
                     'Pdo_Mssql' => 'Pdo_Mssql', 'Pdo_Oci'    => 'Pdo_Oci',
                     'Pdo_Pgsql' => 'Pdo_Pgsql', 'Pdo_Sqlite' => 'Pdo_Sqlite',
                     'Sqlsrv'    => 'Sqlsrv',    'Db2'        => 'Db2',
                     'Mysqli'    => 'Mysqli',    'Oracle'     => 'Oracle'
        );
    }
}