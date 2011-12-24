<?php
require_once 'class.layout.php';
require_once 'class.borrower.php';  
require_once 'class.search.php';     
require_once 'class.dbh.php';
error_reporting(0);

 /**
     * Class lqCase -  Controller class for Case functionally 
     * 
     * 
     * This class handles all aspects of manipulating cases, 
     * a case is merely an ongoing query
     *
     * @author Peter Lorimer <peter@oslo.ie>
     * @license Interleaf
     * @copyright Interleaf Technology Ltd
     * @package LQ                                
     * Date: 22 July 2011
     */
class lqCase {
/**
* Function __construct()
* 
* Class constructor, not yet used
*     
*/
    public function __construct()
    {
        
    }
    
/**
* Function __destruct()
* 
* Class destructor, not yet used
*     
*/

    public function __destruct()
    {
        
        
    }
    
    
public function getLineItems($case_id)
{
    global $dbh;
     $even = 2;
     
    $query = "select * from case_history where case_id =" . $case_id . " limit 0,6";
    $res   = $dbh->query($query);
      //echo $query;
    $data =  "<table style='border-left:solid black 1px; border-right:solid black 1px;border-top:solid black 1px;border-bottom:solid black 1px;' width=85%>";//"<tr><th>Case#</th><th>User</th><td>Date</th><th>Details</th></tr>";
    
    
    while ($obj = $dbh->fetch($res,''))
    {
            if ($even%2==0)
            $color = '#f2f2f2';
            else $color = '#d1d1d1';
        $data .=  '<tr bgcolor=' . $color . '><td>' . 
                  '</td><td><font size=1>' . substr($obj->history_date,0,10) . '</font></td><td><font size=1>' . 
                  substr($obj->history,0,160) . '</font></td></tr>';
            $even++;
    }
    
    $data .= "</table>";
    return $data; 
}    

/**
* Function __searchForm()
* 
* Generates the HTML output for the case search form
*     
*/
       
