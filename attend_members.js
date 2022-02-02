function checkEmailFormat(emailName)
	{
	var namelength = emailName.length;
	var perlocation = emailName.lastIndexOf(".");
	var atlocation =  emailName.lastIndexOf("@");
	
	if ((namelength - perlocation) != 4)
		{
		alert("Invalid domain extension " + emailName);
		return false;
		}
	
	if ((namelength - perlocation) < 3)
		{
		alert("Invalid Domain - " + emailName);
		return false;
		}
	
	if ((perlocation - atlocation) < 3)
		{
		alert("Invalid Domain - " + emailName);
		return false;
		}
	
	if (atlocation < 3)
		{
		alert("Invalid Local Part of email - " + emailName);
		return false;
		}
		
	return true;
	}

var playerCount = 0;
var overallFail = false;
	
function verfyMemberEntries(teamid,newTeam)
	{
	var managerEntry = false;
	var emailError = false;
	var tableSize = document.getElementById("table_count").value;
	
	// Reset every call
	playerCount = 0;
	overallFail = false;

	for (var mgrbox=1; mgrbox <=tableSize; mgrbox++ )
		{
		find_email(mgrbox);
		var mgCheck = document.getElementById('checkBox'+mgrbox);
							
		if (mgCheck.checked)
			managerEntry = true;
		
		var checkEmail = document.getElementById('in_email'+mgrbox);
		if (checkEmail != null)
			{
			var email = checkEmail.value;
			
			if (email.length > 0)
				{
				if (checkEmailFormat(checkEmail.value) == false)
					emailError = true;
				}
			}
		}
		
	if (managerEntry == false)
		{
		alert('No Managers Found and at least 1 is required');
		return;
		}
		
	if (emailError == true)
		{
		// Specific Error message is logged in the check mail function
		return;
		}
		
	var XMLHttpRequestObject = [null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null];
	
	for (var mgrbox=1; mgrbox <=tableSize; mgrbox++ )
		{
		var emailBox = document.getElementById('in_email'+mgrbox);
		var fullNameBox = document.getElementById('in_name'+mgrbox);
		var fullNameText = document.getElementById('name_full'+mgrbox);
		var mgCheck = document.getElementById('checkBox'+mgrbox);
		var subCheck = document.getElementById('subcheckBox'+mgrbox);
		var subEntry = false;
							
		if (mgCheck.checked)
			{
			managerEntry = true;
			}
		else
			{
			managerEntry = false;						
			}
			
		if (subCheck.checked)
			{
			subEntry = true;
			}
		else
			{
			subEntry = false;						
			}
			
		var email = emailBox.value;
		var fullName = "";
		var responseCount = 0;
		
		if (fullNameBox != null)
			{
			fullName = fullNameBox.value;
			}
		else
			{
			var parts = fullNameText.innerHTML.split("<");
			fullName = parts[0];
			}
		if (email.length > 0)
			{
			event.preventDefault();
			XMLHttpRequestObject[mgrbox-1] = new XMLHttpRequest();
			
			if (XMLHttpRequestObject[mgrbox-1]) 
				{
				var phpPath = "http://" + WEBPATH + "/attend_members_db.php?add_mem=add&teamid=" + teamid + "&user=" + email + "&fname=" + fullName + "&mgr=" + managerEntry + "&sub=" + subEntry;
				
				XMLHttpRequestObject[mgrbox-1].open("GET",phpPath);
				playerCount = playerCount+1;
			
				XMLHttpRequestObject[mgrbox-1].onreadystatechange=function()
					{
					if (this.readyState==4 && this.status==200)
						{
						responseCount = responseCount+1;
						try
							{
							var result = JSON.parse(this.responseText);
							
							if (result == "success")
								{
								if ((overallFail==false) && (responseCount==playerCount))
									{
									if (newTeam==true)
										{
										$(location).attr('href', 'attend_games_main.php?team=' + teamid);
										}
									else
										{
										$(location).attr('href', 'attendance_home.php?display=teams');
										}
									}
								}
							else
								{
								alert(result);
								overallFail = true;
								}
							}										
						catch(err)
							{
							alert(this.responseText);	
							overallFail = true;
							}
						delete XMLHttpRequestObject[mgrbox-1];
						XMLHttpRequestObject[mgrbox-1] = null;					
						}
					}
					
				XMLHttpRequestObject[mgrbox-1].send();
				}
				
			}
		}
	}

