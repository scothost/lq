          <script src="js/jquery/jquery.tools.min.js"></script> 
        <script src="js/jquery/jquery-1.1.3.1.pack.js" type="text/javascript"></script> 
        <script src="js/jquery/jquery.history_remote.pack.js" type="text/javascript"></script> 

<?php
  require_once 'config.php';
require_once 'class.form.php';
require_once 'class.table.php';
require_once 'class.case.php';
ini_set("error_reporting",E_ALL & ~E_NOTICE);
error_reporting("on");
$myLayout = new Layout(SITENAME,SITEDESC,$styles,$javascript,$op);
//$myLayout->printHeader();
echo '<IMG src="/img/header.jpg" width="600">';

$myCase = new lqCase();
?>  

<script src=js/layout.js></script>
<script>

          function doIt(value)
          {
              var val = value.replace(/\+/g,' '); 
              var selectobject=window.opener.document.getElementById("subjects");
              var newSubj = opener.document.getElementById('theSubject').value;
              
              alert(val + ' will be added to the list. If it is already there it will be ignored');
              
               for (var i=0; i<selectobject.length; i++){

                 // if (newSubj == selectobject.options[i].value)
                    // selectobject.options[i] = null;


                  if (selectobject[i].value == val)
                      selectobject[i].remove();
 

                    }
                          
                          appendOptionLast(count2++,val,'parent'); // add to list

        
          
          }
</script>

<?php

          echo "
          <h1>Interleaf Library Database Query -> Search</h1>
          <em><font size=-1>The following authority subjects were found</font></em><br>";   
      //phpinfo();
        if  ( count($myCase->getSubjectList($_COOKIE['searchTerm']) ) <= 0)
        {
            echo "<h2 align='center'>No Results Found</h2>";
        }
        else {      
                foreach ($myCase->getSubjectList($_COOKIE['searchTerm'])  as $key => $val)
                {
                    $value = str_replace(" ","+",$val);
                    echo "<a style='text-decoration:none; color:black;' onClick=doIt('";
                    echo  $value;
                    echo "'); href=#  id=subj>" . $val . "</a><br/>";        
                }          
            }       
?>