    public function searchForm()
    {
	
	
       $html = "<center><font color=red size=+1><i>" . $_GET['error'] . "</i></font>
       <form name=searchForm id=searchForm method=post autocomplete=off onSubmit='return validateSearchForm();'>
       
          <p> <br><br>
       <table  cellspacing = 10 cellpadding=10> 
     <tr><td colspan=2><table cellspacing=5><tr><td>Search Type</td>
      <td width=15>&nbsp;</td>
     <td><input type=radio name=type value='local' checked></td>
     <td>Local</td><td width=25>&nbsp;<td><input type=radio name=type value='consortium'></td><td>Consortium</td></tr></table>
    
          
        <tr><td colspan=2><br><br></td></tr>
       <tr><td align=right>Criteria  </td><td>
       <select  id = criteria name=criteria onChange=toggleHidden(document.getElementById('search_dept')); appendOptionLast(count2++,\'undefined\');  >
     <!--  <option value=none>Please choose ...</option> -->
       <option value='all'>Everything</option> 
       <option value='case_id'>Case ID</option>
       <option value='borrower'>Borrower</option>
       <option value='department'>Department</option>        
       <option value='subject'>Subject</option>        
       <option value='header'>Case Header</option> 
       <option value='detail'>Case Details</option>        
       
              
       </select>
       <span id=criteria_error class=criteria_error> -- Please choose a criteria</span>
       </td></tr>
       
       
       <tr ><td></td>
       <td><select name= department id=search_dept class=hidden_dept>
       <option value=0>Choose a department</option>";  
     
     $deptArr = Department::listAll($_SESSION['library_id']);
    
    
     
     foreach ($deptArr as $obj)
     {
      $html .= '<option value = "' . $obj->department_id . '">' .
      $obj->department . '</option>';
     }
       
       $html .= "
       </select>
         <span id=dept_error class=dept_error> -- Please choose a department</span> 
       </td></tr>
       
       <tr><td align=right>Keyword </td><td><input type=text id=keywords name=keywords size=32 onkeyup=\"ajax_showSearchOptions(this,'getCountriesByLetters',event); return doFocus();\" onBlur=doBlur();></td></tr>
      
       <tr><td align=right>From</td><td><input type=text  name = from  size=10 id=calendar4 onClick=document.getElementById('keywords').focus()> 
       To  <input type=text name = to   size=10 id=calendar5>
         <span id=date_error class=date_error> -- In order to search by date blease chose both a from and to date</span> 
       </td></tr>
     
       <tr><td  colspan=2 align = right><center><input type=submit class = lq-button name=search value='Search'></td></tr>
       </table> </p>
       </form>
       
       "; 
       
       return $html;
    }

/**
* Function searchResults()
* 
* Displays the results form a case search in an HTML table
*  
* {@source}   
*/

    public function searchResults($user_id='',$home='')
    {
          global $dbh;
          $sTerm = $_POST['keywords'];
          $from = lqCase::convertDate($_POST['from'],'us');
          $to = lqCase::convertDate($_POST['to'],'us');
          $even = 2;	
          
          if (isset($_POST['department']) && !empty($_POST['department']))
          {
			  $department = $_POST['department'];
			}
          
                   
          if (isset($_POST['keywords']) && !empty($_POST['keywords']))
          {
             $sTerm = $_POST['keywords'];     
          }
          
          if (strpos(substr($sTerm,0,2),'"') == 1 && strpos(substr($sTerm,strlen($sTerm)-2,strlen($sTerm)),'"') == 1 )
          {
              $phrase = 'true'; 
          } 
          else { $phrase = 'false';}	
          
          
          
             
       if (isset($_REQUEST['search']) || !empty($home))
       {
		   
             switch ($_POST['criteria'])
             {
                case "borrower":               
                $results = lqSearch::searchByBorrower($sTerm);
                lqSearch::displayResults($results);
                break;
                
                case "case_id":               
                $results = lqSearch::searchByCaseId($sTerm);
                break;
                                
                case "header":
                $results = lqSearch::searchByCaseHeader($sTerm);
                lqSearch::displayResults($results);
                break;
                
                case "detail":
                $results = lqSearch::searchByCaseDetails($sTerm);
                lqSearch::displayResults($results);
                break;
               
                case "subject":
					$results = lqSearch::searchBySubject($sTerm);
					debug("in here" . $results);
					lqSearch::displayResults($results);
                break;
                
                case "department":
					$results = lqSearch::searchByDepartment($_POST['department'], $sTerm);
					//die($_POST['department']);
					lqSearch::displayResults($results);
                break;
                
                default:
					$results = lqSearch::searchAll($sTerm);
					lqSearch::displayResults($results);
                break;
             }
       }         
		
	}
       /**
    public function searchResults1($user_id='',$home='')
    {
          global $dbh;
          $sTerm = $_POST['keywords'];
          $from = lqCase::convertDate($_POST['from'],'us');
          $to = lqCase::convertDate($_POST['to'],'us');
          $even = 2;
          

          
          if (isset($_POST['keywords']) && !empty($_POST['keywords']))
          {
             $sTerm = $_POST['keywords'];     
          }
          
          if (strpos(substr($sTerm,0,2),'"') == 1 && strpos(substr($sTerm,strlen($sTerm)-2,strlen($sTerm)),'"') == 1 )
          {
              $phrase = 'true'; 
          } 
          else { $phrase = 'false';}
          
          if (isset($sTerm))
          {
              $likeClause = " case_header LIKE '%" . $sTerm . "%'";
          }
          else { $likeClause = " 1 "; }
          
          if (empty($user_id))
          {
              $user_id = $_SESSION['user_id'];
          }
                  
          
          if ($home != '')
          {
              $extra = ' AND assigned_to = ' . $_SESSION['user_id'];
          }
          
          
          if ($_POST['type'] != 'local' && empty($home))
          {
          $query =  "SELECT * from cases WHERE " .
          $likeClause . " " . 
          " AND status = 'Open'";              
              
          }
          else {
          $query =  "SELECT * from cases WHERE " .
          $likeClause . " AND library_id = " 
          . $_SESSION['library_id'] .
          " AND status = 'Open'";
          }
          
          
          if (isset($extra) && !empty($extra))
          {
              $query .= " " . $extra;
          }
        
         
         if (empty($home))
         {
             $caption = "Search Results";
         }
         else
         {
             $caption = "Open cases for " . $_SESSION['username'];
             // $caption = "Search Results";
         }
          
   
       if (isset($_POST['search']) || !empty($home))
       {
             switch ($_POST['criteria'])
             {
                case "borrower":
                lqSearch::searchByBorrower($_POST['criteria']);
                break;

                                
                                $borArr = explode(" ",$sTerm);
                                
                                $whereClause = " WHERE surname =  '" . substr($borArr[0],0,strlen($borArr[0])-1) . "' and firstname like  '" . $borArr[1] . "%'";
/*                foreach ($borArr as $bor)
                {
					if (!empty($bor))
                $whereClause .= " or surname LIKE '%" . $bor . "%' or firstname like '%" .
                $bor . "%' ";
			}
*/  /*
              $sql = "SELECT borrower_id FROM borrower" . $whereClause; 
                 
              
                
                $res = $dbh->query($sql);
if (empty($from) || empty($to))
$borquery = "select * from cases where borrower_id in ( " .$sql . ")";
else
                $borquery = "select * from cases where borrower_id in ( " .$sql . ") AND added_date BETWEEN '" . $from . "' AND '" . $to . "'";
             
 debug("BORROWER Q" . $borquery);               
                    
               
                                
                break;
                
                case "subject";
$query = "select distinct(case_id) from subjects, subject_case, cases where subject_case.subject_id=subjects.subject_id 
AND subject_case.case_id = cases.caseid AND 
subjects.subject LIKE '%" . $sTerm . "%' order by added_date desc";

                break;
                
                
                case "department":
                $extra .= " AND department_id = " . $_POST['department'];
                
                if (!empty($sTerm) )
                  $extra .= " AND (case_header LIKE '%" . $sTerm . "%' OR case_detail LIKE '%" . $sTerm . "%'" .
                  " OR case_response LIKE '%" . $sTerm . "%'" .
                  ")"
                  ;  
                      if (!empty($from) && !empty($to))
                      {
                      $extra .= " AND added_date BETWEEN '" . $from . "' AND '" . $to . "' ";
                      } 
                break; 
                
                case "header":               
                $extra .= "  AND case_header LIKE '%" . $sTerm . "%'";
                     if (!empty($from) && !empty($to))
                     {
                       $extra .= " AND added_date BETWEEN '" . $from . "' AND '" . $to . "' ";
                     }                               
                break;
                


                case "detail":               
                $extra .= " AND case_detail LIKE '%" . $sTerm . "%'";   
                  if (!empty($from) && !empty($to))
                     {
                       $extra .= " AND added_date BETWEEN '" . $from . "' AND '" . $to . "' ";
                     }                                                           
                break;      
                
                case "all":
                $sTermArr = explode(" ", $sTerm);
                $extra .= " AND (case_header LIKE '%" . $sTerm . "%'";
 
               foreach ($sTermArr as $term)
                {
                   if (!empty($term))					
                $extra .= 
                " or case_header LIKE '%" .  $term . "%'" .
                " or case_detail LIKE '%" .  $term . "%'" .
                " or case_response LIKE '%" .  $term . "%'"; 
              
			 } 
			 $extra .=   ")";
                              
                break;
                
                case "header":
               
                $extra .= " AND case_detail LIKE '%" . $sTerm . "%'"; 
  
                              
                break;                                                                         
             }
             
 

    
  // Find individual case  
 
 if ($_POST['criteria'] == 'case_id')
 {
 $sql = "SELECT * FROM cases WHERE caseid=" . $sTerm;
 
 $res = $dbh->query($sql);
 if (@$dbh->rows($res) > 0)
 {
     header("Location: /?op=cases&caseid=" . $sTerm);
 }
 else 
 {
	 print "<script>alert('This case does not exist');</script>";
	 $searchError = "Case+'<b>" .  $sTerm . "</b>'+does+not+exist";
	 header("Location: /?op=search&error=" . $searchError);
 }
 
}
 
 if ($phrase == 'false' && $_POST['criteria'] == 'all')
 {
     $sTermArr = explode(" ",$sTerm);



 /*    $extra .= " AND (1 ";


     foreach ($sTermArr as $term)
     {    
          if (strlen($term) > 0)
         $extra .= " OR  case_header LIKE '%" . trim(trim($term,'\\"')) . "%' " .
                   " OR  case_response LIKE '%" . trim(trim($term,'\\"')) . "%' " .
                   " OR  case_detail LIKE '%" . trim(trim($term,'\\"')) . "%' ";
         
     }
     $extra .= ")";
    
    */ /*
     if (!empty($from) && !empty($to))
     {
     $extra .= " and caseid in (
select caseid from cases where  added_date between '" . $from . "' AND '" . $to . "')";
     }
   //  $extra .= ") ";
     
$extra1 = $extra;     
 }   
 else {
     
     $extra1 = lqCase::no_magic_quotes($extra);
 }
 
 if ($_POST['criteria'] == "subject")
 {
    
  $res = $dbh->query($query);
  while ($obj = $dbh->fetch($res,''))
  {
      $subjArr[] = $obj->case_id;
  }
  
  $query =  "SELECT * from cases WHERE  0 ";
         
  $extra1 = "";
  foreach ($subjArr as $case)
  {
    $extra1 .=  " or caseid =" . $case;  
  }

    $query = "select  * from subject_case, subjects,cases where subject_case.subject_id=subjects.subject_id 
    AND subject_case.case_id = cases.caseid AND subjects.subject LIKE '%" . $sTerm ."%' ";
    
         if (!empty($from) && !empty($to))
         {
     $query .= " AND caseid in ( select caseid from cases where added_date between 
    '" . $from . "' AND '" . $to . "' ) ";
 
         }
         
         		 debug("Search Query - Subject\r\n" . $query);
 }       
 else {      
           


 
 if ($sTerm[0] == '"' && $sterm[strlen($sTerm)-1] == '"')
 {
	 $sTerm2 = substr($sTerm,1,strlen($sTerm)-2); 
	 $extra1 = " and case_header = '" . $sTerm2 . "' or case_detail = '" . $sTerm2 . "' or case_response = '" . $sTerm2 . "'";

 }
                  
if ($_POST['type'] == 'local')
{
 $query =  "SELECT distinct(caseid),  status, cases.borrower_id, added_date, deadline_date, case_header, assigned_to from cases WHERE  " .
          " cases.library_id = " 
          . $_SESSION['library_id'] . $extra1 ;   
          debug("EXTRA: " . $extra1);   
    
}   

else {
 $query =  "SELECT distinct(caseid),  status, cases.borrower_id, added_date, deadline_date, case_header, assigned_to from cases 
 WHERE  ( (1" . $extra1 . ") AND status = 'open' or cases.library_id = " . $_SESSION['library_id'] . $extra1 . ")"
  ;     
 
 
   
}         
 }
  
 
 
 		 debug("Search query\r\n" . $query);
 
 if ($_POST['criteria'] == "borrower")
 {
	 $res = $dbh->query($borquery);
 }
 
 else
  $res   =  $dbh->query($query);  

$op = $_GET['op'];
if ($op == "")
$op = "home";
 
 debug("SEARCH QUERY EXECUTED:" . $query);
 debug("SEARCH TYPE:" . $_POST['type']);
$numRows = $dbh->rows($res);
           if ($numRows <= 0 )
           {
			   if ($op == "home")
			   echo "<p><font color=green><i>You have no open cases</i></font></p>";

			   else
			   echo "<p><font color=red><i>Your search returned no results. Please try a different search</i></font></p>";
		   }
		   else {
			   if ($sTerm == '')
			   $sTerm = ' none';
		  if ($home == '')
                   $caption .= ' - Showing ' . $numRows; 
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
          
          $today = date("Y-m-d"); 
                    
          while ($obj = $dbh->fetch($res,''))
          {
           $deadline = $obj->deadline_date;
          debug("DEADLINE:DATE" . $deadline . ":" . $today); 
               if ($today >= $deadline)
				  $textColor = "red";
			  else $textColor = "black";
              $theBorrower = new Borrower($obj->borrower_id);
              
              if (empty($theBorrower->surName) && empty($theBorrower->firstName))
              $borrowerName = "Anonymous";
              else 
              $borrowerName = $theBorrower->surName . ', ' . $theBorrower->firstName;
             
          if ($even%2==0)
            $color = '#f2f2f2';
            else $color = '#d1d1d1';
              echo '  <tr bgcolor = ' . $color . '><td><a href=?op=cases&caseid=' . $obj->caseid . '>' . 
              $obj->caseid . '</a></td><td>' . $obj->status . '</td><td><font color=' . $textColor . '>'. lqCase::convertDate(substr($obj->added_date,0,10),'eu')  . 
              '</font></a></td><td width="400"><a href=?op=cases&caseid=' . $obj->caseid . '>'  . substr($obj->case_header,0,50) . '...' .  
              '</a></td><td>' . $borrowerName .  '</td><td>' . $this->getLibrary($obj->caseid)->library . '</td> 
         
          
              </tr>';
              $even++;
          } 
          echo '</table>';
	  }
       }
       

    }*/
    
    public function getLibrary($case_id)
    {
		global $dbh;
		$query = "SELECT library.library_id, library.library from cases, library  where cases.library_id=library.library_id and caseid = " . $case_id;
        $res = $dbh->query($query);
        $obj = $dbh->fetch($res);
        
       // die($query);
        return($obj);
	}

 /**
* Function getCatList()
* 
* Creates a hash map of global category names with the id as an index
*  
* {@source}
*  @return array $catMap   
*/
   
    public function getCatList()
    {
        global $dbh;
        $subjectsMap = array();
        $sql = "SELECT category_id, category FROM category order by category asc";
        $res = $dbh->query($sql);
        

        
        while ($row = $dbh->fetch($res,'array'))
        {
         $catMap[$row['category_id']] = $row['category'];   
        }
        
     return $catMap;   
    }    

 /**
* Function subjectId()
* 
* Returns the subject_id given a subject name
* {@source}
* @param string $subject
*  
* @return integer $subject_id   
*/    
    public function subjectId($subject)
    {
        global $dbh;
        
        $sql = "SELECT subject_id FROM subjects where subject = '" . $subject . "'";
        $res = $dbh->query($sql);
        $obj = $dbh->fetch($res,'');
        
        return $obj->subject_id;
    }   
    
    
     
 /**
* Function getSubjectList()
* 
* Creates a hash map of global suject names with the id as an index
* based on a user supplied search term
*  
*  {@source}
*  @param string $term
*  @return array $subjectMap   
*/        
    public function getSubjectList($term)
    {
        global $dbh;
        $subjectsMap = array();
        $sql = "SELECT subject_id, subject FROM subjects where subject = '" . $this->sanitizeFormData($term) . "' order by subject asc";
        $res = $dbh->query($sql);
        
        $rows = $dbh->rows($res);
        
        if ($rows == 0)
        {
        $sql = "SELECT subject_id, subject FROM subjects where subject like '" . $this->sanitizeFormData($term) . "%' order by subject asc";
        $res = $dbh->query($sql);           
        }
 
        
        while ($row = $dbh->fetch($res,'array'))
        {
         $subjectsMap[$row['subject_id']] = $row['subject'];   
        }
        
     return $subjectsMap;   
    }        
 /**
* Function addSubjects()
* 
*  Takes a list of subjects and adds them to the database
*  
*  {@source}
*  @param string $data
*   
*/          
    public function addSubjects($data)
    {
       global $dbh;
       $subjArr = preg_split('/\R/',$data);
       
       
       foreach ($subjArr as $subj) 
       {
           $subj1 = preg_replace('/(?<!,) /', '_', $subj); 
           $sql = "INSERT INTO subjects (subject) VALUES ('" .$subj1 . "')";
           $dbg[] = $sql;

       }
       echo '<!--';
       print_r($dbg);
       echo '-->';
    }
 
  /**
* Function departmentId()
* 
* Returns the department given a case_id 
* {@source}
* @param integer $case_id
*  
* @return integer $department_id   
*/    
    public function departmentId($case_id)
    {
           global $dbh;
        
        $sql = "SELECT department_id FROM cases where caseid = " . $case_id;
        $res = $dbh->query($sql);
        $obj = $dbh->fetch($res,'');
        
        $sql = "SELECT * FROM department where department_id = " . $obj->department_id;
        $res = $dbh->query($sql);
        $obj = $dbh->fetch($res,'');       
        
        return $obj;
    }   
    
    
  
        
    public function sanitizeFormData($data)
    {
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    
    } 
 /**
* Function addCatForm()
* 
*  Generates the HTML for adding a category user input
*    
*/      
    public function addCatForm($page)
    {        
                echo '<h2 align="center">' . $page . '</h2><br>';
                 $form = new HTML_Form(); 
        $output =  '<p>' . 
     
       
        $form->startForm('#', 'post', 'frmAddCat',array('name'=>'addUser','autocomplete'=>'off','onSubmit'=>'return validateCatForm()')) .          
        '<center><table><tr><td>' . 
        $form->addLabelFor('catname', 'Category Name') . ' </td><td>'  .
        $form->addInput('text', 'catname', $name, array('id'=>'catname', 'size'=>32, 'maxlength'=>50) ) . 
         '<span id="catame_error" class="catname_error"> -- This Field is Required</span></td></tr><tr>' .  
         '<td colspan="2" align=center>' . $form->addInput('submit', 'btnAddCategory', "Add this Category",array('class'=>'lq-button')) .   '</td></tr>
         
         </table>
         </center><br><br>'; 
         
         return $output; 
        
    } 

 /**
* Function addCat()
* 
* Adds a category based on user input
*  
* {@source}
*/      
    public function addCat() 
    {
    global $dbh;  
        
            $cat =  $_REQUEST['catname'];
        
        $query = "SELECT category FROM category where category = '" . $cat . "'";
        $res = $dbh->query($query);
        $rows = $dbh->rows($res);
        
        if (!empty($cat))
        {
           if ($rows > 0)
           {
             $errorStack[] = "ERROR: "  . $cat . " is already in the database";
           }
               else {
                      $query = "INSERT INTO category (category)" . 
                        "VALUES ('" . 
                         $cat . 
                         "')"
                         ;

     
                if ($res = $dbh->query($query))
                {
                 echo "<script>alert('Record Added'); location.href='?op=editcat';</script>";
                } 
                else
                 echo "<script>alert('There was an error adding this record'); location.href='?op=editcat';</script>";  
                
                  }
                  
            if (count($errorStack) > 0)
            {
                foreach ($errorStack as $error)
                {
                echo '<span class="errorStack"><center>' . $error . '</center></span>';
                }
            }
        
       }
    }
    
    
    public function saveCat($cat_id,$cat)
    {
       global $dbh;
       $query = "UPDATE category SET category = '" . $cat . "' WHERE category_id = " . $cat_id;
       $dbh->query($query);
       header("Location: ?op=editcat");
    }
    
 /**
* Function viewCats()
* 
*   Displays categories with option to edit or delete
*  
*  
*/      
    public function viewCats()
    {
        global $dbh;
        $even = 2;
       
       
        if (isset($_REQUEST['cid']) && !empty($_REQUEST['cid']))
        {
			$query = "SELECT * FROM category WHERE category_id = " . $_REQUEST['cid'];
			$res = $dbh->query($query);
			
			 $row = $dbh->fetch($res,'');
				 echo "<center><form  action = ?op=savecat METHOD=POST><input type=text name=cat value = '" . $row->category . "'>
				 <input type=hidden name=cat_id value=" . $_REQUEST['cid'] . ">
				 <input type=submit value=Update class=lq-button></form></center>";
			 
           
		}
			else {
        $query = "SELECT * from category";
        
        $res = $dbh->query($query);
        
        echo '<center><h2>View / Modify Categories </h2><br>
        <table width=600>
        <tr bgcolor = "#343434">
        <th class=admin>Id</th><th class=admin>Category Name</th>
        <th colspan="2" class=admin>Action</th>
        </tr>';
        
        while ($row = $dbh->fetch($res,''))
        {
           
            if ($even%2==0)
            $color = '#e0e0e0';
            else $color = '#a1a1a1';
            echo '<tr bgcolor=' . $color . ' class="results"><td>' . $row->category_id . '</td>
            <td class="results">' . $row->category . '</td>
            <td align=right width=30><a href =?op=editcat&cid=' . $row->category_id . '>Edit</a></td><td align=right width=40><a href=?op=delcat&cid=' . $row->category_id . '>Delete</td></tr>';
            $even++;
        }
        
        echo '</table>';
	}
        
    }
 /**
* Function delCats()
* 
*   Deletes a category
*  
*  
*/      
    public function delCat()
    {
        
            global $dbh;
            $id = $_REQUEST['cid'];
            if (!empty($id))
            {
            $query = "DELETE FROM category where category_id = " . $id;
            $res = $dbh->query($query);
            } 
            
            if ($res)
            {  
             echo '<script>alert("Record Deleted"); location.href="?op=editcat"</script>';
            }
            else {
               
                 echo '<script>alert("ERROR:\r\n This record cannot be deleted. Please contact your systems administrator, the message acssociated with this error is  \"Referrential Integrity Violation\""); location.href="?op=editcat"</script>'; 
            }
    }


public function isMember($borrower_id)
{
	global $dbh;
	$rtn = array();
	$sql = "SELECT member FROM borrower WHERE borrower_id = " . $borrower_id;
	$res = $dbh->query($sql);
	$obj = $dbh->fetch($res,'');

    $rtn[0] = $obj->member;

     if ($obj->member == 1)
     $rtn[1] = 'true';
     
     else
     $rtn[1] = 'false';
     
     return $rtn;
}

 /**
* Function processCaseData()
* 
*   Processes Case data by addin/updating a database record based on user input
*   This function adds data to several tables insice a transaction with rollback
*   capabilities
*  
*   {@sourse}
*  
*/                  
    public function processCaseData()
    {
		
        global $dbh;
        $borrower    = str_replace("'","`",$_REQUEST['borrower']);
        $department_id = 1;
        
        $borrower_id            =   $this->sanitizeFormData($_REQUEST['borrower_hidden']);
        $status                 =   $this->sanitizeFormData($_REQUEST['status']);
        $borrowerType           =   $this->sanitizeFormData($_REQUEST['borrowerType']);
        $borrowerIsMember       =   $this->sanitizeFormData($_REQUEST['borrowerIsMember']);
        if ($borrowerIsMember == '')
        $borrowerIsMember = 0;
        $addrStreet             =   $this->sanitizeFormData($_REQUEST['addrStreet']);
        $addrArea               =   $this->sanitizeFormData($_REQUEST['addrArea']);
        $addrTown               =   $this->sanitizeFormData($_REQUEST['addrTown']);
        $addrCounty             =   $this->sanitizeFormData($_REQUEST['addrCounty']);
        $country                =   $this->sanitizeFormData($_REQUEST['country']);
        $phone                  =   $this->sanitizeFormData($_REQUEST['phone']);
        $email                  =   $this->sanitizeFormData($_REQUEST['email']);
        $dateAdded              =   lqCase::convertDate(substr($this->sanitizeFormData($_REQUEST['dateAdded']),0,10),'us');
        $deadline               =   $this->sanitizeFormData($_REQUEST['deadline']);
        $source                 =   $this->sanitizeFormData($_REQUEST['source']);
        $reviewAfter            =   $this->sanitizeFormData($_REQUEST['reviewAfter']);
        $category_id            =   $this->sanitizeFormData($_REQUEST['category']);
        $caseHeader             =   $this->sanitizeFormData($_REQUEST['caseHeader']);
        $subjects               =   $_REQUEST['subjects'];
        $caseDetail             =   $this->sanitizeFormData($_REQUEST['caseDetail']);
        $caseResponse           =   $this->sanitizeFormData($_REQUEST['caseResponse']);
        $caseHistory            =   $this->sanitizeFormData($_REQUEST['caseHistory']);        
        $referredTo             =   $this->sanitizeFormData($_REQUEST['referredTo']);
        $caseNotes              =   $this->sanitizeFormData($_REQUEST['caseNotes']);
        $responseDate           =   $this->sanitizeFormData($_REQUEST['responseDate']);
        $responseType           =   $this->sanitizeFormData($_REQUEST['responseType']);
        $querySrc               =   $this->sanitizeFormData($_REQUEST['sourceType']);
        $publish                =   $this->sanitizeFormData($_REQUEST['publish']);
        $assigned_to            =   $this->sanitizeFormData($_REQUEST['assignedTo']);  
        $assigned_by            =   $this->sanitizeFormData($_REQUEST['assignedBy']);  
        $referred_to            =   $this->sanitizeFormData($_REQUEST['referredTo']);  
        $category               =   $this->sanitizeFormData($_REQUEST['category']); 
  
        $error = false;
   if (empty($borrowerIsMember))
   $borrowerIsMember = 0;     
        
         $theBorrower = new borrower($borrower_id);
         $theCountry  = new lqCountry($addrCounty);
        
        
        if (empty($borrower_id))
        {
			$borObj = $this->getBorrowerClass($_REQUEST['caseid']);
            $borrower_id =  $borObj[id];
            
		}
        if ($borrower_id == '')
        { $borrower_id = 0;}
        
        if ($publish == 'publish')
        {
            $publish_id = 0;
        }
        else { $publish_id = 1; }
        
        if ($borrowerIsMember == 'member')
        {
            $member_id = 1;
        }
        else { $member_id = 0;}
        
        if (!isset($department_id) && !empty($department_id)) 
        {}
        else { $dapartment_id = 1;}
     // Step one build our queries before executing the transaction
      
     // Subjects

     $dbh->query('SET AUTOCOMMIT=0');
     $dbh->query('BEGIN');
     
     $surname = trim($theBorrower->getInputNameValue($borrower,1));
     $firstname = trim($theBorrower->getInputNameValue($borrower,2));
     
     if ($borrower == ",")
     {
     $surname = "NULL";
     $firstname = "NULL";  
     }  
     debug("BORROWER ID" . $borrower_id);
              if ($borrower_id)
              {
				 
				  $query = "UPDATE borrower set surname = '" .  $surname . "',
				   firstname = '" .  $firstname . "',
				   borrower_type = " . $borrowerType . ", addr1 = '" . $addrStreet . "', addr2 = '" . $addrArea . 
				   "', town = '" . $addrTown . "', county = '" . $addrCounty . "', phone = '" . $phone . "',
				   email = '" . $email . "', country_id = " . $country . ", member = " . $member_id  . 
				   " where borrower_id = " . $borrower_id ;
				   $dbh->query($query);
				   debug("QUERY to Add/Update Borrower\r\n" . $query);  
				  
			  }
			  else {
				  
     $query = "INSERT INTO borrower" . 
              "(surname, firstname, borrower_type, addr1, addr2, town, county, phone, email, country_id, member, library_id)" .
              "VALUES" . 
              "(" .
              "'" . $surname . "','" . $firstname . "'," . 
              $borrowerType . ",'" . $addrStreet . "','" . $addrArea . "','" . $addrTown . "','" . $addrCounty . "','" . 
              $phone . "','" . $email . "'," . $country . "," . $member_id . "," . $_SESSION['library_id'] .
              ")";
              
              
             if (empty($_REQUEST['borrower_hidden']))         
             { 
                 $dbh->query($query); 
             }
              
     		 debug("QUERY to Add/Update Borrower\r\n" . $query);         
        
    $dbh->query("LOCK TABLES borrower write");
    $res = $dbh->query("select borrower_id from borrower order by borrower_id desc limit 0,1");
    $dbh->query("UNLOCK TABLES");
        $row = $dbh->fetch($res,'array');
    $borrower_id = $row[0];
              }
             
             
     
     if (isset($_GET['caseid']))
     {
       if (empty($assigned_to))
        $library_id = $_SESSION['library_id'];
       else
        $library_id = User::libraryId($assigned_to);



       if (!empty($assigned_to))
        $department_id = User::department($assigned_to)->department_id;


		 debug("DateAdded: " . $dateAdded);
      $query = "UPDATE cases set assigned_by = " . User::idFromName($assigned_by) . ",borrower_type = " . $borrowerType .
      ", borrower_id=" . $borrower_id . ", member = " . $borrowerIsMember  . " , status = '" . $status . "', country_id = '" . $country . "', added_date = '" .
      lqCase::convertDate($dateAdded,'us') . "', deadline_date = '" . lqCase::convertDate($deadline,'us') . "', review_date = '" . lqCase::convertDate($reviewAfter,'us') . "', case_type= " .
      $category_id . ",case_header = '" . $caseHeader . "', case_detail = '" . $caseDetail . "',case_response='" .
      $caseResponse .  "' ,referred_to='" . $referred_to . "',response_type='" . $responseType . 
      "',category_id='" . $category . "',response_date='" . lqCase::convertDate($responseDate,'us') . "',query_source='" . $querySrc . "', publish=" . $publish_id .  ", library_id = " . $library_id . ",assigned_to=" . $assigned_to . ", department_id = " . $department_id . "
      WHERE caseid = " . $_GET['caseid'];    
     }
     else
     {
     $query = "INSERT INTO `lq`.`cases` (`caseid`, `status`, `borrower_id`, borrower_type, member, `country_id`, 
     `added_date`, `deadline_date`, `library_id`, `review_date`, `case_type`, `case_header`, `case_detail`, `case_response`,
      `assigned_by`, `response_date`, `response_type`, `publish`,  `department_id`, `category_id`,`assigned_to`) 
     VALUES 
     (NULL, '$status', $borrower_id, $borrowerType, $borrowerIsMember, '$country', ' " . lqCase::convertDate($dateAdded,'us') . "', 
     '" . lqCase::convertDate($deadline,'us') . "', '$library_id, '" . lqCase::convertDate($reviewAfter,'us') . "', $category_id, '$caseHeader', '$caseDetail', 
     '$caseResponse', '$assigned_by', 
      '" . lqCase::convertDate($responseDate,'us') . "', '$responseType', $publish_id, $department_id, $category_id,$assigned_to)";
     }
     $dbh->query($query); 
     
    
		 debug("QUERY to Add/Update Case\r\n" . $query);
	  
  
    $dbh->query("LOCK TABLES cases write");
    $res = $dbh->query("SELECT LAST_INSERT_ID() FROM cases");
    $dbh->query("UNLOCK TABLES");
    
    $row = $dbh->fetch($res,'array');
    $case_id = $row[0];
    
    if (empty($case_id))
    $case_id = $_GET['caseid'];
    
    
    if (!empty($_REQUEST['caseHistory']))
    {
        if (isset($_GET['caseid']))
        $case_id = $_GET['caseid'];
    $query = "insert into case_history (case_id, user_id, history) values (" .  
    $case_id . "," . $_SESSION['user_id'] . ",'" .  $caseHistory . "' )";
   
    $dbh->query($query);
    }
    
    $dbh->query('START'); 
 
   if (count($_REQUEST['subjects']) > 0)    
  {                    
 
     foreach ($_REQUEST['subjects'] as $val)
     {
		//$squery = "SELECT case_id FROM sunject_case WHERE subject_id = " . $this->subjectId($val);
//$subjectsQuery[] = "delete from subject_case WHERE case_id = " . $_GET['caseid'] . " and subject_id != " . $this->subjectId($val);

$subjVal = $this->subjectId($val);
    if (!empty($subjVal))
          $subjectsQuery[] = "INSERT INTO subject_case (case_id,subject_id) values (" . $_GET['caseid'] . "," . $this->subjectId($val) .")";   
         
     }
    } 
      if (count ($subjectsQuery) > 0)
      {
		  $squery = "delete from subject_case WHERE case_id = " . $_GET['caseid'];
          $dbh->query($squery);
                  foreach ($subjectsQuery as $val)
                  {   
		           debug ("SUBJECTS QUERY" .  $val . "\r\n" . mysql_error());
                   $dbh->query($val);       
                  }
     } 

 
      if ($dbh->dbErrno() <= 0)
      {
       $dbh->query('COMMIT');
      }
      else {  $dbh->query('ROLLBACK'); }   
      
      
   Header("Location: ?op=cases&caseid=" . $case_id);
     }
     
     
     public function no_magic_quotes($query) {
        $data = explode("\\",$query);
        $cleaned = implode("",$data);
        
        $data =  explode('"',$cleaned);
        $cleaned = implode("",$data);   
        return $cleaned;
}

