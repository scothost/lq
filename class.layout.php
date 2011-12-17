<?php
session_start();
 /**
     * Layout Class - Generates all HTML for layout definitions
     *
     * This class contains the methods for generating the left and right navbars, the footer and header
     * as well as generating dynamic stylesheets by storing the css filenames in a configuration array.
     *
     * @author Peter Lorimer <peter@oslo.ie>
     * @license Interleaf
     * @copyright Interleaf Technology Ltd
     *
     * Date: 22 July 2011
     */



class Layout {
    /**
     * @package Layout 
     *
    */

private $title, $description, $styles, $operation;
    /** 
     * Constructor function - Initialises page title and various meta tags
     *
     * @param string $title         - The page title
     * @param string $description   - The page META descriptin
     * @param string operation      - The operation to perform
     * @param array  $styles        - An array containing the varous stylesheets to be linked for this page
    */
public function __construct($title,$description,$styles,$javascript,$operation)
{
             $this->title       = $title;
             $this->description = $description;
	         $this->styles      = $styles;
             $this->javascript  = $javascript;    
             $this->operation   = $operation;
}

    /** 
     * Function printHeader()     -     Outputs the header HTML
     * return void
    */
 
public function printHeader()
{
    $vis = 'vis';
    
$html = <<< HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>   

          <script src="js/jquery/jquery.tools.min.js"></script> 
        <script src="js/jquery/jquery-1.1.3.1.pack.js" type="text/javascript"></script> 
        <script src="js/jquery/jquery.history_remote.pack.js" type="text/javascript"></script> 
        <script src="js/jquery/jquery.tabs.pack.js" type="text/javascript"></script>     
        
    <link rel="stylesheet" type="text/css" href="/css/standalones.css"/> 
    <link rel="stylesheet" type="text/css" href="/css/apple.css"/> 
        <link rel="stylesheet" href="css/jquery.tabs.css" type="text/css" media="print, projection, screen"> 


HTML;

foreach ($this->styles as $style)
{
$html .= <<< HTML
<link rel="stylesheet" type="text/css" href="css/$style" />\n
HTML;
}

foreach ($this->javascript as $js)
{
$html .= <<< HTML
<script type="text/javascript" src="js/$js"></script>\n
HTML;
}

$html .=  <<< HTML
<title>
HTML;

$html .= $this->title;

if (isset($_POST['username']))
{
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    
    $theUser = new User();
    $obj =  $theUser->Auth(); 
   
    $_SESSION['auth'] = $obj->permissions;
    $_SESSION['user_id'] = $obj->users_id;
    $_SESSION['library_id'] = $theUser->libraryId($obj->users_id);  
    
    if ($obj != 0)    
    Header("Location: /");       
 
}
  
if ($_GET['op'] != 'login' && !User::checkAuth())
{
    header("Location: ?op=login");
}

$html .= <<< HTML
</title> 


<link rel="stylesheet" type="text/css" href="anylinkmenu.css" />

<script type="text/javascript">

//anylinkmenu.init("menu_anchors_class") //Pass in the CSS class of anchor links (that contain a sub menu)
//anylinkmenu.init("menuanchorclass")

</script>
</head>
<body  onload="doOnLoad();thisDate();">   
 
<script>
$(function() {
 
    // if the function argument is given to overlay,
    // it is assumed to be the onBeforeLoad event listener
    $("a[rel]").overlay({
   
        mask: 'lightblue',
        effect: 'apple',
        fixed: false,
 
        onBeforeLoad: function() {
 
            // grab wrapper element inside content
            var wrap = this.getOverlay().find(".contentWrap");
 
            // load the page specified in the trigger
            wrap.load(this.getTrigger().attr("href"));
        }
 
    });    
}); 

</script>

 <div class="apple_overlay" id="overlay"> 
 
    <!-- the external content is loaded inside this tag --> 
    <div class="contentWrap"></div> 
 
</div>   
<div class='shell'>

    <div class='header'>
        <div class='title'>Library Queries Database</div>
        <div class='subtitle'>
HTML;

        
        $html .= User::library($_SESSION[user_id])  . " - " . User::department($_SESSION['user_id'])->department;

$html .= "</div>     
    </div>";

switch ($this->operation){

    case "home":
 $html .= $this->printMenuLinks('home');   
    break; 
    
    case "search":
 $html .= $this->printMenuLinks('search');   
    break; 
    
            
    case "cases":
  $html .= $this->printMenuLinks('cases');   
    break; 
    
    case "express":
  $html .= $this->printMenuLinks('express');   
    break; 
    
    case "reports":
  $html .= $this->printMenuLinks('reports');   
    break; 
    
     case "admin":
     $html .= $this->printMenuLinks('admin');   
break;

      case "adduser":
 $html .= $this->printMenuLinks('admin');   
    break; 
    
case "addlib":
$html .= $this->printMenuLinks('admin');        
 break;
 
 case "viewlib":      
  $html .= $this->printMenuLinks('admin');   
break;
 
 
 case "editlib":
 
$html .=  $this->printMenuLinks('admin');  
break;


 case "dellib":
 
$html .= $this->printMenuLinks('admin'); 
break;

 case "login":
 
$html .= $this->printMenuLinks('admin'); 
break;
              
   default:
   $html .= $this->printMenuLinks('admin');    
   break;
    
}
 
 
$html .= <<< HTML
</div>
HTML;

echo $html;

}

