<?

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

function echo_devices_1($order1=" id asc") {
// Atlasam visas klienta iekārtas!!!
global $db, $_GET, $mlid;
$order_status="";
if(intval($_GET['order_status'])==1)
    $order_status="status asc, ";
    

$device_status[0]="--";
$device_status[1]="OK";
$device_status[2]="Check LIC";
$device_status[3]="Check LIC & grām.";
$device_status[4]="Norakstīt";
$device_status[5]="Arhivēts!";

if (intval($_GET['filter_owner_id'])<>0)
{
	$filter_owner_id="and  owner_id =  ".intval($_GET['filter_owner_id']);
	$filter_owner_id_sort="filter_owner_id=".intval($_GET['filter_owner_id'])."&amp;";
	//echo $filter_owner_id;
}

if (isset($_GET['order1'])) {
if (@$_GET['order1']=="id") $order1=" id ";
elseif (@$_GET['order1']=="device_id") $order1=" device_id ";
elseif (@$_GET['order1']=="hostname") $order1=" hostname ";
elseif (@$_GET['order1']=="user_name") $order1=" user_name ";
elseif (@$_GET['order1']=="description") $order1=" description ";
elseif (@$_GET['order1']=="name") $order1=" name ";
elseif (@$_GET['order1']=="owner_name") $order1=" owner_name ";
elseif (@$_GET['order1']=="id") $order1=" id ";
elseif (@$_GET['order1']=="konts") $order1=" konts ";
elseif (@$_GET['order1']=="inv_id") $order1=" inv_id ";
elseif (@$_GET['order1']=="status") $order1=" status ";
elseif (@$_GET['order1']=="ppr") $order1=" ppr ";




elseif (@$_GET['order1']=="type") $order1=" type ";

else {
	
	$order1="id";     
	
	
	}


if (@$_GET['order2']=="asc") $order2="asc";
else $order2="desc";

$_SESSION['order1']=$order1;
$_SESSION['order2']=$order2;

}

else {
	
	
	if (isset($_SESSION['order1'])){
	$order1=$_SESSION['order1'];
	$order2=$_SESSION['order2'];
						} 
	else {$order1=" type "; $order2="asc"; }
	//DEBUG
	//echo $order1.$order2;

		
}


$sql = "SELECT d.device_id, d.user_id, o.name as owner_name, d.id, dt.name as type, d.hostname, concat(u.name, ' ', u.surname) as user_name, d.status, d.konts,d.inv_id, d.description, concat(i.set, ' ',i.number) as ppr, i.invoice_id
FROM devices d
LEFT JOIN invoices i ON d.invoice_id = i.invoice_id
LEFT JOIN users u ON u.id = d.user_id
LEFT JOIN party p ON i.customer_id = p.party_id
LEFT JOIN party o ON d.owner_id = o.party_id
LEFT JOIN device_type dt ON dt.id = d.device_type
where 1=1 $filter_owner_id
ORDER BY $order_status $order1 $order2
";
$result_devices = mysql_query($sql, $db) or die(mysql_error());
$row_devices = mysql_fetch_assoc($result_devices);
//echo $sql;

// Izvada tabulu ar iekārtu sarakstu, ar mainīšanas un dzēšanas tiesībām, bez sortēšanas pagaidām.
if( $row_devices) { ?>
<table border="0" cellspacing="1" cellpadding="1" bgcolor="#CCCCCC">
 <?php $i=0; $th=0; do {
	//ierakstām header rindu.
	if ($th==0) { 	 
		echo "<tr>" ; 
		
					if($order2=="desc") $order2="asc";
				else $order2="desc";
				
		foreach($row_devices  as $key => $val ) { 
      if($key != 'user_id' && $key != 'invoice_id') {
		@$kk=$mlid[$key]['lv'];
		if ($kk=='') $kk=$key;
        echo "<td align = center> <a href=\"?{$filter_owner_id_sort}order1=$key&order2=$order2\">{$kk}</a> </td> ";
      }
	  }
		$th=1;
		echo "<td>darbības</td>"; echo "</tr>" ; 
	} ?>
    <tr>
	<td bgcolor="#FFFFFF"><?php echo $row_devices['device_id']; ?>&nbsp;</td>
    <td bgcolor="#FFFFFF" style="font-size:x-small"><?php echo $row_devices['owner_name']; ?>&nbsp;</td>
      <td bgcolor="#FFFFFF"><?php echo $row_devices['id']; ?>&nbsp;</td>
      <td bgcolor="#FFFFFF"><?php echo $row_devices['type']; ?>&nbsp;</td>
      <td bgcolor="#FFFFFF"><?php echo $row_devices['hostname']; ?>&nbsp;</td> 
      <td bgcolor="#FFFFFF" nowrap="nowrap"><a href="user_info.php?id=<?php echo $row_devices['user_id']; ?>"><?php echo $row_devices['user_name'];?></a>&nbsp;</td>
            
      <td bgcolor="#FFFFFF"><?php echo @$device_status[$row_devices['status']]; ?>&nbsp;</td>      
      <td bgcolor="#FFFFFF"><?php echo $row_devices['konts']; ?>&nbsp;</td>      
      <td bgcolor="#FFFFFF"><?php echo $row_devices['inv_id']; ?>&nbsp;</td>      
      
      <td title="xxxxxxx" bgcolor="#FFFFFF" width="500px"><?php echo $row_devices['description'];
	  //if($ii<>1) { var_dump($row_devices); $ii=1; } 
	  ?>&nbsp;</td>
      
      <td bgcolor="#FFFFFF" nowrap="nowrap"><a href="invoice.php?id=<?php echo $row_devices['invoice_id']; ?>"><?php echo $row_devices['ppr']; ?></a>&nbsp;</td>

        <td bgcolor="#FFFFFF"><a href="devices_edit.php?device_id=<?=$row_devices['device_id'];?>">Edit </a>&nbsp;<a href="devices_action.php?delete&device_id=<?=$row_devices['device_id']?>" onclick="javascript:return confirm('Tiks dzēsta tikai iekārta, bet ne tais piesaistītā informācija. vai turpināt?')">Dzēst</a></td>        

     </tr>
    <?php } while ($row_devices = mysql_fetch_assoc($result_devices)); ?>
</table>
<?


} else echo "<br />Nav vēl iekārtu...";

}
?>