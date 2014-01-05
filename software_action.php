<?php 
require_once('config.php');




if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "software")) {
$sql = sprintf("INSERT INTO software (name, license_type) VALUES (%s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['type'], "int"));
$result = mysql_query($sql, $db) or die(mysql_error());
header("Location: ".$_SERVER['HTTP_REFERER']);	
}

?> 
