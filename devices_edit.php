<?php require_once('config.php'); 
require_once('func.php');
?>
<?php


@$device_id=intval($_GET['device_id']);

$maxRows_Recordset1 = 10;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;


$query_Recordset1 = "SELECT * FROM invoices ORDER BY invoice_id ASC";
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $db) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;


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

// Atlasam VIENU device klienta iekārtas!!!
$sql = "SELECT d.device_id, d.user_id, d.inv_id, d.konts, d.device_type, d.id, d.description, d.hostname, p.name, i.set, 
d.owner_id, i.number, o.name as owner_name, 
i.invoice_id, d.status
FROM devices d
LEFT JOIN invoices i ON d.invoice_id = i.invoice_id
LEFT JOIN party p ON i.customer_id = p.party_id
LEFT JOIN party o ON d.owner_id = o.party_id
where d.device_id=$device_id ";
$result_device = mysql_query($sql, $db) or die(mysql_error());
$row_device = mysql_fetch_assoc($result_device);
print_r($row_invoices);
$rows = mysql_num_rows($result_device);


// Atlasam visas klienta iekārtas!!! 
/*
$sql = "SELECT d.device_id, d.user_id, d.inv_id, d.konts, d.device_type, d.id, d.description, d.hostname, p.name, o.name as owner_name, d.owner_id,i.number, i.set,
u.name as user_name, u.surname as user_surname
FROM devices d
LEFT JOIN invoices i ON d.invoice_id = i.invoice_id
LEFT JOIN users u ON d.user_id = u.id
LEFT JOIN party p ON i.customer_id = p.party_id
LEFT JOIN party o ON d.owner_id = o.party_id
";
$result_devices = mysql_query($sql, $db) or die(mysql_error());
$row_devices = mysql_fetch_assoc($result_devices);
////print_r($row_invoices);
$rows = mysql_num_rows($result_devices);
*/


$query_invoices = "SELECT * FROM invoices";
$invoices = mysql_query($query_invoices, $db) or die(mysql_error());
$row_invoices = mysql_fetch_assoc($invoices);
$totalRows_invoices = mysql_num_rows($invoices);

$query_licenses = "SELECT l.license_id, s.name as sname, lt.name as ltype, i.number, i.set, i.date from licenses l
left join software s on l.software_id=s.software_id
left join  license_type lt on s.license_type=lt.license_type_id

join  invoices i where i.invoice_id=l.invoice_id 
";
$licenses = mysql_query($query_licenses, $db) or die(mysql_error());
$totalRows_licenses = mysql_num_rows($licenses);



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
<title>LIC - Devices edit</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<? include("menu.php");?>
<form action="devices_action.php" method="post" name="device" id="device">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Invoice:</td>
      <td><select name="invoice_id">
        <option value="NULL">-- norādiet pavadzīmi --</option>
		<?php  
do {  
?>
<option value="<?php echo $row_invoices['invoice_id'];?>" 
<? 
	if($row_device['invoice_id']==$row_invoices['invoice_id'])  {
			echo 'selected="selected"'; 
			$xset=$row_device['set'];
			$xnumber=$row_device['number'];
			$xid=$row_device['invoice_id'];
	}
	
			
			?> >
<?php echo $row_invoices['set']." ".$row_invoices['number'];?></option>
        <?php
} while ($row_invoices = mysql_fetch_assoc($invoices));
?>
      </select> =
      <? if($row_device['invoice_id']>0) {?>
	  
	  <a href="invoice.php?id=<?=$xid;?>"><?php echo $xset." ".$xnumber;?></a>
      <? } ?>
      =</td>
      
      <td>Grāmatvedības dati</td> 
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Computer hostname</td>
      <td><input name="hostname" type="text" id="hostname" value="<?=$row_device['hostname'];?>" size="32" /></td>
      <td>Konts: 
        <select name="konts" id="konts">
          <option value="0">-- konts --</option>
          <?

$sql = "SELECT id, name FROM list_bilances_konti order by id ASC ";
$result = mysql_query($sql, $db) or die(mysql_error());
$row = mysql_fetch_assoc($result);
do {  
?>
          <option value="<?php echo $row['id']?>" <?=($row_device['konts']==$row['id']?"selected='selected'":"");?> ><?php echo $row['id']." - ".$row['name'];?></option>
          <?php
} while ($row = mysql_fetch_assoc($result));
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Computer description, technical etc.(readable):</td>
      <td><textarea name="description" id="description" cols="45" rows="5"><?=$row_device['description'];?></textarea></td>
      <td><input name="inv_id" type="text" id="inv_id" value="<?=$row_device['inv_id'];?>" size="32" /></td>
    </tr>
