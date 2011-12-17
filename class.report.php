<?php 
require_once 'class.layout.php';
require_once 'class.dbh.php';

 /**
     * Report Class - 
     * 
     * 
     *  <p>This class simply takes a name in the format Surname, Firstname and 
     *  retuens the Firstname or Surname only tepending on the input parameters to
     *  member functions. 
     * 
     * #FIXME Should really just be a method of the lqCase class 
     *
     * @author Peter Lorimer <peter@oslo.ie>
     * @license Interleaf
     * @copyright Interleaf Technology Ltd
     * @package LQ                                
     * Date: 22 July 2011
     */


class Report {
    
    
    public function __construct()
    {
        
    }
    
    
    public function __destruct()
    {
    
    }
    
   
    
    public function reportForm()
    {
        global $dbh;
        echo "<form name=report method=post onSubmit='return validateReport()'>
       <h3 align=center> Total Cases by Criteria by Date Range</h3><br> 
      <p align=center> From <input type=text size=12 name=from id=calendar6>&nbsp; To <input type=text size=12 name=to id=calendar7>
       <br>  <br>
       Select Criteria<br>
       
        <select  id=report name=report>
        <option value=0>Please choose ...</option>
        <option value=department>Department</option>
        <option value=category>Category</option>         
        <option value=borrower_type>Borrower Type</option> 
        <option value=member_type>Member Type</option>         
        <option value=country>Country</option>         
        <option value=source>Source</option>         
        <option value=resolved_by>Resolved By</option>  
        <option value=user>User Id</option>  
        <option value=resource>Resource Used</option>
        </select><br>                     
        
        <input class = lq-button type=submit name=Report value = 'Generate'>
        </p></form><br><br>";
        
        
    }
    