 /**
* Function sddNewCase()
* 
*   Populated the HTML form for adding a new case and population various 
*   dropdowns
*  
*  
*/      
    public function addNewCase()
    {
        global $dbh;
        $caseId = '-- auto generated --';
        $assigned_by = User::nameFromId($_SESSION['user_id']);
        
        if (isset($_GET['caseid']))
        {
            $query = "SELECT * FROM cases WHERE caseid = " . $_GET['caseid'];
            $res = $dbh->query($query);
            $obj = $dbh->fetch($res,'');
            //echo $query;
            $caseId = $obj->caseid;
            $caseStatus = $obj->status;
            $added_date_disp = lqCase::convertDate(substr($obj->added_date,0,10),'eu');
            $added_date = lqCase::convertDate(substr($obj->added_date,0,10),'eu');
            $deadline_date = lqCase::convertDate(substr($obj->deadline_date,0,10),'eu');
            $review_date = lqCase::convertDate(substr($obj->review_date,0,10),'eu');
            $case_header = $obj->case_header;
            $case_detail = $obj->case_detail;
            $case_response = $obj->case_response;
            $assigned_by = $obj->assigned_by;
            $assigned_to = $obj->assigned_to;
            $borrower_type = $obj->borrower_type;
            $response_date = lqCase::convertDate(substr($obj->response_date,0,10),'eu');
            $responseType  = $obj->response_type;
            $member        = $obj->member;
            $querySrc      = $obj->query_source;
            $referred_to   = $obj->referred_to;
            $borrower_id   = $obj->borrower_id;
            $category_id   = $obj->category_id;
            $theBorrower   = $this->getBorrowerClass($caseId);
            
            $query = "SELECT * FROM borrower WHERE borrower_id = " . $borrower_id;
            debug("GET BORROWER" . $query);
            $res = $dbh->query($query);
            $obj = $dbh->fetch($res,'array');
           if (!empty($theBorrower['firstname']) || !empty($theBorrower['surname']))
            $name   = $theBorrower['surname'] . ', ' . $theBorrower['firstname'];
             debug("BORROWER NAME" . $name);
            $addr1  = $theBorrower['addr1'];
            $addr2  = $theBorrower['addr2'];   
            $town   = $theBorrower['town'];   
            $county = $theBorrower['county'];   
            $phone  = $theBorrower['phone'];                          
            $email  = $theBorrower['email'];  
            $memberval = $obj->member;
            
            $case_history = '
';             
            
        }
        
        $lqCase = new lqCase();

    if (isset($_POST['btn_save']))
    {
    $lqCase->addSubjects($_POST['subjectsTxt']);
   echo '<!-- <br>GET<br> ';
   print_r($_GET);
   echo '<br>POST<br>';
   print_r($_POST);
   echo '-->';
    
    }
 

$statusArr              = array (
                         'open'     => 'Open','closed'=>'Closed'
                         );
$subjectsArr               = array(); //$lqCase->getSubjectList();            
$objCountry             = new lqCountry('','');
$countriesArr           = $objCountry->getCountryList();
$catArr                 = $lqCase->getCatList();
$source                 = User::library($_SESSION['user_id']);

$form = new HTML_Form();

if (empty($_GET['caseid']))
{
$output = $form->startForm('#', 'post', 'frmAddCase',array('name'=>'addCase','autocomplete'=>'off','onSubmit'=>'return validateForm(0)'));              	
}
else
{
$output = $form->startForm('#', 'post', 'frmAddCase',array('name'=>'addCase','autocomplete'=>'off','onSubmit'=>'return validateForm(1)'));              	
	
}

//$output = $form->startForm('#', 'post', 'frmAddCase',array('name'=>'addCase','autocomplete'=>'off','onSubmit'=>'return validateForm()'));          
$output .= '<center><table border="0"  cellspacing="30"><tr><td valign="top" width="50%">';

$tblLeft = new HTML_Table(null, 'display',0,10,0); 

$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('caseId', 'Case  ID'),null,null,array('align'=>'left'));  
$tblLeft->addCell($form->addInput('text', 'caseId', $caseId, array('disabled'=>'true','readonly'=>true,'id'=>'caseID', 'size'=>16, 'maxlength'=>50) ),null,null,array('width'=>'20%','align'=>'left')); 

if ($caseStatus == 'open')
{
	$selectedOpen = "selected";
    $selectedClosed = ""; 
  }
  else if ($caseStatus == 'closed')
  {
	$selectedOpen = "";
    $selectedClosed = "selected";   
  }
$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('status', 'Case  Status'),null,null,array('align'=>'left','width'=>'20%'));
$tblLeft->addCell(
//$form->addSelectList('status', $statusArr, true, 'Please Choose ...', null, array('id'=>'status','selected'=>$selected) ),null,null,array('align'=>'left')); 
'<select id=status name=status>
<option value=open ' . $selectedOpen . '>Open</option>
<option value=closed ' . $selectedClosed . '>Closed</option>
</select>');

$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('borrower', 'Borrower')
. '<span id="borrower_error" class="borrower_error"> -- Please enter a name of the form "Surname, Firstname"</span>'  
,null,null,array('align'=>'left') );



