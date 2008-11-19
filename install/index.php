<?php

require_once('classes/Database.php');
require_once('classes/Environment.php');
require_once('classes/Source.php');
require_once('classes/Tools.php');


echo "Checking server environment...";

$env = new Environment();
$env->checkSystem();

echo "server environment OK<br />";

echo "Installing database...";

$db = new Database();
$db->connect();
$db->install();

echo "database installed OK<br />";
$source = new Source();
$source->get();
$source->install();