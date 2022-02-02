function delete_row(id)
	{
	$.ajax
		({
		type:'post',
		url:'manage_attend_teams_db.php',
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
			else
				{
				alert('General Failure:'+response);
				}
			}
		});
	}
