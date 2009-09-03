<?php

class Digitalus_Installer_Database
{
    protected $_config = array();
    protected $_db;

    public function connect($name, $host, $username, $password, $prefix, $adapter)
    {
        $this->_config = array(
            'host'     => $host,
            'username' => $username,
            'password' => $password,
            'dbname'   => $name
            'prefix'   => $prefix
        );
        $this->_db = Zend_Db::factory($adapter, $this->_config);
        $this->_db->query("SET NAMES 'utf8'");
        $this->_db->query("SET CHARACTER SET 'utf8'");


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
        $this->_createNodes();
        $this->_createPages();
        $this->_createTrafficLog();
        $this->_createUsers();
        $this->_populate();
    }

    public function testInstallation()
    {
        $tables = array('data', 'content_nodes', 'error_log', 'pages', 'traffic_log', 'users');
        foreach ($tables as $table) {
            $table = $this->_getTableName($table);
            if (!$this->tableExists($table)) {
                return false;
            }
        }
        return true;
    }

    public function insertAdminUser($firstName, $lastName, $username, $password)
    {
        $data = array(
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $username,
            'password'   => md5($password),
            'role'       => 'superadmin'
        );
        $table = 'users';
        $table = $this->_getTableName($table);
        return $this->_db->insert($table, $data);
    }

