<?php 

require_once 'class.dbh.php';

class lqSearch extends lqCase {

    public function __construct()
    { }
    
    public function __destruct()
    {
	unset($this);	
	}
	
    protected function searchAll($criteria)
    {

    }
    
    protected function searchByDepartment($criteria)
    {

    }   
    
    protected function displayResults($results)
    {
// Various search functions pass an array of objects here. The results are displayed in a table

	    global $dbh;
	    $even = 2;
	    $numRecs = count($results);
        $caption = "Showing results 1 to " . $numRecs . " - criteria = 'borrower'";
        $today = date("Y-m-d"); 
                    
      
           
            if (count($results) > 1)
            {	    
	    echo '<table  border="0" cellpadding="0">
          
          <tr><th class=admin colspan=7><font color=black>' . $caption . 
          '</font></th></tr>
          <tr class = "admin">
          <th class="admin" width="60" align=center>Case Id</th>
          <th class="admin" width="60" align=center>Status</th>
           <th class="admin" width="90" align=center>Date Added</th>        
           <th class="admin" width="160">Header</th>
           <th class="admin" width="240">Borrower</th>
           <th class="admin" width="100" align=center>Library</th>
  
          
     
            </tr>
          ';
	  
          
          // Yeah yeah I know you can cycle the colours of <TR> in css. 
          // What can I say, I was in PHP mode and I'm lazy
          foreach ($results as $res)
          {			  
			  // is it past the deadline date
			  $deadline = substr($res->deadline_date,0,10);
          
               if ($today >= $deadline)
				  $textColor = "red";
			       else 
			       $textColor = "black";
			  	       // cycle row backgrounds
			  	            if ($even%2==0)
                             {
								 $color = '#f2f2f2';
								 $even++;
						      }						      		 
                               else { 
				                      $color = '#d1d1d1';
				                      $even--;
			                        }
            
            

            // Get Borrower instance
			  $borObj = Borrower::getName($res->borrower_id);
			  
			  echo '<tr bgcolor = ' . $color . '>
			  <td><a href=/?op=cases&caseid='  . $res->caseid . '>' . $res->caseid . '</a></td>
			  <td>' . $res->status . '</td>
			  <td><font color="' . $textColor . '">' . $res->added_date . '</font></td>
			  <td><a href=/?op=cases&caseid=' . $res->caseid . '>' . $res->case_header . '</a></td>
			  <td>' . $borObj->firstname . ' ' . $borObj->surname  . '</td>
			  <td>' . Library::name($res->library_id) . '</td>
			  </tr>';
		    

		  }       
		  echo '</table>';
	  }
	  		     else 
		         {
					 echo "<p><font color='red'>Your search returned no results</font></p>";
		         }
	  
	}
	
    protected function searchByBorrower($sTerm)
    {
		global $dbh;
		
		if (!empty($_POST['from']))
		$from = lqCase::convertDate($_POST['from'],'us');
		if (!empty($_POST['to']))
          $to = lqCase::convertDate($_POST['to'],'us');
	
// Did the user type in a search term ?	
	if (isset($sTerm) && !empty($sTerm))
	{	
		// Split the term on whitespace and copy each element into an array
		$termArr = explode(' ', str_replace(',',' ',$sTerm));
		
		// check the $termArr is actually an array, iterate through it adding to the query if $term is not empty
		if (is_array($termArr))
		  foreach ($termArr as $term)
		     if (!empty($term))
		         $extraSql     .= " borrower.firstname like '%" . $term . "%' or borrower.surname like '%" . $term . "%' or ";
       }
       
       // Now remove the extra " or" from the end of $extraSql and add a closing ) to it
                 $extraSql = substr($extraSql,0,strlen($extraSql)-3) . ")";
                 
                 // Do we have a date range?
                 if (!empty($from) && !empty($to))
                 $dateCheck = " AND added_date BETWEEN '" . $from . "' AND '" . $to . "' ";
                 else $dateCheck = ""; 
                 
                 // Add sorting criteria to the query
		         $orderClause  = "  order by status desc,added_date desc ";		
		
		// The stem of the query
		
		if ($_POST['type'] == 'local')
        {
			
		
		$query = "SELECT distinct(caseid),  status, cases.borrower_id, added_date, deadline_date, 
		          case_header, assigned_to,cases.library_id 
		          FROM cases,borrower 
		          WHERE   cases.library_id = " . $_SESSION['library_id'] . " 
		          AND cases.borrower_id = borrower.borrower_id and (";
		}   else   { 
		              $query = "SELECT distinct(caseid),  status, cases.borrower_id, added_date, deadline_date, 
		                        case_header, assigned_to,cases.library_id 
		                        FROM cases,borrower 
		                        WHERE   
		                        cases.borrower_id = borrower.borrower_id and (";
                    }
                    
		// If we got extra criteria then contatinate it to the end of $query				
		if (isset($extraSql) && !empty($extraSql)) 
			$query .= $extraSql; 
			
		// Finally concatenate the sorting criteria to the end of the query
		$query =  $query . $dateCheck  . $orderClause;
	    //die ($query);
		
		$res = $dbh->query($query);
		// Execute the query and iterate through the results and create an array of objects, 
		// return this array back to the calling process

		while ($obj = $dbh->fetch($res))
		$rtn[] = $obj;
		
        return $rtn;
    }
 
    protected function searchBySubject($criteria)
    {

    }
    

    protected function searchByCaseId($criteria)
    {
		global $dbh;

        $sql = "SELECT * FROM cases WHERE caseid=" . $criteria;
 
        $res = $dbh->query($sql);
        if (@$dbh->rows($res) > 0)
        {
         header("Location: /?op=cases&caseid=" . $criteria);
        }
        else 
           {
	         $searchError = "<p><font color='red'>Case '" .  $criteria . "' does not exist</font></p>";
	         echo $searchError;
           }
    }
    
    protected function searchByCaseHeader($criteria)
    {

    }
    
    protected function searchByCaseDetails($criteria)
    {

    }
    
    protected function isExact($term)
    {
		// If the term is qualified by double quotes we are searching for only the exact phrase 
		// and not every word in it. Return true if the term is qualified otherwise return false
		
		return true;
	}
    

}

?> 