    public function view($resource_id)
    {
        global $dbh;
        $to = lqCase::convertDate($_POST['to'],'us');
        $from = lqCase::convertDate($_POST['from'],'us');
        
        if (!empty($_POST) && (empty($to) || empty($from))) 
        {
			echo "<center><font color=red>Error - please select a date range</font></center>";
        }
        else {
        $criteria = $_POST['report'];
        
        switch ($criteria)
        {
            case "department":
            $deptArr = Department::listAll($_SESSION['library_id']);
            
            echo "<center><h3>Total Cases by Department by Date Range:</h3><table class=tbloutline><tr><th class=admin width=160>Department</th><th class=admin width=40>Open</th>
            <th class=admin width=40>Closed</th><th class=admin>Total</th></tr>";
            

            foreach ($deptArr as $dept)
            {

                $query = "select count(*) from cases where status = 'Open' AND department_id = " . $dept->department_id . 
                " AND added_date between '" . $from . "' AND '" . $to . "'";
                $res = $dbh->query($query);
                $row1 = $dbh->fetch($res,'array');
                $openCases = $row1[0];
                print '<tr><td>' . $dept->department . '</td><td>' . $row1[0];
               
                $query2 = "select count(*) from cases where status <> 'Open' AND department_id = " . $dept->department_id .
                " AND added_date between '" . $from . "' AND '" . $to . "'";   
                $res2 = $dbh->query($query2);
                $row2 = $dbh->fetch($res2,'array');

                $total = $row1[0]+$row2[0];
                echo '</td><td>' . $row2[0] . '</td><td>' . $total;
                print '</tr>';
                               
                
               
                 
            }
        
           echo "</table></center>";
            debug("DEPARTMENT Report:" . $query . "\r\n" . $query2 . "\r\nTOTAL:" .$total);
            break;
            
           case "category":
           $catArr = lqCase::getCatList(); 
            
            echo "<center><h3>Total Cases by Category by Date Range:</h3><table class=tbloutline><tr>
            <th class=admin width=160>Category</th><th class=admin width=40>Open</th>
            <th class=admin width=40>Closed</th><th class=admin>Total</th></tr>";
            

            foreach ($catArr as $catid=>$catname)
            {

                $query = "select count(*) from cases where status = 'Open' AND category_id = " . $catid . 
                " AND added_date between '" . $from . "' AND '" . $to . "'";
                $res = $dbh->query($query);
                $row1 = $dbh->fetch($res,'array');
                $openCases = $row1[0];
                print '<tr><td>' . $catname . '</td><td>' . $row1[0];
   
                $query2 = "select count(*) from cases where status <> 'Open' AND department_id = " . $catid .
                " AND added_date between '" . $from . "' AND '" . $to . "'";   
                $res2 = $dbh->query($query2);
                $row2 = $dbh->fetch($res2,'array');

                $total = $row1[0]+$row2[0];
                echo '</td><td>' . $row2[0] . '</td><td>' . $total;
                print '</tr>';
                               
                
               
                 
            }
        
           echo "</table></center>";
            debug("CATEGORY Report:" . $query . "\r\n" . $query2 . "\r\nTOTAL:" .$total);
            break;    
            
            
            case "borrower_type":
           $btArr = array(array('Adult',1),array('Student',2)); 
            
            echo "<center><h3>Total Cases by Borrower Type by Date Range:</h3>
            <table class=tbloutline><tr><th class=admin width=160>Borrower Type</th><th class=admin width=40>Open</th>
            <th class=admin width=40>Closed</th><th class=admin>Total</th></tr>";
            

            foreach ($btArr as $bt)
            {

                $query = "select count(*) from cases where  
                cases.status = 'open' AND borrower_type  = '" . $bt[1] . 
                "' AND library_id = " . $_SESSION['library_id'] . " AND cases.added_date between '" . $from . "' AND '" . $to . "'";
                
                $res = $dbh->query($query);
                $row1 = $dbh->fetch($res,'array');
                $openCases = (empty($row1[0]))?0:$row1[0];
                
                print '<tr><td>' . $bt[0] . '</td><td>' . $openCases;
                   
                $query2 = "select count(*) from cases where  
                 cases.status = 'closed' AND borrower_type  = '" . $bt[1] . 
                "' AND library_id = " . $_SESSION['library_id'] . " AND cases.added_date between '" . $from . "' AND '" . $to . "'";
               
                $res2 = $dbh->query($query2);
                @$row2 = $dbh->fetch($res2,'array');
                $closedCases = (empty($row2[0]))?0:$row2[0];

                $total = $openCases+$closedCases;
                echo '</td><td>' . $closedCases . '</td><td>' . $total;
                print '</tr>';
                               
               
               
                 
            }
        
           echo "</table></center>";
            debug("BORROWER TYPE Report:" . $query . "\r\n" . $query2 . "\r\nTOTAL:" .$total);
            break;     
            
            
            case "member_type":
           $mtArr = array('Non-Member','Member'); 
            
            echo "<center><h3>Total Cases by Member Type by Date Range:</h3><table class=tbloutline><tr><th class=admin width=160>Department</th><th class=admin width=40>Open</th>
            <th class=admin width=40>Closed</th><th class=admin>Total</th></tr>";
            

            foreach ($mtArr as $mtk=>$mt)
            {

                $query = "select count(*) from cases where  cases.status = 'Open' AND member  = " . $mtk . 
                " AND cases.library_id = " . $_SESSION['library_id'] . " AND cases.added_date between '" . $from . "' AND '" . $to . "'";
                $res = $dbh->query($query);
                @$row1 = $dbh->fetch($res,'array');
                $openCases = (empty($row1[0]))?0:$row1[0];
                print '<tr><td>' . $mt . '</td><td>' . $row1[0];
                // print $query;  
                $query2 = "select count(*) from cases where " . 
                " cases.status <> 'Open' AND member  = " . $mtk . 
                " AND cases.library_id = " . $_SESSION['library_id'] . " AND cases.added_date between '" . $from . "' AND '" . $to . "'";
                $res2 = $dbh->query($query2);
                @$row2 = $dbh->fetch($res2,'array');
                $closedCases = (empty($row2[0]))?0:$row2[0];

                $total = $openCases+$closedCases;
                echo '</td><td>' . $closedCases . '</td><td>' . $total;
                print '</tr>';
                               
                
               
                 
            }
        
           echo "</table></center>";
            debug("MEMBER TYPE Report:" . $query . "\r\n" . $query2 . "\r\nTOTAL:" .$total);
            break; 
            
            case "source":
                       $sArr = array('phone','email','post','person'); 
            
            echo "<center><h3>Total Cases by source of enquiry by Date Range:</h3><table class=tbloutline><tr>
            <th class=admin width=160>Source</th><th class=admin width=40>Open</th>
            <th class=admin width=40>Closed</th><th class=admin>Total</th></tr>";
            $openCases = 0;
            
            foreach ($sArr as $sk=>$sv)
            {

                $query = "select count(*) from cases where " . 
                "  cases.status = 'Open' AND query_source  = '" . $sv . 
                "'  AND cases.library_id = " . $_SESSION['library_id'] . " AND cases.added_date between '" . $from . "' AND '" . $to . "'";
                $res = $dbh->query($query);
                @$row1 = $dbh->fetch($res,'array');
                
                $openCases = (empty($row1[0]))?0:$row1[0];
                print '<tr><td>' . $sv . '</td><td>' . $openCases;
                 
                $query2 = "select count(*) from cases,borrower where borrower.borrower_id = cases.borrower_id" . 
                " and cases.status <> 'Open' AND query_source  = '" . $sv . 
                "'  AND cases.library_id = " . $_SESSION['library_id'] . " AND cases.added_date between '" . $from . "' AND '" . $to . "'";
                $res2 = $dbh->query($query2);
                @$row2 = $dbh->fetch($res2,'array');
                $closedCases = (empty($row2[0]))?0:$row2[0];

                $total = $openCases+$closedCases;
                echo '</td><td>' . $closedCases . '</td><td>' . $total;
                print '</tr>';
                
                debug("REPORT BY SOURCE" . $query . '\r\n' . $query2);
                               
                
               
                 
            }
        
           echo "</table></center>";
            
            break; 
            
            
                        case "resolved_by":
                       $sArr = array('phone','email','post','person'); 
            
            echo "<center><h3>Total Cases by 'resolved by' by Date Range:</h3><table class=tbloutline><tr>
            <th class=admin width=160>Resolved By</th><th class=admin width=40>Open</th>
            <th class=admin width=40>Closed</th><th class=admin>Total</th></tr>";
            $openCases = 0;
            
            foreach ($sArr as $rv)
            {

                $query = "select count(*) from cases where " . 
                " cases.status = 'Open' AND response_type  = '" . $rv . 
                "'  AND cases.library_id = " . $_SESSION['library_id'] . " AND cases.added_date between '" . $from . "' AND '" . $to . "'";
                $res = $dbh->query($query);
                @$row1 = $dbh->fetch($res,'array');
                
                $openCases = (empty($row1[0]))?0:$row1[0];
                print '<tr><td>' . $rv . '</td><td>' . $openCases;
                 
                $query2 = "select count(*) from cases where " . 
                " AND cases.library_id = " . $_SESSION['library_id'] . " and  cases.status <> 'Open' AND responsetype  = '" . $rv . 
                "' AND cases.added_date between '" . $from . "' AND '" . $to . "'";
                $res2 = $dbh->query($query2);
                @$row2 = $dbh->fetch($res2,'array');
                $closedCases = (empty($row2[0]))?0:$row2[0];

                $total = $openCases+$closedCases;
                echo '</td><td>' . $closedCases . '</td><td>' . $total;
                print '</tr>';
                
                debug("REPORT BY Resolved by" . $query . '\r\n' . $query2);
                               
                
               
                 
            }
        
           echo "</table></center>";
            
            break; 
            
            
            
            case "country":
            
            echo "<center><h3>Total Cases by Country by Date Range:</h3><table class=tbloutline><tr><th class=admin width=160>Department</th><th class=admin width=40>Open</th>
            <th class=admin width=40>Closed</th><th class=admin>Total</th></tr>";
            
             $cArr = lqCountry::getCountryList();
             
            foreach ($cArr as $id=>$country)
            {

                $query = "select count(*) from cases where country_id = " . $id .  
                " and status = 'Open' " . 
                " AND cases.added_date between '" . $from . "' AND '" . $to . "'";
                $res = $dbh->query($query);
                $row1 = $dbh->fetch($res,'array');
                $openCases = $row1[0];
               
                $query2 = "select count(*) from cases where country_id = " . $id .  
                  " and status <> 'Open' " . 
                " AND cases.added_date between '" . $from . "' AND '" . $to . "'";
                $res2 = $dbh->query($query2);
                $row2 = $dbh->fetch($res2,'array');

                $total = $row1[0]+$row2[0];
                
                if ($total > 0)
                 echo '<tr><td>' . $country . '</td><td>' . $row1[0] .    
                 '</td><td>' . $row2[0] . '</td><td>' . $total . '</tr>';
                               
                
               
                 
            }
        
           echo "</table></center>";
            
            break;  
            
            case "user":
            $query = "SELECT users_id, user_name FROM users WHERE library_id = " . $_SESSION['library_id'];
            $res = $dbh->query($query);
            
            
            while ($row = $dbh->fetch($res,'array'))
            {
            $userArr[$row['users_id']] = $row['user_name'];
		    } 
           
    echo "<center><h3>Total Cases by User by Date Range:</h3><table class=tbloutline><tr><th class=admin width=160>User</th><th class=admin width=40>Open</th>
            <th class=admin width=40>Closed</th><th class=admin>Total</th></tr>";
 
       foreach ($userArr as $id=>$user)
       {           
               $query = "select count(*) from cases where assigned_to = " . $id .  
                " and status = 'Open' " . 
                " AND cases.added_date between '" . $from . "' AND '" . $to . "'";
                $res = $dbh->query($query);
                $row1 = $dbh->fetch($res,'array');
                $openCases = $row1[0];
                 
                $query2 = "select count(*) from cases where assigned_to = " . $id .  
                  " and status <> 'Open' " . 
                " AND cases.added_date between '" . $from . "' AND '" . $to . "'";
                $res2 = $dbh->query($query2);
                $row2 = $dbh->fetch($res2,'array');            
           

                $total = $row1[0]+$row2[0];
                
                if ($total > 0)
                 echo '<tr><td>' . $user . '</td><td>' . $row1[0] .    
                 '</td><td>' . $row2[0] . '</td><td>' . $total . '</tr>';
             debug("REPORT BY Use Id" . $query . '\r\n' . $query2);
             
		 }
		 echo "</table></center>";
            break; 
            
            case "resource":
            echo "<center><h3>Total Resources Used by Date Range:</h3><table class=tbloutline><tr>
            <th class=admin width=90>Resource Used</th><th class=admin>Times Used</th>
            </tr>";
            
            $resArr = Resource::getNames();
            
            foreach($resArr as $key=>$val)
            {
            $query = "select count(*) from resource_transaction where used_date between '" . 
            $from . "' and '" . $to . "' and resource_id = " . $key;
            $res = $dbh->query($query);
            $row = $dbh->fetch($res,'array');
            debug("RESOURCE Report" . $query);
             if ($row[0] > 0)
                 echo '<tr><td align = center>' . $val . '</td><td align=center>' . $row[0] .    
                 '</td></tr>';
             }
             
             
          
             echo "</table></center>";
             
            // if ($noResults=1)
             //echo $row[0] . "<center><font color = red>No results found</font></center>";
             
            break;          
                            
        }
        
   }
     
    }
}     

     
?>