$tblLeft->addCell(
$form->addInput('text', 'borrower',$name,array('id'=>'borrower','size'=>32, 'maxlength'=>50,'onkeyup'=>'ajax_showOptions(this,\'getCountriesByLetters\',event); ') )

,null,null,array('align'=>'left') ); 
//$form->addInput('hidden', 'borrower_id','',array('id'=>'borrower_id')); 
//  JavaScript workaround required to satisfy autocomplete
$output .= '<input type="hidden" name = "borrower_hidden" value="" id="borrower_hidden">';

$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('borrowerType', 'Borrower Type'),null,null,array('align'=>'left'));

$ismember = $this->isMember($borrower_id);


if ($member == 1)
$checkd = 'checked';
elseif ($memberval == 1)
$checkd = 'checked';
else $checkd = '';

if ($borrower_type == 1) 
{
	$btchk = true;
    $btchk1 = false;
}
else {
	$btchk = false;
    $btchk1 = true;
}

$tblLeft->addCell(
$form->addInput('radio', 'borrowerType',1, array('checked'=>$btchk)) . $form->addLabelFor('memberType', ' Adult ') . 
$form->addInput('radio', 'borrowerType', 2, array('checked'=>$btchk1) ) . 
$form->addLabelFor('borrowerType', ' Student ') . 
"<input type=checkbox name=borrowerIsMember value = 1 " .  $checkd . ">". 

$form->addLabelFor('memberType', ' Member'),null,null,array('align'=>'left') );

