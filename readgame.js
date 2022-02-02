function readSeasonStats()
{
	var gamenum = getQueryVariable("game")
	getSeasonData(gamenum);
}

function checkInningScore(innScore)
{
	if (innScore == null)
		return " ";
	
	return innScore;
}

function getSeasonData(datasource)
	{
    event.preventDefault();
    var XMLHttpRequestObject = false;
    XMLHttpRequestObject = new XMLHttpRequest();
    
    if (XMLHttpRequestObject) 
		{
		XMLHttpRequestObject.open("GET","http://"+ WEBPATH +"/getgame.php?game=" + datasource);
    
        XMLHttpRequestObject.onreadystatechange=function()
			{
			var hitTableNm = "";
            var firstHitLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='hitterlist'><thead><tr><th style='text-align:center' onclick='sortTableString(\"hitterlist\",0)'>Order</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",1)'>Hitter</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",2)'>G</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",3)'>AB</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",4)'>R</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",5)'>H</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",6)'>2B</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",7)'>3B</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",8)'>HR</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",9)'>RBI</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",10)'>BB</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",11)'>K</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",12)'>AVG</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",13)'>OBP</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",14)'>SLG</th></tr></thead><tbody>";
			var hittable = '';
            var lastline = '</table>';

            var firstPitchLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='pitcherlist'><thead><tr><th style='text-align:center' onclick='sortTableString(\"pitcherlist\",0)'>Pitcher</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",1)'>G</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",2)'>W</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",3)'>L</th>";
				firstPitchLine += "<th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",4)'>PCT</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",5)'>INN</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",6)'>R</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",7)'>RA</th>";
				firstPitchLine += "<th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",8)'>K</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",9)'>BB</th></tr></thead><tbody>";
			var pitchtable = '';
			
			var scoreTableUs = '';
			var scoreTableThem = '';
			var summaryTableUs = '';
			var summaryTableThem = '';
			
            if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
                var result = JSON.parse(XMLHttpRequestObject.responseText);
				
				var firstScoreLine = "<table border='1' cellspacing='1' cellpadding='10' style='float: left' class='center' id='scores'><thead><tr><th>Teams</th>"
				if (result.season.ind_inning_score==true)
					{
					firstScoreLine += "<th style='text-align:center'>1</th><th style='text-align:center'>2</th><th style='text-align:center'>3</th><th style='text-align:center'>4";
					firstScoreLine += "</th><th style='text-align:center'>5</th><th style='text-align:center'>6</th><th style='text-align:center'>7</th><th style='text-align:center'>8</th><th style='text-align:center'>9</th><th style='text-align:center'>Ex</th>";
					}
					firstScoreLine += "</tr></thead><tbody>";
				var firstSummaryLine = "<table border='1' cellspacing='1' cellpadding='10' style='float: left' class='center' id='summary'><thead><tr><th style='text-align:center'>R</th><th style='text-align:center'>LOB</th></tr></thead><tbody>";
				
				// *** HITTER SECTION ***
                if (result.hitters.length == 0) 
					{
					hittable += '<tr><td>No Data</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
					}
				else
					{
					var AtBats = 0;
					var Runs = 0;
					var Hits = 0;
									
					for (var x = 0; x < result.hitters.length; x++ )
						{
						var hitter = result.hitters[x].nickname;

						AtBats = parseInt(AtBats) + parseInt(result.hitters[x].atbats);
						Runs = parseInt(Runs) + parseInt(result.hitters[x].runs);
						Hits = parseInt(Hits) + parseInt(result.hitters[x].hits);

						hittable += '<tr><td style="text-align:center">' + result.hitters[x].bat_order + '</td>';
						if (result.hitters[x].sub > 0)
							hittable += '<td><a href=\"hitterstats.php?player=' + result.hitters[x].player_id + '&season=' + result.season.season_id + '\">-->' + hitter + '</a></td><td>';
						else
							hittable += '<td><a href=\"hitterstats.php?player=' + result.hitters[x].player_id + '&season=' + result.season.season_id + '\">' + hitter + '</a></td><td>';
						hittable +=  result.hitters[x].game + '</td><td style="text-align:center">' + result.hitters[x].atbats + '</td><td style="text-align:center">' + result.hitters[x].runs + '</td><td style="text-align:center">' + result.hitters[x].hits  + '</td><td style="text-align:center">' + result.hitters[x].doubles;
						hittable += '</td><td style="text-align:center">' + result.hitters[x].triples + '</td><td style="text-align:center">' + result.hitters[x].homeruns + '</td><td style="text-align:center">' + result.hitters[x].rbi + '</td><td style="text-align:center">' + result.hitters[x].walks + '</td><td style="text-align:center">' + result.hitters[x].strikeout;
						var bavg = 0;
						if (parseInt(result.hitters[x].atbats) > 0)
							bavg = parseInt(result.hitters[x].hits)/parseInt(result.hitters[x].atbats);
						hittable += '</td><td style="text-align:center">' + parseFloat(bavg).toFixed(3);
						var obp = 0;
						if((parseInt(result.hitters[x].atbats)+parseInt(result.hitters[x].walks)+parseInt(result.hitters[x].sacrifice)) > 0)
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
                
				// *** PITCHER SECTION ***
                if (result.pitchers.length == 0) 
					{
					pitchtable += '<tr><td>No Data</td><td>0</td><td>0</td><td>0</td><td>0.000</td><td>0.0</td><td>0</td><td>0.00</td><td>0</td></tr>';
					}
				else
					{
					for (var x = 0; x < result.pitchers.length; x++ )
						{
						var pitchername = result.pitchers[x].nickname;
					
						pitchtable += '<tr><td>' + pitchername + '</td><td style="text-align:center">' + result.pitchers[x].game + '</td><td style="text-align:center">' + result.pitchers[x].wins + '</td><td style="text-align:center">' + result.pitchers[x].losses;
						var winPercent = parseInt(result.pitchers[x].wins)/(result.pitchers[x].wins+result.pitchers[x].losses);
						if ((result.pitchers[x].wins+result.pitchers[x].losses) == 0)
							winPercent = 0;
						pitchtable += '</td><td style="text-align:center">' + parseFloat(winPercent).toFixed(3);
						pitchtable += '</td><td style="text-align:center">' + parseFloat(result.pitchers[x].innings).toFixed(1);
						pitchtable += '</td><td style="text-align:center">' + result.pitchers[x].runsallowed;
						var runAvg = result.pitchers[x].runsallowed * 7 / result.pitchers[x].innings;
						pitchtable += '</td><td style="text-align:center">' + parseFloat(runAvg).toFixed(2);
						pitchtable += '</td><td style="text-align:center">' + result.pitchers[x].strikeout + '</td>';
						pitchtable += '</td><td style="text-align:center">' + result.pitchers[x].walks + '</td></tr>';
						}
					document.getElementById('pitcherlist').innerHTML = firstPitchLine + pitchtable + lastline;
					}
					
				// *** GAME SECTION ***
				if (result.games.length == 0) 
					{
					gametable += '<tr><td>No Data</td><td>No Data</td><td>No Data</td><td>No Data</td><td></td>';
					}
				else
					{
					scoreTableThem += '<tr><td>' + result.games[0].opponent + '</td>' 
					if (result.season.ind_inning_score==true)
						{
						scoreTableThem += '<td style="text-align:center">' + checkInningScore(result.games[0].ra1) + '</td><td style="text-align:center">' + checkInningScore(result.games[0].ra2);
						scoreTableThem += '</td><td style="text-align:center">' + checkInningScore(result.games[0].ra3) + '</td><td style="text-align:center">' + checkInningScore(result.games[0].ra4);
						scoreTableThem += '</td><td style="text-align:center">' + checkInningScore(result.games[0].ra5) + '</td><td style="text-align:center">' + checkInningScore(result.games[0].ra6);
						scoreTableThem += '</td><td style="text-align:center">' + checkInningScore(result.games[0].ra7) + '</td><td style="text-align:center">' + checkInningScore(result.games[0].ra8);
						scoreTableThem += '</td><td style="text-align:center">' + checkInningScore(result.games[0].ra9) + '</td><td style="text-align:center">' + checkInningScore(result.games[0].raex);
						}
					scoreTableThem += '</td></tr>';
					
					summaryTableThem = '<tr><td style="text-align:center">' + result.games[0].run_allowed +  '</td><td style="text-align:center">' + checkInningScore(result.games[0].opplob) + '</td></tr>';
					
					scoreTableUs += '<tr><td>' + result.season.team_name + '</td>';
					if (result.season.ind_inning_score==true)
						{
						scoreTableUs += '<td style="text-align:center">' + checkInningScore(result.games[0].rs1) + '</td><td style="text-align:center">' + checkInningScore(result.games[0].rs2)							
						scoreTableUs += '</td><td style="text-align:center">' + checkInningScore(result.games[0].rs3) + '</td><td style="text-align:center">' + checkInningScore(result.games[0].rs4);
						scoreTableUs += '</td><td style="text-align:center">' + checkInningScore(result.games[0].rs5) + '</td><td style="text-align:center">' + checkInningScore(result.games[0].rs6);
						scoreTableUs += '</td><td style="text-align:center">' + checkInningScore(result.games[0].rs7) + '</td><td style="text-align:center">' + checkInningScore(result.games[0].rs8);
						scoreTableUs += '</td><td style="text-align:center">' + checkInningScore(result.games[0].rs9) + '</td><td style="text-align:center">' + checkInningScore(result.games[0].rsex);
						}
					scoreTableUs += '</td></tr>';
					
					summaryTableUs = '<tr><td style="text-align:center">' + result.games[0].run_scored +  '</td><td style="text-align:center">' + checkInningScore(result.games[0].lob) + '</td></tr>';
					
					if (result.games.location == "V")
						{
						document.getElementById('scorestable').innerHTML = firstScoreLine + scoreTableUs + scoreTableThem + lastline;						
						document.getElementById('summary').innerHTML = firstSummaryLine + summaryTableUs + summaryTableThem + lastline;
						}
					else
						{
						document.getElementById('scorestable').innerHTML = firstScoreLine + scoreTableThem + scoreTableUs + lastline;						
						document.getElementById('summary').innerHTML = firstSummaryLine + summaryTableThem + summaryTableUs + lastline;
						}
					}
					
				var title = '<h2 id="gameheader">' +  result.season.team_name + '<br>' + result.season.season_name + '</h2><h4><br>' + convertDateToString(result.games[0].game_datetime) + '</h4>';
				document.getElementById('gameheader').innerHTML = title;
				
				document.getElementById('updatetag').innerHTML = '<p class="text-right" id="updatetag">Last Updated: ' + result.games[0].updated + '</p>';
				
				delete XMLHttpRequestObject;
				XMLHttpRequestObject = null;					
				} 
			}
        XMLHttpRequestObject.send();
		}
	}

window.addEventListener('load', readSeasonStats);
