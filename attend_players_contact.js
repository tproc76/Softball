function edit_phonerow(id)
	{
	var phone=document.getElementById("phone"+id).innerHTML;

	document.getElementById("phone"+id).innerHTML="<input type='text' id='ename_phone"+id+"' value='"+phone+"'>";

	document.getElementById("edit_button"+id).style.display="none";
	document.getElementById("save_button"+id).style.display="block";
	}

function save_phonerow(id)
	{
	var phone=document.getElementById("ename_phone"+id).value;

	$.ajax
		({
		type:'post',
		url:'attend_players_contact_db.php',
		data:
			{
			edit_row:'edit_row',
			row_id:id,
			name_phone:phone
			},
		success:function(response) 
			{
			if(response=="success")
				{
				document.getElementById("phone"+id).innerHTML=phone;
				document.getElementById("edit_button"+id).style.display="block";
				document.getElementById("save_button"+id).style.display="none";
				}
			}
		});
	}
