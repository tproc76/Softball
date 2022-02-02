function edit_row(id)
	{
	var email=document.getElementById("name_email"+id).innerHTML;
	var full=document.getElementById("name_full"+id).innerHTML;
	var phone=document.getElementById("phone"+id).innerHTML;

	document.getElementById("name_email"+id).innerHTML="<input type='text' id='ename_email"+id+"' value='"+email+"'>";
	document.getElementById("name_full"+id).innerHTML="<input type='text' id='ename_full"+id+"' value='"+full+"'>";
	document.getElementById("phone"+id).innerHTML="<input type='text' id='ename_phone"+id+"' value='"+phone+"'>";

	document.getElementById("edit_button"+id).style.display="none";
	document.getElementById("save_button"+id).style.display="block";
	}

function save_row(id)
	{
	var email=document.getElementById("ename_email"+id).value;
	var fname=document.getElementById("ename_full"+id).value;
	var phone=document.getElementById("ename_phone"+id).value;

	$.ajax
		({
		type:'post',
		url:'manage_attend_players_db.php',
		data:
			{
			edit_row:'edit_row',
			row_id:id,
			name_email:email,
			name_full:fname,
			name_phone:phone
			},
		success:function(response) 
			{
			if(response=="success")
				{
				document.getElementById("name_email"+id).innerHTML=email;
				document.getElementById("name_full"+id).innerHTML=fname;
				document.getElementById("phone"+id).innerHTML=phone;
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
		url:'manage_attend_players_db.php',
		data:
			{
			delete_row:'delete_row',
			row_id:id,
			},
		success:function(response) 
			{
			var parts = response.split(":");
			
			if(parts[0]=="success")
				{
				var row=document.getElementById("row"+id);
				row.parentNode.removeChild(row);
				}
			else if (parts[0]=="member")
				{
				alert('Player is still a member of a team - ' + parts[1]);
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
	var email=document.getElementById("new_email").value;
	var fname=document.getElementById("new_name").value;
	var phone=document.getElementById("new_phone").value;

	$.ajax
		({
		type:'post',
		url:'manage_attend_players_db.php',
		data:
			{
			insert_row:'insert_row',
			name_email:email,
			name_full:fname,
			name_phone:phone
			},
		success:function(response) 
			{
			if(response!="")
				{
				var id=response;
				var table=document.getElementById("playertable");
				var table_len=(table.rows.length)-1;
				
				var insertRow = "<tr id='row"+id+"'><td id='name_email"+id+"'>"+email+"</td><td id='name_full"+id+"'>"+fname+"</td><td id='phone"+id+"'>"+phone+"</td>";
					insertRow += "<td><input type='button' class='edit_button' id='edit_button"+id+"' value='edit' onclick='edit_row("+id+");'/>";
					insertRow += "<input type='button' class='save_button' id='save_button"+id+"' value='save' onclick='save_row("+id+");'  style='display: none;' />";
					insertRow += "<input type='button' class='delete_button' id='delete_button"+id+"' value='delete' onclick='delete_row("+id+");'/></td></tr>";
				var row = table.insertRow(table_len).outerHTML=insertRow;

				document.getElementById("new_email").value="";
				document.getElementById("new_name").value="";
				document.getElementById("new_phone").value="";
				}
			}
		});
	}
