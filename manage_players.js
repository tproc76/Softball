function edit_row(id)
	{
	var name=document.getElementById("name_nick"+id).innerHTML;
	var full=document.getElementById("name_full"+id).innerHTML;
	var note=document.getElementById("notes"+id).innerHTML;

	document.getElementById("name_nick"+id).innerHTML="<input type='text' id='ename_nick"+id+"' value='"+name+"'>";
	document.getElementById("name_full"+id).innerHTML="<input type='text' id='ename_full"+id+"' value='"+full+"'>";
	document.getElementById("notes"+id).innerHTML="<input type='text' id='ename_note"+id+"' value='"+note+"'>";

	document.getElementById("edit_button"+id).style.display="none";
	document.getElementById("save_button"+id).style.display="block";
	}

function save_row(id)
	{
	var nname=document.getElementById("ename_nick"+id).value;
	var fname=document.getElementById("ename_full"+id).value;
	var note=document.getElementById("ename_note"+id).value;

	$.ajax
		({
		type:'post',
		url:'manage_players_db.php',
		data:
			{
			edit_row:'edit_row',
			row_id:id,
			name_nick:nname,
			name_full:fname,
			name_note:note
			},
		success:function(response) 
			{
			if(response=="success")
				{
				document.getElementById("name_nick"+id).innerHTML=nname;
				document.getElementById("name_full"+id).innerHTML=fname;
				document.getElementById("notes"+id).innerHTML=note;
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
		url:'manage_players_db.php',
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
			else if (response=="hitter")
				{
				alert('Player still used in Hitter Table');
				}
			else if (response=="pitcher")
				{
				alert('Player still used in Pitcher Table');
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
	var nname=document.getElementById("new_nickname").value;
	var fname=document.getElementById("new_fullname").value;
	var note=document.getElementById("new_notes").value;

	$.ajax
		({
		type:'post',
		url:'manage_players_db.php',
		data:
			{
			insert_row:'insert_row',
			name_nick:nname,
			name_full:fname,
			name_note:note
			},
		success:function(response) 
			{
			if(response!="")
				{
				var id=response;
				var table=document.getElementById("playertable");
				var table_len=(table.rows.length)-1;
				
				var insertRow = "<tr id='row"+id+"'><td id='name_nick"+id+"'>"+nname+"</td><td id='name_full"+id+"'>"+fname+"</td><td id='notes"+id+"'>"+note+"</td>";
					insertRow += "<td><input type='button' class='edit_button' id='edit_button"+id+"' value='edit' onclick='edit_row("+id+");'/>";
					insertRow += "<input type='button' class='save_button' id='save_button"+id+"' value='save' onclick='save_row("+id+");'  style='display: none;' />";
					insertRow += "<input type='button' class='delete_button' id='delete_button"+id+"' value='delete' onclick='delete_row("+id+");'/></td></tr>";
				var row = table.insertRow(table_len).outerHTML=insertRow;

				document.getElementById("new_nickname").value="";
				document.getElementById("new_fullname").value="";
				document.getElementById("new_notes").value="";
				}
			}
		});
	}
