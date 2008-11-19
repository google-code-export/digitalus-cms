<?php
class Database
{
    private $_connection;
    
    private $_host;
    private $_name;
    private $_username;
    private $_password;
    
    public function __construct()
    {
        $config = simplexml_load_file('./data/config.xml');
        $this->_host = $config->database->host;
        $this->_name = $config->database->name;
        $this->_username = $config->database->username;
        $this->_password = $confif->database->password;
    }
    
    public function connect()
    {
        $this->_connection = mysql_connect($this->_host, $this->_username, $this->_password);
        if(!$this->_connection){
            trigger_error('DB Connection failed', E_USER_ERROR);
        }
    }
    
    private function execute($sql)
    {
        return mysql_query($sql);
    }
    
    public function install()
    {
        $this->create();
        $this->insertInitialData();
    }
    
    public function create()
    {
        //create database
        $this->execute("CREATE DATABASE IF NOT EXISTS " . $this->_name); 
        $this->execute("USE " . $this->_name);
        
        $this->execute("DROP TABLE IF EXISTS `acl_resources`");
        $this->execute("  
            CREATE TABLE `acl_resources` (
              `id` int(11) NOT NULL auto_increment,
              `label` varchar(50) default NULL,
              `controller` varchar(50) default NULL,
              `actions` varchar(50) default NULL,
              `admin_section` varchar(50) default NULL,
              PRIMARY KEY  (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8");

        $this->execute("DROP TABLE IF EXISTS `content`");
        $this->execute(" 
            CREATE TABLE `content` (
              `id` int(11) unsigned NOT NULL auto_increment,
              `content_type` varchar(50) default NULL,
              `format` varchar(50) default NULL,
              `reference` text,
              `related_content` text,
              `tags` text,
              `meta_keywords` text,
              `meta_description` text,
              `parent_id` int(10) unsigned NOT NULL default '0',
              `title` text NOT NULL,
              `show_on_menu` int(11) default '0',
              `label` varchar(100) default NULL,
              `position` int(2) default NULL,
              `headline` text NOT NULL,
              `intro` text,
              `content` text NOT NULL,
              `additional_content` text,
              `template_path` varchar(100) default NULL,
              `layout_path` varchar(100) default NULL,
              `publish_level` int(10) unsigned NOT NULL default '0',
              `publish_date` int(11) default NULL,
              `archive_date` int(11) default NULL,
              `author_id` int(10) unsigned NOT NULL default '0',
              `create_date` int(10) unsigned NOT NULL default '0',
              `editor_id` int(10) unsigned NOT NULL default '0',
              `edit_date` int(10) unsigned NOT NULL default '0',
              `properties` text COMMENT 'this is linked to a list',
              `hits` int(11) default NULL,
              `filepath` varchar(250) default NULL,
              PRIMARY KEY  (`id`),
              KEY `REL_CONTENT` (`id`,`content_type`),
              FULLTEXT KEY `FT_CONTENT` (`content`,`tags`),
              FULLTEXT KEY `FT_RELATED_CONTENT` (`related_content`)
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8");

        $this->execute("DROP TABLE IF EXISTS `error_log`");
        $this->execute(" 
            CREATE TABLE `error_log` (
              `id` int(11) NOT NULL auto_increment,
              `referer` text,
              `uri` text,
              `date_time` int(11) default NULL,
              `error_data` text,
              PRIMARY KEY  (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8");

        $this->execute("DROP TABLE IF EXISTS `people`");
        $this->execute(" 
            CREATE TABLE `people` (
              `id` int(11) NOT NULL auto_increment,
              `role` varchar(50) default NULL,
              `email` varchar(200) default NULL,
              `password` text,
              `first_name` varchar(100) default NULL,
              `last_name` varchar(100) default NULL,
              `address` varchar(250) default NULL,
              `city` varchar(100) default NULL,
              `state` varchar(100) default NULL,
              `country` varchar(100) default NULL,
              `phone` varchar(50) default NULL,
              `phone_alt` varchar(50) default NULL,
              `properties` text,
              PRIMARY KEY  (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8");

        $this->execute("DROP TABLE IF EXISTS `redirectors`");
        $this->execute(" 
            CREATE TABLE `redirectors` (
              `id` int(11) NOT NULL auto_increment,
              `request` text,
              `response` text,
              `response_code` int(11) default NULL,
              PRIMARY KEY  (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8");

        $this->execute("DROP TABLE IF EXISTS `references`");
        $this->execute(" 
            CREATE TABLE `references` (
              `id` int(11) NOT NULL auto_increment,
              `parent_id` int(11) default NULL,
              `child_id` int(11) default NULL,
              PRIMARY KEY  (`id`),
              KEY `REL` (`parent_id`,`child_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8");

        $this->execute("DROP TABLE IF EXISTS `traffic_log`");
        $this->execute(" 
            CREATE TABLE `traffic_log` (
              `id` int(11) NOT NULL auto_increment,
              `page` varchar(200) default NULL,
              `ip` varchar(50) default NULL,
              `user_id` int(2) default NULL,
              `timestamp` int(11) default NULL,
              `day` int(1) default NULL,
              `week` int(2) default NULL,
              `month` int(2) default NULL,
              `year` int(4) default NULL,
              PRIMARY KEY  (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8");

        $this->execute("DROP TABLE IF EXISTS `users`");
        $this->execute(" 
            CREATE TABLE `users` (
              `id` int(10) unsigned NOT NULL auto_increment,
              `first_name` varchar(45) NOT NULL default '',
              `last_name` varchar(45) NOT NULL default '',
              `email` varchar(100) NOT NULL default '',
              `password` text NOT NULL,
              `role` varchar(45) NOT NULL default 'staff',
              `acl_roles` varchar(50) NOT NULL default '0',
              PRIMARY KEY  (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;");
    }
    
    public function insertInitialData()
    {
        $this->execute(" INSERT INTO `acl_resources` VALUES ('1', 'Content editor', 'page', '', 'admin')");
        $this->execute(" INSERT INTO `acl_resources` VALUES ('3', 'Contact manager', 'contact', null, 'module')");
        $this->execute("INSERT INTO `acl_resources` VALUES ('4', 'News manager', 'news', null, 'module')");
        $this->execute("INSERT INTO `acl_resources` VALUES ('5', 'Image Gallery', 'gallery', null, 'module')");
        $this->execute("INSERT INTO `acl_resources` VALUES ('6', 'Events Calendar', 'event', null, 'module')");
        $this->execute("INSERT INTO `acl_resources` VALUES ('2', 'Navigation', 'navigation', null, 'admin')");
        $this->execute("INSERT INTO `content` VALUES ('1', 'page', null, null, '', '', '', '', '0', 'home', '1', '', '0', '', '', '<p>Congratulations.&nbsp; You  have successfully installed the Digitalus CMS.&nbsp;</p>\r\n<p>The next step is to start building your site.&nbsp; This process is broken down into 3 areas:</p>\r\n<ul>\r\n    <li>Site administration</li>\r\n    <li>Design</li>\r\n    <li>Development</li>\r\n</ul>\r\n<h2>Site administration</h2>\r\n<p>Site administration includes setting up your site, managing content, and adding dynamic page parts called modules.</p>\r\n<h2>Design</h2>\r\n<p>The content you add to your site is displayed in a template. You can set up as many templates and subtemplates as you like.</p>\r\n<h2>Development</h2>\r\n<p>Your website should be as unique as your message. This system is built to make customization as painless as possible.</p>', '', '', '', '0', null, null, '0', '0', '1', '1209473945', 'O:8:\"stdClass\":4:{s:7:\"general\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:7:\"modules\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":3:{s:6:\"module\";s:1:\"0\";s:6:\"action\";s:0:\"\";s:6:\"params\";N;}}s:9:\"meta_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:9:\"user_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}}', '64', null)");
        $this->execute("INSERT INTO `users` VALUES ('1', 'test', 'admin', 'admin@email.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'superadmin', '1,2,3,4,5,6')");
    }
    
    public function close()
    {
        mysql_close($this->_connection);
    }
}