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
        $this->_createErrorLog();
        $this->_createContentPage();
        $this->_createContentUser();
        $this->_createPages();
        $this->_createTrafficLog();
        $this->_createUsers();
        $this->_populate();
    }

    public function testInstallation()
    {
        $tables = array('data', 'content_page', 'content_user', 'error_log', 'pages', 'traffic_log', 'users');
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

    private function _createContentPage()
    {
        $table = 'content_page';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
                    `id` int(11) NOT NULL auto_increment,
                    `page_id` int(11) NOT NULL,
                    `node` varchar(100) default NULL,
                    `version` varchar(100) default NULL,
                    `content` mediumtext,
                    PRIMARY KEY (`id`),
                    KEY (`page_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }

    private function _createContentUser()
    {
        $table = 'content_user';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
                    `id` int(11) NOT NULL auto_increment,
                    `name` varchar(30) DEFAULT NULL,
                    `content_type` varchar(100) DEFAULT NULL,
                    `content` text,
                    PRIMARY KEY (`id`),
                    KEY (`name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }

    private function _createData()
    {
        $table = 'data';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
                    `id` int(11) NOT NULL auto_increment,
                    `tags` varchar(500) DEFAULT NULL,
                    `data` text,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }
    private function _createErrorLog()
    {
        $table = 'error_log';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
                    `id` int(11) NOT NULL auto_increment,
                    `referer` text,
                    `uri` text,
                    `date_time` int(11) DEFAULT NULL,
                    `error_data` text,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }

    private function _createPages()
    {
        $table = 'pages';
        $table = Digitalus_Db_Table::getTableName($table, $this->_config['prefix']);
        $sql = "CREATE TABLE `" . $table . "` (
                    `id` int(11) NOT NULL auto_increment,
                    `user_name` varchar(30) DEFAULT NULL,
                    `create_date` int(11) DEFAULT NULL,
                    `publish_date` int(11) DEFAULT NULL,
                    `archive_date` int(11) DEFAULT NULL,
                    `publish_level` enum('1','11','21') DEFAULT '11',
                    `name` varchar(250) DEFAULT NULL,
                    `label` varchar(250) DEFAULT NULL,
                    `namespace` varchar(100) DEFAULT NULL,
                    `content_template` varchar(100) DEFAULT NULL,
                    `related_pages` text,
                    `parent_id` int(11) DEFAULT NULL,
                    `position` int(11) DEFAULT NULL,
                    `is_home_page` tinyint(1) DEFAULT 0,
                    `show_on_menu` tinyint(1) DEFAULT 0,
                    `design` int(11) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `user_name` (`user_name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

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
                    `active` tinyint(1) NOT NULL DEFAULT 0,
                    `first_name` varchar(45) NOT NULL DEFAULT '',
                    `last_name` varchar(45) NOT NULL DEFAULT '',
                    `email` varchar(100) NOT NULL DEFAULT '',
                    `openid` varchar(100) DEFAULT NULL,
                    `password` text NOT NULL,
                    `role` varchar(45) NOT NULL DEFAULT 'guest',
                    `acl_resources` text,
                    PRIMARY KEY (`name`),
                    UNIQUE KEY (`openid`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }

    private function _populate()
    {
        $content_page = Digitalus_Db_Table::getTableName('content_page', $this->_config['prefix']);
        $data  = Digitalus_Db_Table::getTableName('data',  $this->_config['prefix']);
        $pages = Digitalus_Db_Table::getTableName('pages', $this->_config['prefix']);
        $users = Digitalus_Db_Table::getTableName('users', $this->_config['prefix']);
        $queries = array(
            'INSERT INTO `' . $pages . '` VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' => array(
                array(1, 'administrator', time(), time(), null, 1, 'Site Offline', '', 'content', 'default_default', null, 0, 1, null, 0, null)
                array(2, 'administrator', time(), time(), null, 1, '404 Page',     '', 'content', 'default_default', null, 0, 0, null, 0, null),
                array(3, 'administrator', time(), time(), null, 1, 'Home',         '', 'content', 'default_default', null, 0, 2, 1,    1, null),
            ),
            'INSERT INTO `' . $content_page . '` VALUES (?, ?, ?, ?, ?)' => array(
                array(1, 1, 'headline', 'en', 'Site Offline'),
                array(2, 2, 'content',  'en', 'Sorry, our site is currently offline for maintenance.')
                array(3, 2, 'headline', 'en', 'HTTP/1.1 404 Not Found'),
                array(4, 2, 'content',  'en', 'Sorry, the page you are looking for has moved or been renamed.'),
                array(5, 3, 'content',  'en', "Congratulations! You have successfully installed Digitalus CMS.<br>To get started why don't you log in and change this page:<br><ol><li>Log in to site administration with the username and password you set up in the installer.</li><li>Go to the pages section.</li><li>Click on the Home page on the left sidebar.</li><li>Now update it and click update page!</li></ol>If you have any questions here are some helpful links:<br><ul><li><a href=\"http://forum.digitaluscms.com\">Digitalus Forum</a></li><li><a href=\"http://wiki.digitaluscms.com\">Digitalus Documentation</a><br></li></ul>"),
                array(6, 3, 'headline', 'en', 'Digitalus CMS'),
            ),
            'INSERT INTO `' . $data . '` VALUES (?, ?, ?)' => array(
                array(1, 'site_settings', "<?xml version=\"1.0\"?>\n<settings><name>Digitalus CMS Site</name><online>1</online><addMenuLinks>0</addMenuLinks><default_locale/><default_language>en</default_language><default_charset>utf-8</default_charset><default_timezone>America/Los_Angeles</default_timezone><default_date_format/><default_currency_format/><default_email/><default_email_sender/><use_smtp_mail>0</use_smtp_mail><smtp_host/><smtp_username/><smtp_password/><google_tracking/><google_verify/><title_separator> - </title_separator><add_menu_links>1</add_menu_links><publish_pages>11</publish_pages><doc_type>XHTML1_TRANSITIONAL</doc_type><home_page>1</home_page><page_not_found>2</page_not_found><offline_page>3</offline_page><meta_description/><meta_keywords/><xml_declaration/></settings>\n")
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