<?php 

require_once('config.php');
require_once('func.php');

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

$query_invoices = "SELECT * FROM invoices order by `set`,`number`";
$invoices = mysql_query($query_invoices, $db) or die(mysql_error());
$row_invoices = mysql_fetch_assoc($invoices);

$totalRows_invoices = mysql_num_rows($invoices);

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
.red {
	color: #F00;
}
-->
</style>
</head>

<body onload="init()">
<? include("menu.php");?>
<form action="devices_action.php" method="post" name="device" id="device">
  <table border="0" align="center" cellpadding="2">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Invoice:</td>
      <td><select name="invoice_id">
        <option value="NULL">-- norādiet pavadzīmi --</option>      
        <?php  
do {  
?>
        <option value="<?php echo $row_invoices['invoice_id']?>" ><?php echo $row_invoices['set']." ".$row_invoices['number'];?></option>
        <?php
} while ($row_invoices = mysql_fetch_assoc($invoices));
?>
      
<option value="cits"  >-- jauna ---</option>            
      
      </select> Ja jauna: 
      
      <input type="text" name="new_set"  size="8" />
      <input type="text" name="new_number"  size="8" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Datora &quot;hostname&quot;</td>
      <td><input name="hostname" type="text" id="hostname" value="" size="32" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="top">
      <td align="right"><br />
        Iekārtas apraksts, <br />
      tehniskais apraksts, <br />      <span class="red">cena</span>:</td>
      <td><textarea name="description" id="description" cols="45" rows="5"></textarea></td>
      <td><br />
        <strong>        Grāmatvedības dati</strong><br />
        Konts:
<select name="konts" id="konts">
            <option value="0">-- konts --</option>
            <?

$sql = "SELECT id, name FROM list_bilances_konti order by id ASC ";
$result = mysql_query($sql, $db) or die(mysql_error());
$row = mysql_fetch_assoc($result);
do {  
?>
            <option value="<?php echo $row['id']?>" ><?php echo $row['id']." - ".$row['name'];?></option>
            <?php
} while ($row = mysql_fetch_assoc($result));
?>
          </select>
        <br />
        Inventerizācijas ID:
<input name="inv_id" type="text" id="inv_id" size="16" />
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Vienādo iekārtu skaits:</td>
      <td><input style="border: 1px solid red; padding: 1px; padding-left: 5px" name="amount" type="text" id="amount" value="1" size="1" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Device unique ID</td>
      <td><input name="id" type="text" id="id" value="" size="32" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Responsible user, user.</td>
      <td><select name="user_id" id="user_id">
<option value="0">-- lietotājs --</option>
<?

$sql = "SELECT name, surname, id FROM users order by name, surname";
$result = mysql_query($sql, $db) or die(mysql_error());
$row_users = mysql_fetch_assoc($result);
do {  
?>
        <option value="<?php echo $row_users['id']?>" ><?php echo $row_users['name']." ".$row_users['surname'];?></option>
        
		
		<?php
} while ($row_users = mysql_fetch_assoc($result));
?>
      </select></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Iekārtas veids</td>
      <td><select name="device_type" id="device_type">
        <option value="0">-- iekārtas veids --</option>
        <?

$sql = "SELECT name, id FROM device_type order by name";
$result = mysql_query($sql, $db) or die(mysql_error());
$row_device_type = mysql_fetch_assoc($result);
do {  
?>
        <option value="<?php echo $row_device_type['id']?>"  > <?php echo $row_device_type['name'];?></option>
        <?php
} while ($row_device_type = mysql_fetch_assoc($result));
?>
      </select></td>
      <td valign="baseline">&nbsp;</td> 
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Īpašnieks (šobrīd):</td>
      <td><select name="owner_id" id="owner_id">
        <?php  
do {  
?>
        <option value="<?php echo $row_party_customers['party_id']?>" ><?php echo $row_party_customers['name']?></option>
        <?php
} while ($row_party_customers = mysql_fetch_assoc($party_customers));
?>
      </select></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="device" />
</form>


<div id="readroot" style="display: none"> <?=software_menu();?> <input id="license_count_" type="text" name="license_count_"  /> 

<input type="button" value=" - " onclick="this.parentNode.parentNode.removeChild(this.parentNode);" />
  
    

</div>
	
Filtrēt pēc: Īpašnieks 
<form action="" name="filter" method="get">
<select name="filter_owner_id" id="owner_id">
        <?php  		
$query_party_customers = "SELECT name, party_id FROM party WHERE type=1";
$party_customers = mysql_query($query_party_customers, $db) or die(mysql_error());
$row_party_customers = mysql_fetch_assoc($party_customers);
$totalRows_party_customers = mysql_num_rows($party_customers);

?>
<option value="0">-- rādīt visus --</option>
<?
do {  
if($row_party_customers['party_id']==intval($_GET['filter_owner_id'])) $s="selected=\"selected\"";
												  else $s="";
?>
        <option  value="<?php echo $row_party_customers['party_id']?>" <?=$s;?> ><?php echo $row_party_customers['name']?></option>
        <?php
} while ($row_party_customers = mysql_fetch_assoc($party_customers));
?>
      </select>
      <input type="submit" value="Atlasīt" onclick="submit" />
      </form>
      
      
<?
$row_party_customers = mysql_fetch_assoc($party_customers);
//var_dump($row_party_customers);
//$f=intval($_GET['filer_owner_id']);
echo echo_devices_1();
?>


<br />
<br />



</body>
</html>