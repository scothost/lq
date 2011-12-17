function validateForm (validateDate)
{      
var valid = true;

// ADD CASE FORM                                               
var borrower        =   document.getElementById('borrower');                                  
var addrStreet      =   document.getElementById('addrStreet');
var addrArea        =   document.getElementById('addrArea');
var addrTown        =   document.getElementById('addrTown');        
var addrCounty      =   document.getElementById('addrCounty');    
var phone           =   document.getElementById('phone');    
var email           =   document.getElementById('email');    
var source          =   document.getElementById('source');       
var caseHeader      =   document.getElementById('caseHeader');      
var subjects        =   document.getElementById('subjects'); 
var caseDetail      =   document.getElementById('caseDetail'); 
var caseResponse    =   document.getElementById('caseResponse'); 
var referredTo      =   document.getElementById('referredTo'); 
var caseNotes       =   document.getElementById('caseNotes'); 


    var addedDate = document.getElementById('calendar').value;
    var deadlineDate = document.getElementById('calendar1').value;
    var reviewDate = document.getElementById('calendar2').value;
    var responseDate = document.getElementById('calendar3').value;
    var x=new Date();
    var y=new Date();     
    // #FIXME - hmm - US to UK date conversions  are doing my head in, lets see if this works
    var mySplit = responseDate.split('-'); 
    var mySplit1 = addedDate.split('-'); 
    
x.setFullYear(mySplit[2],mySplit[1]-1,mySplit[0]);
y.setFullYear(mySplit1[2],mySplit1[1]-1,mySplit1[0]);
var today = new Date()


if (y>today)
  {
  alert("The Added Date  " + y + " is in the future, please choose a date on or before today");
  return false;
  }
  
if (x>today)
  {
  alert("The Date of Response  " + x + " is in the future, please choose a date on or before today");
  return false;
  }
  
    if (deadlineDate < addedDate)
    {
		alert('Error - The deadline date cannot be before the date added');
		return false;
	}   
	
	
	

        // Test borrower name has only letters, spaces and dashes
         if (borrower.value != "")
        {
             if (borrower.value.search( /^['"\sA-Za-z-]+(\s{1,2},|,)(\s[A-Za-z-]+|[A-Za-z-]+)/) == -1)
             {
                 valid = false;
                 document.getElementById('borrower_error').style.display = 'block'; 
                 return valid;
             } 
		 }
/*         
        if (addrStreet.value == "")
        {
            
            document.getElementById('addrStreet_error').style.display = 'block';
           // addrStreet.focus();
            valid =  false;       
        } 
        else {
             document.getElementById('addrStreet_error').style.display = 'none';            
        }

        if (addrArea.value == "")
        {
            
            document.getElementById('addrArea_error').style.display = 'block';
           // addrStreet.focus();
            valid =  false;       
        }
        else {
             document.getElementById('addrArea_error').style.display = 'none';            
        }        

        if (addrTown.value == "")
        {
            
            document.getElementById('addrTown_error').style.display = 'block';
           // addrStreet.focus();
            valid =  false;       
        }        
        else {
             document.getElementById('addrTown_error').style.display = 'none';            
        }
        
        if (addrCounty.value == "")
        {
            
            document.getElementById('addrCounty_error').style.display = 'block';
           // addrStreet.focus();
            valid =  false;       
        }        

        else {
             document.getElementById('addrCounty_error').style.display = 'none';            
        }
                
        if (phone.value == "")
        {
            
            document.getElementById('phone_error').style.display = 'block';
           // addrStreet.focus();
            valid =  false;       
        }        
        else {
             document.getElementById('phone_error').style.display = 'none';            
        }
        
        if (email.value == "")
        {
            document.getElementById('email_error').style.display = 'block';
            valid = false;  
        }
        else  if (email.value.search(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/) == -1 ) 
        {
            document.getElementById('email_error').style.display = 'block';
            valid = false;        
        }      
           
        else {
             document.getElementById('email_error').style.display = 'none';            
        }
               
*/   

     
        if (source.value == "" )
        {
            
            document.getElementById('source_error').style.display = 'block';
            valid = false;       
        }     
           
        else {
             document.getElementById('source_error').style.display = 'none';            
        }
        
   
        if (caseHeader.value == "" )   
        {
            
            document.getElementById('caseHeader_error').style.display = 'block';
           // addrStreet.focus();
            valid = false;       
        }     
           
        else {
             document.getElementById('caseHeader_error').style.display = 'none';            
        }
        
/*
         if (subjects.value == "" )   
        {
            
            document.getElementById('subjects_error').style.display = 'block';
           // addrStreet.focus();
            valid = false;       
        }     
           
        else {
             document.getElementById('subjects_error').style.display = 'none';            
        }
        
       if (caseDetail.value == "" )   
        {
            
            document.getElementById('caseDetail_error').style.display = 'block';
           // addrStreet.focus();
            valid = false;       
        }     
           
        else {
             document.getElementById('caseDetail_error').style.display = 'none';            
        }
                
       if (caseResponse.value == "" )   
        {
            
            document.getElementById('caseResponse_error').style.display = 'block';
           // addrStreet.focus();
            valid = false;       
        }     
           
        else {
             document.getElementById('caseResponse_error').style.display = 'none';            
        }
        
      if (referredTo.value == "" )   
        {
            
            document.getElementById('referredTo_error').style.display = 'block';
           // addrStreet.focus();
            valid = false;       
        }     
           
        else {
              document.getElementById('referredTo_error').style.display = 'none';    
        }
              
      if (caseNotes.value == "" )   
        {
            
            document.getElementById('caseNotes_error').style.display = 'block';
           // addrStreet.focus();
            valid = false;       
        }     
           
        else {
             document.getElementById('caseNotes_error').style.display = 'none';            
        }                            
 */       
       
 return valid;       

}

function validateUserForm ()
{ 
var valid          =   true;
var username       =   document.getElementById('username'); 
var password       =   document.getElementById('password'); 
var email          =   document.getElementById('email'); 
var library        =   document.getElementById('library'); 
var department     =   document.getElementById('department');   
           // User

     
        
        if (username.value == "" )   
        {
            
            document.getElementById('username_error').style.display = 'block';
            valid = false;       
        }     
           
        else {
             document.getElementById('username_error').style.display = 'none';            
        }   

        if (password.value == "" )   
        {
            
            document.getElementById('password_error').style.display = 'block';
            valid = false;       
        }   
       
           
        else {
             document.getElementById('password_error').style.display = 'none';            
        }   
        
        if (email.value == "" )   
        {
            
            document.getElementById('email_error').style.display = 'block';
            valid = false;       
        } 
        
         else  if (email.value.search(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/) == -1 ) 
        {
            document.getElementById('email_error').style.display = 'block';
            valid = false;        
        }      
           
        else {
             document.getElementById('email_error').style.display = 'none';            
        } 
        
        if (library.value == "" )   
        {
            
            document.getElementById('library_error').style.display = 'block';
            valid = false;       
        }  
        else
        {
			 document.getElementById('library_error').style.display = 'none';
		}     
        if (department.value == "" )   
        {
            
            document.getElementById('department_error').style.display = 'block';
            valid = false;       
        }  
        else
        {
			 document.getElementById('department_error').style.display = 'none';
		}                 
   
   if (valid == true)
   {
   alert('Success!'); 
   }    
   return valid; 
}



function validateLibForm ()
{ 
var valid             =   true;
var libraryname       =   document.getElementById('libraryname'); 

           // User
      
        if (libraryname.value == "" )   
        {
            
            document.getElementById('libname_error').style.display = 'block';
           // addrStreet.focus();
            valid = false;       
        }     
           
        else {
             document.getElementById('libname_error').style.display = 'none';            
        }   

        
                
   
  
   return valid; 
}

function validateDeptForm ()
{ 
var valid             =   true;
var deptname       =   document.getElementById('deptname'); 

           // User
      
        if (deptname.value == "" )   
        {
            
            document.getElementById('deptname_error').style.display = 'block';
           // addrStreet.focus();
            valid = false;       
        }     
           
        else {
             document.getElementById('deptname_error').style.display = 'none';            
        }   

        
                
   
  
   return valid; 
}

function toggleCaseHistory()
{
    var e = document.getElementById('caseHistory').style.display;
    
     if (e == 'none' || e =='')
     {
                document.getElementById('lineitems').style.display = 'block';  
                document.getElementById('caseHistory').style.display = 'block';
                document.getElementById('caseHistoryLabel').style.display = 'block';             
     }                                         
     else
     {
         document.getElementById('lineitems').style.display = 'none';  
         document.getElementById('caseHistory').style.display = 'none';
         document.getElementById('caseHistoryLabel').style.display = 'none';           
     }
}

function toggleHidden(e)
{
   var choice = document.getElementById('criteria');
      
 // alert(choice.value);  
  if ( (e.id == "search_dept"))
  {
     
         if (e.style.display == 'none' || e.style.display =='')
         {
               if (choice.value == "department")
               {
                e.style.display = 'block';
               }
         }                                         
     else
     {
        e.style.display = 'none';
     }
     
      
  }   
    
}

function validateSearchForm()
{
    var criteria     = document.getElementById('criteria');
    var dept         = document.getElementById('search_dept');
    var from         = document.getElementById('calendar4');  
    var to           = document.getElementById('calendar5');    
    
    if (criteria.value == "none" && from.value == '' && to.value == '')
    {
        document.getElementById('criteria_error').style.display = 'block';
        return false;
    }
    
    if (dept.style.display == 'block' && dept.value == 0 && from.value == '' && to.value == '')
    {
        document.getElementById('dept_error').style.display = 'block';
        return false;
    }
    
    if ( (from.value != '' && to.value == '') || (from.value == '' && to.value !=''))
    {
        document.getElementById('date_error').style.display = 'block';
        return false;   
    }
    
    return true;
}

function validateResource()
{
    var resource        =   document.getElementById('resource');
    var resourcedate    =   document.getElementById('calendar4');
    
  
    if (resource.value <= 0)
    {
        document.getElementById('resource_error').style.display = 'block';
        document.getElementById('resourcedate_error').style.display = 'none';        
        return false;        
    }
    
        if (!resourcedate.value)
    {
		 
        document.getElementById('resourcedate_error').style.display = 'block';
        document.getElementById('resource_error').style.display = 'none';
        return false;        
    }
    
    return true;     
    
}
