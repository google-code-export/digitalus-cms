<?php 
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" type="text/css" href="./install/install.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Digitalus CMS Installer</title>
</head>

<body>
    <div id='wrapper'>
        <h1>Digitalus CMS Installer</h1>
        <?php
        if(file_exists('./install/installation_ok.txt')) {
            require('remove_installer.php');
        }else{
            if(isset($_GET['step'])) {
                $step = intval($_GET['step']);
            }else{
                $step = 1;
            }
            
            switch ($step) {
                case 1:
                    require("check_enviroment.php");
                    break;
                case 2:
                    require("install_cms.php");
                    break;
                case 3:
                    require("finish.php");
                    break;
            }
        }
        ?>
        <div id="footer">
        	<p><a href="http://digitaluscms.com"><img src="./images/logo.png" /></a></p>
        <p>For help installing Digitalus CMS check out our site: <a href="http://digitaluscms.com">http://digitaluscms.com</a></p>
        	</div>
    </div>
</body>
</html>