    /**
     * Function printLHS()       -  Outputs HTML for the left navbar
     * return void
    */
public function printMenuLinks($page)
{
     
    
        ${$page} = ' selected ';
   
    $html .= "
    
          <div class='menu'>        
        <a class = '". $home . "' href='/?op-home'>Home</a>        
        <a class = '". $search . "' href='/?op=search'>Search</a>        
        <a class = '". $cases . "' href='/?op=cases'>Add</a>        
        <a class = '". $express . "' href='/?op=express'>Express</a>        
        <a class = '". $reports . "' href='/?op=reports'>Reports</a> ";
        
        if (User::checkAuth() == AUTHGLOBALADMIN || User::checkAuth() == AUTHLOCALADMIN)
        {
          
    $html .= "<dl  class='dropdown " .  $admin . "'>
<dt id=\"one-ddheader\" onmouseover=\"ddMenu('one',1)\" onmouseout=\"ddMenu('one',-1)\">

<b>Admin</b>



</dt>
<dd id=\"one-ddcontent\" onmouseover=\"cancelHide('one')\" onmouseout=\"ddMenu('one',-1)\">
";

 if (User::checkAuth() == AUTHGLOBALADMIN)
 {
$html .= "
     <ul>
<li><center>Users </center> </li>
<li><a href=\"?op=adduser\" class=\"underline\">--- Add a User</a></li>
<li><a href=\"?op=edituser\" class=\"underline\">--- View/Edit/Remove</a></li>

</ul>

<ul>

<li><center><center>Libraries</center></li>
<li><a href=\"?op=addlib\" class=\"underline\">--- Add a Library</a></li>
<li><a href=\"?op=editlib\" class=\"underline\">--- View/Edit/Remove</a></li>
   </ul>  ";
 }
 
 $html .= "
<ul>
<li> <center> Departments</center></li>

<li><a href=\"?op=adddept\" class=\"underline\">--- Add a Department</a></li>
<li><a href=\"?op=editdept\" class=\"underline\">--- View/Edit/Remove</a></li>

</ul>

<ul>
<li> <center> Categories</center></li>

<li><a href=\"?op=addcat\" class=\"underline\">--- Add a Category</a></li>
<li><a href=\"?op=editcat\" class=\"underline\">--- View/Edit/Remove</a></li>

</ul>

<ul>
<li> <center> Resources</center></li>

<li><a href=\"?op=addresource\" class=\"underline\">--- Add a Resource</a></li>
<li><a href=\"?op=editresource\" class=\"underline\">--- View/Edit/Remove</a></li>

</ul>


</dl>
";
        }

if (User::checkAuth())
 {
  $html .= <<< HTML
<span style='float:right;'><a href=#>Welcome back $_SESSION[username]</a><a href=?op=logout>Logout</a></span>
    </div>

    <div class='main'>

    </div>

</div>   

HTML;
 }
 
 else {
$html .= <<< HTML
<span style='float:right;'><a href=?op=login>Login</a></span>
    </div>

    <div class='main'>

    </div>

</div>   

HTML;
 }
return $html;    
}

    /**
     * Function printRHS()       -  Outputs HTML for the right navbar
     * return void
    */
public function printRHS()
{}

    /**
     * Function printFooter()    -  Outputs HTML for the footer
     * return void
    */
public function printFooter()
{
$html = <<< HTML
</div><br/>
<div class="footer">
Website design and hosting by <a href = "http://www.interleaf.ie">Interleaf Technology Ltd</a>
</div>

</body></html>


HTML;

echo $html;

}

}



?>
