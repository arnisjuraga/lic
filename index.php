<?php require_once('config.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

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

if ((isset($_GET['invoice_id'])) && ($_GET['invoice_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM invoices WHERE invoice_id=%s",
                       GetSQLValueString($_GET['invoice_id'], "int"));

  
  $Result1 = mysql_query($deleteSQL, $db) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form4")) {
  //print_r($_POST);
for ($i=1; $i<=$_POST['software_menu_counter'];$i++) {
  

  $insertSQL = sprintf("INSERT INTO licenses (invoice_id, software_id) VALUES (%s, %s)",
                       @GetSQLValueString($_POST['invoice_id'], "int"),
                       @GetSQLValueString($_POST['software_id_'.$i], "int"));
					   //echo "<br />";

  //
	if (isset($_POST['license_count_'.$i]) && ($_POST['license_count_'.$i]>0))  
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


$query_party_suppliers = "SELECT name, party_id FROM party WHERE type=2 order by name asc";
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
global $db;
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
<? include("menu.php");?>

<form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Date:</td>
      <td><input type="text" name="date" value="" size="32" class="datepicker" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Set:</td>
      <td><input type="text" name="set" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Number:</td>
      <td><input type="text" name="number" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Supplier_id:</td>
      <td><select name="supplier_id">
      <option value="0">-- Izvēlieties --- </option>
        <?php 
do {  
?>
        <option value="<?php echo $row_party_suppliers['party_id']?>" ><?php echo $row_party_suppliers['name']?></option>
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
      <td><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2" />
</form>
<div id="readroot" style="display: none"> <?=software_menu();?> <input id="license_count_" type="text" name="license_count_" value="1"  /> 

<input type="button" value=" - " onclick="this.parentNode.parentNode.removeChild(this.parentNode);" />
  
    

</div>
	
<form method="post" style="background-color: #CCC; padding:10px; width: 900px;">
<strong>Forma, ar kuru pievienot programmatūru kādai no pavadzīmēm</strong><br />

<select name="invoice_id">
        <?php  
do {  
?>
        <option value="<?php echo $row_invoices['invoice_id']?>" ><?=$row_invoices['set']." ".$row_invoices['number'];?></option>
        <?php
} while ($row_invoices = mysql_fetch_assoc($invoices));
?>
  </select>
      

	<span id="writeroot"></span>


    
<input type="button" id="moreFields" value=" + " /> <br /><input type="submit" value="Add licenses" />
    <input type="hidden" name="software_menu_counter" id="software_menu_counter" value=0 />
    
  <input type="hidden" name="MM_insert" value="form4" />
</form>



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
	echo "<td  bgcolor=\"#FFFFFF\">
	<a href='invoice.php?id=".$row['invoice_id']."'>Open</a>&nbsp;
	<a href='invoices_edit.php?invoice_id=".$row['invoice_id']."'>Edit</a>&nbsp;
	
	".'<a href="invoice_action.php?delete&invoice_id='.$row['invoice_id'].'" onclick="javascript:return confirm(\'Vai tiešām vēlies dzēst?\')">Delete</a></td>  </tr>'; } 
   ?>    
</table>


FORMas laukus pievienot





<form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Party_id:</td>
      <td><input type="text" name="party_id" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Name:</td>
      <td><input type="text" name="name" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Type:</td>
      <td><select name="type">
        <?php 
do {  
?>
        <option value="<?php echo $row_party_type['party_type_id']?>" ><?php echo $row_party_type['name']?></option>
        <?php
} while ($row_party_type = mysql_fetch_assoc($party_type));
?>
      </select>
      </td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form3" />
</form>
<p>&nbsp;</p>

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
mysql_free_result($Recordset1);

mysql_free_result($party_suppliers);

mysql_free_result($party_customers);

mysql_free_result($party_type);

mysql_free_result($invoices);

@mysql_free_result($software);
?>