    private function _createNodes()
    {
        $table = 'content_nodes';
        $table = $this->_getTableName($table);
        $sql = "CREATE TABLE `" . $table . "` (
              `id` int(11) NOT null auto_increment,
              `parent_id` varchar(50) default null,
              `node` varchar(100) default null,
              `version` varchar(100) default null,
              `content_type` varchar(100) default null,
              `content` text,
              PRIMARY KEY  (`id`),
              KEY `NODE_TO_PAGE` (`parent_id`),
              KEY `NODE_KEYS` (`node`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }

    private function _createData()
    {
        $table = 'data';
        $table = $this->_getTableName($table);
        $sql = "CREATE TABLE `" . $table . "` (
              `id` int(11) NOT null auto_increment,
              `tags` varchar(500) default null,
              `data` text,
              PRIMARY KEY  (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }
    private function _createErrorLog()
    {
        $table = 'error_log';
        $table = $this->_getTableName($table);
        $sql = "CREATE TABLE `" . $table . "` (
              `id` int(11) NOT null auto_increment,
              `referer` text,
              `uri` text,
              `date_time` int(11) default null,
              `error_data` text,
              PRIMARY KEY  (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }

    private function _createPages()
    {
        $table = 'pages';
        $table = $this->_getTableName($table);
        $sql = "CREATE TABLE `" . $table . "` (
              `id` int(11) NOT null auto_increment,
              `author_id` int(11) default null,
              `create_date` int(11) default null,
              `publish_date` int(11) default null,
              `archive_date` int(11) default null,
              `publish_level` int(11) default null,
              `name` varchar(250) default null,
              `label` varchar(250) default null,
              `namespace` varchar(100) default null,
              `content_template` varchar(100) default null,
              `related_pages` text,
              `parent_id` int(11) default null,
              `position` int(11) default null,
              `is_home_page` int(11) default null,
              `show_on_menu` int(11) default null,
              `design` int(11) default null,
              PRIMARY KEY  (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        ";

        return $this->_db->query($sql);
    }

    private function _createTrafficLog()
    {
        $table = 'traffic_log';
        $table = $this->_getTableName($table);
        $sql = "CREATE TABLE `" . $table . "` (
              `id` int(11) NOT null auto_increment,
              `page` varchar(200) default null,
              `ip` varchar(50) default null,
              `user_id` int(2) default null,
              `timestamp` int(11) default null,
              `day` int(1) default null,
              `week` int(2) default null,
              `month` int(2) default null,
              `year` int(4) default null,
              PRIMARY KEY  (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }


    private function _createUsers()
    {
        $table = 'users';
        $table = $this->_getTableName($table);
        $sql = "CREATE TABLE `" . $table . "` (
          `id` int(10) unsigned NOT null auto_increment,
          `first_name` varchar(45) NOT null default '',
          `last_name` varchar(45) NOT null default '',
          `email` varchar(100) NOT null default '',
          `password` text NOT null,
          `role` varchar(45) NOT null default 'admin',
          `acl_resources` text,
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }

    private function _populate()
    {
        $table = $this->_getTableName('content_nodes');
        $queries = array(
            "INSERT INTO `" . $table . "` VALUES (1, 'page_1', 'content', null, null, 'Welcome to Digitalus CMS');",
            "INSERT INTO `" . $table . "` VALUES (2, 'page_1', 'tagline', 'en', null, 'About Digitalus');",
            "INSERT INTO `" . $table . "` VALUES (3, 'page_1', 'content', 'en', null, 'Congratulations! You have successfully installed Digitalus CMS.<br>To get started why don\'t you log in and change this page:<br><ol><li>Log in to site administration with the username and password you set up in the installer.</li><li>Go to the pages section.</li><li>Click on the Home page on the left sidebar.</li><li>Now update it and click update page!</li></ol>If you have any questions here are some helpful links:<br><ul><li><a href=\"http://forum.digitaluscms.com\">Digitalus Forum</a></li><li><a href=\"http://wiki.digitaluscms.com\">Digitalus Documentation</a><br></li></ul>');",
            "INSERT INTO `" . $table . "` VALUES (4, 'page_1', 'headline', 'en', null, 'Digitalus CMS');",
            "INSERT INTO `" . $table . "` VALUES (5, 'page_1', 'teaser', 'en', null, '');",
            "INSERT INTO `" . $table . "` VALUES (6, 'page_2', 'headline', 'en', null, 'HTTP/1.1 404 Not Found');",
            "INSERT INTO `" . $table . "` VALUES (7, 'page_2', 'teaser', 'en', null, '');",
            "INSERT INTO `" . $table . "` VALUES (8, 'page_2', 'content', 'en', null, 'Sorry, the page you are looking for has moved or been renamed.');",
            "INSERT INTO `" . $table . "` VALUES (9, 'page_3', 'headline', 'en', null, 'Site Offline');",
            "INSERT INTO `" . $table . "` VALUES (10, 'page_3', 'teaser', 'en', null, '');",
            "INSERT INTO `" . $table . "` VALUES (11, 'page_3', 'content', 'en', null, 'Sorry, our site is currently offline for maintenance.');",
        $table = $this->_getTableName('data');
            "INSERT INTO `" . $table . "` VALUES (1, 'site_settings', '<?xml version=\"1.0\"?>\n<settings><name>Digitalus CMS Site</name><online>1</online><addMenuLinks>0</addMenuLinks><default_locale/><default_language>en</default_language><default_charset>utf-8</default_charset><default_timezone>America/Los_Angeles</default_timezone><default_date_format/><default_currency_format/><default_email/><default_email_sender/><use_smtp_mail>0</use_smtp_mail><smtp_host/><smtp_username/><smtp_password/><google_tracking/><google_verify/><title_separator> - </title_separator><add_menu_links>1</add_menu_links><publish_pages>0</publish_pages><doc_type>XHTML1_TRANSITIONAL</doc_type><home_page>1</home_page><page_not_found>2</page_not_found><offline_page>3</offline_page><meta_description/><meta_keywords/><xml_declaration/></settings>\n');",
        $table = $this->_getTableName('pages');
            "INSERT INTO `" . $table . "` VALUES (1, 1, '" . time() ."', '" . time() ."', null, 1, 'Home', '', 'content', 'default_default', null, 0, 2, 1, 1, null);",
            "INSERT INTO `" . $table . "` VALUES (2, 1, '" . time() ."', '" . time() ."', null, 1, '404 Page', '', 'content', 'default_default', null, 0, 0, null, 0, null);",
            "INSERT INTO `" . $table . "` VALUES (3, 1, '" . time() ."', '" . time() ."', null, 1, 'Site Offline', '', 'content', 'default_default', null, 0, 1, null, 0, null);"
        );
        foreach ($queries as $query) {
            $this->_db->query($query);
        }
    }

    private function _dropTable($table)
    {
        $sql = "DROP TABLE IF EXISTS " . $table;
        return $this->_db->query($sql);
    }

    public function tableExists($table)
    {
        $tables = $this->_db->listTables();
        if (in_array($table, $tables)) {
            return true;
        }
    }

    private function _getTableName($table)
    {
        $prefix = $this->_config('prefix');
        return $prefix . $table;
    }
}
?>