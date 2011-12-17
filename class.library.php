<?php
require_once 'class.layout.php';
require_once 'class.dbh.php';
/**
     * Class Library - Controller class for libraries
     * 
     * 
     *  This class adds, removes and modifies libraries
     * 
     * 
     *
     * @author Peter Lorimer <peter@oslo.ie>
     * @license Interleaf
     * @copyright Interleaf Technology Ltd
     * @package LQ                                
     * Date: 22 July 2011
     */
class Library {
    
    private $name, $page;
    
    public function construct()
    {
        $this->name = $name;
        $this->page = $page;
    }
    
    public function getUserInput($page)
    {
        echo '<h2 align="center">' . $page . '</h2><br>';
                 $form = new HTML_Form(); 
        $output =  '<p>' . 
     
       
        $form->startForm('#', 'post', 'frmAddLib',array('name'=>'addUser','autocomplete'=>'off','onSubmit'=>'return validateLibForm()')) .          
        '<center><table><tr><td>' . 
        $form->addLabelFor('libraryname', 'Library Name') . ' </td><td>'  .
        $form->addInput('text', 'libraryname', $name, array('id'=>'libraryname', 'size'=>32, 'maxlength'=>50) ) . 
         '<span id="libname_error" class="libname_error"> -- This Field is Required</span></td></tr><tr>' .  
         '<td colspan="2" align=center>' . $form->addInput('submit', 'btnAddLibrary', "Add this Library",array('class'=>'lq-button')) .   '</td></tr>
         
         </table>
         </center><br><br>'; 
         
         return $output; 
    }
    
  public function name  ($library_id)
  {
	  global $dbh;
	  
	  $sql = "SELECT library FROM library where library_id = " . $library_id;
	  $res = $dbh->query($sql);
	  $rec = $dbh->fetch($res,'');
	  
	  return $rec->library;
  }
  
    public function view()
    {
        global $dbh;
        $even = 2;
        
        
        if (isset($_REQUEST['library']) && !empty($_REQUEST['library']))
        {
			$query = "SELECT library FROM library WHERE library_id = " . $_REQUEST['library'];
			$res = $dbh->query($query);
			
			 $row = $dbh->fetch($res,'');
				 echo "<center><form  action = ?op=savelibrary METHOD=POST><input type=text name=library value = '" . $row->library . "'>
				 <input type=hidden name=library_id value=" . $_REQUEST['library'] . ">
				 <input type=submit value=Update class=lq-button></form></center>";
			 
           
		}
			else
			{
        $query = "SELECT * from library";
        
        $res = $dbh->query($query);
        
        echo '<center><h2>View / Modify Libraries </h2><br>
        <table width=600>
        <tr bgcolor = "#343434">
        <th class=admin>Id</th><th class=admin>Library Name</th>
        <th colspan="2" class=admin>Action</th>
        </tr>';
        
        while ($row = $dbh->fetch($res,''))
        {
           
            if ($even%2==0)
            $color = '#e0e0e0';
            else $color = '#a1a1a1';
            echo '<tr bgcolor=' . $color . ' class="results"><td>' . $row->library_id . '</td>
            <td class="results">' . $row->library . '</td>
            <td align=right width=30><a href = ?op=editlib&library=' . $row->library_id .'>Edit</a></td><td align=right width=40><a href=?op=dellib&lid=' . $row->library_id . '>Delete</td></tr>';
            $even++;
        }
        
        echo '</table>';
	}
	
	
	
        
    }
    
    public function add()
    {
    global $dbh; 
    $library =  $_REQUEST['libraryname'];
        
        $query = "SELECT library FROM library where library = '" . $library . "'";
        $res = $dbh->query($query);
        $rows = $dbh->rows($res);
        
        if (!empty($library))
        {
           if ($rows > 0)
           {
             $errorStack[] = "ERROR: "  . $library . " is already in the database";
           }
        else {
               $query = "INSERT INTO library (library)" . 
                 "VALUES ('" . 
                 $_REQUEST['libraryname'] . 
                 "')"
                 ;
     
         if ($res = $dbh->query($query))
         {
             echo "<script>alert('Record Added'); location.href='?op=editlib';</script>";
         } 
                
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
    
    public function save($library_id,$library)
    {
       global $dbh;
       $query = "UPDATE library SET library = '" . $library . "' WHERE library_id = " . $library_id;
       $dbh->query($query);
       header("Location: ?op=editlib");
    }
    
    public function del()
    {
    global $dbh;
            $id = $_REQUEST['lid'];
            if (!empty($id))
            {
            $query = "DELETE FROM library where library_id = " . $id;
            $res = $dbh->query($query);
            } 
            
            if ($res)
            {  
             echo '<script>alert("Record Deleted"); location.href="?op=editlib"</script>';
            }
            else {
               
                 echo '<script>alert("ERROR:\r\n This record cannot be deleted. Please contact your systems administrator, the message associated with this error is  \"Referential Integrity Violation\""); location.href="?op=editlib"</script>'; 
            }
    }      
    
    public function getAll()
    {
		    global $dbh;
           
            $query = "select library_id, library FROM library";
            $res = $dbh->query($query);
            
            while ($row = $dbh->fetch($res,''))
            {
				$rtn[$row->library_id] = $row->library;
			}
            return $rtn;
	}
	
    public function __destruct()
    {
      $name = $this->name;
      $page = $this->page;
      unset($name); 
      unset($page);
    }
    
}

?>
