
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html lang="en"> 
    <head> <title>Surado Dashboard</title> 
    <meta http-equiv="Refresh" Content="180; URL=http://172.16.0.50/surado/main.pl"> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <meta http-equiv="Content-Style-Type" content="text/css"> 
        <meta http-equiv="Content-Script-Type" content="text/javascript"> 
 
       
        <script src="js/jquery/jquery.tools.min.js"></script> 
        <script src="js/jquery/jquery-1.1.3.1.pack.js" type="text/javascript"></script> 
        <script src="js/jquery/jquery.history_remote.pack.js" type="text/javascript"></script> 
        <script src="js/jquery/jquery.tabs.pack.js" type="text/javascript"></script> 
        
    <link rel="stylesheet" type="text/css" href="css/standalones.css"/> 
    <link rel="stylesheet" type="text/css" href="http://static.flowplayer.org/tools/css/overlay-apple.css"/> 
        <link rel="stylesheet" href="css/jquery.tabs.css" type="text/css" media="print, projection, screen"> 
        <!-- Additional IE/Win specific style sheet (Conditional Comments) --> 
        <!--[if lte IE 7]>
        <link rel="stylesheet" href="jquery.tabs-ie.css" type="text/css" media="projection, screen">
        <![endif]--> 
        <style type="text/css" media="screen, projection"> 
 
 
            /* Not required for Tabs, just to make this demo look better... */
 
            body {
                font-size: 16px; /* @ EOMB */
            }
            * html body {
                font-size: 100%; /* @ IE */
            }
            body * {
                font-size: 87.5%;
                font-family: "Trebuchet MS", Trebuchet, Verdana, Helvetica, Arial, sans-serif;
            }
            body * * {
                font-size: 100%;
            }
            h1 {
                margin: 1em 0 1.5em;
                font-size: 18px;
            }
            h2 {
                margin: 2em 0 1.5em; 
                font-size: 16px;
            }
            p {
                margin: 0;
            }
            pre, pre+p, p+p {
                margin: 1em 0 0;
            }
            code {
                font-family: "Courier New", Courier, monospace;
            }
            table.res {
    border-width: 1px;
    border-spacing: 0px;
    border-style: dotted;
    border-color: blue;
    border-collapse: collapse;
    background-color: white;
    width: 90%;
    }
    table.res th {
    border-width: 1px;
    padding: 1px;
    border-style: dotted;
    border-color: blue;
    background-color: white;
    -moz-border-radius: ;
    }
    table.res td {
    border-width: 1px;
    padding: 3px;
    border-style: dotted;
    border-color: blue;
    background-color: white;
    -moz-border-radius: ;
    }
</style> 
 
<style> 
    
    /* use a semi-transparent image for the overlay */
    #overlay {
        background-image:url(http://static.flowplayer.org/img/overlay/transparent.png);
        color:#efefef;
        height:450px;
    }
    
    /* container for external content. uses vertical scrollbar, if needed */
    div.contentWrap {
        height:441px;
        font-size: 140%;
                font-family: "Trebuchet MS", Trebuchet, Verdana, Helvetica, Arial, sans-serif;
                color: black;
                background-color: ;
        overflow-y:auto;
        overflow-x:auto;
    }
    
    </style> 
        
 
    </head> 
<a href="searchform.pl" rel="#overlay"><span>Search Contacts</span></a> 
    <body> 
        <h2>Surado At A Glance</h2> 
    
 
        <div id="container-5"> 
            <ul> 
        <li><a href="#fragment-13"><span>Home</span></a></li> 
    
      <li><a href="#fragment-20"><span>Search</span></a></li> 
      <li><a href="#fragment-21"><span>Add</span></a></li> 
      <li><a href="#fragment-20"><span>express</span></a></li> 
      <li><a href="#fragment-21"><span>Reports</span></a></li> 
    
</div><div id="fragment-20">
 
</div><div class="apple_overlay" id="overlay"> 
 
    <!-- the external content is loaded inside this tag --> 
    <div class="contentWrap"></div> 
 
</div> 
 
<!-- make all links with the 'rel' attribute open overlays --> 
<script> 
 
$(function() {
 
    // if the function argument is given to overlay,
    // it is assumed to be the onBeforeLoad event listener
    $("a[rel]").overlay({
 
        mask: 'lightblue',
        effect: 'apple',
 
        onBeforeLoad: function() {
 
            // grab wrapper element inside content
            var wrap = this.getOverlay().find(".contentWrap");
 
            // load the page specified in the trigger
            wrap.load(this.getTrigger().attr("href"));
        }
 
    });
});
</script> 
<div id="fragment-21">

</div> 
 
 
<script type="text/javascript"> 
            $(function() {
              $('#container-5').tabs({ fxSlide: true, fxFade: true, fxSpeed: 'slow' });
            });
        </script> 
 
 
 
    </body> 
</html> 