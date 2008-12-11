<?php
require 'Classes/Environment.php';
$env = new Environment();
?>

<p>Thanks for choosing Digitalus CMS.  Before we get started we need to check a few things on your system...</p>

<?php
//test the environment
$env->checkSystem();
if($env->hasErrors()) {
    $errors = $env->getErrors();
?>

<p>Sorry, your server is not set up properly to run Digitalus CMS.  Here are the errors that we encountered:</p>

<?php foreach ($errors as $error) {?>
<p class="error test"><?php echo $error; ?></p>
<?php }}else{?>
<p class="passed test">Server environment OK</p>
<p>Good, your server is set up right!  Now we can install your cms.</p>

<?php 
require("install_cms.php");
} ?>