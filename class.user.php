<?php
require_once 'class.layout.php';
require_once 'class.borrower.php';    
require_once 'class.dbh.php'; 

/**
     * Class User - Controller class for users
     * 
     * 
     *  This class adds, removes, modifies and authenticates users
     * 
     * 
     *
     * @author Peter Lorimer <peter@oslo.ie>
     * @license Interleaf
     * @copyright Interleaf Technology Ltd
     * @package LQ                                
     * Date: 22 July 2011
     */

class User {
    protected $username, $password, $library_id, $department_id, $email;
    
    public function __construct($username = '', $password = '',$email = 'dev@oslo.ie', $department_id = 1, $library_id = 1)
    {
        $this->username = $username;
        $this->password = $password;
    
    }
    
    public function __destruct()
    {
        $username = $this->username;
        $password = $this->password;
        unset($username);
        unset($password);
    }
    
    public function library($user_id)
    {
        global $dbh;
        
        $query = "
        SELECT library.library 
        FROM users, library 
        WHERE
         users.library_id=library.library_id 
         AND
          users.users_id = " . 
        $user_id;
        $res = $dbh->query($query);
        $obj = @$dbh->fetch($res,'');
        
        return $obj->library;      
    }
    
    public function libraryId($user_id)
    {
        global $dbh;
        
        $query = "
        SELECT library.library_id 
        FROM users, library 
        WHERE
         users.library_id=library.library_id 
         AND
          users.users_id = " . 
        $user_id;
        $res = $dbh->query($query);
        $obj = $dbh->fetch($res,'');
          
        return $obj->library_id;      
    }    
    
    
    public function Auth()
    {
         global $dbh;
         
         $query = "SELECT users_id,user_name, password, permissions FROM users 
         WHERE user_name = '" . $_POST['username'] . "' AND  DECODE(password," . SEED . ") = '"  .
         $_POST['password'] . "'";
              //print $query;
         $res = $dbh->query($query);
         
         if ($dbh->rows($res) == 1)
         {
             $obj = $dbh->fetch($res,'');
                 if (session_name()=='') {
        // Session not started yet
        session_start();
    }
    else {
        // Session was started, so destroy
        @session_destroy();

        // But we do want a session started for the next request
        session_start();
        session_regenerate_id();

        // PHP < 4.3.3, since it does not put
        setcookie(session_name(), session_id());
    }
    
    if (isset($_POST['username']))
    {
    $_SESSION['username'] = $_POST['username'];  
         }
    $query = "UPDATE users set session_id = '" . session_id() . "' where users_id =" . $obj->users_id;
    $dbh->query($query);
    //echo $query;
   
             return $obj;
         }
         else { return 0; }
          
        
    }
    
    public function checkAuth()
    {
        global $dbh;
      // return 1; 
        
        $user_id = $_SESSION['user_id'];
        
                $query = "select session_id FROM users 
         WHERE users_id = " . $user_id . " and session_id = '" . session_id() . "'";
                if ($res = $dbh->query($query))
                $rows = @$dbh->rows($res);
              //  die($query);
                if ( $rows > 0)
        return $_SESSION['auth'];
        
       else if ($_GET['op'] != 'login') header("Location: /?op=login");   
    }
    
    public function logInForm()
    {
        echo "<center>Please enter your username and password<form name=logInForm method=post>
       <table><tr><th>Username</th> <td><input type=text size=12 name=username></td></tr>
       <tr><th>Password</th><td><input type=password name=password size=12></td></tr>
        <tr><td>&nbsp</td><td align = center><input class = lq-button type=submit name = logon value=' Log In '></td>
        </table>  </form>";
    }
 
     public function department($user_id)
    {
        global $dbh;
        
        $query = 
        "SELECT department.department, department.department_id  
        FROM users, department 
        WHERE
         users.department_id=department.department_id 
        AND
        users.users_id = " . 
        $user_id;
        
        $res = $dbh->query($query);
        $obj = $dbh->fetch($res,'');
            
        return $obj;      
    }
       
    
    public function del()
    {
        global $dbh;
            $id = $_REQUEST['uid'];
            if (!empty($id))
            {
            $query = "DELETE FROM users where users_id = " . $id;
            $res = $dbh->query($query);
            } 
            
            if ($res)
            {  
             echo '<script>alert("Record Deleted"); location.href="?op=edituser"</script>';
            }
            else {
               
                 echo '<script>alert("ERROR:\r\n This record cannot be deleted. Please contact your systems administrator, the message associated with this error is  \"Referential Integrity Violation\""); location.href="?op=edituser"</script>'; 
            }
    }
   
       public function save($user_id,$name)
    {
       global $dbh;
       $query = "UPDATE users SET email = '" . $_POST['email'] . "', library_id=" . $_POST['library'] . 
       ",department_id = " . $_POST['department'] . ",permissions=" . $_POST['type'] . ", user_name = '" . $name . "' ,password= encode('" . $_POST['password'] . "','31415927') 
       WHERE users_id = " . $user_id;
       $dbh->query($query);
       debug("UPDATE USER:" . $query);
       header("Location: ?op=edituser");
    }
 