function loadTeamMembers()
	{				
	var create = getQueryVariable("create");
	var copyTeam = getQueryVariable("copyteam");
	
	// If this is creating a new team, add the person creating the team automatically.
	if ((create == "true") && ((copyTeam == null) || (copyTeam == "-1")))
		{
		var email = getSessionPlayerEmail();
		var fname = getSessionPlayerName();
			
		document.getElementById("in_email1").value = email;
		document.getElementById("in_name1").value = fname;
		
		var checkbox = document.getElementById("checkBox1");
		checkbox.checked = true;
		
		return;
		}
	
	var teamid = getQueryVariable("team");
	
	if (copyTeam != null)
		{
		teamid = copyTeam;
		}
	
	var XMLHttpRequestObject = false;
	XMLHttpRequestObject = new XMLHttpRequest();
	
	if (XMLHttpRequestObject) 
		{
		XMLHttpRequestObject.open("GET","http://" + WEBPATH + "/attend_members_db.php?get_mem=get&teamid=" + teamid);
	
		XMLHttpRequestObject.onreadystatechange=function()
			{
			if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
				var result = JSON.parse(XMLHttpRequestObject.responseText);
				var pid = getSessionPlayerId();
				var table=document.getElementById("playertable");
				var table_len=(table.rows.length)-2;

				// update table here.
				for (var x = 0; x < result.length; x++ )
					{
					var cnt = x + 1;
					
					if (cnt > 15)
						{
						var insertRow = "<tr id='row"+cnt+"' bgcolor='#FFFFFF'><td style='text-align:center'><input type='checkbox' name='subcheckBox"+cnt+"' id='subcheckBox"+cnt+"'></td>";
							insertRow += "<td id='email"+cnt+"'><input type='text' name='name_email"+cnt+"' id='in_email"+cnt+"'></td>";
							insertRow += "<td id='find"+cnt+"'><input type='button' class='ind_button' id='find_button"+cnt+"' value='Find' onclick='find_email("+cnt+");'></td>";
							insertRow += "<td id='name_full"+cnt+"'><input type='text' name='name_full"+cnt+"' id='in_name"+cnt+"'></td>";
							insertRow += "<td style='text-align:center'><input type='checkbox' name='checkBox"+cnt+"' id='checkBox"+cnt+"'></td>";
							insertRow += "<td style='text-align:center'><input type='button' value='Cut' onclick='deleteRow("+cnt+")'></td></tr>";										

						var row = table.insertRow(table_len).outerHTML=insertRow;
						table_len = table_len+1;
						}
					
					document.getElementById("in_email"+cnt).value = result[x].email;
					
					if (copyTeam == null)
						{
						var newField = result[x].name + "<input type='hidden' id='pid" + cnt + "' value='" + result[x].player_id + "'>"
						document.getElementById("name_full"+cnt).innerHTML = newField;
						
						if (result[x].role == "M")
							{
							var checkbox = document.getElementById("checkBox"+cnt);
							checkbox.checked = true;
							therow = document.getElementById("row"+cnt);
							therow.bgColor="#AFDCEC";		// BDEDFF
							}
						if (result[x].role == "S")
							{
							var subcheckbox = document.getElementById("subcheckBox"+cnt);
							subcheckbox.checked = true;
							therow = document.getElementById("row"+cnt);
							therow.bgColor="#F3E5AB";		// BDEDFF
							}
						}
					else if (result[x].player_id==pid)
						{
						var checkbox = document.getElementById("checkBox"+cnt);
						checkbox.checked = true;
						var subcheckbox = document.getElementById("subcheckBox"+cnt);
						subcheckbox.checked = false;
						}
					}		

				var extraLines = 0;
				if (result.length > (table_len-5))
					{
					extraLines = ((result.length+5)-table_len)
					}
				
				// add blank lines 
				cnt = table_len+1;
				var tableSizeElement = document.getElementById("table_count");	
				for (var x = 0; x < extraLines; x++,cnt++ )
					{
					var insertRow = "<tr id='row"+cnt+"'bgcolor='#FFFFFF'><td style='text-align:center'><input type='checkbox' name='subcheckBox"+cnt+"' id='subcheckBox"+cnt+"'></td>";
						insertRow += "<td id='email"+cnt+"'><input type='text' name='name_email"+cnt+"' id='in_email"+cnt+"'></td>";
						insertRow += "<td id='find"+cnt+"'><input type='button' class='ind_button' id='find_button"+cnt+"' value='Find' onclick='find_email("+cnt+");'></td>";
						insertRow += "<td id='name_full"+cnt+"'><input type='text' name='name_full"+cnt+"' id='in_name"+cnt+"'></td>";
						insertRow += "<td style='text-align:center'><input type='checkbox' name='checkBox"+cnt+"' id='checkBox"+cnt+"' onclick='checkManager("+cnt+");')></td>";
						insertRow += "<td style='text-align:center'><input type='button' value='Cut' onclick='deleteRow("+cnt+")'></td></tr>";										

					var row = table.insertRow(table_len).outerHTML=insertRow;
					table_len = table_len+1;
					
					tableSizeElement.value = table_len;
					}
					
				delete XMLHttpRequestObject;
				XMLHttpRequestObject = null;					
				}
			}
			
		XMLHttpRequestObject.send();
		}					
	}

