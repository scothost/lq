<?php
/**
 * This is the main index page
 *
 * Used to trigger class instances and generate layouts
 *
 * @author    Peter Lorimer <peter@oslo.ie>
 * @license   Interleaf
 * @copyright Interleaf Technology Ltd
 *
 * Date       22 July 2011
 */

require_once 'config.php';
require_once 'class.layout.php'; 
require_once 'class.form.php';
require_once 'class.table.php';
require_once 'class.case.php';
require_once 'class.user.php';  
require_once 'class.library.php';   
require_once 'class.department.php';   
require_once 'class.resource.php';
require_once 'class.report.php';  


if (isset($_GET['op']))
{
    $op = $_REQUEST['op'];
}
else { $op='home'; }

$myLayout = new Layout(SITENAME,SITEDESC,$styles,$javascript,$op);
$myLayout->printHeader();

$myCase = new lqCase();

 switch ($op)
 {
     case "cases":
       echo $myCase->addNewCase();
       break;
       
       case "savecat":
      case "editcat":
      if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHLOCALADMIN)
     {
  
     $myCase->viewCats();
     if (isset($_POST['cat_id']))
     $myCase->saveCat($_POST['cat_id'],$_POST['cat']);
 
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>';   
     break;

     case "editresource":
     if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHLOCALADMIN)
     {
     $theRes = new Resource();
     $theRes->view();
     $theRes->__destruct();
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>';   
     break; 
     
     case "delresource":
      if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHLOCALADMIN)
     {
     $theResource = new Resource();
     $theResource->del($_REQUEST['rid'],$_POST['resource']);
     $theResource->__destruct();
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>';   
     break;      
      
     case "saveresource":
      if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHLOCALADMIN)
     {
     $theResource = new Resource();
     $theResource->save($_REQUEST['rid'],$_POST['resource']);
     $theResource->__destruct();
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>';   
     break;         
      
     case "addresource":
      if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHLOCALADMIN)
     {
     $theResource = new Resource();
     echo $theResource->getUserInput('Add a Resource');
     $theResource->add();
     $theResource->__destruct();
     }
     break;        
       
     case "adduser":
     if (User::checkAuth() == AUTHGLOBALADMIN )
     {
     $theUser = new User();
     echo $theUser->getUserInput();
     $theUser->add();
     $theUser->__destruct();
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>';   
     break;  
     
     case "edituser":
     if (User::checkAuth() == AUTHGLOBALADMIN)
     {
     $theUser = new User();
     $theUser->view();
     $theUser->__destruct();     
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>'; 
     break;
     
     case "saveuser":
     if (User::checkAuth() == AUTHGLOBALADMIN)
     {
     $theUser = new User();
     $theUser->save($_POST['user_id'],$_POST['user']);
     $theUser->__destruct();     
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>'; 
     break;

     
     case "deluser":
     if (User::checkAuth() == AUTHGLOBALADMIN)
     {     
     $theUser = new User();
     $theUser->del();
     $theUser->__destruct();
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>'; 
     break;
     
     case "addlib":
     if (User::checkAuth() == AUTHGLOBALADMIN)
     {
     $theLibrary = new Library();
     echo $theLibrary->getUserInput("Add a Library");
     $theLibrary->add();
     $theLibrary->__destruct();
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>'; 
     break;  
  
     case "editlib":
      if (User::checkAuth() == AUTHGLOBALADMIN)
     {
     $theLibrary = new Library();
     $theLibrary->view();
     $theLibrary->__destruct();
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>';   
     break;
     
      case "savelibrary":
      if (User::checkAuth() == AUTHGLOBALADMIN)
     {
     $theLibrary = new Library();
     $theLibrary->save($_POST['library_id'],$_POST['library']);
     $theLibrary->__destruct();
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>';   
     break;    
     
    case "savedept":
      if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHLOCALADMIN)
     {
     $theLibrary = new Department();
     $theLibrary->save($_POST['dept_id'],$_POST['dept']);
     $theLibrary->__destruct();
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>';   
     break;        
     
     case "dellib":
 if (User::checkAuth() == AUTHGLOBALADMIN)
     {     
     $theLibrary = new Library();
     $theLibrary->del();
     $theLibrary->__destruct();
     }
     else echo '<h2 align="center"> Access Denied - Insufficient Privileges</h2>'; 
     break;
     
     case "adddept":
      if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHGLOBALADMIN)
     {
     $theDept = new Department();
     echo $theDept->getUserInput('Add a Department');
     $theDept->add();
     $theDept->__destruct();
     }
     break;  
     
     case "editdept":
      if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHGLOBALADMIN)
     {
     $theDept = new Department();
     $theDept->view();
     $theDept->__destruct();   
     }  
     break;
     
     case "deldept":
      if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHGLOBALADMIN)
     {
     $theDept = new Department();
     $theDept->del();
     $theDept->__destruct();
     }
     break;          
 
     case "addcat":
      if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHGLOBALADMIN)
     {
     $theCase = new lqCase();
     echo $theCase->addCatForm('Add a Category');  
     $theCase->addCat();
     $theCase->__destruct();
     }
     break;    
     
     case "editcat":
     if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHGLOBALADMIN)
     {
     $theCase = new lqCase();
     $theCase->viewCats();
     $theCase->__destruct();
     }
     break;    
     
     case "delcat":
     if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHGLOBALADMIN)
     {
     $theCase = new lqCase();
     $theCase->delCat();
     $theCase->__destruct();
     }
     break;  
     
     case "search":
     $theCase = new lqCase();
     echo $theCase->searchForm();
     $theCase->searchResults();
     $theCase->__destruct();
     break; 
     
     case "dept":
     $depaertment_id = $_GET['department_id'];
     Department::viewCases($department_id);
     break;   
     
     case "express":
     $resource = new Resource();
     $resource->resourceForm();
     $resource->insertResource($_POST['resource']);
     break; 
     
     case "reports":
     $report = new Report();
     $report->reportForm();
     $report->view($_POST['report_type']);
     break;  
     
     case "login":
     $theUser = new User();
     $theUser->logInForm();
     $theUser->__destruct();         
     break;

    case "logout":
     $theUser = new User();
     session_destroy();
     Header("Location: ?op=login");
     $theUser->__destruct();         
     break;
          
     case "home":
       $homeData = User::homeScreen($_SESSION['user_id']);
    $even = 2;
    
     
     echo '<center><table valign=top><tr><td valign=top><table><tr><td valign=top></td><td valign=top> ';
      echo '</td><td valign=top><table class="tbloutline" width=100% cellpadding=0><tr>
<th class=admin colspan = 2><font color=black> Open Cases by Department</font></th></tr>
<tr>
<th class="admin">Cases</th><th class="admin">Department</th></tr>';

foreach ($homeData as $obj)
{
             if ($even%2==0)
            $color = '#f2f2f2';
            else $color = '#d1d1d1';
    
    echo '<tr bgcolor="' . $color . '"><td class=admin align=center>'. 
    $obj->count . '</td><td><em><a style="text-decoration:none; color:blue" href=?op=dept&dept=' . 
    $obj->department_id . '>' . $obj->department .
   
    '</a></em></td></tr>';

$even++;
}

echo '</table></td><td valign=top>';
     

     $theCase = new lqCase();

     $theCase->searchResults($_SESSION['user_id'],'home');
     $theCase->__destruct();
      
     
echo '</td></tr></table>';           
break;
 }


$borArr = array('id'=>1);
$borrower = new lqBorrower($borArr);
$borrower->getBorType();


if (isset($_REQUEST['save']))
{
   
    $myCase->processCaseData();
}



$myLayout->printFooter();

//phpinfo();

?>
