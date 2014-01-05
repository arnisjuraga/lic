<?php 
require_once('config.php');
require_once('func.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LIC - Devices</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.red {
	color: #F00;
}
-->
</style>
</head>

<body>
<? include("menu.php");?>
<form action="user_action.php" method="post" name="user" id="user">
  <table border="0" align="center" cellpadding="2">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">User name</td>
      <td><input name="name" type="text" id="name" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">User surname</td>
      <td><input name="surname" type="text" id="surname" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Save" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="user" />
</form>

<br />


<?php 
//Atlasām konkrētas licences instalācijas, piesaistes pa datoriem
$sql = "SELECT name, surname, id from users

order by name, surname ";
$result = mysql_query($sql, $db) or die(mysql_error());
$count = mysql_num_rows($result);
if ($count>0) {
?>
<br />
<br />
<table border="1">
  <? while ($row=mysql_fetch_assoc($result)) {;
  	echo "<tr>" ; foreach($row  as $key => $val) 
	if ($key<>'id'){ echo "<td> $val &nbsp</td>"; } 
	else 

	echo "<td bgcolor='#FFFFFF'><a href='action.php?edit_user&amp;id=".$row['id']."'>Edit </a>&nbsp;<a href='action.php?delete_user&amp;id=".$row['id']."' onclick='javascript:return confirm(\"dzēst?\")'>Dzēst</a></td>";
	
	
	echo "</tr>"; } 
   ?>    
</table>
<?php } ?>

<br />



</body>
</html>