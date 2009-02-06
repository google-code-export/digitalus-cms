<?php

class DSF_Installer_Database{
    protected $_config = array();
    protected $_db;

    public function connect($name, $host, $username, $password, $adapter)
    {
        $this->_config = array(
            'host'     => $host,
            'username' => $username,
            'password' => $password,
            'dbname'   => $name
        );
        $this->_db = Zend_Db::factory($adapter, $this->_config);


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
        $this->_createDesigns();
        $this->_createErrorLog();
        $this->_createNodes();
        $this->_createPages();
        $this->_createTrafficLog();
        $this->_createUsers();
        $this->_populate();
    }

    public function testInstallation()
    {
        $tables = array('data','content_nodes','designs','error_log','pages','traffic_log','users');
        foreach ($tables as $table) {
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
            'role'       => "superadmin"
        );
        return $this->_db->insert('users', $data);
    }

    private function _createNodes()
    {
        $sql = "CREATE TABLE `content_nodes` (
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
        $sql = "CREATE TABLE `data` (
              `id` int(11) NOT null auto_increment,
              `tags` varchar(500) default null,
              `data` text,
              PRIMARY KEY  (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }

    private function _createDesigns()
    {
        $sql = "CREATE TABLE `designs` (
              `id` int(11) NOT null auto_increment,
              `name` varchar(250) default null,
              `notes` text,
              `layout` varchar(500) default null,
              `styles` text,
              `inline_styles` text,
              `template` varchar(500) default null,
              `placeholders` text,
              `scripts` text,
              `is_default` int(11) default null,
              PRIMARY KEY  (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }

    private function _createErrorLog()
    {
        $sql = "CREATE TABLE `error_log` (
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
        $sql = "CREATE TABLE `pages` (
              `id` int(11) NOT null auto_increment,
              `author_id` int(11) default null,
              `create_date` int(11) default null,
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
        $sql = "CREATE TABLE `traffic_log` (
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
        $sql = "CREATE TABLE `users` (
          `id` int(10) unsigned NOT null auto_increment,
          `first_name` varchar(45) NOT null default '',
          `last_name` varchar(45) NOT null default '',
          `email` varchar(100) NOT null default '',
          `password` text NOT null,
          `role` varchar(45) NOT null default 'staff',
          `acl_resources` text,
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        ";
        return $this->_db->query($sql);
    }

    private function _populate()
    {
        $queries = array(
            "INSERT INTO `pages` VALUES ('1', 0, " . time() . ", 'Home', null, 'content', 'base_page', null, '0', null, '1', '1', '1')",
            "INSERT INTO `content_nodes` VALUES ('1', 'page_1', 'content', null, null, 'Welcome to Digitalus CMS')",
            "INSERT INTO `data` VALUES ('1', 'site_settings', '<?xml version=\"1.0\"?>\n<settings><name>Digitalus 1.5.0 test</name><online>0</online><addMenuLinks>0</addMenuLinks><default_locale/><default_language>en</default_language><default_charset>utf8</default_charset><default_date_format/><default_currency_format/><default_email>info@digitaluscms.com</default_email><default_email_sender>Digitalus CMS</default_email_sender><use_smtp_mail>0</use_smtp_mail><smtp_host/><smtp_username/><smtp_password/><google_tracking/><google_verify/><title_separator> - </title_separator><add_menu_links>1</add_menu_links><doc_type>XHTML1_TRANSITIONAL</doc_type></settings>\n')",
            "INSERT INTO `designs` VALUES ('1', 'Default', 'This is the standard page.', 'simple-page.phtml', 'a:2:{s:10:\"blank-page\";a:2:{i:0;s:7:\"nav.css\";i:1;s:9:\"style.css\";}s:8:\"grid-960\";a:1:{i:0;s:7:\"960.css\";}}', 'body{\r\nbackground:#333;\r\n}', null, null, null, '1')"
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

}
?>