$tblLeft->addRow();

$tblLeft->addRow();
$tblLeft->addCell(
$form->addLabelFor('addrStreet', 'Address1')
. '<span id="addrStreet_error" class="addrStreet_error"> -- This Field is Required</span>' 
,null,null,array('align'=>'left')); 
$tblLeft->addCell($form->addInput('text','addrStreet',$addr1, array('id'=>'addrStreet','size'=>32)));

$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('addrArea', 'Address2')
. '<span id="addrArea_error" class="addrArea_error"> -- This Field is Required</span>' 
,null,null,array('align'=>'left')); 
$tblLeft->addCell(
$form->addInput('text','addrArea',$addr2,array('id'=>'addrArea','size'=>32)));

$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('addrTown', 'Town')
. '<span id="addrTown_error" class="addrTown_error"> -- This Field is Required</span>' 
,null,null,array('align'=>'left')); 
$tblLeft->addCell($form->addInput('text','addrTown',$town,array('id'=>'addrTown','size'=>32)));

if (isset($_GET['caseid']))
$country_id = lqCountry::caseCountry($_GET['caseid']);

if ($country_id == "")
$country_id = intval(lqCountry::getDefaultCountry(DEFAULT_COUNTRY));

$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('addrCounty', 'County')
. '<span id="addrCounty_error" class="addrCounty_error"> -- This Field is Required</span>' 
,null,null,array('align'=>'left')); 
$tblLeft->addCell($form->addInput('text','addrCounty',$county,array('id'=>'addrCounty','size'=>32)));

