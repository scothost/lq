<?php
require_once 'config.php';

  


echo " The following text has been wriiten to the Subjects Database Table Subject: ";

foreach ($_GET as $key => $val)
{
$query = "insert into subjects (subject) values('" . str_replace('_'," ",$key) . "')";
$res = $dbh->query($query);  
echo str_replace('_',' ',$key);
}


?>
