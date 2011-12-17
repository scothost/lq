var anylinkmenu1={divclass:'anylinkmenu', inlinestyle:'', linktarget:''} //First menu variable. Make sure "anylinkmenu1" is a unique name!
anylinkmenu1.items=[
	["Add a User", "?op=adduser"],
	["Edit a User", "http://www.cssdrive.com/"],
	["Delete A User", "http://www.javascriptkit.com/"] //no comma following last entry!
]



var anylinkmenu2={divclass:'anylinkmenu', inlinestyle:'width:150px; background:#FDD271', linktarget:'_new'} //Second menu variable. Same precaution.
anylinkmenu2.items=[
	["Add a Department", "http://www.cnn.com/"],
	["Edit a Department", "http://www.msnbc.com/"],
	["Remove a Department", "http://www.google.com/"]
	
]



var anylinkmenu3={divclass:'anylinkmenucols', inlinestyle:'', linktarget:''} //Third menu variable. Same precaution.
anylinkmenu3.cols={divclass:'column', inlinestyle:''} //menu.cols if defined creates columns of menu links segmented by keyword "efc"
anylinkmenu3.items=[
    ["Add a User", "?op=adduser"],
    ["Edit a User", "?op=moduser"],
    ["Delete A User", "?op=deluser" , "efc"], 
    ["Add a Department", "?op=adddept"],
    ["Edit a Department", "?op=moddept"],
    ["Remove a Department", "?op=deldept", "efc"],
    ["Add a Library", "?op=addlib"],
    ["Edit a Library", "?op=modlib"],
    ["Remove a Library", "?op=dellib"]    
]

var anylinkmenu4={divclass:'anylinkmenu', inlinestyle:'width:150px; background:#DFFDF4', linktarget:'_new'} //Second menu variable. Same precaution.
anylinkmenu4.items=[
	["Google", "http://www.google.com/"],
	["BBC News", "http://news.bbc.co.uk"] //no comma following last entry!
]