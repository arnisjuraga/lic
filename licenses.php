<?php require_once('config.php'); ?>
<?php

$query_party_suppliers = "SELECT name, party_id FROM party WHERE type=2";
$party_suppliers = mysql_query($query_party_suppliers, $db) or die(mysql_error());
$row_party_suppliers = mysql_fetch_assoc($party_suppliers);
$totalRows_party_suppliers = mysql_num_rows($party_suppliers);


$query_party_customers = "SELECT name, party_id FROM party WHERE type=1";
$party_customers = mysql_query($query_party_customers, $db) or die(mysql_error());
$row_party_customers = mysql_fetch_assoc($party_customers);
$totalRows_party_customers = mysql_num_rows($party_customers);


$query_party_type = "SELECT * FROM party_type";
$party_type = mysql_query($query_party_type, $db) or die(mysql_error());
$row_party_type = mysql_fetch_assoc($party_type);
$totalRows_party_type = mysql_num_rows($party_type);

// Atlasam visas klienta iekārtas!!!
$sql = "SELECT d.device_id, d.id, d.description, d.hostname, p.name, o.name as owner_name
FROM devices d
LEFT JOIN invoices i ON d.invoice_id = i.invoice_id
LEFT JOIN party p ON i.customer_id = p.party_id
LEFT JOIN party o ON d.owner_id = o.party_id
";


$result_devices = mysql_query($sql, $db) or die(mysql_error());
$row_devices = mysql_fetch_assoc($result_devices);
//print_r($row_invoices);
$rows = mysql_num_rows($result_devices);


$sql = "SELECT i.invoice_id, i.date, i.set, i.number, cus.name as customer, sup.name as supplier from invoices i
left join party cus on i.customer_id=cus.party_id
left join party sup on i.supplier_id=sup.party_id";
$result_invoices_all = mysql_query($sql, $db) or die(mysql_error());


function software_menu() {
global $database_db, $db;


$query_software = "SELECT s.software_id, s.name as software_name, t.name as license_type  FROM software s left join  license_type t on s.license_type=t.license_type_id";
$software = mysql_query($query_software, $db) or die(mysql_error());
$row_software = mysql_fetch_assoc($software);
$totalRows_software = mysql_num_rows($software);

$html = "<select name='software_id_'>";
do {  
$html.="<option value='".$row_software['software_id']."' >".$row_software['software_name']." - ".$row_software['license_type']."</option>";

} while ($row_software = mysql_fetch_assoc($software));
$html.=" </select>";
return $html;      

}





?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>




<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LIC - Devices</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<? include("menu.php");?>

<form action="licenses_action.php" method="post" name="licence" id="license">
<?
//Atlasa konkrētu licenci, kuru tad arī editēsim.
$sql = "SELECT l.*, d.hostname, s.name  FROM licenses l
left join software s on s.software_id=l.software_id
left join devices d on d.device_id = l.device_id


where l.license_id=".@intval($_GET['license_id']);
//echo $sql; die();

$result = mysql_query($sql, $db) or die(mysql_error());
$a = mysql_fetch_assoc($result);
//print_r($a);
?>
  <table align="center">

    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><strong><?=$a['name'];?></strong>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Invoice:</td>
      <td><select name="invoice_id">
<? 
$sql = "SELECT * FROM invoices order by `set`,`number`";
$result = mysql_query($sql, $db) or die(mysql_error());
$row = mysql_fetch_assoc($result);
?>
     
        <option value="NULL">-- norādiet pavadzīmi --</option>      
        <?php  
do {  
?>
        <option value="<?php echo $row['invoice_id']?>" <?=($a['invoice_id']==$row['invoice_id']?"selected=\"selected\"":"");?> ><?php echo $row['set']." ".$row['number'];?></option>
        <?php
} while ($row = mysql_fetch_assoc($result));
?>
      
<option value="cits"  >-- jauna ---</option>            
      
      </select> Ja jauna: 
      
      <input type="text" name="new_set"  size="8" />
      <input type="text" name="new_number"  size="8" /></td>
      <td>Grāmatvedības dati</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>User:
        <select name="device_id" id="device_id">
          <option value="">-- device --</option>
          <?

$sql = "SELECT d.id, d.device_id, d.hostname, u.name, u.surname, dt.name as device_type FROM devices d 
left join users u on u.id=d.user_id
left join device_type dt on d.device_type=dt.id

