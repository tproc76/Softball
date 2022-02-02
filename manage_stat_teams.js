var editTeamLink;

function edit_row(id)
	{
	editTeamLink = document.getElementById("team_link"+id);
	var season=document.getElementById("team_season"+id).innerHTML;
	var teamname=document.getElementById("team_link"+id).innerHTML;
	var league=document.getElementById("team_league"+id).innerHTML;
	var group=document.getElementById("team_grouping"+id).innerHTML;
	var year=document.getElementById("team_year"+id).innerHTML;

	document.getElementById("team_season"+id).innerHTML="<input type='text' id='eteam_season"+id+"' value='"+season+"'>";
	document.getElementById("team_name"+id).innerHTML="<input type='text' id='eteam_name"+id+"' value='"+teamname+"'>";
	document.getElementById("team_league"+id).innerHTML="<input type='text' id='eteam_league"+id+"' value='"+league+"'>";
	document.getElementById("team_grouping"+id).innerHTML="<input type='text' id='eteam_grouping"+id+"' value='"+group+"'>";
	document.getElementById("team_year"+id).innerHTML="<input type='text' id='eteam_year"+id+"' value='"+year+"'>";

	document.getElementById("edit_button"+id).style.display="none";
	document.getElementById("save_button"+id).style.display="block";
	}

function save_row(id)
	{
	var season=document.getElementById("eteam_season"+id).value;
	var teamname=document.getElementById("eteam_name"+id).value;
	var league=document.getElementById("eteam_league"+id).value;
	var group=document.getElementById("eteam_grouping"+id).value;
	var year=document.getElementById("eteam_year"+id).value;

	$.ajax
		({
		type:'post',
		url:'manage_stat_teams_db.php',
		data:
			{
			edit_row:'edit_row',
			row_id:id,
			team_season:season,
			team_name:teamname,
			team_league:league,
			team_group:group,
			team_year:year
			},
		success:function(response) 
			{
			var parts = response.split(":");
			if(parts[0]=="success")
				{
				document.getElementById("team_season"+id).innerHTML=season;
				editTeamLink.innerHTML = teamname;
				document.getElementById("team_name"+id).innerHTML=editTeamLink.outerHTML;
				document.getElementById("team_league"+id).innerHTML=league;
				document.getElementById("team_grouping"+id).innerHTML=group;
				document.getElementById("team_year"+id).innerHTML=year;
				document.getElementById("team_update"+id).innerHTML=parts[1];
				
				document.getElementById("edit_button"+id).style.display="block";
				document.getElementById("save_button"+id).style.display="none";
				}
			else
				{
				alert('Failure:' + response);
				}
			}
		});
	}

function delete_row(id)
	{
	$.ajax
		({
		type:'post',
		url:'manage_stat_teams_db.php',
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
				alert('General Failure - ' + response);
				}
			}
		});
	}

function showGames(seasonId)
	{
	var team=document.getElementById("team_name"+seasonId);
	
	if (team.expanded == true)
		{
		team.innerHTML = team.saveteam;
		team.expanded = false;
		}
	else
		{
		team.expanded = true;
		team.saveteam = team.innerHTML;
	
		$.ajax
			({
			type:'post',
			url:'manage_stat_teams_db.php',
			data:
				{
				get_games:'get',
				row_id:seasonId
				},
			success:function(result) 
				{
				var gamename = team.innerHTML;
				gamename += "<br>";
				
				for (var x=0; x < result.length; x++)
					{
					gamename += "<p id='game" + result[x].game_id
					gamename += "'>"	
					gamename += "<input type='button' class='delete_button' id='delete_button" + result[x].game_id + "' value='delete' onclick='delete_game(";
					gamename += result[x].game_id + ");'>";
					gamename += result[x].game_datetime;
					gamename += "\\";
					gamename += result[x].opponent;
					gamename += "</p>";
					}
				team.innerHTML = gamename;
				}
			});
		}
	}
	
function delete_game(id)
	{
	$.ajax
		({
		type:'post',
		url:'manage_stat_teams_db.php',
		data:
			{
			delete_game:'delete_game',
			game_id:id
			},
		success:function(response) 
			{
			var parts = response.split(":");
			
			if(parts[0]=="success")
				{
				var gameentry=document.getElementById("game"+id);
				gameentry.innerHTML="";
				}
			else
				{
				alert('General Failure - ' + response);
				}
			},
		  error:function(jqXHR, textStatus, errorThrown) 
			{
			alert(errorThrown);
			updateActive = false;
			}
		});
	}
