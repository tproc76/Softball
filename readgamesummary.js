function readSeasonStats()
	{
	var seasonname = getQueryVariable("season");
	
	if (seasonname == null)
		seasonname = document.getElementById("seasonname").getAttribute("dataname");

	getSeasonData(seasonname);
	}

function getSeasonData(datasource)
	{
    event.preventDefault();
    var XMLHttpRequestObject = false;
    XMLHttpRequestObject = new XMLHttpRequest();
    
    if (XMLHttpRequestObject) 
		{
		XMLHttpRequestObject.open("GET","http://"+ WEBPATH + "/getseasongamesum.php?season=" + datasource);
    
        XMLHttpRequestObject.onreadystatechange=function()
			{
            var lastline = "</table>";

            var firstlinegame = "<table border='1' cellspacing='0' cellpadding='8' class='center' id='gamelist'><thead><tr><th onclick='sortTableDate(\"gamelist\",0)'>Date</th><th onclick='sortTableString(\"gamelist\",1)'>Opponent</th>";
			    firstlinegame += "<th onclick='sortTableNumber(\"gamelist\",2)'>RS</th><th onclick='sortTableNumber(\"gamelist\",3)'>RA</th><th onclick='sortTableString(\"gamelist\",4)'>Result</th>";
			    firstlinegame += "<th onclick='sortTableNumber(\"gamelist\",5)'>AVG</th><th onclick='sortTableNumber(\"gamelist\",6)'>OBP</th><th onclick='sortTableNumber(\"gamelist\",7)'>SLG</th></tr></thead><tbody>";
			var gametable = "";
			
            if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
                var result = JSON.parse(XMLHttpRequestObject.responseText);
				var gamesPlayed = result.games.length;
									
				// *** GAME SECTION ***
				if (result.games.length == 0) 
					{
					gametable += '<tr><td>No Data</td><td>No Data</td><td>No Data</td><td>No Data</td><td></td>';
					}
				else
					{
					for (var x = 0; x < result.games.length; x++ )
						{
						// The result of N is for the placeholder for seasons without game details
						if (result.games[x].result != 'N')
							{
							var datetime = convertDateToString(result.games[x].game_datetime);
							if (result.games[x].location == 'H')
								opponent = 'vs. ' + result.games[x].opponent;
							else if (result.games[x].location == 'V')
								opponent = '@ ' + result.games[x].opponent;
							else
								opponent = result.games[x].opponent;
							
							if (result.season.ind_game_stat==true)
								{
								gametable += '<tr><td><a href=\"singlegame.php?game=' + result.games[x].game_id + '\">' + datetime + '</a></td><td>';
								}
							else
								{
								gametable += '<tr><td>' + datetime + '</td><td>';
								}
								
							var bavg = 0;
							if (parseInt(result.games[x].atbats) > 0)
								bavg = parseInt(result.games[x].hits)/parseInt(result.games[x].atbats);
							var obp = 0;
							if ((parseInt(result.games[x].atbats)+parseInt(result.games[x].walks)+parseInt(result.games[x].sacrifice)) > 0)
								obp = (parseInt(result.games[x].hits)+parseInt(result.games[x].walks))/(parseInt(result.games[x].atbats)+parseInt(result.games[x].walks)+parseInt(result.games[x].sacrifice));
							var slg = 0;
							if (parseInt(result.games[x].atbats) > 0)
								slg = (parseInt(result.games[x].hits)+parseInt(result.games[x].doubles)+parseInt(result.games[x].triples)*2+parseInt(result.games[x].homeruns)*3)/parseInt(result.games[x].atbats);
								
							gametable += opponent + '</td><td style="text-align:center">' + result.games[x].run_scored + '</td><td style="text-align:center">' + result.games[x].run_allowed + '</td><td style="text-align:center">' + result.games[x].result + '</td><td>' 
							gametable += parseFloat(bavg).toFixed(3)  + '</td><td style="text-align:center">' + parseFloat(obp).toFixed(3) + '</td><td style="text-align:center">' + parseFloat(slg).toFixed(3) + '</td></tr>';
							}
						}
					document.getElementById('gamelist').innerHTML = firstlinegame + gametable + lastline;					
					}
				//datasource = season name
				document.getElementById('pageheader').innerHTML = '<h2 id="pageheader">' + result.season.team_name + '<br>' + result.season.season_name + '</h2>';
				
				document.getElementById('updatetag').innerHTML = '<p class="text-right" id="updatetag">Last Updated: ' + result.season.updated + '</p>';
				
				delete XMLHttpRequestObject;
				XMLHttpRequestObject = null;					
				} 
			else
				{
                document.getElementById('gamelist').innerHTML = firstlinegame + gametable + lastline;
				}
			}
        XMLHttpRequestObject.send();
		}
	}

window.addEventListener('load', readSeasonStats);
