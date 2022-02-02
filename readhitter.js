function readHitterSeasonStats()
	{
	//https://stackoverflow.com/questions/979975/how-to-get-the-value-from-the-get-parameters
	//window.location.search
	seasonnum = getQueryVariable("season");
	leaguename = getQueryVariable("league");
	playernum = getQueryVariable("player");
	getHitterData(seasonnum,playernum,leaguename);
	}


function getHitterData(seasonId,hitterId,league)
	{
    event.preventDefault();
    var XMLHttpRequestObject = false;
    XMLHttpRequestObject = new XMLHttpRequest();
    
    if (XMLHttpRequestObject) 
		{
		if (seasonId != null)
			XMLHttpRequestObject.open("GET","http://" + WEBPATH + "/gethitterseason.php?season=" + seasonId + "&player=" + hitterId);
		else
			XMLHttpRequestObject.open("GET","http://" + WEBPATH + "/gethittercareer.php?league=" + league + "&player=" + hitterId);
    
        XMLHttpRequestObject.onreadystatechange=function()
			{
            var firstHitLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='hitterlist'><thead>";			
			if (seasonId != null)
				{
                firstHitLine += "<tr><th style='text-align:center' onclick='sortTableString(\"hitterlist\",0)'>Date/Time</th><th style='text-align:center' onclick='sortTableString(\"hitterlist\",1)'>Opponent</th>";
				}
			else
				{
                firstHitLine += "<tr><th style='text-align:center' onclick='sortTableLinkSeason(\"hitterlist\",0)'>Season</th><th style='text-align:center' onclick='sortTableString(\"hitterlist\",1)'>Team Name</th>";
				}
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",2)'>G</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",3)'>AB</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",4)'>R</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",5)'>H</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",6)'>2B</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",6)'>3B</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",8)'>HR</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",9)'>RBI</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",10)'>BB</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",10)'>K</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",12)'>AVG</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",13)'>OBP</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",14)'>SLG</th></tr></thead><tbody>";
			var hittable = '';
            var lastline = '</table>';

			
            if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
                var result = JSON.parse(XMLHttpRequestObject.responseText);
				
				// *** HITTER SECTION ***
                if (result.hitters.length == 0) 
					{
					hittable += '<tr><td>No Date</td><td>None</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
					}
				else
					{
					var AtBats = 0;
					var Runs = 0;
					var Hits = 0;
									
					for (var x = 0; x < result.hitters.length; x++ )
						{
						var opponent = result.hitters[x].opponent;
						
						if (result.hitters[x].location == "H")
							{
							opponent = "Vs. " + result.hitters[x].opponent;
							}
						else if (result.hitters[x].location == "V")
							{
							opponent = "@ " + result.hitters[x].opponent;
							}
						
						AtBats = parseInt(AtBats) + parseInt(result.hitters[x].atbats);
						Hits = parseInt(Hits) + parseInt(result.hitters[x].hits);

						if (seasonId != null)
							{
							hittable += '<tr><td><a href="singlegame.php?game=' + result.hitters[x].game_id + '">' + convertDateToString(result.hitters[x].game_datetime) + '</a></td><td>' + opponent;
							}
						else
							{
							hittable += '<tr><td><a href="season.php?season=' + result.hitters[x].season_name + '">' + result.hitters[x].season_name + '</td><td>' + result.hitters[x].team_name;
							}
						hittable += '</td><td>' + result.hitters[x].game + '</td><td style="text-align:center">' + result.hitters[x].atbats + '</td><td style="text-align:center">' + result.hitters[x].runs + '</td><td style="text-align:center">' + result.hitters[x].hits  + '</td><td style="text-align:center">' + result.hitters[x].doubles;
						hittable += '</td><td style="text-align:center">' + result.hitters[x].triples + '</td><td style="text-align:center">' + result.hitters[x].homeruns + '</td><td style="text-align:center">' + result.hitters[x].rbi + '</td><td style="text-align:center">' + result.hitters[x].walks + '</td><td style="text-align:center">' + result.hitters[x].strikeout;
						var bavg = 0;
						if (parseInt(result.hitters[x].atbats) > 0 )
							bavg = parseInt(result.hitters[x].hits)/parseInt(result.hitters[x].atbats);
						hittable += '</td><td style="text-align:center">' + parseFloat(bavg).toFixed(3);
						var obp = 0;
						if ((parseInt(result.hitters[x].atbats)+parseInt(result.hitters[x].walks)+parseInt(result.hitters[x].sacrifice)) > 0)
							obp = (parseInt(result.hitters[x].hits)+parseInt(result.hitters[x].walks))/(parseInt(result.hitters[x].atbats)+parseInt(result.hitters[x].walks)+parseInt(result.hitters[x].sacrifice));
						hittable += '</td><td style="text-align:center">' + parseFloat(obp).toFixed(3);
						var slg = 0;
						if (parseInt(result.hitters[x].atbats) > 0)
							slg = (parseInt(result.hitters[x].hits)+parseInt(result.hitters[x].doubles)+parseInt(result.hitters[x].triples)*2+parseInt(result.hitters[x].homeruns)*3)/parseInt(result.hitters[x].atbats);
						hittable += '</td><td style="text-align:center">' + parseFloat(slg).toFixed(3);
						hittable += '</td></tr>';
						}
					document.getElementById('hitterlist').innerHTML = firstHitLine + hittable + lastline;
					}
                
				document.getElementById('playerheader').innerHTML = '<h2 id="pageheader">' + result.hitters[0].nickname + '</h2>';
								
				if (seasonId != null)
					{
					document.getElementById('playerheader').innerHTML = '<h2 id="pageheader">' + result.hitters[0].nickname + '<br>' + result.season.season_name + '<br>' + result.season.team_name + '</h2>';
					document.getElementById('updatetag').innerHTML = '<p class="text-right" id="updatetag">Last Updated: ' + result.season.updated + '</p>';
					}
				else
					{
					document.getElementById('playerheader').innerHTML = '<h2 id="pageheader">' + result.hitters[0].nickname + '</h2>';
					document.getElementById('updatetag').innerHTML = '<p class="text-right" id="updatetag"></p>';
					}
				delete XMLHttpRequestObject;
				XMLHttpRequestObject = null;
				
				if (seasonId != null)
					sortTableString("hitterlist",0,"asc");
				else
					sortTableLinkSeason("hitterlist",0,"asc");
				} 
			else
				{
                document.getElementById('hitterlist').innerHTML = firstHitLine + hittable + lastline;
				}
			}
        XMLHttpRequestObject.send();
		}
	}

window.addEventListener('load', readHitterSeasonStats);