order by device_type asc, u.name asc, u.surname ASC ";
//echo $sql."==sql";
$result = mysql_query($sql, $db) or die(mysql_error());
$row = mysql_fetch_assoc($result);
do {  
?>
          <option value="<?php echo $row['device_id']?>" <?=($a['device_id']==$row['device_id']?"selected=\"selected\"":"");?> ><?php echo $row['device_type'].": ".$row['name']." ".$row['surname']." - ".$row['hostname'];?></option>
          <?php
} while ($row = mysql_fetch_assoc($result));
?>
        </select><br />

        &nbsp;
Edit link:     
<? if($a['hostname']=='') $a['hostname']="HOSTNAME nav norādīts"; ?>
<?=($a['device_id']<>0)?("<a href='devices_edit.php?device_id={$a['device_id']}'>Edit Device {$a['hostname']}</a>"):(" Device nav piesaistīts");?>     </td>
      <td>Konts:
        <select name="konts" id="konts">
          <option value="0">-- konts --</option>
          <?

$sql = "SELECT id, name FROM list_bilances_konti order by id ASC ";
$result = mysql_query($sql, $db) or die(mysql_error());
$row = mysql_fetch_assoc($result);
do {  
?>
          <option value="<?php echo $row['id']?>" <?=($a['konts']==$row['id']?"selected=\"selected\"":"");?> ><?php echo $row['id']." - ".$row['name'];?></option>
          <?php
} while ($row = mysql_fetch_assoc($result));
?>
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Device description, technical etc.(readable):</td>
      <td><textarea name="description" id="description" cols="45" rows="5"><?=$a['description'];?></textarea></td>
      <td><input name="inv_id" type="text" id="inv_id" value="<?=$a['inv_id'];?>" size="32" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td><select name="status" id="status">
        <option value="0">-- Statuss --</option>
        <option value="1" <?=(1==$a['status']?"selected='selected'":" "); ?> >OK</option>
        <option value="2" <?=(2==$a['status']?"selected='selected'":" ");?> >Check LIC</option>
        <option value="3" <?=(3==$a['status']?"selected='selected'":" ");?> >Check LIC &amp; grāma.</option>
        <option value="4" <?=(4==$a['status']?"selected='selected'":" ");?> >Norakstīt.</option>
        <option value="5" <?=(5==$a['status']?"selected='selected'":" ");?> >Arhivēts</option>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="form" value="license" />
  <input type="hidden" name="license_id" value="<?=$a['license_id'];?>" />  
</form>

<div id="readroot" style="display: none"> <?=software_menu();?> <input id="license_count_" type="text" name="license_count_"  /> 

<input type="button" value=" - " onclick="this.parentNode.parentNode.removeChild(this.parentNode);" />
  
    

</div>
	

<br />
<table border="1">
<? $query_licenses = "SELECT l.license_id, s.name as sname, l.konts, l.inv_id, lt.name as ltype, i.number, i.set, i.date, l.status, i.invoice_id
from licenses l
left join software s on l.software_id=s.software_id
left join  license_type lt on s.license_type=lt.license_type_id

join  invoices i where i.invoice_id=l.invoice_id 
order by status asc, sname asc, l.license_id asc
";
$licenses = mysql_query($query_licenses, $db) or die(mysql_error());
$totalRows_licenses = mysql_num_rows($licenses);
?>



  <? 
  $temp=-1;
  while ($row44=mysql_fetch_assoc($licenses)) {;
  	
	if ($row44['status']<>$temp) {
		echo "<tr style='background-color: grey; color: white; font-weight: bold;'><td></td><td colspan=10>--- {$status[$row44['status']]} ---</td></tr>" ; $temp=$row44['status'];
	}

  	echo "<tr>" ; 
	
	foreach($row44  as $key => $val) { 
		
	if ($key=='number') echo  "<td><a href='invoices_edit.php?invoice_id={$row44['invoice_id']}'>$val</a></td>";

	else 	echo "<td> $val; </td> "; } 	
	
	
	echo "<td><a href=\"licenses.php?license_id=".$row44['license_id']."\">Edit</a></td>";
	echo "<td><a href=\"licenses_action.php?delete=1&license_id=".$row44['license_id']."\" onclick=\"javascript:return confirm('Tiks dzēsta LICENCE no sistēmas. Vai turpināt?')\">Delete</a></td>";

	echo "</tr>"; } 
   ?>    
</table>
</body>
</html>