<?php
require_once 'class.layout.php';
require_once 'class.dbh.php';
/**
     * Class Department - Controller class for departments 
     * 
     * 
     *  This class adds, removes and modifies departments
     * 
     * 
     *
     * @author Peter Lorimer <peter@oslo.ie>
     * @license Interleaf
     * @copyright Interleaf Technology Ltd
     * @package LQ                                
     * Date: 22 July 2011
     */

class Department {
    
    private $name, $page;
    
    public function construct()
    {
        $this->name = $name;
        $this->page = $page;
    }
    
    public function listAll($library_id)
    {
        global $dbh;
        $objArr = array();
        $sql = "SELECT department_id, department from department WHERE library_id = " .
        $_SESSION['library_id'] . " order by department asc";
        $res = $dbh->query($sql);
        
      
        while ($obj = $dbh->fetch($res,'') )
        {
            $objArr[] = $obj;
        }
        
      
        return $objArr;    
    }
    
    public function getUserInput($page)
    {
        global $dbh;
        echo '<h2 align="center">' . $page . '</h2><br>';
                 
        echo '<p>' . 
     
       
            '<center><form method=post onsubmit="return validateDeptForm()">' . 
       'Department Name : <input type=text name=deptname>';
        
         
         
         if (User::checkAuth()==1 || User::checkAuth()==3)
         {
             
             $query = "SELECT library, library_id FROM library";
             
             if (User::checkAuth()==3)
              $query .= " where library_id = " . $_SESSION['library_id'];
             $res = $dbh->query($query);
             
             
        echo " Library: <select name=lib>" ;
        while ($obj = $dbh->fetch($res,''))
        {
         echo "<option value=" . $obj->library_id . ">" . $obj->library . "</option>"; 
         }
         echo '</select><br>';
	 }
         
         echo  '<input type=submit name=btnAddDept class=lq-button value = "Add this Department">';
         
         echo '
         </form>
         </center><br><br>';          
         
         
    }
   public function viewCases($department_id)
   {
        global $dbh;
        $even = 2;
        $query = "SELECT * from cases where  status = 'open' AND department_id = " . $_GET['dept'];
  
$link = "<a href=/?sort=date_added_desc&op=dept&dept=" . $_GET['dept'] . ">Date Added</a>";
 if (isset($_GET['sort']))
 {
	 switch ($_GET['sort'])
	 {
		 case "borrower":
         $query .= " order by borrower.surname asc ";
         break;
         
         case "date_added_desc":
         $query .= " order by added_date desc"; 
         $link = "<a href=/?sort=date_added_asc&op=dept&dept=" . $_GET['dept'] . ">Date Added</a>";
         break;
         
         case "date_added_asc":
         $query .= " order by added_date asc"; 
         $link = "<a href=/?sort=date_added_desc&op=dept&dept=" . $_GET['dept'] . ">Date Added</a>";
         break;         
         
         default:
         $query .= "  order by added_date desc ";
         break;
      }   
 }
       
        $res = $dbh->query($query);
        
        echo '<center> ';
                 echo '<table class = "admin" border="0" cellpadding="30">
          <tr><th colspan=4 class=admin><font color=black>Open Cases by ' . User::department($_SESSION['user_id'])->department . 
          '</font></th><tr>
          <tr class = "admin">
          <th class="admin" width="50" align=center>Case Id</th>
           <th class="admin" width="70" align=center>Date added</th>
           <th class="admin" width="160">Header</th>
           <th class="admin" width="200">Borrower</th>
           
         
           
            </tr>
          ';
          
          
          $today = strtotime("now"); 
        
          
          while ($obj = $dbh->fetch($res,''))
          {
	          $deadline = strtotime(substr($obj->deadline_date,0,10));
              $theBorrower = new Borrower($obj->borrower_id);
              $reviewdate = strtotime(substr($obj->review_date,0,10));
          if ( $today > $reviewdate  or $obj->status == 'open')
          {
              
              if ($today >= $deadline)
				  $textColor = "red";
			  else $textColor = "black";

          if ($even%2==0)
            $color = '#e0e0e0';
            else $color = '#a1a1a1';
              echo '  <tr bgcolor = ' . $color . '><td><a href=?op=cases&caseid=' . $obj->caseid . '>' . 
              $obj->caseid . '</a></td><td><font color=' . $textColor . '>' . lqCase::convertDate(substr($obj->added_date,0,10),'eu')  . 
              '</font></td><td width="400"><a href=?op=cases&caseid=' . $obj->caseid . '>' . substr($obj->case_header,0,150) . '...' .  
              '</td><td>' . $theBorrower->firstName . ', ' .  $theBorrower->surName .  '</td> 
             
              
              </tr>';
              $even++;
		  }
          } 
          echo '</table>';
       
   } 
   
