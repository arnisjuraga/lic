<?php require_once('config.php'); ?>
<?php
//print_r($_SESSION);


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO invoices (`date`, `set`, `number`, supplier_id, customer_id) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['date'], "text"),
                       GetSQLValueString($_POST['set'], "text"),
                       GetSQLValueString($_POST['number'], "text"),
                       GetSQLValueString($_POST['supplier_id'], "int"),
                       GetSQLValueString($_POST['customer_id'], "int"));

  
  $Result1 = mysql_query($insertSQL, $db) or die(mysql_error());

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

//delete dzēst invoice pēc invoice_id
/*
if ((isset($_GET['invoice_id'])) && ($_GET['invoice_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM invoices WHERE invoice_id=%s",
                       GetSQLValueString($_GET['invoice_id'], "int"));

  
  $Result1 = mysql_query($deleteSQL, $db) or die(mysql_error());
}
*/


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form4")) {
  //print_r($_POST);
for ($i=1; $i<=$_POST['software_menu_counter'];$i++) {
  

  $insertSQL = sprintf("INSERT INTO licenses (invoice_id, software_id) VALUES (%s, %s)",
                       GetSQLValueString($_POST['invoice_id'], "int"),
                       GetSQLValueString($_POST['software_id_'.$i], "int"));
					   //echo "<br />";

  //
	if ($_POST['license_count_'.$i]>0)  
		do { 
			//echo $_POST['license_count_'.$i]."<br />";
			
			$Result1 = mysql_query($insertSQL, $db) or die(mysql_error());
			$_POST['license_count_'.$i]--;
	} while ($_POST['license_count_'.$i] > 0) ;



  
}
  
  
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
  $insertSQL = sprintf("INSERT INTO party (party_id, name, type) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['party_id'], "int"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['type'], "int"));

  
  $Result1 = mysql_query($insertSQL, $db) or die(mysql_error());
}

$maxRows_Recordset1 = 10;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;


//atlasam pavadzīmi, kura TIKKO IEVIETOTA AR NEPILNIEM DATIEM, 
// vai arī pavadzīmi, no GET linka.
if (isset($_SESSION['last_invoice_inserted'])) 
	$invoice_id=$_SESSION['last_invoice_inserted'];
else 
	@$invoice_id=intval($_GET['invoice_id']);
						 
$sql = "SELECT * FROM invoices where invoice_id=".$invoice_id;
$result = mysql_query($sql, $db) or die(mysql_error());
$row_invoice = mysql_fetch_assoc($result);
 





/*if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
//  $all_Recordset1 = mysql_query($query_Recordset1);
//  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;
*/

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


$query_invoices = "SELECT * FROM invoices order by `set`, `number`";
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


$query_software = "SELECT s.software_id, s.name as software_name, t.name as license_type  FROM software s left join  license_type t on s.license_type=t.license_type_id order by software_name ASC";
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
<title>LIC - Invoices</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body onload="init()">
<? include("menu.php");?><br />
<br />

<? 
//print_r($_SERVER); 
?>

<? if(isset($_SESSION['last_invoice_inserted']) && ($_SESSION['last_invoice_inserted']>0)) 
echo "<span class='red_big'>Ir pievienota jauna pavadzīme, kurai nav precizēti pārējie dati!!!</span>"; ?>

<form action="invoice_action.php" method="post" name="invoice_edit" id="invoice_edit">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Date:</td>
      <td><input type="text" name="date" value="<?=$row_invoice['date'];?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Set:</td>
      <td><input type="text" name="set" value="<?=@$row_invoice['set'];?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Number:</td>
      <td><input type="text" name="number" value="<?=@$row_invoice['number'];?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Supplier_id:</td>
      <td><select name="supplier_id">
      <option value="0">-- Izvēlieties --- </option>
        <?php 
do {  
?>
        <option value="<?php echo $row_party_suppliers['party_id']?>" <?=($row_invoice['supplier_id']==$row_party_suppliers['party_id']?"selected=\"selected\"":" ");?> ><?php echo $row_party_suppliers['name']?></option>
        <?php
} while ($row_party_suppliers = mysql_fetch_assoc($party_suppliers));
?>
      </select>
      </td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Customer_id:</td>
      <td><select name="customer_id">
        <?php 

$query_party_customers = "SELECT name, party_id FROM party WHERE type=1";
$party_customers = mysql_query($query_party_customers, $db) or die(mysql_error());
$row_party_customers = mysql_fetch_assoc($party_customers);
$totalRows_party_customers = mysql_num_rows($party_customers);
		
		
		
do {  
?>
        <option value="<?php echo $row_party_customers['party_id']?>" ><?php echo $row_party_customers['name']?></option>
        <?php
} while ($row_party_customers = mysql_fetch_assoc($party_customers));
?>
      </select>
      </td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update invoice" /></td>
    </tr>
  </table>
  <input type="hidden" name="form" value="invoice_edit" />
  <input type="hidden" name="invoice_id" value="<?=$invoice_id;?>" />  
</form>
<div id="readroot" style="display: none"> <?=software_menu();?> <input id="license_count_" type="text" name="license_count_"  /> 

<input type="button" value=" - " onclick="this.parentNode.parentNode.removeChild(this.parentNode);" />
  
    

</div>
	 

<p>Pavadzīmju saraksts:</p>
<table border="0" bgcolor="#CCCCCC" cellspacing="1" cellpadding="1">
  <? while ($row=mysql_fetch_assoc($result_invoices_all)) {;
  	if (@$th==0) {
		echo "<tr>";
		foreach($row  as $key => $val ) { echo "<td  bgcolor=\"#FFFFFF\"> $key </td>\n\r";  }
		echo"<td>action</td></tr>";
	}
	$th=1;
  	
  	echo "<tr>" ; 
	
	
	
		foreach($row as $val) { echo "<td  bgcolor=\"#FFFFFF\"> $val; </td> "; } 
	echo "<td  bgcolor=\"#FFFFFF\"><a href='invoices_edit.php?invoice_id=".$row['invoice_id']."'>Edit</a></td>  </tr>"; } 
   ?>    
</table>


<table border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
  <tr>
    <td>name</td>
    <td>party_id</td>
  </tr>
  <?php do { ?>
    <tr>
      <td bgcolor="#FFFFFF"><?php echo $row_party_suppliers['name']; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_party_suppliers['party_id']; ?></td>
    </tr>
    <?php } while ($row_party_suppliers = mysql_fetch_assoc($party_suppliers)); ?>
</table>
<br />
<table border="1">
  <? while ($row44=mysql_fetch_assoc($licenses)) {;
  	echo "<tr>" ; foreach($row44  as $key => $val) { echo "<td> $val; </td> "; } 
	echo "</tr>"; } 
   ?>    
</table>
</body>
</html>
<?php
@mysql_free_result($Recordset1);
@mysql_free_result($party_suppliers);
@mysql_free_result($party_customers);
@mysql_free_result($party_type);
@mysql_free_result($invoices);
@mysql_free_result($software);
?>
