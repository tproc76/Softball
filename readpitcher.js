function readPitcherSeasonStats()
{
	//https://stackoverflow.com/questions/979975/how-to-get-the-value-from-the-get-parameters
	//window.location.search
	seasonnum = getQueryVariable("season");
	leaguename = getQueryVariable("league");
	playernum = getQueryVariable("player");
	getPitcherData(seasonnum,playernum,leaguename);
}

function getPitcherData(seasonId,pitcherId,league)
	{
    event.preventDefault();
    var XMLHttpRequestObject = false;
    XMLHttpRequestObject = new XMLHttpRequest();
    
    if (XMLHttpRequestObject) 
		{
		if (seasonId != null)
			XMLHttpRequestObject.open("GET","http://" + WEBPATH + "/getpitcherseason.php?season=" + seasonId + "&player=" + pitcherId);
		else
			XMLHttpRequestObject.open("GET","http://" + WEBPATH + "/getpitchercareer.php?league=" + league + "&player=" + pitcherId);
    
        XMLHttpRequestObject.onreadystatechange=function()
			{
            var firstPitchLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='pitcherlist'><thead>";
			if (seasonId != null)
				{
			    firstPitchLine += "<tr><th style='text-align:center' onclick='sortTableString(\"pitcherlist\",0)'>Date/Time</th><th style='text-align:center' onclick='sortTableString(\"pitcherlist\",1)'>Opponent</th>";
				}
			else
				{
			    firstPitchLine += "<tr><th style='text-align:center' onclick='sortTableLinkSeason(\"pitcherlist\",0)'>Season</th><th style='text-align:center' onclick='sortTableString(\"pitcherlist\",1)'>Team Name</th>";
				}
				firstPitchLine += "<th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",2)'>G</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",3)'>W</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",4)'>L</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",5)'>PCT</th>";
				firstPitchLine += "<th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",6)'>INN</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",7)'>R</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",8)'>RA</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",9)'>K</th></thead><tbody>";
			var pitchtable = '';
            var lastline = '</table>';

			
            if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
                var result = JSON.parse(XMLHttpRequestObject.responseText);
				
				// *** PITCHER SECTION ***
                if (result.pitchers.length == 0) 
					{
					pitchtable += '<tr><td>No Date</td><td>None</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
					}
				else
					{
					var AtBats = 0;
					var Runs = 0;
					var Hits = 0;
									
					for (var x = 0; x < result.pitchers.length; x++ )
						{
						var opponent = result.pitchers[x].opponent;
						
						if (result.pitchers[x].location == "H")
						{
							opponent = "Vs. " + result.pitchers[x].opponent;
						}
						else if (result.pitchers[x].location == "V")
						{
							opponent = "@ " + result.pitchers[x].opponent;
						}
					
						if (seasonId != null)
							pitchtable += '<tr><td><a href="singlegame.php?game=' + result.pitchers[x].game_id + '">' + convertDateToString(result.pitchers[x].game_datetime) + '</td><td>' + opponent;
						else
							pitchtable += '<tr><td><a href="season.php?season=' + result.pitchers[x].season_name + '">' + result.pitchers[x].season_name + '</td><td>' + result.pitchers[x].team_name;

						pitchtable += '</td><td>' + result.pitchers[x].game + '</td><td style="text-align:center">' + result.pitchers[x].wins + '</td><td style="text-align:center">' + result.pitchers[x].losses;
						var winPercent = parseInt(result.pitchers[x].wins)/(parseInt(result.pitchers[x].wins)+parseInt(result.pitchers[x].losses));
						if ((result.pitchers[x].wins+result.pitchers[x].losses) == 0)
							winPercent = 0;
						pitchtable += '</td><td style="text-align:center">' + parseFloat(winPercent).toFixed(3) + '</td><td style="text-align:center">' + parseFloat(result.pitchers[x].innings).toFixed(1) + '</td><td style="text-align:center">' + result.pitchers[x].runsallowed;
						var runAvg = result.pitchers[x].runsallowed * 7 / result.pitchers[x].innings;
						if ((result.pitchers[x].innings) == 0)
							runAvg = 99.99;
						pitchtable +=  '</td><td style="text-align:center">' + parseFloat(runAvg).toFixed(2) + '</td><td style="text-align:center">' + result.pitchers[x].strikeout + '</td></tr>';
						}
					document.getElementById('pitcherlist').innerHTML = firstPitchLine + pitchtable + lastline;
					}
                
				if (seasonId != null)
					{
					document.getElementById('playerheader').innerHTML = '<h2 id="pageheader">' + result.pitchers[0].nickname + '<br>' + result.season.season_name + '<br>' + result.season.team_name + '</h2>';
					document.getElementById('updatetag').innerHTML = '<p class="text-right" id="updatetag">Last Updated: ' + result.season.updated + '</p>';
					}
				else
					{
					document.getElementById('playerheader').innerHTML = '<h2 id="pageheader">' + result.pitchers[0].nickname + '</h2>';
					document.getElementById('updatetag').innerHTML = '<p class="text-right" id="updatetag"></p>';
					}
				delete XMLHttpRequestObject;
				XMLHttpRequestObject = null;
				
				if (seasonId != null)
					sortTableString("pitcherlist",0,"asc");
				else
					sortTableLinkSeason("pitcherlist",0,"asc");
				} 
			else
				{
                document.getElementById('pitcherlist').innerHTML = firstPitchLine + pitchtable + lastline;
				}
			}
        XMLHttpRequestObject.send();
		}
	}

window.addEventListener('load', readPitcherSeasonStats);
