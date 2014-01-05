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


<script type="text/javascript">
<!--

var counter = 0;

function init() {
	document.getElementById('moreFields').onclick = moreFields;
	moreFields();

}

function moreFields() {
	counter++;
	software_menu_counter=document.getElementById('software_menu_counter');
	software_menu_counter.value=counter;
	
	var newFields = document.getElementById('readroot').cloneNode(true);
	newFields.id = '';
	newFields.style.display = 'block';
	var newField = newFields.childNodes;
	for (var i=0;i<newField.length;i++) {
		var theName = newField[i].name
		if (theName)
			newField[i].name = theName + counter;
	}
	var insertHere = document.getElementById('writeroot');
	insertHere.parentNode.insertBefore(newFields,insertHere);
}

// -->
</script>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LIC - Devices</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.align_left {
	text-align: left;
}
-->
</style>
</head>

<body onload="init()">
<? include("menu.php");?>



<br />
<br />

<table border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td><?php 
//Atlasām visas  uzņēmuma licenču veidus, KURI NAV arhivēti.
if (isset($_GET['licence'])) $lic=intval($_GET['licence']); 
else $lic=0;
$query_licenses = "SELECT s.software_id, s.name as sname, lt.name as ltype from licenses l
left join software s on l.software_id=s.software_id
left join  license_type lt on s.license_type=lt.license_type_id

where l.status <> 5

group by sname, ltype
order by sname asc

";
$licenses = mysql_query($query_licenses, $db) or die(mysql_error());
$totalRows_licenses = mysql_num_rows($licenses);
if ($totalRows_licenses>0) {
?>
<br />
<br />
<table border="1">
  <? while ($row44=mysql_fetch_assoc($licenses)) {;
  	echo "<tr>" ; 
		foreach($row44  as $key => $val) { echo "<td> $val; </td> "; } 
			echo "<td><a href='licences.php?licence=".$row44['software_id']."'>Apskatīt piesaistes</a></td>";
	echo "</tr>"; } 
   ?>    
</table>
<?php } ?>&nbsp;</td>
  <td align="left" valign="top" class="align_left">
  
  Licences, kas NAV arhivēts, un kas ir uz šiem datoriem.
<?php 
//Atlasām konkrētas licences instalācijas, piesaistes pa datoriem
if (isset($_GET['licence'])) $lic=intval($_GET['licence']);
else $lic=0;
$sql = "SELECT l.license_id, s.name as sname, lt.name as ltype, 
d.hostname,
i.set, i.number, i.invoice_id,
d.device_id from licenses l
left join software s on l.software_id=s.software_id
left join license_type lt on s.license_type=lt.license_type_id
left join devices d on l.device_id=d.device_id
left join invoices i on l.invoice_id=i.invoice_id
where s.software_id=$lic
and l.status <>5
order by d.hostname
";
//echo $sql;
$result = mysql_query($sql, $db) or die(mysql_error());
$totalRows_licenses = mysql_num_rows($result);
if ($totalRows_licenses>0) {
?>
<br />
<br />
<table border="1">
  <? while ($row=mysql_fetch_assoc($result)) {;
  	echo "<tr>" ; 
	echo "<td>{$row['license_id']}</td>"; 
	echo "<td>{$row['sname']}</td>"; 
	echo "<td>{$row['ltype']}</td>"; 	
	echo "<td><a href='invoice.php?id={$row['invoice_id']}'> {$row['set']} {$row['number']}</a></td>"; 	

	echo "<td><a href='licenses.php?license_id={$row['license_id']}'>Mainīt licenci</a></td>"; 

	if($row['device_id']>0) echo "<td>Device: <a href='devices_edit.php?device_id={$row['device_id']}'>{$row['hostname']}</a></td>"; 	
	else echo "<td style='color:red; font-weight: bold;'>NAV pieaistīta IEKĀRTA!</td>";

echo "</tr>"; } 
   ?>    
</table>
<?php } ?>
<br />
<b>Licences, kas IR arhivētas, bet kas IR piesaistītas datoriem.</b>
<?php 
if (isset($_GET['licence'])) $lic=intval($_GET['licence']);
else $lic=0;
$sql = "SELECT l.license_id, s.name as sname, lt.name as ltype, 
d.hostname,
i.set, i.number, i.invoice_id,
d.device_id from licenses l
left join software s on l.software_id=s.software_id
left join license_type lt on s.license_type=lt.license_type_id
left join devices d on l.device_id=d.device_id
left join invoices i on l.invoice_id=i.invoice_id
where s.software_id=$lic
and l.status =5
order by d.hostname
";
//echo $sql;
$result = mysql_query($sql, $db) or die(mysql_error());
$totalRows_licenses = mysql_num_rows($result);
if ($totalRows_licenses>0) {
?>

<br />
<table border="1">
  <? while ($row=mysql_fetch_assoc($result)) {;
  	echo "<tr>" ; 
	echo "<td>{$row['license_id']}</td>"; 
	echo "<td>{$row['sname']}</td>"; 
	echo "<td>{$row['ltype']}</td>"; 	
	echo "<td><a href='invoice.php?id={$row['invoice_id']}'> {$row['set']} {$row['number']}</a></td>"; 	

	echo "<td><a href='licenses.php?license_id={$row['license_id']}'>Mainīt licenci</a></td>"; 

	if($row['device_id']>0) echo "<td>Device: <a href='devices_edit.php?device_id={$row['device_id']}'>{$row['hostname']}</a></td>"; 	
	else echo "<td style='color:red; font-weight: bold;'>NAV pieaistīta IEKĀRTA!</td>";

echo "</tr>"; } 
   ?>    
</table>
<?php } ?>





DATORI, kuri NAV arhivēti, un kuriem nav šis:




<?php 
//Atlasām konkrētas licences instalācijas, piesaistes pa datoriem
if (isset($_GET['licence'])) $lic=intval($_GET['licence']);
else $lic=0;
$sql = "SELECT d.hostname, d.device_id
from devices d

	where  NOT EXISTS (
SELECT d.hostname from licenses l where d.device_id=l.device_id  
	and l.software_id=$lic
	) and (d.device_type=1 OR d.device_type=9)  and d.status<>5
	order by d.hostname asc

";
//echo $sql;

$result = mysql_query($sql, $db) or die(mysql_error());
$totalRows_licenses = mysql_num_rows($result);
if ($totalRows_licenses>0) {
?>
<br />
<br />

  <? while ($row=mysql_fetch_assoc($result)) {
	  	echo "<a href='devices_edit.php?device_id={$row['device_id']}'>{$row['device_id']} - {$row['hostname']}</a>;<br /> ";
		} 
   ?>    

<?php } ?>
















&nbsp;</td>
  </tr>
</table>




</body>
</html>