     public function listAll()
    {
        global $dbh;
       // $objArr = array();
        $sql = "SELECT users_id, user_name from users WHERE library_id = " .
        $_SESSION['library_id'] . " order by user_name asc";
        $res = $dbh->query($sql);
        debug("ALL USERS: " . $sql);
      
        while ($obj = $dbh->fetch($res,'') )
        {			
            $objArr[$obj->users_id] = $obj->user_name; 
        }
        
        
        return $objArr;    
    }
 
     
    public function view()
    {
        global $dbh;
        $even = 2;
        
        
        if (isset($_REQUEST['uid']) && !empty($_REQUEST['uid']))
        {
			$query = "SELECT user_name, decode(password,'31415927') as password, email,library_id,department_id,permissions 
			FROM users 
			WHERE users_id = " . $_REQUEST['uid'];
			$res = $dbh->query($query);
			
			 $row = $dbh->fetch($res,'');
				 echo "<p><center><form  action = ?op=saveuser METHOD=POST onSubmit='return validateUserForm();'>
				 <table>
				 <tr><td>Username</td><td><input type=text name=user id=username value = '" . $row->user_name . "'>
				  " .'<span id="username_error" class="username_error"> -- This Field is Required</span>'. "
				 
				 </td></tr> 
				
				 <input type=hidden name=user_id value=" . $_REQUEST['uid'] . ">
				 <input type=hidden name=password  value = " . $row->password . ">
				
				 <tr><td>Password</td><td><input type=text name=password id=password value = '" . $row->password . "'>
				  " .'<span id="password_error" class="password_error"> -- This Field is Required</span>'. "
				 </td></tr>
				
				 <tr><td>Email</td><td><input type=text name=email id=email value = '" . $row->email . "'>
				 " .'<span id="email_error" class="email_error"> -- Please enter a valid email address</span>'. "
				 </td></tr>
				 <tr><td>Library</td><td><select name=library id = library onChange = 'libDepartments(this);'>"; 
				 
				  $libraryArr = Library::getAll();
				  
				  foreach ($libraryArr as $key=>$lib)
				  {
					  if ($row->library_id == $key)
					  $selected = "selected";
					  else $selected = null;
					echo "<option value=" . $key . " " .$selected . ">" . $lib . "</option>";   
				  }
				 
	    $query = "SELECT department_id, department from department where library_id = " . $row->library_id;
        $res = $dbh->query($query);
        
        while ($rec = $dbh->fetch($res,'array'))
        {
            $deptArr[$rec['department_id']] = $rec['department'];
        }
				 
				 echo "</select></td></tr>
				  " .'<span id="library_error" class="library_error"> -- This Field is Required</span>'. "
				 <tr><td>Department</td><td><select name=department id=department>";
				 
				 foreach ($deptArr as $key=>$val)
				 {
					  if ($row->department_id == $key)
					  $selected = "selected";
					  else $selected = null;
                        echo "<option value = " . $key . " " . $selected . ">" . $val . "</option>";				 
			      }
			      

			      
				 echo "</select></td></tr>
				  " .'<span id="department_error" class="department_error"> -- This Field is Required</span>'. "
				 <tr><td>User Type</td><td>";
				 
				 $perms = array("1"=>"Local Library Admin","2"=>"Standard User","3"=>"Global Consortium Admin");
				 
				 echo "<select name=type>
				 ";
			      foreach ($perms as $key=>$val)
			      {
					  if ($row->permissions == $key)
					  $selected = "selected";
					  else $selected = "";
					  
					  echo "<option value = " . $key . " " . $selected . ">" . $val . "</option>";
				  }				 
				 
				 echo "</select>
				 </td></tr>
				 
				 <tr><td colspan=2 align=center><input type=submit value=Update class=lq-button></td></tr></table></form></center></p>";
		
           
		}
			else
			{
        $query = "SELECT * from users";
        
        $res = $dbh->query($query);
        
        echo '<center><h2>View / Modify Users </h2><br>
        <table width=600>
        <tr bgcolor = "#343434">
        <th class=admin>User Name</th><th class=admin>Library</th>
        <th class=admin>Department</th><th class=admin>Email</th><th colspan="2" class=admin>Action</th>
        </tr>
        
        ';
        while ($row = $dbh->fetch($res,''))
        {
           $library    = User::library($row->users_id);
           $department = User::department($row->users_id);
           
            if ($even%2==0)
            $color = '#e0e0e0';
            else $color = '#a1a1a1';
            echo '<tr bgcolor=' . $color . ' class="results"><td>' . $row->user_name . '</td>
            <td class="results">'  .User::library($row->users_id) .  '</td><td>'  . User::department($row->users_id)->department   .  '</td><td>' . $row->email . '</td>
            <td align=right width=30><a href =?op=edituser&uid=' . $row->users_id . '>Edit</a></td><td align=right width=40><a href=?op=deluser&uid=' . $row->users_id . '>Delete</td></tr>';
            $even++;
        }
        
        echo '</table>';
        
	}
    }
 