    public function name  ($department_id)
  {
	  global $dbh;
	  
	  $sql = "SELECT department FROM department where department_id = " . $department_id;
	  $res = $dbh->query($sql);
	  $rec = $dbh->fetch($res,'');
	  
	  return $rec->department;
  }
  
  
    public function view()
    {
        global $dbh;
        $even = 2;
        
        if (isset($_REQUEST['did']) && !empty($_REQUEST['did']))
        {
			$query = "SELECT * FROM department WHERE department_id = " . $_REQUEST['did'];
			$res = $dbh->query($query);
			
			 $row = $dbh->fetch($res,'');
				 echo "<center><form  action = ?op=savedept METHOD=POST><input type=text name=dept value = '" . $row->department . "'>
				 <input type=hidden name=dept_id value=" . $_REQUEST['did'] . ">
				 <input type=submit value=Update class=lq-button></form></center>";
			 
           
		}
			else
			{
        $query = "SELECT * from department where library_id = " . $_SESSION['library_id'];
        
        $res = $dbh->query($query);
        
        echo '<center><h2>View / Modify Departments </h2><br>
        <table width=600>
        <tr bgcolor = "#343434">
        <th class=admin>Id</th><th class=admin>Department Name</th>
        <th colspan="2" class=admin>Action</th>
        </tr>';
        
        while ($row = $dbh->fetch($res,''))
        {
           
            if ($even%2==0)
            $color = '#e0e0e0';
            else $color = '#a1a1a1';
            echo '<tr bgcolor=' . $color . ' class="results"><td>' . $row->department_id . '</td>
            <td class="results">' . $row->department . '</td>
            <td align=right width=30><a href =?op=editdept&did=' . $row->department_id . '>Edit</a></td><td align=right width=40><a href=?op=deldept&did=' . $row->department_id . '>Delete</td></tr>';
            $even++;
        }
        
        echo '</table>';
        
	}
    }
 
     public function save($dept_id,$dept)
    {
       global $dbh;
       $query = "UPDATE department SET department = '" . $dept . "' WHERE department_id = " . $dept_id;
       $dbh->query($query);
       header("Location: ?op=editlib");
    }
    
    public function add()
    {
    global $dbh; 
    $department  =  $_REQUEST['deptname'];
    $library     =  $_REQUEST['lib'];
        
        $query = "SELECT department FROM department where department = '" . $department . 
        "' AND library_id = ". $library;
        
        $res = $dbh->query($query);
        $rows = $dbh->rows($res);
        
        if (!empty($department))
        {
           if ($rows > 0)
           {
             $errorStack[] = "ERROR: "  . $department . " is already in the database";
           }
        else {
               $query = "INSERT INTO department (department,library_id)" . 
                 "VALUES ('" . 
                 $department . 
                 "',". $library. ")"
                 ;
        
         $res = $dbh->query($query);        
        }
        
        }
        
        if (count($errorStack) > 0)
        {
            foreach ($errorStack as $error)
            {
                echo '<span class="errorStack"><center>' . $error . '</center></span>';
            }
        }
    }
    
    public function edit()
    {

            
    }
    
    public function del()
    {
    global $dbh;
            $id = $_REQUEST['did'];
            if (!empty($id))
            {
            $query = "DELETE FROM department where department_id = " . $id;
            $res = $dbh->query($query);
            } 
            
            if ($res)
            {  
             echo '<script>alert("Record Deleted"); location.href="?op=editdept"</script>';
            }
            else {
               
                 echo '<script>alert("ERROR:\r\n This record cannot be deleted. Please contact your systems administrator, the message associated with this error is  \"Referential Integrity Violation\""); location.href="?op=editdept"</script>'; 
            }
    }      
    
    public function __destruct()
    {
      $name = $this->name;
      $page = $this->page;
      unset($name); 
      unset($page);
    }
    
}
