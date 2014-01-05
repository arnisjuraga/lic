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
<form action="customer_action.php" method="post" name="customer" id="customer">
  <table border="0" align="center" cellpadding="2">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Customer name</td>
      <td><input name="name" type="text" id="name" value="" size="32" /></td>
    </tr>
    <tr valign="top">
      <td align="right" valign="baseline" nowrap="nowrap">Responsible user, user.</td>
      <td valign="baseline">
        
        
  <select name="type" id="type">
    <option value="0">-- customer type --</option>
    <?

$sql = "SELECT * FROM party_type";
$result = mysql_query($sql, $db) or die(mysql_error());
$row = mysql_fetch_assoc($result);
do {  
?>
    <option value="<?php echo $row['party_type_id']?>" ><?php echo $row['name'];?></option>
    <?php
} while ($row = mysql_fetch_assoc($result));
?>
  </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Save" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="customer" />
</form>

<br />
<br />



<?php 
//Atlasām konkrētas licences instalācijas, piesaistes pa datoriem
$sql = "SELECT p.name, t.name as tname, p.party_id from party p
left join party_type t on p.type=t.party_type_id
order by name asc";
$result = mysql_query($sql, $db) or die(mysql_error());
$count = mysql_num_rows($result);
if ($count>0) {
?>
<br />
<br />
<table border="1">
  <? while ($row=mysql_fetch_assoc($result)) {;
  	echo "<tr>" ; 
	foreach($row  as $key => $val) 
		if($key<>'party_id')
		{ echo "<td> $val &nbsp;</td>"; } 
		else 
			echo "<td bgcolor='#FFFFFF'><a href='action.php?edit_party&amp;party_id=".$row['party_id']."'>Edit </a>&nbsp;<a href='action.php?delete_party&amp;party_id=".$row['party_id']."' onclick='javascript:return confirm(\"dzēst?\")'>Dzēst</a></td>";
	
	
	echo "</tr>"; } 
   ?>    
</table>
<?php } ?>




</body>
</html>