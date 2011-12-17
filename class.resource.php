  <?php 
require_once 'class.layout.php';
require_once 'class.dbh.php';

 /**
     * Resource Class - 
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


class Resource {
    
    
    public function __construct()
    {
        
    }
    
    
    public function __destruct()
    {
    
    }
    
    public function getNames()
    {
		 global $dbh; 
		 
		 $query = "select * from resource";
		 $res = $dbh->query($query);
		 
		 while ($obj = $dbh->fetch($res,''))
		 {
			 $rtn[$obj->resource_id] = $obj->resource;
		 }
		 return $rtn;
	}
    
    public function add()
    {
    global $dbh; 
    $resource =  $_REQUEST['resource'];
        
        $query = "SELECT resource FROM resource where resource = '" . $resource . "'";
        $res = $dbh->query($query);
        $rows = $dbh->rows($res);
        
        if (!empty($resource))
        {
           if ($rows > 0)
           {
             $errorStack[] = "ERROR: "  . $resource . " is already in the database";
           }
        else {
               $query = "INSERT INTO resource (resource)" . 
                 "VALUES ('" . 
                 $_REQUEST['resource'] . 
                 "')"
                 ;
     
         if ($res = $dbh->query($query))
         {
             echo "<script>alert('Record Added'); location.href='?op=editresource';</script>";
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
    
    public function edit()
    {}
    
    public function del()
    {
         global $dbh;
            $id = $_REQUEST['rid'];
            if (!empty($id))
            {
            $query = "DELETE FROM resource where resource_id = " . $id;
            $res = $dbh->query($query);
            } 
            
            if ($res)
            {  
             echo '<script>alert("Record Deleted"); location.href="?op=editresource"</script>';
            }
            else {
               
                 echo '<script>alert("ERROR:\r\n This record cannot be deleted. Please contact your systems administrator, the message acssociated with this error is  \"Referrential Integrity Violation\""); location.href="?op=editresource"</script>'; 
            }
    }
    
    public function resourceForm()
    {
        global $dbh;
        echo "<form name=resource method=post onSubmit='return validateResource()'>
       <h3 align=center> Please choose the resource used</h3><br> 
       <p align=center> <select id=resource name=resource>
        <option value=0>Please choose ...</option>
        ";
        
        $query = "Select * from resource";
        $res = $dbh->query($query);
        
        while ($obj = $dbh->fetch($res,''))
        {
            echo '<option value=' . $obj->resource_id . '>' . $obj->resource . '</option>';
        }
        
        echo "</select>
         <span id=resource_error class=resource_error> -- Please choose</span>  
         Date 
         <input type=text  name = date_used  size=10 id=calendar4>
 <span id=resourcedate_error class=resourcedate_error> -- Please choose a date</span>  
        <input class=lq-button type=submit name=addResource value = 'Add Now'>
        </p></form>";
        
        
    }
    
    public function insertResource($resource_id)
    {
        global $dbh;
        
        $thisDate = getdate();
        
        $today = $thisDate['year'] . '-' . $thisDate['mon'] . '-' . $thisDate['mday'];
        $date_used = $_REQUEST['date_used'];
        
        $query = "insert into resource_transaction (resource_id, used_date)" .
        " values (" . $resource_id . ",'" . lqCase::convertDate($date_used,'us') . "')";
       debug("INSERTING RESOURCE" . $query);
        $dbh->query($query);
    }

    public function getUserInput($page)
    {
        echo '<h2 align="center">' . $page . '</h2><br>
        
        <form method=post><center><table><tr><th>Resource Name</th><td><input type=text name=resource></td></tr>
        <tr><td colspan="2" align="center"><input class = "lq-button" type=submit value="Add this Resource"></form></td></tr></table></center>';
        
     }   

     public function save($resource_id,$resource)
     {
	   global $dbh;
       $query = "UPDATE resource SET resource = '" . $resource . "' WHERE resource_id = " . $resource_id;
       $dbh->query($query);
       header("Location: ?op=editresource");
	 }
	 
     public function view()
     {
        global $dbh;
        $even = 2;
        
        
        if (isset($_REQUEST['rid']) && !empty($_REQUEST['rid']))
        {
			$query = "SELECT resource FROM resource WHERE resource_id = " . $_REQUEST['rid'];
			$res = $dbh->query($query);
			
			 $row = $dbh->fetch($res,'');
				 echo "<center><form  action = ?op=saveresource&rid=" . $_REQUEST['rid'] . " METHOD=POST>
				 <input type=text name=resource value = '" . $row->resource . "'>
				 <input type=hidden name=resource_id value=" . $_REQUEST['rid'] . ">
				 <input type=submit value=Update class=lq-button></form></center>";
			 
           
		}
			else
			{
        $query = "SELECT * from resource";
        
        $res = $dbh->query($query);
        
        echo '<center><h2>View / Modify Resources</h2><br>
        <table width=600>
        <tr bgcolor = "#343434">
        <th class=admin>Id</th><th class=admin>Resource Name</th>
        <th colspan="2" class=admin>Action</th>
        </tr>';
        
        while ($row = $dbh->fetch($res,''))
        {
           
            if ($even%2==0)
            $color = '#e0e0e0';
            else $color = '#a1a1a1';
            echo '<tr bgcolor=' . $color . ' class="results"><td>' . $row->resource_id . '</td>
            <td class="results">' . $row->resource . '</td>
            <td align=right width=30><a href = ?op=editresource&rid=' . $row->resource_id .'>Edit</a></td><td align=right width=40><a href=?op=delresource&rid=' . $row->resource_id . '>Delete</td></tr>';
            $even++;
        }
        
        echo '</table>';
	}
     }

}     

     
?>
