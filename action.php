<?php 
//print_r($_POST);
require_once('config.php');

if (isset($_GET['delete_user']) && isset($_GET['id']))
	{
	$sql="delete from users where id=".intval($_GET['id'])." limit 1";
	$result = mysql_query($sql, $db) or die(mysql_error());	
	header("Location: ".$_SERVER['HTTP_REFERER']);	
	}
	
	
if (isset($_GET['delete_software']) && isset($_GET['software_id']))
	{
	$sql="delete from software where software_id=".intval($_GET['software_id'])." limit 1";
	$result = mysql_query($sql, $db) or die(mysql_error());	
	header("Location: ".$_SERVER['HTTP_REFERER']);	
	}
	
if (isset($_GET['delete_party']) && isset($_GET['party_id']))
	{
	$sql="delete from party where party_id=".intval($_GET['party_id'])." limit 1";
	$result = mysql_query($sql, $db) or die(mysql_error());	
	header("Location: ".$_SERVER['HTTP_REFERER']);	
	}	
	



?> 
