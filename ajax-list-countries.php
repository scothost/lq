<?
require_once 'class.dbh.php'; 



	$letters = $_GET['letters'];
	$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
    
    $sql = "SELECT
                 borrower_id,surname, firstname, email " . 
            "FROM
             borrower where surname LIKE
              '".$letters."%' or firstname like '".$letters."%'";

	$res = $dbh->query($sql);
    
	while($inf = $dbh->fetch($res,'array')){
		echo $inf['borrower_id']."###".$inf['firstname']. ' ' . $inf['surname'] . ' - ' . $inf['email'] . "|";
	}	

?>
