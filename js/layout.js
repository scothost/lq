var count1 = 0;
var count2 = 0;

function fillSubjects()
{
var myselect = document.getElementById('subjects');   
var subjects = myselect.value;
var valueToAdd;
var subjectsTxt = document.addCase.subjectsTxt.value;
var match = -1; //subjectsTxt.search(subjects);
var nl = '\n';
//var hr = '~~~~';
 
for (var i=0; i < myselect.options.length; i++){
 if (myselect.options[i].selected == true){
  valueToAdd = myselect.options[i].text;
  break
 }
}

 
 if (document.addCase.subjectsTxt.value != '')
 {
  if (subjects != '' && subjects != 'none' && match == -1)
    {
     document.addCase.subjectsTxt.value = subjectsTxt+nl+valueToAdd; 
     }
  }
  else {
       if (subjects != '' && subjects != 'none' && match == -1)
    {
  document.addCase.subjectsTxt.value = valueToAdd;
    }
  }

}
   
function updateData(param) {  
// FIXME - Hard codeed         
  var myurl = 'addNewSubject.php';   

  http.open("GET", myurl + "?" + escape(param), true);
  http.onreadystatechange = useHttpResponse;
  http.send(null);

}

function useHttpResponse() {
  if (http.readyState == 4) {
    var textout = http.responseText;
   alert(textout);
  }
}


// Workaround for clients request that global admin to be able to change library so depatment needs to be 
// updated based on the value of the library  select form control
function useHttpResponse2() {
  if (http.readyState == 4) {
    var textout = http.responseText;
     var deptArr = new Array();
  var deptArr = textout.split(',');
  var vals;
  var libSel = document.getElementById('library');
   var theSel = document.getElementById('department'); 
   theSel.length = 0;
   //libSel.options[0] = null;
 
  for (el in deptArr)
  {	 
	vals = deptArr[el].split(':');
	

	theSel.options[theSel.length] = new Option(vals[1], vals[0]);
	
  }
    
 }
}
 
function libDepartments(param) {  
// FIXME - Hard codeed  
       
  var myurl = 'libDepartments.php?lib='+param.value;   
  //alert(myurl);
 http = new XMLHttpRequest();
 theSel = document.getElementById('department');    
    
  http.open("GET", myurl, true);
  http.onreadystatechange = useHttpResponse2;
  http.send(null);
 
}
// Add an enrty to the end of a selcet list. window.parent = IE, window.opener = GEKO 
function appendOptionLast(num,val,win)
{
  var newTxt;     
  var elOptNew = self.document.createElement('option');  
  var elSel = self.document.getElementById('subjects'); 
  

  if (win == 'parent')
  {
    elOptNew = opener.document.createElement('option');  
      elSel = opener.document.getElementById('subjects'); 
      
  }
  
 
  if (val != 'undefined')
  {
        newTxt  =  val;     
  }
  else {
         
      if (win == 'parent')
      {
       newTxt = opener.document.getElementById('theSubject').value;  
  
      }
      else {
          newTxt = self.document.getElementById('theSubject').value;   
          
      }
  
  } 
  
//replace + with spaces in the value of subject  
  var reg =  /[+]/g;
  newTxt = newTxt.replace(reg,' ') ;
  elOptNew.text = newTxt;
  elOptNew.value = newTxt;
  
   
  if (newTxt == '' || newTxt == 'undefined')
  {
      alert ('You never entered anything to add');
      elSel.focus();
      return false;
      exit;
  }
  else {
 
   
  
    if (val == 'undefined')
    {  
      http = new XMLHttpRequest();
      updateData(newTxt);
      useHttpResponse();  
    }   
      
      

           
  }
  

  
// Process new event here - Peter L
  try {
    elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
  }
  catch(ex) {
    elSel.add(elOptNew); // IE only
  }      
 
 
// A nifty bit of JQuery to remove duplicates from a select list for newly created items 
var usedNames = {};
$("select[name='subjects[]'] > option").each(function () {
    if(usedNames[this.text]) {
        $(this).remove();
    } else {
        usedNames[this.text] = this.value;
       
    }
});



}

// Select all the subkects
function selectAll(selectBox,selectAll) {
    // have we been passed an ID
    if (typeof selectBox == "string") {
        selectBox = document.getElementById(selectBox);
    }

    // is the select box a multiple select box?
    if (selectBox.type == "select-multiple") {
        for (var i = 0; i < selectBox.options.length; i++) {
            selectBox.options[i].selected = selectAll;
        }
    }
}


function redirectPage(url)
{
window.location = url;
}

var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["calendar","calendar1", "calendar2", "calendar3","calendar4","calendar5","calendar6","calendar7"]);
}

function doOnLoadImgLink() {
    myCalendar = new dhtmlXCalendarObject("calendarHere");
    myCalendar.show();
}


function thisDate()
{
    var thisDate = new Date();
    
    var y,m,d,today,nextweek;
    var addedDate = document.getElementById('calendar').value;
    var deadlineDate = document.getElementById('calendar1').value;
    var reviewDate = document.getElementById('calendar2').value;
  
  // Add trailing zeros to dates so 1-1-2011 becomes 01-01-2011   
    y  = thisDate.getFullYear();
    d  = thisDate.getDate();
    if (d <10)
    {
		d = "0"+d;
	}
     if (m <10)
    {
		m = "0"+d;
	}
    
    d1 = thisDate.getDate()+7;
    m  = thisDate.getMonth()+1;
    
    today = d+'-'+m+'-'+y;
    nextweekDate = new Date( (new Date()).getTime()+604800000);
    todaysDate = new Date( (new Date()).getTime());
    
    nwy  = nextweekDate.getFullYear();
    nwd  = nextweekDate.getDate();
    nwd1 = nextweekDate.getDate()+7;
    nwm  = nextweekDate.getMonth()+1;  
    
    nextweek = nwd+'-'+nwm+'-'+nwy; 

  // Initialize claender values
    if (document.getElementById('calendar').value == "")
    {
    document.getElementById('calendar').value = today;
    }
    if (document.getElementById('calendar1').value == "")
    { 
    document.getElementById('calendar1').value = nextweek;
    }
    if (document.getElementById('calendar2').value == "30-11-1999")
    { 
    document.getElementById('calendar2').value = '';
    }
    if (document.getElementById('calendar3').value == "")
    {
    document.getElementById('calendar3').value = today;
    }  

  return true;     
}



function createCookie(c_name,value,exdays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
}


