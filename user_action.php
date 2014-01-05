<?php 
//print_r($_POST);
require_once('config.php');




if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "user")) {
$sql = sprintf("INSERT INTO users (name, surname) VALUES (%s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['surname'], "text"));
//echo $sql;
$result = mysql_query($sql, $db) or die(mysql_error());
header("Location: ".$_SERVER['HTTP_REFERER']);	
}





?> 