$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('country', 'Country'),true,80,array('align'=>'left')); 
$tblLeft->addCell($form->addSelectList('country',$countriesArr,true, intval($country_id)));
$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('phone', 'Phone')
. '<span id="phone_error" class="phone_error"> -- This Field is Required</span>' 
,null,null,array('align'=>'left')); 
$tblLeft->addCell($form->addInput('text','phone',$phone,array('id'=>'phone','size'=>32)));

$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('email', 'E-Mail')
. '<span id="email_error" class="email_error"> -- Please enter a valid email address</span>' 
,null,null,array('align'=>'left')); 
$tblLeft->addCell($form->addInput('text','email',$theBorrower['email'],array('id'=>'email','size'=>32)));


$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('dateAdded', 'Date Added'),null,null,array('align'=>'left')); 
$tblLeft->addCell($form->addInput('text','dateAdded',$added_date,array('id'=>'calendar','size'=>8))
. '<input type=hidden name=dateAdded2 value = ' . $added_date . '>'
);

$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('deadline', 'Deadline'),null,null,array('align'=>'left')); 
$tblLeft->addCell($form->addInput('text','deadline',$deadline_date,array('id'=>'calendar1','size'=>8)));
$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('sourceType', '<b>Source of Query</b>')); 

$echecked = true;
if (!empty($_GET['caseid']))
{
if ($querySrc == 'phone')
$phchecked = true;
else
$phchecked = false; 
if ($querySrc == 'email')
$echecked = true;
else
$echecked = false; 
if ($querySrc == 'post')
$pochecked = true;
else
$pochecked = false; 
if ($querySrc == 'person')
$pechecked = true;
else
$pechecked = false; 
}
$tblLeft->addCell(
$form->addInput('radio','sourceType',1,array('checked'=>$phchecked)) . $form->addLabelFor('sourceType',' Phone ') .
$form->addInput('radio','sourceType',2,array('checked'=>$echecked)) . $form->addLabelFor('sourceType',' Email ') . 
$form->addInput('radio','sourceType',3,array('checked'=>$pochecked)) . $form->addLabelFor('sourceType',' Post ') . 
$form->addInput('radio','sourceType',4,array('checked'=>$pechecked)) . $form->addLabelFor('sourceType',' In Person ') ,null,null,array('valign'=>'top')
);


$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('source', 'Source')
. '<span id="source_error" class="source_error"> -- This Field is Required</span>'  
,null,null,array('align'=>'left')); 
$tblLeft->addCell(
$form->addInput('text','source',$source,array('id'=>'source','size'=>32,'disabled'=>true))
);

