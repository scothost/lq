<?php

require_once 'config.php';
require_once 'class.dbh.php';

$query = "select department_id, department from department where library_id = " . $_GET['lib'];
$res = $dbh->query($query);
while ($obj = $dbh->fetch($res,''))
$result .= $obj->department_id . ":" . $obj->department . ",";

echo substr($result,0,strlen($result)-1);



?>

