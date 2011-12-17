<?php
require_once('config.php');
require_once('class.case.php');
require_once('class.user.php');
require_once('class.library.php');
require_once('class.department.php');
require_once('class.phpmailer.php');


$caseid = $_REQUEST[caseid];
$query = "SELECT * FROM cases, borrower WHERE cases.borrower_id = borrower.borrower_id AND cases.caseid = " . $caseid;
$res = $dbh->query($query);
$obj = $dbh->fetch($res,'');

$sql = "SELECT category from category WHERE category_id = " . $obj->category_id;
$res = $dbh->query($sql);
$catObj = $dbh->fetch($res,'');

$table1 = "
<table border=0 align=center cellspacing=0 cellpadding=0>
<tr><td colspan=2><img src=img/lqpdf.jpg></td></tr>
  <tr> 
    <td>Case ID: " . $caseid . "</td>
        <td align=right>" . Library::name($obj->library_id) . " Library</td>
  </tr>
    <tr> 
    <td>Date Added: " .  lqCase::convertDate(substr($obj->added_date,0,10)) . "</td>
        <td align=right>" . Department::name($obj->department_id) . " Department</td>
  </tr>
  
  <tr> 
    
        <td colspan=2>&nbsp;</td></td>
  </tr>
  
  <tr> 
    
        <td colspan=1>Borrower Name: " . $obj->surname . ", " . $obj->firstname . "</td>
        <td align=right>" . date("F j, Y, g:i a") . "</td>
  </tr>
  
  <tr> 
   </tr></table>
  
   
   <table align=left width=725 border=0>
   <tr><td ><br><br>Case Header:</td></tr></table>
   <table border=1 width=250>
   <tr ><td >" . $obj->case_header . "<br></td></tr>
   
   
   </table>

   
   <table align=center width=250 >
   <tr><td ><br><br>Case Detail:</td></tr></table>
   
   <table width=250 border=1><tr ><td>" . $obj->case_detail . "</td></tr>
   
   </table>
   
 
   
   <table align=center width=725 >
   <tr><td ><br><br>Case Response:</td></tr></table>
   
   <table border=1 width=250>
   <tr ><td >" . $obj->case_response . "</td></tr>
   
   </table>
   

   
   
   <table align=center width=500 border=0 >
   <tr><td><br><br>Referred To:</td></tr>
   </table>
   
   <table border=1 width=250>
   <tr ><td>" . $obj->referred_to . "</td></tr>
   
   </table>
   <br>
   
   
   <table align=center width=250>
   <tr><td><br>Date of Response: " . 
   lqCase::convertDate(substr($obj->response_date,0,10))  . "</td><td align=right><br>Staff Member: " . User::nameFromId($obj->assigned_to) . "</td></tr>
   
   </table>
   
   
   


";

//print $table1;


define('FPDF_FONTPATH','pdf/font/');
require('pdf/lib/pdftable.inc.php');
$p = new PDFTable();
//$p->AddPage();
//$p->Image('img/header.png',NULL,NULL,136.5,NULL,NULL,NULL);
$p->setfont('arial','',10);
$p->htmltable($table1);

if ($_GET['email'] != 'true')
$p->output('Library-Query-' . $caseid . '.pdf','I');
else if ($_GET['email'] == 'true')
{
$p->output('Library-Query-' . $caseid . '.pdf','F');
$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

$mail->IsSMTP(); // telling the class to use SMTP
$msqHtml = "<font color=black>Thank you for your query to " . Library::name($obj->library_id) . ", " . 
Department::name($obj->department_id) . 
" Department .<br><p> Your query was assigned the case number, 
" . $caseid . " . 
</p><p>
Please find attached the response to your query. <br><br>If  " . Library::name($obj->library_id) . 
" can help you in any other way please do not hesitate to contact us. <br>
Please quote the case number, caseid, in any further correspondence.
</p><p>
Kind Regards
<br>
The Team
<br><br>" . 
Library::name($obj->library_id) . ", " .  Department::name($obj->department_id) . "Department
</p></font>";

 
try {
 // $mail->Host       = "mail.yourdomain.com"; // SMTP server
  $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
  $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
  $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
  $mail->Username   = "peter.lorimer@gmail.com";  // GMAIL username
  $mail->Password   = "retr0spect";            // GMAIL password
  $mail->AddReplyTo('peter.lorimer@gmail.com', 'Peter Lorimer');
  $mail->AddAddress($obj->email, '');
  $mail->SetFrom('peter.lorimer@gmail.com', 'Peter Lorimer');
  $mail->AddReplyTo('peter.lorimer@gmail.com', 'Peter Lorimer');
  $mail->Subject = 'LQ Case Details';
  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
  $mail->MsgHTML($msqHtml);
  $mail->AddAttachment('Library-Query-' . $caseid . '.pdf');      // attachment
  //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
  $mail->Send();
  echo "<script>alert('Message Sent OK'); location.href='/?op=cases&caseid=" . $caseid . "'</script>";
} catch (phpmailerException $e) {
  echo "<script>alert('ERROR - There is a problem with your SMTP configuration, Please contact your systems administrator!!!'); location.href='/?op=cases&caseid=" . $caseid . "'</script>"; //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
}


?>