function deleteRow(theRow)
	{
	var XMLHttpRequestObject = false;
	XMLHttpRequestObject = new XMLHttpRequest();
	var usemail = document.getElementById("in_email"+theRow).value;
	var teamid = getQueryVariable("team");
	
	if (XMLHttpRequestObject) 
		{
		XMLHttpRequestObject.open("GET","http://" + WEBPATH + "/attend_members_db.php?del_mem=del&teamid=" + teamid + "&email=" + usemail);
	
		XMLHttpRequestObject.onreadystatechange=function()
			{
			if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
				var result = JSON.parse(XMLHttpRequestObject.responseText);
				
				var responses = result.split(":");
				
				if (responses[0]=="success")
					{
					var emailIn=document.getElementById("in_email"+theRow);
					var nameIn=document.getElementById("in_name"+theRow);
					var fullnameIn=document.getElementById("name_full"+theRow);
					var mgrIn=document.getElementById("checkBox"+theRow);
					var subIn=document.getElementById("subcheckBox"+theRow);

					emailIn.value="";
					if (nameIn==null)
						{
						fullnameIn.innerHTML="<input type='text' name='name_full"+theRow+"' id='in_name"+theRow+"'>";
						}
					else
						{
						nameIn.value="";
						}
					mgrIn.checked = false;
					subIn.checked = false;
					}
				else
					{
					alert(responses[1]);
					}
				}
			}
			
		XMLHttpRequestObject.send();
		}
	}
	
function checkManager(theRow)
	{
	var checkbox = document.getElementById("checkBox"+theRow);
	var subcheckbox = document.getElementById("subcheckBox"+theRow);
	var therow = document.getElementById("row"+theRow);
	
	subcheckbox.checked = false;
	
	if (checkbox.checked)
		{
		therow.bgColor="#AFDCEC";		// BDEDFF
		}
	else
		{
		therow.bgColor="#FFFFFF";
		}
		
	}
	
function checkSub(theRow)
	{
	var checkbox = document.getElementById("checkBox"+theRow);
	var subcheckbox = document.getElementById("subcheckBox"+theRow);
	var therow = document.getElementById("row"+theRow);
	
	checkbox.checked = false;
	
	if (subcheckbox.checked)
		{
		therow.bgColor="#F3E5AB";
		}
	else
		{
		therow.bgColor="#FFFFFF";
		}	
	}

window.addEventListener('load', loadTeamMembers);
