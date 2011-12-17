<?php 
require_once 'class.layout.php';
require_once 'class.dbh.php';
 /**
     * Borrower Class - 
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


class borrower {
 
    var $firstName, $surName, $addr1, $addr2, $town, $county, $country, $phone, $email;
 
 /*   
    public function __construct($fistName='', $surName='', $addr1='', $addr2='', $town='', $county='', $country='', $phone='', $email='')
    {
         $this->firstName       =   $firstName;
         $this->surName         =   $surName;
         $this->addr1           =   $addr1;
         $this->addr2           =   $addr2;
         $this->town            =   $town;
         $this->county          =   $county;
         $this->country         =   $country;
         $this->phone           =   $phone;
         $this->email           =   $email;
         
    }
   */ 
   
/**
* Function __constructor()
* 
* Presently takes no input
* 
* @return void
*    
*/
     public function __construct($borrower_id = '')
     {
         global $dbh;
        
        $sql = "SELECT *  FROM borrower where borrower_id = " . $borrower_id;
       // echo $sql;
        $res = $dbh->query($sql);
        $obj = @$dbh->fetch($res,'');
        
         $this->borrower_id     =   $obj->borrower_id;
         $this->library_id      =   $obj->library_id; 
         $this->firstName       =   $obj->firstname;
         $this->surName         =   $obj->surname;
         $this->addr1           =   $obj->addr1;
         $this->addr2           =   $obj->addr2;
         $this->town            =   $obj->town;
         $this->county          =   $obj->county;
         $this->country_id      =   $obj->country_id;
         $this->phone           =   $obj->phone;
         $this->email           =   $obj->email; 
         $this->borrower_type  =   $obj->borrower_type;  
         
       
     
     }

/**
* Function getInputNameValue()
* 
* 
*  Splits a full name into surname and firstname
* 
* @param string $name - a full name in the format Surname, Lastname
* @param integer $type - The type of name to return 1 = surname 2 = firstname
* 
* 
* @return string
*    
*/ 
public function getInputNameValue($name,$type)
{
   
    global $dbh;
       
    $nameArr = explode(',',$name);
    
    if ($type == 1)
    {
        return $nameArr[0];
    }
    else if ($type == 2)
    {
        return $nameArr[1];
    }
    else {
        $errorStack[] = "Input validations error";
        return $errorStack;
    }
}    
    
    
}