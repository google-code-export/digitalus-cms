<?php 
require 'Classes/Database.php';
unset($errors);
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
    //fetch post
   $db_name = $_POST['db_name'];
   $db_host = $_POST['db_host'];
   $db_username = $_POST['db_username'];
   $db_password = $_POST['db_password'];
   
   //validate the data passed
   if(empty($db_name)) {$errors[] = "The database name is required";} 
   if(empty($db_host)) {$errors[] = "The database host is required";} 
   if(empty($db_username)) {$errors[] = "The database username is required";} 
   
   if(!isset($errors)) {
       $db = new Database($db_name, $db_host, $db_username, $db_password);
       $result = $db->install();
       if($result === true) {
           header("location: " . $_SERVER['PHP_SELF'] . "?step=3");
       }else{
           $errors = $db->getErrors();
       }
   }
}
if($errors) {?>
<p>Sorry, we were not able to install the CMS.  The following errors occured:</p>

<?php 
foreach ($errors as $error) {
echo "<p class='error test'>{$error}</p>";
}
}
?>
<form action='<?php echo $_SERVER['PHP_SELF']?>?step=2' method="post" id="install">
    <p>Please enter the connection information for your database here:</p>
    <div class="formRow">
    	<label>Database Name:</label>
    	<input type="text" name="db_name" value="<?php echo $db_name;?>" />
		<br class="clear" />
	</div>
    <div class="formRow">
        <label>Database Host:</label>
        <input type="text" name="db_host" value="<?php echo $db_host;?>" />
		<br class="clear" />
	</div>
    <div class="formRow">
        <label>Database Username:</label>
        <input type="text" name="db_username" value="<?php echo $db_username; ?>" />
		<br class="clear" />
	</div>
    <div class="formRow">
        <label>Database Password:</label>
        <input type="password" name="db_password" value="<?php echo $db_password;?>" />
		<br class="clear" />
	</div>
    <div class="formRow">
        <label>&nbsp;</label>
        <input type="submit" value="Install Digitalus CMS" />
		<br class="clear" />
	</div>
</form>
<?php
/*
require 'Classes/Database.php';
$db = new Database();

$db = new Database();
$db->connect();
$db->install();
echo "database installed OK<br />";
*/