if (intval($assigned_by) <=0)
	$assignedByUser = User::nameFromId(intval($_SESSION['user_id']));
else
$assignedByUser = User::nameFromId($assigned_by);



$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('assignedBy', 'Assigned By')
. '<span id="source_error" class="referredBy_error"> -- This Field is Required</span>'  
,null,null,array('align'=>'left')); 
$tblLeft->addCell(
$form->addInput('text','assignedBy',$assignedByUser,array('id'=>'assignedBy','size'=>32,'readonly'=>true))
);


$userList = User::listAll();



$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('assignedTo', 'Assigned To')
. '<span id="reviewAfter_error" class="assignedTo_error"> -- This Field is Required</span>'  
,null,null,array('align'=>'left')); 
$tblLeft->addCell($form->addSelectList('assignedTo',$userList,true, intval($assigned_to), null));

$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('reviewAfter', 'Review After')
. '<span id="reviewAfter_error" class="reviewAfter_error"> -- This Field is Required</span>'  
,null,null,array('align'=>'left')); 
$tblLeft->addCell($form->addInput('text','reviewAfter',$review_date,array('id'=>'calendar2','size'=>8)));

$tblLeft->addRow();
$tblLeft->addCell($form->addLabelFor('category', 'Category'),null,null,array('align'=>'left')); 
$tblLeft->addCell($form->addSelectList('category',$catArr,true, intval($category_id), null));

$output .= $tblLeft->display();   
$output .= '</td><td valign="top" width=50%>';


$tblRight = new HTML_Table(null, 'display',0,10,0);   
$tblRight->addRow();     
$tblRight->addCell($form->addLabelFor('caseHeader', 'Case  Header')
. '<span id="caseHeader_error" class="caseHeader_error"> -- This Field is Required</span>'  
,null,null,array('align'=>'left','width'=>'20%'));
$tblRight->addCell(

$form->addInput('text', 'caseHeader', $case_header, array('id'=>'caseHeader', 'size'=>32, 'maxlength'=>100) )

);  
  
 $tblRight->addRow(); 
  
  $tblRight->addCell($form->addLabelFor('theSubject', 'Subject'),null,null,array('align'=>'left','width'=>'20%'));   
  
   $tblRight->addCell(   
  $form->addInput('text', 'theSubject', $theSubject, array('id'=>'theSubject', 'size'=>32, 'maxlength'=>100) ) .  

   
  
  $form->addInput('button', 'addSubject', 'New',array('id'=>'addSubject','class'=>'button_sm', 'onClick'=>'
appendOptionLast(count2++,\'undefined\',\'self\');'))  .   
  '<a onClick=\'createCookie("searchTerm",document.getElementById("theSubject").value,3);var subjectswindow=window.open("grabSubjects.php","subjectswindow","width=600,height=800");removeDups();\'  href=# >' . 
  $form->addInput('button','btn_newSubject','Search',array('id'=>'btn_newSubject','class'=>'button_sm')) . 
  '</a>'  

,null,null,array('align'=>'left')
);
 
// Get this cases subjects   
if (isset($_GET['caseid']))
{
$squery = "SELECT subjects.subject FROM subjects, subject_case WHERE" . 
" subjects.subject_id = subject_case.subject_id AND subject_case.case_id = " . $_GET['caseid'] . " order by subjects.subject";
$res = $dbh->query($squery);
debug("SELECT SUBJECTS" . $squery);


   while ($row = $dbh->fetch($res,'array') )
       $subjectsArr[] = $row[0];
}                                     
$tblRight->addRow();
$tblRight->addCell(





$form->addLabelFor('subjects', 'Subjects Added')
. '<span id="subjects_error" class="subjects_error"> -- This Field is Required</span>'
,null,null,array('align'=>'left'));
$tblRight->addCell(      
   $form->addSelectList('subjects[]', $subjectsArr, true, '', null, array('id'=>'subjects','multiple'=>true,'style'=>'width:270px;') )
  
   ,null,null,array('align'=>'left','width'=>200));
 
 
 $tblRight->addRow();     
// This hidden stuff needs a workaround for IE8, untested on 9/10 dont care about 7 - Peter L    
$output .= "<!--[if !IE]><!-->"; 
$tblRight->addCell();     
$output .= "<!--<![endif]-->";
// End IE Workaround


$tblRight->addRow(); 
$tblRight->addCell($form->addLabelFor('caseDetail', 'Case  Detail')
. '<span id="caseDetail_error" class="caseDetail_error"> -- This Field is Required</span>'
,null,null,array('align'=>'left')); 
$tblRight->addCell($form->addTextArea('caseDetail', 5, 40, $case_detail,array('id'=>'caseDetail','onFocus'=>'selectAll(\'subjects\',true);')  ),'','',array('align'=>'left')); 

// Hidden content    
$tblRight->addRow(); 
$tblRight->addCell(
'<span id="caseHistoryPrompt" onClick=toggleCaseHistory();>'.

'-- case  history --


</span>'); 

if (isset($_GET['caseid']))
$lineItems = lqCase::getLineItems($_GET['caseid']);

 
$tblRight->addCell($lineItems,null,null,array('id'=>'lineitems')); 

$tblRight->addRow(); 
$tblRight->addCell();
$tblRight->addCell(
$form->addLabelFor('caseHistory', 'Enter line items below',array('id'=>'caseHistoryLabel')) .     
$form->addTextArea('caseHistory', 2, 40, $case_history,array('id'=>'caseHistory'),null,null,array('align'=>'left'))); 
 

$tblRight->addRow(); 
$tblRight->addCell($form->addLabelFor('caseResponse', 'Response')
. '<span id="caseResponse_error" class="caseResponse_error"> -- This Field is Required</span>'
,null,null,array('align'=>'left')); 
$tblRight->addCell($form->addTextArea('caseResponse', 5, 40, $case_response, array('id'=>'caseResponse')  ),'','',array('align'=>'left')); 

$tblRight->addRow(); 
$tblRight->addCell($form->addLabelFor('referredTo', 'Referred To')
. '<span id="referredTo_error" class="referredTo_error"> -- This Field is Required</span>'
,null,null,array('align'=>'left')); 
$tblRight->addCell($form->addTextArea('referredTo', 3, 40, $referred_to, array('id'=>'referredTo') ),'','',array('align'=>'left')); 


$tblRight->addRow();
$tblRight->addCell($form->addLabelFor('responseType', '<b>Type of Response</b>')); 

if ($responseType == 'phone')
$phchecked = true;
else
$phchecked = false;

if ($responseType == 'email')
$echchecked = true;
else
$ehchecked = false;

if ($responseType == 'post')
$pochecked = true;
else
$pochecked = false;

if ($responseType == 'person')
$pechecked = true;
else
$pechecked = false;

if (empty($responseType))
$echecked = true;


$tblRight->addCell(
$form->addInput('radio','responseType','phone',array('checked'=>$phchecked)) . $form->addLabelFor('responseType',' Phone ') .
$form->addInput('radio','responseType','email',array('checked'=>$echecked)) . $form->addLabelFor('responseType',' Email ') . 
$form->addInput('radio','responseType','post',array('checked'=>$pochecked)) . $form->addLabelFor('responseType',' Post ') . 
$form->addInput('radio','responseType','person',array('checked'=>$pechecked)) . $form->addLabelFor('responseType',' In Person ') ,null,null,array('valign'=>'top')
);

$tblRight->addRow();
$tblRight->addCell($form->addLabelFor('responseDate', 'Date of Response'),null,array('align'=>'left')); 
$tblRight->addCell($form->addInput('text','responseDate',$response_date,array('size'=>8, 'id'=>'calendar3')) . 
$form->addLabelFor('publish', '&nbsp;&nbsp;&nbsp;&nbsp;<b>Local</b>&nbsp;&nbsp;&nbsp;&nbsp;') .
$form->addInput('checkbox', 'publish', 'publish',array('checked'=>true) )

);


$output .= $tblRight->display();     
$output .= '</td></tr></td></tr>
</table>' .


'
<center><table width=66% align=center><tr><td class=menu_sm width=60%></td><td>' . 
$form->addInput('button', 'print', 'Print',array('class'=>'lq-button','onclick'=>'location.href=\'pdf.php?caseid=' . $_REQUEST[caseid] . '\''))  . '</td><td>' .  
$form->addInput('button', 'email', 'Email',array('class'=>'lq-button','onClick'=>'location.href=\'pdf.php?email=true&caseid=' . $_REQUEST[caseid] . '\''))  . '</td><td>' .      
$form->addInput('submit', 'save', 'Save',array('class'=>'lq-button','onClick'=>'selectAll(\'subjects\',true); truncateBorrower();'))  .    '</td><td>' .        
$form->addInput('button', 'cancel', 'Cancel', array('class'=>'lq-button','onClick'=>'redirectPage(\'index.php\');'))  .    

 '</td></tr>
</table>';

$output .= $form->endForm(); 

$html .= $output;
    
return($html);    
}

   public function convertDate($date,$to)
    {
        $dateArr = explode('-',$date);

debug ("CONVERTING DATE TO " . $to);
       if ($to == "eu")    
       {
		debug("DATE: " .  date("d-m-Y",mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0])));   
        return date("d-m-Y",mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]));
	    }
        else if (substr($date,2,1) == '-') {
		 debug("DATE USA: \r\n");
		 debug("OLD: " .  $date); 
		 debug("NEW: " .  $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0]);   	
         //return date("Y-m-d",mktime(0,0,0,$dateArr[2],$dateArr[1],$dateArr[0]));
         return($dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0]);
		}
		else return $date;
    }
    



