<?php 
//print_r($_POST);
require_once('config.php');


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
  header("Location: devices.php");
}

if ((isset($_GET['invoice_id'])) && ($_GET['invoice_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM invoices WHERE invoice_id=%s",
                       GetSQLValueString($_GET['invoice_id'], "int"));
  $Result1 = mysql_query($deleteSQL, $db) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form4")) {
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
  header("Location: devices.php");
}



if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "device")) {
  if($_POST["invoice_id"]=="cits") {
	  $sql = sprintf("INSERT INTO invoices (`set`, `number`) VALUES (%s, %s)",
                       GetSQLValueString($_POST['new_set'], "text"),
                       GetSQLValueString($_POST['new_number'], "int"));
	  
	  $result = mysql_query($sql, $db) or die(mysql_error());		
	  //!!! Ja ir noradita jaunas PPR numurs, tad javeic parbaude, vai gadijuma jau tads neeksiste?
	  $last_invoice_inserted=mysql_insert_id();
	  $_SESSION['last_invoice_inserted']=$last_invoice_inserted;
	  
	 
	  
	  }
	else $last_invoice_inserted=$_POST['invoice_id'];
	  
  $sql = sprintf("INSERT INTO devices (`description`, `hostname`, `owner_id`, `invoice_id`,`id`) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['hostname'], "text"),
                       GetSQLValueString($_POST['owner_id'], "int"),
                       $last_invoice_inserted,
                       GetSQLValueString($_POST['id'], "text"));  


$Result1 = mysql_query($sql, $db) or die(mysql_error());
if(isset($_SESSION['last_invoice_inserted']) and ($_SESSION['last_invoice_inserted']>0)) 
	$location="invoices_edit.php";
else
	$location="devices.php?device_inserted"; 
															
header("Location: ".$location);
}

/////////////////////****************************/////////////////////////////
// devices_edit.php UPDATE forma. (action=update, form=device
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "device")) {
$sql="UPDATE `devices` SET `description`=".GetSQLValueString($_POST['description'],"text")." , 
`hostname`=".GetSQLValueString($_POST['hostname'], "text").", 
`id`=".GetSQLValueString($_POST['id'], "text").", 
`owner_id`=".GetSQLValueString($_POST['owner_id'], "int").",
`invoice_id`=".GetSQLValueString($_POST['invoice_id'], "int").
" WHERE `device_id` = ".GetSQLValueString($_POST['device_id'], "text")." LIMIT 1" ;
//echo $sql;
$Result1 = mysql_query($sql, $db) or die(mysql_error());
header("Location: devices_edit.php?device_id=".$_POST['device_id']);
}

/////////////////////****************************/////////////////////////////
// devices_edit.php UPDATE forma. (action=update, form=licenses_update
if ((isset($_POST["MM_device_update"])) && ($_POST["MM_device_update"] == "licenses")) {
//print_r($_POST);
//echo $_POST["license_1"];
	$i=1;
	if ($_POST['count']>0)  
	
		do { 
		
		$sql="UPDATE `licenses` SET `device_id`=".GetSQLValueString($_POST['device_id'],"int")." where ";
		$sql.=" license_id= ".$_POST["license_".$i]." limit 1;" ;
		//echo $sql;
		//izpildam tikai tad, ja ir iezimets checkbox.
		if ($_POST["license_".$i]>0) 
			$result = mysql_query($sql, $db) or die(mysql_error());


			
		
		$i++;

		} while ($i<=$_POST['count']) ;
	header("Location: devices_edit.php?device_id=".$_POST['device_id']);			

} 


if ((isset($_POST["form"])) && ($_POST["form"] == "invoice_edit")) {

		$sql="UPDATE `invoices` SET 
			`date`=".GetSQLValueString($_POST['date'],"text").",
			`set`=".GetSQLValueString($_POST['set'],"text").",
			`number`=".GetSQLValueString($_POST['number'],"int").",
			
			`supplier_id`=".GetSQLValueString($_POST['supplier_id'],"int").",
			`customer_id`=".GetSQLValueString($_POST['customer_id'],"int")."
		
		
		where ";
		$sql.=" invoice_id=".GetSQLValueString($_POST['invoice_id'],"int")." limit 1;" ;
	 //echo $sql;
	$result = mysql_query($sql, $db) or die(mysql_error());	 
	if (isset($_SESSION['last_invoice_inserted'])) {
		unset($_SESSION['last_invoice_inserted']);
		$location="devices.php";
		
		}
	else 
		$location=$_SERVER['HTTP_REFERER'];

//print_r($_SERVER);			
header("Location: $location");			
}


if (isset($_GET['delete']) && isset($_GET['invoice_id']))
	{
	$sql="delete from invoices where invoice_id=".intval($_GET['invoice_id'])." limit 1";
	$result = mysql_query($sql, $db) or die(mysql_error());	
	header("Location: ".$_SERVER['HTTP_REFERER']);	
	}




?>