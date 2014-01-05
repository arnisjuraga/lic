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
<form action="software_action.php" method="post" name="software" id="software">
  <table border="0" align="center" cellpadding="2">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Software name</td>
      <td><input name="name" type="text" id="name" value="" size="32" /></td>
    </tr>
    <tr valign="top">
      <td align="right" valign="baseline" nowrap="nowrap">Software type</td>
      <td valign="baseline">
        
        
  <select name="type" id="type">
    <option value="0">-- software type --</option>
    <?

$sql = "SELECT * FROM license_type";
$result = mysql_query($sql, $db) or die(mysql_error());
$row = mysql_fetch_assoc($result);
do {  
?>
    <option value="<?php echo $row['license_type_id']?>" ><?php echo $row['name'];?></option>
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
  <input type="hidden" name="MM_insert" value="software" />
</form>

<br />


<?php 
//Atlasām konkrētas licences instalācijas, piesaistes pa datoriem
$sql = "SELECT s.name as sname, l.name, s.software_id from software s
left join license_type l on l.license_type_id=s.license_type 
order by sname asc";
$result = mysql_query($sql, $db) or die(mysql_error());
$count = mysql_num_rows($result);
if ($count>0) {
?>
<br />
<br />
<table border="1">
  <?
  	$th=0;
  	while ($row=mysql_fetch_assoc($result)) {
	if ($th==0) foreach($row  as $key => $val ) { echo "<td> <strong>$key</strong> </td> ";  }
	$th=1;
  	echo "<tr>" ; 
	foreach($row  as $key => $val) 
		if($key<>'software_id')
		{ echo "<td> $val &nbsp;</td>"; } 
		else 
			echo "<td bgcolor='#FFFFFF'><a href='action.php?edit_software&amp;software_id=".$row['software_id']."'>Edit </a>&nbsp;<a href='action.php?delete_software&amp;software_id=".$row['software_id']."' onclick='javascript:return confirm(\"dzēst?\")'>Dzēst</a></td>";
		
	
	echo "</tr>"; } 
   ?>    
</table>
<?php } ?>

<br />
</body>
</html>