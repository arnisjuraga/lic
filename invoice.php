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
<?php 

require_once('config.php');
require_once('func.php');
require_once('db_layer.php');  
require_once('menu.php');


mb_internal_encoding("UTF-8");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_POST = removeMagicQuotes($_POST);
}

// init db layer
$dd = new DB('', '', '', '', $db);

if(isset($_GET['id']) && is_num($_GET['id'])) {

  if(isset($_GET['delete_dev']) && is_num($_GET['delete_dev'])) {
    
    $dd->query('delete from devices where device_id='.escq($_GET['delete_dev']).' limit 1');
  
  }
  if(isset($_GET['delete_lic']) && is_num($_GET['delete_lic'])) {
    
    $dd->query('delete from licenses where license_id='.escq($_GET['delete_lic']).' limit 1');
  
  }


  echo '<h2>Invoice info: </h2>';
  $u = $dd->fetchRow('select *
                      from invoices 
                      where invoice_id='.escq($_GET['id']).'
                      limit 1');
  if($u) {
        
        $pname = $dd->fetchArray('select * from party where party_id in ('.$u['supplier_id'].','.$u['customer_id'].')', 'party_id');
  
    echo 'PPR: '.$u['set'] .' ' . $u['number'] . '<br />'.
          'Date: '.$u['date'] . '<br />'.
          'Supplier: '.$pname[$u['supplier_id']]['name']. '<br />'.
          'Customer: '.$pname[$u['customer_id']]['name']. '<br /><br />';
          
          $device_types = $dd->fetchArray('select * from device_type', 'id');
          
          
          $items = $dd->fetchArray('select d.device_id,d.description,d.hostname,d.user_id,concat(u.name, \' \', u.surname) as user_name,d.device_type from devices d
                                      left join users u on u.id=d.user_id
                                        
                                   where d.invoice_id='.escq($_GET['id']));
          if($items) {
            echo '<h2>Devices: </h2>';
            echo '<table border="1">';
            echo '<tr><th>ID</th>
                <th>Device Type</th>
                <th>Description</th>
                 <th>Hostname</th>
                 <th>User</th>
                 <th>Edit</th>
                </tr>';
            foreach($items as $d) {
         
                 echo '<tr><td>'.$d['device_id'].'</td>
              <td>'.($d['device_type']!=0?$device_types[$d['device_type']]['name']:'').'</td>
              <td>'.$d['description'].'</td>
               <td>'.$d['hostname'].'</td>
               <td><a href="user_info.php?id='.$d['user_id'].'">'.$d['user_name'].'</a></td>
               <td><a href="devices_edit.php?device_id='.$d['device_id'].'">Edit</a> | <a href="invoice.php?id='.$_GET['id'].'&amp;delete_dev='.$d['device_id'].'" onclick="javascript:return confirm(\'Vai tiešām vēlaties dzēst?\')">Delete</a></td>
              </tr>';
      
          }
          echo '</table>';
        }
        
        
        
        
         $items = $dd->fetchArray('select l.license_id, l.software_id, s.name as software_name, s.license_type, description, t.name as license_type_name
                                      from licenses l
                                      left join software s on l.software_id=s.software_id
                                      left join license_type t on t.license_type_id=s.license_type
                                        
                                   where l.invoice_id='.escq($_GET['id']));
         if($items) {
          
          echo '<h2>Licences: </h2>';
          echo '<table border="1">';
          echo '<tr><th>ID</th>
              <th>Software</th>
              <th>Licences tips</th>
              <th>Description</th>
               <th>Edit</th>
              </tr>';
          foreach($items as $d) {
       
               echo '<tr><td>'.$d['license_id'].'</td>
            <td>'.$d['software_name'].'</td>
            <td>'.$d['license_type_name'].'</td>
            <td>'.$d['description'].'</td>
             <td><a href="licenses.php?license_id='.$d['license_id'].'">Edit</a> | <a href="invoice.php?id='.$_GET['id'].'&amp;delete_lic='.$d['license_id'].'" onclick="javascript:return confirm(\'Vai tiešām vēlaties dzēst?\')">Delete</a></td>
            </tr>';
    
        }
        echo '</table>';
        }
     
          
          
          
          
          
          
          
  }
  else {
    echo 'Invoice not found!';
   
  }
}


?>



</body>
</html>