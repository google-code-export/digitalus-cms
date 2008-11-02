<?php
$query  = array(
"USE digitalus_demo_site;",
"SET FOREIGN_KEY_CHECKS=0;",
"DROP TABLE `acl_resources`;",
"CREATE TABLE `acl_resources` (
  `id` int(11) NOT NULL auto_increment,
  `label` varchar(50) default NULL,
  `controller` varchar(50) default NULL,
  `actions` varchar(50) default NULL,
  `admin_section` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;",
"DROP TABLE `content`;",
"CREATE TABLE `content` (
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
) ENGINE=MyISAM AUTO_INCREMENT=523 DEFAULT CHARSET=latin1;",
"DROP TABLE `error_log`;",
"CREATE TABLE `error_log` (
  `id` int(11) NOT NULL auto_increment,
  `referer` text,
  `uri` text,
  `date_time` int(11) default NULL,
  `error_data` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;",
"DROP TABLE `people`;",
"CREATE TABLE `people` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;",
"DROP TABLE `redirectors`;",
"CREATE TABLE `redirectors` (
  `id` int(11) NOT NULL auto_increment,
  `request` text,
  `response` text,
  `response_code` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;",
"DROP TABLE `references`;",
"CREATE TABLE `references` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) default NULL,
  `child_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `REL` (`parent_id`,`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;",
"DROP TABLE `traffic_log`;",
"CREATE TABLE `traffic_log` (
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
) ENGINE=MyISAM AUTO_INCREMENT=65082 DEFAULT CHARSET=latin1;",
"DROP TABLE `users`;",
"CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `first_name` varchar(45) NOT NULL default '',
  `last_name` varchar(45) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `password` text NOT NULL,
  `role` varchar(45) NOT NULL default 'staff',
  `acl_roles` varchar(50) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;",