     public function idFromName($user_name)
    {
       global $dbh;
       
       $sql = "SELECT users_id from users where user_name = '" . $user_name . "'";
       $res = $dbh->query($sql);
       $obj = $dbh->fetch($res,'');
       return $obj->users_id;    
    }
       
    public function nameFromId($user_id)
    {
       global $dbh;
       
       $sql = "SELECT user_name from users where users_id = " . $user_id;
       $res = $dbh->query($sql);
       $obj = $dbh->fetch($res,'');
       return $obj->user_name;    
    }
    
    public function getUserInput($type = '')
    {
        global $dbh;
        $this->type = $type;
        
        $libraryArr = array();
        $deptArr = array();
         
        $query = "SELECT library_id, library from library";
        $res = $dbh->query($query);
        
        //$libraryArr[1] = "Please Choose ....";
        
        while ($row = $dbh->fetch($res,'array'))
        {
            $libraryArr[$row['library_id']] = $row['library'];
        }
        
        $query = "SELECT department_id, department from department";
        $res = $dbh->query($query);
        
        while ($row = $dbh->fetch($res,'array'))
        {
            $deptArr[$row['department_id']] = $row['department'];
        }
        
        
        
         $form = new HTML_Form(); 
        $output =  '<h2 align="center">Add a new user</h2><br><br><p>' . 
     
       
        $form->startForm('#', 'post', 'frmAddUser',array('name'=>'addUser','autocomplete'=>'off','onSubmit'=>'return validateUserForm()')) .          
        '<center><table><tr><td>' . 
        $form->addLabelFor('newusername', 'Username') . '</td><td>'  .
        $form->addInput('text', 'newusername', $username, array('id'=>'username', 'size'=>32, 'maxlength'=>50) ) . 
         '<span id="username_error" class="username_error"> -- This Field is Required</span></td></tr><tr><td>' .  
         
       $form->addLabelFor('newpassword', 'Password') .   '</td><td>'    .
        $form->addInput('newpassword', 'newpassword', $newpassword, array('id'=>'password', 'size'=>32, 'maxlength'=>50) ) . 
         '<span id="password_error" class="password_error"> -- This Field is Required</span><tr><td>' .  
         
         
         $form->addLabelFor('email', 'E-Mail') . '</td><td>' .   
        $form->addInput('text', 'email', $email, array('id'=>'email', 'size'=>32, 'maxlength'=>50) ) . 
         '<span id="email_error" class="email_error"> -- Please enter a valid email address</span>
         </td></tr><tr><td>' . 
         
          $form->addLabelFor('email', 'Library') . '</td><td>' .    
        
        $form->addSelectList('library', $libraryArr, true, '', 'Please choose', array('id'=>'library','onChange'=>'libDepartments(this);') )   .
        


        '  <span id="library_error" class="library_error"> -- This Field is Required</span></td></tr><tr><td>' . 
         
          $form->addLabelFor('department', 'Department') . '</td><td>' .    
        
        $form->addSelectList('department', null, true, '', 'Please choose a library first', array('id'=>'department') )   .
        
          '<span id="department_error" class="department_error"> -- This Field is Required</span><tr><td>' . 
             $form->addLabelFor('type', 'User Type') . '</td><td>' .    
        
        $form->addSelectList('type', array('1'=>'Local Library Admin', '2'=>'Standard User', '3'=>'Global Consortium Admin'), true, 2, null, array('id'=>'type') )   .
        
        
         
        
        ' </td></tr><tr><td colspan="2" align=center>' . $form->addInput('submit', 'btnAddUser', "Add this User", array('class'=>'lq-button')) .   '</td></tr>
         
         </table>
         </center><br><br>';  
         
          
         
         return $output;        
        
    }
    public function add()
    {
		
        global $dbh;
        
        if (isset($_REQUEST['btnAddUser']) && !empty($_REQUEST['btnAddUser']))
        {
        $query = "INSERT INTO users (user_name,password,email,department_id,library_id,permissions)" . 
                 "VALUES ('" . 
                 $_REQUEST['newusername'] . "',ENCODE('" . $_REQUEST['newpassword'] . "'," . SEED . ")" . ",'" . $_REQUEST['email'] . "'," . $_REQUEST['department'] . "," .  $_REQUEST['library'] .   "," . $_REQUEST['type'] .
                 ")"
                 ;
           
         $res = $dbh->query($query);
	 }        
    }
    
  
 
 public function homeScreen($user_id)
 {
     global $dbh;
     $library = User::libraryId($user_id);
     $department = User::department($user_id);
     $objArr = array();
 
 // For current library display a count of each departments open cases

     $query = "SELECT count(*) as count, cases.department_id, department.department
FROM cases, department
WHERE cases.department_id=department.department_id
AND status = 'open' 
AND department.library_id = " . $_SESSION['library_id'] .
" GROUP BY department_id ";

     $res = $dbh->query($query);
        debug("HOME QUERY" . $query);
      
     while ($obj = $dbh->fetch($res,''))
         $objArr[] = $obj;
     
    return $objArr; 
       
 }  
 
   
 }
  
?>