public function getBorrowerClass($caseId)
{
	global $dbh;
	
	$sql = "SELECT borrower_id FROM cases WHERE caseid = " . $caseId;
	$res = $dbh->query($sql);
	$obj = $dbh->fetch($res,'');
	$borrowerId = $obj->borrower_id;
		
	$sql = "SELECT * FROM borrower WHERE borrower_id = " . $borrowerId;
	$res = $dbh->query($sql);
	$obj = $dbh->fetch($res,'');
	
	$borArr = array (
	
						'id'			=> $obj->borrower_id,
						'type' 			=> $obj->borrower_type,
						'surname'		=> $obj->surname,
						'firstname' 	=> $obj->firstname,
						'addr1'			=> $obj->addr1,
						'addr2'			=> $obj->addr2,
						'town'			=> $obj->town,
						'county'		=> $obj->county,
						'phone'			=> $obj->phone,
						'email'			=> $obj->email,
						'country' 		=> $obj->country_id,
						'member'		=> $obj->member
	                );
	
	$borObj = new lqBorrower($borArr);
	
	return $borArr;
}

} // END Class lqCase


 /**
     * Class lqCountry -  Controller class for countries
     * 
     * 
     * This class handles all aspects of manipulating countries, 
     * names, descriptions, ISO codes e.t.c, 
     * 
     * #FIXME does not really deserve to be a class and should be moved else where 
     *
     * @author Peter Lorimer <peter@oslo.ie>
     * @license Interleaf
     * @copyright Interleaf Technology Ltd
     * @package LQ                                
     * Date: 22 July 2011
     */
class lqCountry {
    private $name, $iso;
/**
* Function __construct()
* 
* Class constructor
*  
*    
*/    
    public function __construct($name='',$iso='')
    {
        $this->name = $name;
        $this->iso  = $iso;
        
    }
    
/**
* Function __destruct()
* 
* Class destructor
*     
*/    
 public function __destruct()
    {
        $name = $this->name;
        $iso = $this->iso;
        unset($name);
        unset($iso);
        
    }    
    
 
 /**
* Function getCountryList()
* 
* Gets id and name of a each country and uses it to populate a drop-down
* 
* {@source}
* 
* @return integer $country_id    
*/    
    public function getCountryList()
    {
        global $dbh;
        $countryMap = array();
        $sql = "SELECT name, country_id FROM country";
        $res = $dbh->query($sql);
        
        while ($row = $dbh->fetch($res,'array'))
        {
         $countryMap[$row['country_id']] = $row['name'];   
        }
        
     return $countryMap;   
    }
    
    public function getDefaultCountry($ISOCode)
    {
		global $dbh;
		$sql = "SELECT country_id FROM country where iso_code = '" . $ISOCode . "'";
        $res = $dbh->query($sql);
        
        $obj = $dbh->fetch($res,'');
 
        
     return $obj->country_id;   
	}
	 
     public function Id()
    {
      return $this->country_id;
    }
    
    public function caseCountry($case_id)
    {
        global $dbh;
        
        $sql = "select  country_id from cases where caseid = " . $case_id;
    //   print $sql;
        $res = $dbh->query($sql);
        $obj = $dbh->fetch($res,'array');
        return ($obj[0]);
    }
    
}

 /**
     * Class lqBorrower -  Controller class for borrower
     * 
     * 
     * This class handles  borrowers, 
     *  
     * 
     * Used to store borrower information to be added to a database table
     *
     * @author Peter Lorimer <peter@oslo.ie>
     * @license Interleaf
     * @copyright Interleaf Technology Ltd
     * @package LQ                                
     * Date: 22 July 2011
     */
class lqBorrower {
    
    private $id, $type,$urname, $firstname, $addr1, $addr2, $town, $county, $phone, $email, $country, $member; 

/**
* Function __construct()
* 
* Class constructor
*  
* {@source}
*    
*/ 
    
    public function __construct($borArr)
    {
        if (isset($borArr['id']) && !empty($borArr['id']))
        $this->id             =       $borArr['id'];
        
        if (isset($borArr['type]']) && !empty($borArr['type']))          
        $this->type           =       $borArr['type'];
        
        if (isset($borArr['firstname]']) && !empty($borArr['firstname']))          
        $this->firstname           =       $borArr['firstname'];
        
        if (isset($borArr['surname]']) && !empty($borArr['surname']))          
        $this->surname           =       $borArr['surname'];
        
        if (isset($borArr['addr1']) && !empty($borArr['addr1']))  
        $this->addr1          =       $borArr['addr1']; 
        
        if (isset($borArr['addr2']) && !empty($borArr['addr2']))  
        $this->addr2          =       $borArr['addr2'];     
                    
        if (isset($borArr['town']) && !empty($borArr['town']))  
        $this->town           =       $borArr['town'];     
        if (isset($borArr['county']) && !empty($borArr['county']))  
        $this->county         =       $borArr['county'];
        
        if (isset($borArr['phone']) && !empty($borArr['phone']))  
        $this->phone          =       $borArr['phone']; 
        
        if (isset($borArr['email']) && !empty($borArr['email']))  
        $this->email          =       $borArr['email'];    
                     
        if (isset($borArr['country']) && !empty($borArr['country']))  
        $this->country        =       $borArr['country'];  
           
        if (isset($borArr['member']) && !empty($borArr['member']))  
        $this->member         =       $borArr['member'];             
    }
    

/**
* Function __construct()
* 
* Gets the borrower type from the ID
*  
* {@source}
*    
*/ 
public function getBorType()
{    global $dbh;
     $sql = "SELECT borrower_type FROM borrower WHERE borrower_id = $this->id";
     $res = $dbh->query($sql);
     $row = $dbh->fetch($res,'object');
     return $row->borrower_type;
}

    public function Type()
    { return $this->type; }

    public function Addr1()
    { return $this->Addr2; }
        
    public function Addr2()
    { return $this->addr2; }
    
    public function Town()
    { return $this->town; }
    
    public function County()
    { return $this->county; }
    
    public function Phone()
    { return $this->phone; }
    
    public function Email()
    { return $this->email; }
    
    public function Country()
    { return $this->country; }
    
    
    public function Member()
    { return $this->member; }                                

 

}


?>
