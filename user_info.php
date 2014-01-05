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


  echo '<h2>User info: </h2>';
  $u = $dd->fetchRow('select * from users where id='.escq($_GET['id']).' limit 1');
  if($u) {
    echo 'Name: '.$u['name'] . '<br />Surname: '.$u['surname']. '<br /><br />';
    
    
    
    $device_types = $dd->fetchArray('select * from device_type', 'id');
    $parties = $dd->fetchArray('select * from party', 'party_id');

    
    echo '<h2>Devices: </h2>';
    $devices = $dd->fetchArray('select * from devices where user_id='.escq($_GET['id']));
    echo '<table border="1">';
    echo '<tr><th>ID</th>
        <th>Device Type</th>
        <th>Description</th>
         <th>Hostname</th>
         <th>Owner</th>
         <th>Edit</th>
        </tr>';
    foreach($devices as $d) {
    
        echo '<tr><td>'.$d['device_id'].'</td>
        <td>'.$device_types[$d['device_type']]['name'].'</td>
        <td>'.$d['description'].'</td>
         <td>'.$d['hostname'].'</td>
         <td>'.$parties[$d['owner_id']]['name'].'</td>
         <td><a href="devices_edit.php?device_id='.$d['device_id'].'">Edit</a></td>
        </tr>';
    
    }
    echo '</table>';
  }


}


?>



</body>
</html>