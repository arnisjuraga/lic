<table border="1">
  <? while ($row=mysql_fetch_assoc($result_invoices_all)) {;
  	if ($th==0) foreach($row  as $key => $val ) { echo "<td> $key </td> ";  }
	$th=1;
  	echo "<tr>" ; 
		foreach($row as $val) { echo "<td> $val; </td> "; } 
	echo "</tr>"; } 
   ?>    
</table>


<br />
<table border="1">
<? $query_licenses = "SELECT l.license_id, s.name as sname, l.konts, l.inv_id, lt.name as ltype, i.number, i.set, i.date from licenses l
left join software s on l.software_id=s.software_id
left join  license_type lt on s.license_type=lt.license_type_id

join  invoices i where i.invoice_id=l.invoice_id 
";
$licenses = mysql_query($query_licenses, $db) or die(mysql_error());
$totalRows_licenses = mysql_num_rows($licenses);
?>



  <? while ($row44=mysql_fetch_assoc($licenses)) {;
  	echo "<tr>" ; foreach($row44  as $key => $val) { echo "<td> $val; </td> "; } 
	echo "</tr>"; } 
   ?>    
</table>