"INSERT INTO `acl_resources` VALUES ('1', 'Content editor', 'page', '', 'admin');",
"INSERT INTO `acl_resources` VALUES ('3', 'Contact manager', 'contact', null, 'module');",
"INSERT INTO `acl_resources` VALUES ('4', 'News manager', 'news', null, 'module');",
"INSERT INTO `acl_resources` VALUES ('5', 'Image Gallery', 'gallery', null, 'module');",
"INSERT INTO `acl_resources` VALUES ('6', 'Events Calendar', 'event', null, 'module');",
"INSERT INTO `acl_resources` VALUES ('2', 'Navigation', 'navigation', null, 'admin');",
"INSERT INTO `content` VALUES ('1', 'page', null, null, '', '', '', '', '0', 'home', '1', 'Home', '0', '', '', '<p>Welcome to the Digitalus CMS demo site.</p>\r\n<br />\r\n<p>The process of building a Digitalus driven site is broken down into 3 areas:</p>\r\n<ul>\r\n    <li>Site administration</li>\r\n    <li>Design</li>\r\n    <li>Development</li>\r\n</ul>\r\n<h2>Site administration</h2>\r\n<p>Site administration includes setting up your site, managing content, and adding dynamic page parts called modules.</p>\r\n<h2>Design</h2>\r\n<p>The content you add to your site is displayed in a template. You can set up as many templates and subtemplates as you like.</p>\r\n<h2>Development</h2>\r\n<p>Your website should be as unique as your message. This system is built to make customization as painless as possible.</p>', '', '', '', '0', null, null, '0', '0', '1', '1209473945', 'O:8:\"stdClass\":4:{s:7:\"general\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:7:\"modules\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":3:{s:6:\"module\";s:1:\"0\";s:6:\"action\";s:0:\"\";s:6:\"params\";N;}}s:9:\"meta_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:9:\"user_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}}', '121', null);
INSERT INTO `content` VALUES ('520', 'newsItem', null, null, null, '', null, null, '0', 'Digitalus CMS breaks 10k download mark', '1', null, '9', '', '', '<p>This last week was a very exciting time for the Digitalus team as the cms hit the 10,000 downloads mark.<span style=\\\"\\\">&nbsp; </span>The enthusiastic response from PHP developers worldwide validates the countless hours that our team has put into building the most flexible cms framework possible.</p>', '', null, null, '0', '1218513600', '0', '1', '1218547942', '1', '1218547996', null, null, null);",
"INSERT INTO `content` VALUES ('511', 'page', null, null, null, null, null, null, '0', 'Getting Started', '1', '', '1', '', null, '<p>Getting started using any new system can be a daunting task.&nbsp; We have worked tirelessly to make Digitalus as easy as possible to learn and manage.</p>\r\n<p>Here is a quick start guide to adding content to your Digitalus site...</p>\r\n<p><b>Log In</b></p>\r\n<p>Before you can work on your site you need to log in. Go to yoursite.com/admin to log in. You will need to enter your username and password.</p>\r\n<p><a href=\"http://wiki.digitaluscms.com/index.php?title=Image:Form.png\" class=\"image\" title=\"Image:Form.png\"><img width=\"400\" height=\"148\" border=\"0\" alt=\"Image:Form.png\" src=\"http://wiki.digitaluscms.com/images/d/d0/Form.png\" /></a></p>\r\n<p>For more information about logging in and security see the login page.</p>\r\n<p><b>Create a New Page</b></p>\r\n<p>To create a new page go to the pages section.  The main form will be the the add a new page form.</p>\r\n<p><a href=\"http://wiki.digitaluscms.com/index.php?title=Image:Page-add.png\" class=\"image\" title=\"Image:page-add.png\"><img width=\"400\" height=\"156\" border=\"0\" alt=\"Image:page-add.png\" src=\"http://wiki.digitaluscms.com/images/3/30/Page-add.png\" /></a></p>\r\n<p>Enter the name for your new page (this should be letters and numbers only).</p>\r\n<p>Then select the parent page. The page will be placed in the parent page\'s folder. Note that you do not need to create folders. Once you select a page to be the parent the CMS will turn it into a folder if it is not already.</p>\r\n<p>Then you choose if you want to continue adding pages or not.  Make sure this is unchecked, and click Create Page.</p>\r\n<p><b>The Page Editor</b></p>\r\n<p>When you create a new page (and have not selected to continue adding pages) the editor will open after you create a new page.</p>\r\n<p><a href=\"http://wiki.digitaluscms.com/index.php?title=Image:Page-editor-form.png\" class=\"image\" title=\"Image:Page-editor-form.png\"><img width=\"400\" height=\"396\" border=\"0\" alt=\"Image:Page-editor-form.png\" src=\"http://wiki.digitaluscms.com/images/6/67/Page-editor-form.png\" /></a></p>\r\n<p>Enter some text in the rich text editor, then click Save Changes. Add your page to the menu</p>\r\n<p>When you create a page the CMS automatically adds it to the end of the proper menu. This is an optional feature that you can change in site settings. If you want to update your menu you do so in the navigation section. That\'s it!</p>\r\n<p>That is all there is to creating a new webpage. If you look at your site now you will see that your new page is listed on your menu. Click that link just to test your work (you always want to be the first one to see your work).</p>', null, null, null, '0', null, null, '1', '1218544231', '0', '0', null, '15', null);",
"INSERT INTO `content` VALUES ('513', 'page', null, null, null, '', '', '', '0', 'Image Gallery', '1', 'Gallery', '2', '', '', '<p>The image gallery module enables you to add and manage image galleries on your site.</p>\r\n<p><a href=\\\"/mod_gallery/index/edit/id/518\\\">Click here</a> to manage this gallery.</p>', '', null, null, '0', null, null, '1', '1218544288', '1', '1218545533', 'O:8:\"stdClass\":4:{s:7:\"general\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:7:\"modules\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":3:{s:6:\"module\";s:7:\"gallery\";s:6:\"action\";s:13:\"simplegallery\";s:6:\"params\";a:1:{s:7:\"gallery\";s:3:\"518\";}}}s:9:\"meta_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:9:\"user_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}}', '73', null);",
"INSERT INTO `content` VALUES ('514', 'page', null, null, null, null, null, null, '0', 'News', '1', '', '3', '', null, '<p>The news module enables you to publish and manage news for your site.&nbsp; This view is a simple list of the news items.&nbsp; Optionally you can categorize your news, then display a category view.</p>', null, null, null, '0', null, null, '1', '1218544294', '0', '0', 'O:8:\"stdClass\":4:{s:7:\"general\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:7:\"modules\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":3:{s:6:\"module\";s:4:\"news\";s:6:\"action\";s:8:\"list-all\";s:6:\"params\";N;}}s:9:\"meta_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:9:\"user_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}}', '48', null);",
"INSERT INTO `content` VALUES ('515', 'page', null, null, null, null, null, null, '0', 'Events', '1', '', '4', '', null, '<p>The events module enables you to publish and manage events on your site.&nbsp; You can optionally manage multiple calendars.</p>', null, null, null, '0', null, null, '1', '1218544300', '0', '0', 'O:8:\"stdClass\":4:{s:7:\"general\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:7:\"modules\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":3:{s:6:\"module\";s:5:\"event\";s:6:\"action\";s:15:\"events-calendar\";s:6:\"params\";a:1:{s:8:\"calendar\";s:3:\"521\";}}}s:9:\"meta_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:9:\"user_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}}', '19', null);",
"INSERT INTO `content` VALUES ('516', 'page', null, null, null, null, null, null, '0', 'Contact Form', '1', 'Contact', '5', '', null, '<p>This page uses the contact module.&nbsp; For security purposes we have disabled mail from this site, but you can get a feel for how it works.</p>\r\n<p>For a live example check out <a href=\"http://forrestlyman.com/Contact\">this page</a>.</p>', null, null, null, '0', null, null, '1', '1218544310', '0', '0', 'O:8:\"stdClass\":4:{s:7:\"general\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:7:\"modules\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":3:{s:6:\"module\";s:7:\"contact\";s:6:\"action\";s:12:\"contact-form\";s:6:\"params\";a:6:{s:9:\"recipient\";s:0:\"\";s:5:\"email\";s:0:\"\";s:20:\"autoresponse_subject\";s:0:\"\";s:20:\"autoresponse_message\";s:0:\"\";s:14:\"successMessage\";s:0:\"\";s:12:\"errorMessage\";s:0:\"\";}}}s:9:\"meta_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:9:\"user_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}}', '10', null);",
"INSERT INTO `content` VALUES ('518', 'image_gallery', null, null, null, null, null, null, '0', 'Screenshots', '1', null, '7', '', null, '<p>This is some sample content.&nbsp; Each gallery has this field.</p>', null, null, null, '0', null, null, '1', '1218545215', '1', '1218546328', 'O:8:\"stdClass\":5:{s:7:\"general\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:7:\"modules\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:9:\"meta_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:9:\"user_data\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":0:{}}s:6:\"images\";O:13:\"DSF_Data_List\":1:{s:5:\"items\";O:8:\"stdClass\":2:{s:10:\"1218545256\";O:18:\"DSF_Resource_Image\":8:{s:9:\"', null, null);",
"INSERT INTO `content` VALUES ('519', 'newsItem', null, null, null, '', null, null, '0', 'Torture testing the system', '1', null, '8', '', '', '<p>I have gotten a lot of questions from people about just how large a site the Digitalus CMS can handle.</p>\r\n<p>This is a reasonable concern...usually flexibility and ease of use come at the expense of performance and scaleability.&nbsp; So I had a lazy Sunday morning to burn, and decided to see exactly how well the system does perform with a large site.</p>\r\n<h3>Setting up the testing database</h3>\r\n<p>I started by creating 10 folders.&nbsp; I added 10 folders in each and so on until I had 110,000 pages +/-.&nbsp; I inserted 200 words in each page.&nbsp; Don\\\'t try this at home!&nbsp; It would take 2.5 years for the average good typist.</p>\r\n<h3>So here are the stats:</h3>\r\n<ul>\r\n    <li>110, 000 pages</li>\r\n    <li>about 22,000,000 words</li>\r\n    <li>pages nested 5 levels deep</li>\r\n</ul>\r\n<h3>The results</h3>\r\n<p>I then went to the deepest darkest corner of the database:</p>\r\n<ul>\r\n    <li>Home\r\n    <ul>\r\n        <li>Folder 10\r\n        <ul>\r\n            <li>Folder 10\r\n            <ul>\r\n                <li>Folder 10<br />\r\n                <ul>\r\n                    <li>Folder 10<br />\r\n                    <ul>\r\n                        <li>Page 10</li>\r\n                    </ul>\r\n                    </li>\r\n                </ul>\r\n                </li>\r\n            </ul>\r\n            </li>\r\n        </ul>\r\n        </li>\r\n    </ul>\r\n    </li>\r\n</ul>\r\n<p>The page took <b>0</b><b>.74 seconds to load the first time</b>.&nbsp; Please keep in mind that the Digitalus Site Manager also includes advanced page caching (much thanks to Zend Cache) which is enabled by default.&nbsp; This saves the HTML page that the CMS generated in a certain kind of text file, so the database does not need to rebuild it each time.&nbsp; <b>The second time I loaded the page it took 0.019 seconds to load</b>!*</p>\r\n<h3>Other tests</h3>\r\n<p>100,000 page sites are fairly rare, but 5-10,000 pages are pretty common once a site has grown for a number of years with multiple contributors.&nbsp; The difference between this site loading its 150 +/- pages and a test site loading 10,000 was unnoticable.</p>\r\n<p><i>*note that I performed all of these test utilizing the Zend Db Profiler.&nbsp; </i></p>', '', null, null, '0', '1218513600', '0', '1', '1218547848', '1', '1218547898', null, null, null);",
"INSERT INTO `content` VALUES ('521', 'calendar', null, null, '522', null, null, null, '0', 'PHP Events', '1', null, '10', '', null, '<p>This is a little block of text that describes the calendar.</p>', null, null, null, '0', null, null, '1', '1218548292', '0', '0', null, null, null);",
"INSERT INTO `content` VALUES ('522', 'event', null, null, '521', null, null, null, '0', 'Zend Con 2008', '1', null, '11', '', null, '<p>The 4th Annual Zend/PHP conference will be held on September 15-18, 2008 in Santa Clara, California and will bring together PHP developers and business managers from around the world for three days of exceptional presentations and networking events. The theme of this year&rsquo;s conference is &ldquo;High Impact PHP&rdquo; and sessions will explore the many ways that developers are delivering breakthrough business advantages with their PHP applications.</p>', null, null, null, '0', '1221451200', '1221710400', '1', '1218548343', '1', '1218548418', null, null, null);",
"INSERT INTO `error_log` VALUES ('4741', 'http://demo-site.digitalus-projects.com/', '/public/scripts/reflection.js', '1218550075', null);",
"INSERT INTO `error_log` VALUES ('4742', 'http://demo-site.digitalus-projects.com/news', '/public/scripts/reflection.js', '1218550079', null);",
"INSERT INTO `error_log` VALUES ('4743', 'http://demo-site.digitalus-projects.com/', '/images/loadingAnimation.gif', '1218550083', null);",
"INSERT INTO `error_log` VALUES ('4744', null, '/favicon.ico', '1218550109', null);",
"INSERT INTO `error_log` VALUES ('4745', 'http://demo-site.digitalus-projects.com/getting-started', '/public/scripts/reflection.js', '1218550111', null);",
"INSERT INTO `error_log` VALUES ('4746', 'http://demo-site.digitalus-projects.com/getting-started', '/images/loadingAnimation.gif', '1218550137', null);",
"INSERT INTO `error_log` VALUES ('4747', 'http://demo-site.digitalus-projects.com/', '/public/scripts/reflection.js', '1225555246', null);",
"INSERT INTO `error_log` VALUES ('4748', 'http://demo-site.digitalus-projects.com/', '/images/loadingAnimation.gif', '1225555246', null);",
"INSERT INTO `traffic_log` VALUES ('65070', '/', '41.204.127.121', null, '1218549992', '2', '33', '8', '2008');",
"INSERT INTO `traffic_log` VALUES ('65071', '/news', '41.204.127.121', null, '1218549993', '2', '33', '8', '2008');",
"INSERT INTO `traffic_log` VALUES ('65072', '/public/scripts/reflection.js', '41.204.127.121', null, '1218550075', '2', '33', '8', '2008');",
"INSERT INTO `traffic_log` VALUES ('65073', '/public/scripts/reflection.js', '41.204.127.121', null, '1218550079', '2', '33', '8', '2008');",
"INSERT INTO `traffic_log` VALUES ('65074', '/images/loadingAnimation.gif', '41.204.127.121', null, '1218550083', '2', '33', '8', '2008');",
"INSERT INTO `traffic_log` VALUES ('65075', '/getting-started', '41.204.127.121', null, '1218550108', '2', '33', '8', '2008');",
"INSERT INTO `traffic_log` VALUES ('65076', '/favicon.ico', '41.204.127.121', null, '1218550108', '2', '33', '8', '2008');",
"INSERT INTO `traffic_log` VALUES ('65077', '/public/scripts/reflection.js', '41.204.127.121', null, '1218550111', '2', '33', '8', '2008');",
"INSERT INTO `traffic_log` VALUES ('65078', '/images/loadingAnimation.gif', '41.204.127.121', null, '1218550137', '2', '33', '8', '2008');",
"INSERT INTO `traffic_log` VALUES ('65079', '/', '72.43.53.86', null, '1225555245', '6', '44', '11', '2008');",
"INSERT INTO `traffic_log` VALUES ('65080', '/public/scripts/reflection.js', '72.43.53.86', null, '1225555246', '6', '44', '11', '2008');",
"INSERT INTO `traffic_log` VALUES ('65081', '/images/loadingAnimation.gif', '72.43.53.86', null, '1225555246', '6', '44', '11', '2008');",
"INSERT INTO `users` VALUES ('1', 'test', 'admin', 'admin@email.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'superadmin', '1,2,3,4,5,6');",
"INSERT INTO `users` VALUES ('23', 'testq', 'asgfas', 'frosty@email.com', 'c70fd4260c9eb90bc0ba9d047c068eb8', 'admin', '0');"
);

$demoUsername = 'demo_admin';
$demoPassword = 'uiEW879K2';
$demoDB = "digitalus_demo_site";

$link = mysql_connect('localhost', $demoUsername, $demoPassword);
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

foreach ($query as $sql){
	$result = mysql_query($sql, $link);
	
	if (!$result) {
		echo "Query failed: " . $sql . "<br />";
	    die('Invalid query: ' . mysql_error());
	}else{
		echo "Query OK: " . $sql . "<br />";
	}
}

mysql_close($link);
?>