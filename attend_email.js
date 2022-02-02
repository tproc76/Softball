function find_email(id)
	{
	var emailelement=document.getElementById("in_email"+id);
	var emailaddress=emailelement.value;

	$.ajax
		({
		type:'post',
		url:'attend_email_lookup.php',
		data:
			{
			find_email:'find_email',
			find:emailaddress,
			row_id:id
			},
		success:function(response) 
			{
			if(response=="multiple")
				{
				var nameField = document.getElementById("name"+id);
				
				if (nameField != null)
					nameField.value = "Multiple";
				}
			else if(response=="none")
				{
				var nameField = document.getElementById("name"+id);
				
				if (nameField != null)
					nameField.value = "None";
				}
			else
				{
				var thename = response.split(":");
				var newField = thename[1] + "<input type='hidden' id='pid" + id + "' value='" + thename[2] + "'>"
				document.getElementById("name_full"+id).innerHTML = newField;
				}				
			}
		});
	}