<tr>
  <td>Responsible user, user.</td>
      <td><select name="user_id" id="user_id">
<option value="0">-- lietotājs --</option>
<?

$sql = "SELECT name, surname, id FROM users order by name, surname";
$result = mysql_query($sql, $db) or die(mysql_error());
$row_users = mysql_fetch_assoc($result);
do {  
?>
        <option value="<?php echo $row_users['id']?>" <?=($row_device['user_id']==$row_users['id']?"selected='selected'":"");?> ><?php echo $row_users['name']." ".$row_users['surname'];?></option>
        
		
		<?php
} while ($row_users = mysql_fetch_assoc($result));
?>
      </select></td>
      <td>&nbsp;</td>
      
      


    <tr>
      <td>Device_type</td>
      <td><select name="device_type" id="device_type">
        <option value="0">-- iekārtas veids --</option>
        <?

$sql = "SELECT name, id FROM device_type order by name";
$result = mysql_query($sql, $db) or die(mysql_error());
$row_device_type = mysql_fetch_assoc($result);
do {  
?>
        <option value="<?php echo $row_device_type['id']?>" <?=($row_device['device_type']==$row_device_type['id']?"selected='selected'":" ");?> >
		<?php echo $row_device_type['name'];?></option>
        <?php
} while ($row_device_type = mysql_fetch_assoc($result));
?>
      </select></td>
      <td><select name="status" id="status">
      <option value="0">-- Statuss --</option>
      <option value="1" <?=(1==$row_device['status']?"selected='selected'":" "); ?> >OK</option>
      <option value="2" <?=(2==$row_device['status']?"selected='selected'":" ");?> >Check LIC</option>
      <option value="3" <?=(3==$row_device['status']?"selected='selected'":" ");?> >Check LIC & grāma.</option>
      <option value="4" <?=(4==$row_device['status']?"selected='selected'":" ");?> >Norakstīt.</option>      
      <option value="5" <?=(5==$row_device['status']?"selected='selected'":" ");?> >Arhivēts</option>      

      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Computer unique ID</td>
      <td><input name="id" type="text" id="id" value="<?=$row_device['id'];?>" size="32" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Owner &quot;at this moment&quot;</td>
      <td><select name="owner_id" id="owner_id">
        <?php  
do {  
?>
        <option value="<?php echo $row_party_customers['party_id']?>"
        <? if($row_device['owner_id']==$row_party_customers['party_id']) echo 'selected="selected"'; ?> >

		<?php echo $row_party_customers['name'];?> </option>
        <?php
} while ($row_party_customers = mysql_fetch_assoc($party_customers));
?>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update" /></td>
      <td>&nbsp;</td>
    </tr>
  </table> 
  <input type="hidden" name="MM_update" value="device" />
  <input type="hidden" name="device_id" value="<?=$row_device['device_id'];?>" />  

</form>

<div id="readroot" style="display: none"> <?=software_menu();?> <input id="license_count_" type="text" name="license_count_"  /> 

<input type="button" value=" - " onclick="this.parentNode.parentNode.removeChild(this.parentNode);" />
  
    

</div>
	
<? 
//atlasām OEM softus uz šīs PPR
$sql="select software.name, license_type.name as type, license_id from licenses
left join software on software.software_id=licenses.software_id
left join license_type on license_type.license_type_id=software.license_type

 where (device_id<1 OR device_id is NULL) and invoice_id = ".intval($row_device['invoice_id']);

$result = mysql_query($sql, $db) or @die(mysql_error());
$row_count = mysql_num_rows($result);
$row = mysql_fetch_assoc($result);

?>

<div style="width: 1120px;">

<div style="float:left; margin-right:10px;">
<div style="border:2px solid blue;margin:5px; padding:5px  ">

