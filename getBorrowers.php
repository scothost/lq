<?
require_once 'config.php'; 



	$letters = $_GET['letters'];
	$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
    
    $sql = "SELECT
                 borrower_id,surname, firstname, email, addr1 " . 
            "FROM
             borrower where surname LIKE
              '".$letters."%' or firstname like '".$letters."%'";

	$res = $dbh->query($sql);
    
	while($inf = $dbh->fetch($res,'array')){
				$query = "select count(caseid) from cases where borrower_id =" . $inf['borrower_id'];
				
		$mres = $dbh->query($query);
		$rec = $dbh->fetch($mres,'array');
		
		
	//if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') === false)
		echo $inf['borrower_id']."###"  . $inf['surname']. ', ' . $inf['firstname']  . ' (' . $rec[0] . ') ' . $inf['email'] . ' ' . $inf['addr1'] . "<hr>" .  "|";
  //   else
  //   echo $inf['borrower_id']."###"  . $inf['surname']. ', ' . $inf['firstname'] . "<hr>" .  "|";
    

	}	

?> 
