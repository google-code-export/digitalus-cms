<?php
if (file_exists('./install/install.php')) {
    require './install/install.php';
} else {
    require './application/bootstrap.php';
}
?>