<h3 title="Uz tās pašas PPR, uz kuras ir ŠIS dators, ir arī šādi brīvie softi">Brīvās licences uz šīs PPR</h3>
<? if($row_count>0) { ?>

<form action="devices_action.php" method="post">

<input type="submit" value="Pievienot" align="right" />
<table>
<tr><th>Nosaukums</th><th>tips</th><th>V</th></tr>
<? $count=0;
if ($row) do { 	$count++;
?>
    <tr>
     <td bgcolor="#FFFFFF"><?php echo $row['name']; ?></td>
     <td bgcolor="#FFFFFF"><?php echo $row['type']; ?></td>     
        <td bgcolor="#FFFFFF"> <input type="checkbox" name ="license_<?=$count;?>" value="<?=$row['license_id'];?>"  /></td>                
      </tr>
    <?php 

	}
	 while ($row = mysql_fetch_assoc($result)); 

	 ?>
</table>
<input type="submit" value="Pievienot" align="right" />
<input type="hidden" name="MM_device_update" value="licenses" />
<input type="hidden" name="count" value="<?=$count;?>" />
<input type="hidden" name="device_id" value="<?=$device_id;?>" />
</form>

<? } 

else 
echo "Uz šīs PPR NAV citu/brīvu licenču!";
?>

</div>

<div style="border: red solid 2px; margin:5px; padding:5px ">

<form action="devices_action.php" method="post">
<div align="left">
  <h2>CITAS licences</h2>
NE OEM Licences, KURAS NAV uz šīs pavadzīmas. <br />
(NETIEK atlasītas Arhivētās licences!)</div>

<input type="submit" value="Pievienot"/>

<? 
$sql="select software.name, license_type.name as type, license_id from licenses
left join software on software.software_id=licenses.software_id
left join license_type on license_type.license_type_id=software.license_type

 where (device_id<1 OR device_id is NULL) and licenses.status<>5 and software.license_type<>1 and invoice_id<>".intval($row_device['invoice_id']);

$result = mysql_query($sql, $db) or @die(mysql_error());
$row = mysql_fetch_assoc($result);

?>

<table border=0 cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
<tr><td>-</td><td>-</td><td>-</td></tr>
<? $count=0;
if ($row) do { 	$count++;
?>
    <tr>
     <td bgcolor="#FFFFFF"><?php echo $row['name']; ?></td>
     <td bgcolor="#FFFFFF"><?php echo $row['type']; ?></td>     
        <td bgcolor="#FFFFFF"> <input type="checkbox" name ="license_<?=$count;?>" value="<?=$row['license_id'];?>"  /></td>                
        </tr>
    <?php 

	}
	 while ($row = mysql_fetch_assoc($result)); ?>
</table>
<input type="submit" value="Pievienot" align="right" />
<input type="hidden" name="MM_device_update" value="licenses_notoem" />
<input type="hidden" name="count" value="<?=$count;?>" />
<input type="hidden" name="device_id" value="<?=$device_id;?>" />
</form>

</div>






</div>


<div style="float:left; border: 2px solid green; margin:5px; padding:5px " >
<h2>Šim datoram piesaistītie softi</h2>

<? 
// Sekojošā selecta beigās bija šāds vēl:
//." and invoice_id = ".intval($row_device['invoice_id'])
$sql="select software.name, license_type.name as type, license_id from licenses
left join software on software.software_id=licenses.software_id
left join license_type on license_type.license_type_id=software.license_type

 where device_id=".$device_id;

$result = mysql_query($sql, $db) or die(mysql_error());
$row = mysql_fetch_assoc($result);

?>
<table border=0 cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
<tr><td>Dators:<?=$device_id;?></td><td>licence</td><td>tips</td></tr>
<? $count=0;
if ($row) do { 	$count++;
?>
    <tr>
     <td bgcolor="#ffffff">(id<?=$row['license_id'];?>) <?php echo $row['name']; ?></td>
     <td bgcolor="#ffffff"><?php echo $row['type']; ?></td>     
        <td bgcolor="#ffffff"><a href="licenses_action.php?remove=1&amp;license_id=<?=$row['license_id'];?>" onclick="javascript:return confirm('Vai dzēst licences piesaisti?')">remove</td></td>                
        </tr>
    <?php 

	}
	 while ($row = mysql_fetch_assoc($result));
else echo "<tr><td colspan=3 bgcolor=white>Šim datoram nav pieškirtu softu</td></tr>";?>
</table>


</div>

<div style="border: 2px solid yellow; float:left; width: 640px;margin:5px; padding:5px ">
<h2>Datoru saraksts</h2>
<? echo echo_devices_1();?>
</div>

</div>

</body>
</html>