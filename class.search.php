<?php 

require_once 'class.dbh.php';

class lqSearch extends lqCase {

    public function __construct()
    {

		
		 }
    
    public function __destruct()
    {
	unset($this);	
	}
	
    protected function searchAll($sTerm)
    {
		
		global $dbh;
       
       
       if (empty($sTerm))
		die('<font color=red>Error please enter some keyword(s) to search for</font>');
		
        if (!empty($_POST['from']))
        $from = lqCase::convertDate($_POST['from'],'us');
        if (!empty($_POST['to']))
          $to = lqCase::convertDate($_POST['to'],'us');
   
// Did the user type in a search term ?   
    if (!empty($sTerm))
    {   
		$exactPhrase = lqSearch::isExact($sTerm);
		
		if ($exactPhrase === 0)
				{
					
				  // exiting code and query here
				 
				          // Split the term on whitespace and copy each element into an array
						$termArr = explode(' ', str_replace(',',' ',$sTerm));
					   
						// check the $termArr is actually an array, iterate through it adding to the query if $term is not empty
						if (is_array($termArr))
						  foreach ($termArr as $term)
							 if (!empty($term))
								 $extraSql     .= "(cases.case_detail LIKE '%" . $term . "%' OR cases.case_header LIKE '%" . $term . "%' OR cases.case_response LIKE '%" . $term . "%')    and ";
				}
				else
				{
				$sTerm = $exactPhrase;
				// PETER -L : This is an exact search no need for an array or terms
				// Split the term on whitespace and copy each element into an array
			$extraSql     .= " (cases.case_detail like '%" . $sTerm . "%' OR cases.case_header like '%" . 
			$sTerm . "%' OR cases.case_response like '%" . $sTerm . "%') and ";	
				
			}
			 // Now remove the extra " or" from the end of $extraSql and add a closing ) to it
             $extraSql = substr($extraSql,0,strlen($extraSql)-4) . ")";
       }
       else $extraSql = ""; // To satisfy the query
                      
                
                 // Do we have a date range?
                 if (!empty($from) && !empty($to))
                 $dateCheck = " AND added_date BETWEEN '" . $from . "' AND '" . $to . "' ";
                 else $dateCheck = "";
                
                 // Add sorting criteria to the query
                 $orderClause  = "  order by status desc,added_date desc ";       
       
        // The stem of the query
       
        if ($_POST['type'] == 'local')
        {
           
         
			  if (empty($sTerm))
        $query = "SELECT distinct(caseid),  status, cases.borrower_id, added_date, deadline_date,
                  case_header, assigned_to,cases.library_id
                  FROM  cases, category, borrower, department, library, subjects, subject_case
                  WHERE   cases.library_id = " . $_SESSION['library_id'] . "
                   ";
                   else
        $query = "SELECT distinct(caseid),  status, cases.borrower_id, added_date, deadline_date,
                  case_header, assigned_to,cases.library_id
                  FROM  cases, category, borrower, department, library, subjects, subject_case
                  WHERE   cases.library_id = " . $_SESSION['library_id'] . "
                  AND (";
                  
        }   else   {
			        if (empty($sTerm))
			         $query = "SELECT distinct(caseid),  status, cases.borrower_id, added_date, deadline_date,
                                case_header, assigned_to,cases.library_id
                                FROM  cases
                                WHERE   cases.publish = 1 or cases.library_id = " . $_SESSION['library_id'];
                                 else
                      $query = "SELECT distinct(caseid),  status, cases.borrower_id, added_date, deadline_date,
                                case_header, assigned_to,cases.library_id
                                FROM  cases, category, borrower, department, library, subjects, subject_case
                                WHERE  
                                 (";
                    }
               
        // If we got extra criteria then contatinate it to the end of $query               
        if (isset($extraSql) && !empty($extraSql))
            $query .= $extraSql;
          
           
        // Finally concatenate the sorting criteria to the end of the query
        $query =  $query . $dateCheck  . $orderClause;
        debug ("SEARCHALL: " . $query);
        debug ("EXTRA: " . $extraSql);
      //  die("term is " . $sTerm);
       
        $res = $dbh->query($query);
        // Execute the query and iterate through the results and create an array of objects,
        // return this array back to the calling process

        while ($obj = $dbh->fetch($res))
        $rtn[] = $obj;
       
        return $rtn;

    }
    
    protected function searchByDepartment($department,$sTerm)
    {
		
		global $dbh;
		debug("department is " . $department);
		$extraSql = " and cases.department_id  = " . $_POST["department"] . "   ";

		if (!empty($_POST['from']))
		$from = lqCase::convertDate($_POST['from'],'us');
		if (!empty($_POST['to']))
          $to = lqCase::convertDate($_POST['to'],'us');
	

		// Did the user type in a search term ?	
			if (isset($sTerm) && !empty($sTerm))
			{	
				$exactPhrase = lqSearch::isExact($sTerm);
				debug("exactPhrase" . $exactPhrase);
				if ($exactPhrase === 0)
				{
					debug("in like  section");
					// Split the term on whitespace and copy each element into an array
					$termArr = explode(' ', str_replace(',',' ',$sTerm));
					
					// check the $termArr is actually an array, iterate through it adding to the query if $term is not empty
					if (is_array($termArr))
							$extraSql .= " AND (";
					  foreach ($termArr as $term)
						 if (!empty($term))
							 $extraSql     .= "cases.case_header  LIKE '%" . $term . "%' or cases.case_detail LIKE '%". $term . "%' or cases.case_response LIKE '%". $term . "%' OR ";
				}
				else
				{
					$sTerm = $exactPhrase;
						 if (!empty($sTerm))
							 $extraSql     .= " AND ( cases.case_header  like '%" . $sTerm . "%' or cases.case_detail like '%". 
							 $sTerm . "%' or cases.case_response like '%". $sTerm . "%' OR ";
					}
			   }
       
       // Now remove the extra " or" from the end of $extraSql and add a closing ) to it
				
				
					$extraSql = substr($extraSql,0,strlen($extraSql)-3);
			  if (!empty($sTerm)) $extraSql .= ")";
                 
                 // Do we have a date range?
                 if (!empty($from) && !empty($to))
                 $dateCheck = " AND added_date BETWEEN '" . $from . "' AND '" . $to . "' ";
                 else $dateCheck = ""; 
                 
                 // Add sorting criteria to the query
		         $orderClause  = "  order by status desc,added_date desc ";		
		
		// The stem of the query
					
		// Only one  type of query since one department in one library is being queried

        if (empty($sTerm))
		$query = "SELECT distinct(caseid),  status, borrower_id, added_date, deadline_date, 
		          case_header, assigned_to,cases.library_id 
		          FROM cases,department
		          WHERE cases.department_id = department.department_id and
		          cases.library_id = department.library_id 
		           ";
		          else
		$query = "SELECT distinct(caseid),  status, borrower_id, added_date, deadline_date, 
		          case_header, assigned_to,cases.library_id 
		          FROM cases,department
		          WHERE cases.department_id = department.department_id and
		          cases.library_id = department.library_id 
		           ";		          

     
		// If we got extra criteria then contatinate it to the end of $query				
		if (isset($extraSql) && !empty($extraSql)) 
			$query .= $extraSql; 
			
		// Finally concatenate the sorting criteria to the end of the query
		$query =  $query . $dateCheck  . $orderClause;
	    //die ($query);
             debug("query " . $query);
             debug("extraSQL".$extraSql); 		
		$res = $dbh->query($query);
		// Execute the query and iterate through the results and create an array of objects, 
		// return this array back to the calling process

		while ($obj = $dbh->fetch($res))
		$rtn[] = $obj;
		
        return $rtn;

    }   
    
    protected function displayResults($results)
    {
// Various search functions pass an array of objects here. The results are displayed in a table

	    global $dbh;
	    $even = 2;
	    $numRecs = count($results);
	    if (!empty($_POST['criteria']))
        $caption = "Showing results 1 to " . $numRecs . " - criteria = '" . $_POST['criteria'] . "'";
        else $caption = " Open cases for " . User::nameFromId($_SESSION['user_id']);
        $today = date("Y-m-d"); 
                    
      
           
            if (count($results) > 0)
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
	
 
    protected function openCases($user_id)
    {
		global $dbh;
		
		$query = "SELECT * from cases where assigned_to = " . $user_id . " and status = 'open'";
		$res = $dbh->query($query);
		
		        while ($obj = $dbh->fetch($res))
        $rtn[] = $obj;
       
        return $rtn;
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
 
    protected function searchBySubject($sTerm)
    {
		global $dbh;
		
		if (!empty($_POST['from']))
		$from = lqCase::convertDate($_POST['from'],'us');
		if (!empty($_POST['to']))
          $to = lqCase::convertDate($_POST['to'],'us');
	
// Did the user type in a search term ?	
	if (isset($sTerm) && !empty($sTerm))
	{	
		// Assume the user has only typed in one word??
		$extraSql= "subjects.subject like '%" .$sTerm . "%' ";
       }
                 
                 // Do we have a date range?
                 if (!empty($from) && !empty($to))
                 $dateCheck = " AND added_date BETWEEN '" . $from . "' AND '" . $to . "' ";
                 else $dateCheck = ""; 
                 
                 // Add sorting criteria to the query
		         $orderClause  = "  order by cases.status,cases.added_date";		
		
		// The stem of the query
		
		if ($_POST['type'] == 'local')
        {
			
		
		$query = "SELECT distinct(caseid),  status, borrower_id, added_date, deadline_date, 
		          case_header, assigned_to,cases.library_id 
		          FROM cases 
		          INNER  JOIN subject_case ON cases.caseid = subject_case.case_ID 
				  INNER JOIN  subjects ON subject_case.subject_id = subjects.subject_id 
		          WHERE ";
		          		  if (empty($sTerm))
		                        $query .= " 1 and ";
		            $query .= " cases.library_id = " . $_SESSION['library_id'] . " 
		          ";
		}   else   { 
		              $query = "SELECT distinct(caseid),  status, borrower_id, added_date, deadline_date, 
		                        case_header, assigned_to,cases.library_id 
		                        FROM cases
		                        INNER  JOIN subject_case ON cases.caseid = subject_case.case_ID 
				                INNER JOIN  subjects ON subject_case.subject_id = subjects.subject_id  
		                        WHERE   
		                        ";
		                       // if (empty($sTerm))
		                        $query .= " 1 ";
                    }
           debug("sql   " . $query);   
           debug("" . $extraSql);       
		// If we got extra criteria then contatinate it to the end of $query	
		
		if (isset($extraSql) && !empty($extraSql)) 
			$query .= " and " . $extraSql; 
			
		// Finally concatenate the sorting criteria to the end of the query
		$query =  $query . $dateCheck  . $orderClause;
	    //die ($query);
		       debug("sql   " . $query);   
           debug("" . $extraSql);   
		$res = $dbh->query($query);
		// Execute the query and iterate through the results and create an array of objects, 
		// return this array back to the calling process

		while ($obj = $dbh->fetch($res))
		$rtn[] = $obj;
		
        return $rtn;
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
    
    protected function searchByCaseHeader($sTerm)
    {
		global $dbh;
		
		
		if (empty($sTerm))
		die('<font color=red>Error please enter some keyword(s) to search for</font>');
		
		
		if (!empty($_POST['from']))
		$from = lqCase::convertDate($_POST['from'],'us');
		if (!empty($_POST['to']))
          $to = lqCase::convertDate($_POST['to'],'us');

		// Did the user type in a search term ?	
		if (isset($sTerm) && !empty($sTerm))
		{	
			$exactPhrase = lqSearch::isExact($sTerm);
			debug("exactPhrase" . $exactPhrase);
			if ($exactPhrase === 0)
				{
					debug("in like  section");
					// Split the term on whitespace and copy each element into an array
					$termArr = explode(' ', str_replace(',',' ',$sTerm));
					// check the $termArr is actually an array, iterate through it adding to the query if $term is not empty
					if (is_array($termArr))
						foreach ($termArr as $term)
							if (!empty($term))
								$extraSql     .= " case_header like '%" . $term . "%' or ";
				}
			else
			{
				debug("in equals secion");
				$sTerm = $exactPhrase;
								$extraSql     .= " case_header like '%" . $sTerm . "%'   or ";
                                                                $extraSql = substr($extraSql,0,strlen($extraSql)-3) . ")";

			}

       }
       
       // Now remove the extra " or" from the end of $extraSql and add a closing ) to it
                 $extraSql = substr($extraSql,0,strlen($extraSql)-3) . ")";

                 // Do we have a date range?
                 if (!empty($from) && !empty($to))
                 $dateCheck = " AND added_date BETWEEN '" . $from . "' AND '" . $to . "' ";
                 else $dateCheck = ""; 
                 
                 // Add sorting criteria to the query
		         $orderClause  = "  order by status, added_date";		
		
		// The stem of the query
		
		if ($_POST['type'] == 'local')
        {
			
		
		$query = "SELECT distinct(caseid),  status, borrower_id, added_date, deadline_date, 
		          case_header, assigned_to,cases.library_id 
		          FROM cases
		          WHERE   library_id = " . $_SESSION['library_id'] . " 
		          AND (";
		}   else   { 
		              $query = "SELECT distinct(caseid),  status, borrower_id, added_date, deadline_date, 
		                        case_header, assigned_to,library_id 
		                        FROM cases
		                        WHERE   
		                        (";
                    }
                    
		// If we got extra criteria then contatinate it to the end of $query				
		if (isset($extraSql) && !empty($extraSql)) 
			$query .= $extraSql; 
			
		// Finally concatenate the sorting criteria to the end of the query
		$query =  $query . $dateCheck  . $orderClause;
//	    die ($query);
		
		$res = $dbh->query($query);
		// Execute the query and iterate through the results and create an array of objects, 
		// return this array back to the calling process

		while ($obj = $dbh->fetch($res))
		$rtn[] = $obj;
		
        return $rtn;

    }
    
    protected function searchByCaseDetails($sTerm)
    {
		global $dbh;
		
		
				if (empty($sTerm))
		die('<font color=red>Error please enter some keyword(s) to search for</font>');
		
		if (!empty($_POST['from']))
		$from = lqCase::convertDate($_POST['from'],'us');
		if (!empty($_POST['to']))
          $to = lqCase::convertDate($_POST['to'],'us');

// Did the user type in a search term ?	
	if (isset($sTerm) && !empty($sTerm))
	{	
		$exactPhrase = lqSearch::isExact($sTerm);
			debug("exactPhrase" . $exactPhrase);
			if ($exactPhrase === 0)
				{
					debug("in like  section");
					// Split the term on whitespace and copy each element into an array
					$termArr = explode(' ', str_replace(',',' ',$sTerm));
					
					// check the $termArr is actually an array, iterate through it adding to the query if $term is not empty
					if (is_array($termArr))
					  foreach ($termArr as $term)
						 if (!empty($term))
							
							 $extraSql     .= " case_detail like '%" . $term . "%' or ";
				}
			else
			{
				debug("in equals  section");
				$sTerm = $exactPhrase;
				
				// Split the term on whitespace and copy each element into an array
					$termArr = explode(' ', str_replace(',',' ',$sTerm));
					
					// check the $termArr is actually an array, iterate through it adding to the query if $term is not empty
							
							 $extraSql     .= " case_detail like '%" . $sTerm . "%'   or ";
			}
       }
       
       // Now remove the extra " or" from the end of $extraSql and add a closing ) to it
                 $extraSql = substr($extraSql,0,strlen($extraSql)-3) . ")";
                 debug("sql". $extraSql);
                 // Do we have a date range?
                 if (!empty($from) && !empty($to))
                 $dateCheck = " AND added_date BETWEEN '" . $from . "' AND '" . $to . "' ";
                 else $dateCheck = ""; 
                 
                 // Add sorting criteria to the query
		         $orderClause  = "  order by status desc,added_date desc ";		
		
		// The stem of the query
		
		if ($_POST['type'] == 'local')
        {
			
		
		$query = "SELECT distinct(caseid),  status, borrower_id, added_date, deadline_date, 
		          case_header, assigned_to,cases.library_id 
		          FROM cases
		          WHERE   library_id = " . $_SESSION['library_id'] . " 
		          AND (";
		}   else   { 
		              $query = "SELECT distinct(caseid),  status, borrower_id, added_date, deadline_date, 
		                        case_header, assigned_to,library_id 
		                        FROM cases
		                        WHERE   
		                        (";
                    }
                    
		// If we got extra criteria then contatinate it to the end of $query				
		if (isset($extraSql) && !empty($extraSql)) 
			$query .= $extraSql; 
			
		// Finally concatenate the sorting criteria to the end of the query
		$query =  $query . $dateCheck  . $orderClause;
	    //die ($query);
	    debug("Query". $query);
		debug("Query ran with". $extraSQL);
		$res = $dbh->query($query);
		// Execute the query and iterate through the results and create an array of objects, 
		// return this array back to the calling process

		while ($obj = $dbh->fetch($res))
		$rtn[] = $obj;
		
        return $rtn;
		

    }
    
    protected function isExact($sTerm)
    {
		// If the term is qualified by double quotes we are searching for only the exact phrase 
		// and not every word in it. Return true if the term is qualified otherwise return false
		// If the term is qualified by double quotes we are searching for only the exact phrase
        // and not every word in it. Return true if the term is qualified otherwise return false
        $pos = strpos($sTerm, '"');
		$lastpos = strrpos($sTerm, '"');
		$length = strlen($sTerm)-1;
        if ($pos == 0 && $lastpos ==  $length)
        {
			$newTerm = substr($sTerm,1,strlen($sTerm)-2);
			return  $newTerm;
		}
		else
		{
			debug("lqSearch::isExact retuned zero");
			return 0;
		}
	}
    

}

?> 
