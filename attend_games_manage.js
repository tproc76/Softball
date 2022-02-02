String.prototype.replaceAt=function(index, replacement) {
    return this.substr(0, index) + replacement+ this.substr(index + replacement.length);
}

function get2DigString(digit)
	{
	var retString = "";
	
	if (digit < 10)
		{
		retString += "0";
		}
		
	retString += digit;
	
	return retString;
	}

function convertDateString(dateobject)
	{
	var dateString = "";
	
	if (dateobject.getMonth()==0)
		{
		dateString = "Jan-"
		}
	else if (dateobject.getMonth()==1)
		{
		dateString = "Feb-"
		}
	else if (dateobject.getMonth()==2)
		{
		dateString = "Mar-"
		}
	else if (dateobject.getMonth()==3)
		{
		dateString = "Apr-"
		}
	else if (dateobject.getMonth()==4)
		{
		dateString = "May-"
		}
	else if (dateobject.getMonth()==5)
		{
		dateString = "Jun-"
		}
	else if (dateobject.getMonth()==6)
		{
		dateString = "July-"
		}
	else if (dateobject.getMonth()==7)
		{
		dateString = "Aug-"
		}
	else if (dateobject.getMonth()==8)
		{
		dateString = "Sept-"
		}
	else if (dateobject.getMonth()==9)
		{
		dateString = "Oct-"
		}
	else if (dateobject.getMonth()==10)
		{
		dateString = "Nov-"
		}
	else if (dateobject.getMonth()==11)
		{
		dateString = "Dec-"
		}
		
	dateString += get2DigString(dateobject.getDate());
	dateString += "-";
	dateString += get2DigString(dateobject.getFullYear());
	dateString += " ";
	dateString += get2DigString(dateobject.getHours());
	dateString += ":";
	dateString += get2DigString(dateobject.getMinutes());
	
	return dateString;
	}
	
function edit_row(id)
	{
	var date=document.getElementById("date"+id).innerHTML;
	var loc=document.getElementById("location"+id).innerHTML;
	var opp=document.getElementById("opponent"+id).innerHTML;
	
	var theDateObj = new Date(date);
	var dateString = theDateObj.getFullYear();
	dateString += "-";
	dateString += get2DigString(theDateObj.getMonth()+1);
	dateString += "-";
	dateString += get2DigString(theDateObj.getDate());
	dateString += "T";
	dateString += get2DigString(theDateObj.getHours());
	dateString += ":";
	dateString += get2DigString(theDateObj.getMinutes());

	document.getElementById("date"+id).innerHTML="<input type='datetime-local' id='in_date"+id+"' value='"+dateString+"'>";
	document.getElementById("location"+id).innerHTML="<input type='text' id='in_location"+id+"' value='"+loc+"'>";
	document.getElementById("opponent"+id).innerHTML="<input type='text' id='in_opponent"+id+"' value='"+opp+"'>";

	document.getElementById("edit_button"+id).style.display="none";
	document.getElementById("save_button"+id).style.display="block";
	}

function save_row(id)
	{
	var date=document.getElementById("in_date"+id).value;
	var loc=document.getElementById("in_location"+id).value;
	var opp=document.getElementById("in_opponent"+id).value;
	var team = getQueryVariable("team");
	
	$.ajax
		({
		type:'post',
		url:'attend_games_db.php',
		data:
			{
			edit_row:'edit_row',
			att_team:team,
			row_id:id,
			att_date:date,
			att_loc:loc,
			att_opp:opp
			},
		success:function(response) 
			{
			if(response=="success")
				{
				var theDateObj = new Date(date);
				document.getElementById("date"+id).innerHTML=convertDateString(theDateObj);
				document.getElementById("location"+id).innerHTML=loc;
				document.getElementById("opponent"+id).innerHTML=opp;
				document.getElementById("edit_button"+id).style.display="block";
				document.getElementById("save_button"+id).style.display="none";
				}
			}
		});
	}

function delete_row(id)
	{
	$.ajax
		({
		type:'post',
		url:'attend_games_db.php',
		data:
			{
			delete_row:'delete_row',
			row_id:id,
			},
		success:function(response) 
			{
			if(response=="success")
				{
				var row=document.getElementById("row"+id);
				row.parentNode.removeChild(row);
				}
			else
				{
				alert('General Failure');
				}
			}
		});
	}

function insert_row()
	{
	var date=document.getElementById("new_date").value;
	var loc=document.getElementById("new_location").value;
	var opp=document.getElementById("new_opponent").value;
	var team = getQueryVariable("team");

	$.ajax
		({
		type:'post',
		url:'attend_games_db.php',
		data:
			{
			insert_row:'insert_row',
			att_date:date,
			att_loc:loc,
			att_opp:opp,
			att_team:team
			},
		success:function(response) 
			{
			if(response=="date")
				{
				alert("Incomplete Date");
				}
			else if(response=="loc")
				{
				alert("Missing Location");
				}
			else if(response=="opp")
				{
				alert("Missing Opponent");
				}
			else if(response!="")
				{
				var id=response;
				var table=document.getElementById("gametable");
				var table_len=(table.rows.length)-1;
				var dateobject = new Date(date);
				var formatdate = convertDateString(dateobject);
				
				var insertRow = "<tr id='row"+id+"'><td id='date"+id+"'>"+ formatdate +"</td><td id='location"+id+"'>"+loc+"</td><td id='opponent"+id+"'>"+opp+"</td>";
					insertRow += "<td><input type='button' class='edit_button' id='edit_button"+id+"' value='edit' onclick='edit_row("+id+");'/>";
					insertRow += "<input type='button' class='save_button' id='save_button"+id+"' value='save' onclick='save_row("+id+");'  style='display: none;' />";
					insertRow += "<input type='button' class='delete_button' id='delete_button"+id+"' value='delete' onclick='delete_row("+id+");'/></td></tr>";
				var row = table.insertRow(table_len).outerHTML=insertRow;

				document.getElementById("new_date").value="";
				document.getElementById("new_location").value="";
				document.getElementById("new_opponent").value="";
				}
			}
		});
	}
