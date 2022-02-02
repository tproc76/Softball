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
		XMLHttpRequestObject.open("GET","http://"+ WEBPATH + "/getseason.php?season=" + datasource);
    
        XMLHttpRequestObject.onreadystatechange=function()
			{
			var hitTableNm = "";
            var firstHitLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='hitterlist' data-totals='true'><thead><tr><th style='text-align:center' onclick='sortTableLinkString(\"hitterlist\",0)'>Hitter</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",1)'>G</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",2)'>AB</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",3)'>R</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",4)'>H</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",5)'>2B</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",6)'>3B</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",7)'>HR</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",8)'>RBI</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",9)'>BB</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",10)'>K</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",11)'>AVG</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",12)'>OBP</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",13)'>SLG</th></tr></thead><tbody>";
			BattingAverageColumn = 11;
			var hittable = '';
            var lastline = '</table>';

            var firstPitchLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='pitcherlist' data-totals='true'><thead><tr><th style='text-align:center' onclick='sortTableLinkString(\"pitcherlist\",0)'>Pitcher</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",1)'>G</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",2)'>W</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",3)'>L</th>";
				firstPitchLine += "<th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",4)'>PCT</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",5)'>INN</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",6)'>R</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",7)'>RA</th>";
				firstPitchLine += "<th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",8)'>K</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",9)'>BB</th></tr></thead><tbody>";
			var pitchtable = '';
			
            var firstlinestand = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='standings'><thead><tr><th style='text-align:center'>W</th><th style='text-align:center'>L</th><th style='text-align:center'>T</th><th style='text-align:center'>PCT</th><th style='text-align:center'>Scored</th><th style='text-align:center'>Allowed</th><th>Pyth</th></tr></thead><tbody>";
            var firstlinegame = "<table border='1' cellspacing='0' cellpadding='8' class='center' id='gamelist'><thead><tr><th>Date</th><th>Opponent</th><th>RS</th><th>RA</th><th>Result</th><th>Details</th></tr></thead><tbody>";
			var gametable = '';
			var standingtable = '';
			var winPct = '0.999';
			var Pyth = '0.998';
			var RunScored = 0;
			var RunAllowed = 0;
			
            if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
                var result = JSON.parse(XMLHttpRequestObject.responseText);
				var gamesPlayed = result.games.length;
				
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
					
					if (result.season.ind_game_stat==false)
						{
						// Extra place holder game exists in the length above.
						gamesPlayed = gamesPlayed-1;
						}
														
					for (var x = 0; x < result.hitters.length; x++ )
						{
						var hitter = result.hitters[x].nickname;

						AtBats = parseInt(AtBats) + parseInt(result.hitters[x].atbats);
						Runs = parseInt(Runs) + parseInt(result.hitters[x].runs);
						Hits = parseInt(Hits) + parseInt(result.hitters[x].hits);
						
						if (result.season.ind_game_stat==true)
							{
							hittable += '<tr><td><a href=\"hitterstats.php?player=' + result.hitters[x].player_id + '&season=' + result.season.season_id + '\">' + hitter + '</a></td><td>';
							}
						else
							{
							hittable += '<tr><td>' + hitter + '</td><td>';
							}
						hittable +=  result.hitters[x].game + '</td><td style="text-align:center">' + result.hitters[x].atbats + '</td><td style="text-align:center">' + result.hitters[x].runs + '</td><td style="text-align:center">' + result.hitters[x].hits  + '</td><td style="text-align:center">' + result.hitters[x].doubles;
						hittable += '</td><td style="text-align:center">' + result.hitters[x].triples + '</td><td style="text-align:center">' + result.hitters[x].homeruns + '</td><td style="text-align:center">' + result.hitters[x].rbi + '</td><td style="text-align:center">' + result.hitters[x].walks + '</td><td style="text-align:center">' + result.hitters[x].strikeout;
						var bavg = 0;
						if (parseInt(result.hitters[x].atbats) > 0)
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
						
					hittable +=	'<tfoot><tr><td>Totals</td><td>';

					hittable +=  gamesPlayed + '</td><td style="text-align:center">' + result.htotals.atbats + '</td><td style="text-align:center">' + result.htotals.runs + '</td><td style="text-align:center">' + result.htotals.hits  + '</td><td style="text-align:center">' + result.htotals.doubles;
					hittable += '</td><td style="text-align:center">' + result.htotals.triples + '</td><td style="text-align:center">' + result.htotals.homeruns + '</td><td style="text-align:center">' + result.htotals.rbi + '</td><td style="text-align:center">' + result.htotals.walks + '</td><td style="text-align:center">' + result.htotals.strikeout;
					var bavg = 0;
					if (parseInt(result.htotals.atbats) > 0)
						bavg = parseInt(result.htotals.hits)/parseInt(result.htotals.atbats);
					hittable += '</td><td style="text-align:center">' + parseFloat(bavg).toFixed(3);
					var obp = 0;
					if ((parseInt(result.htotals.atbats)+parseInt(result.htotals.walks)+parseInt(result.htotals.sacrifice)) > 0)
						obp = (parseInt(result.htotals.hits)+parseInt(result.htotals.walks))/(parseInt(result.htotals.atbats)+parseInt(result.htotals.walks)+parseInt(result.htotals.sacrifice));
					hittable += '</td><td style="text-align:center">' + parseFloat(obp).toFixed(3);
					var slg = 0;
					if (parseInt(result.htotals.atbats) > 0)
						slg = (parseInt(result.htotals.hits)+parseInt(result.htotals.doubles)+parseInt(result.htotals.triples)*2+parseInt(result.htotals.homeruns)*3)/parseInt(result.htotals.atbats);
					hittable += '</td><td style="text-align:center">' + parseFloat(slg).toFixed(3);
					hittable += '</td></tr></tfoot>';
					
					document.getElementById('hitterlist').innerHTML = firstHitLine + hittable + lastline;
					sortTableNumber("hitterlist",11,"desc");
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
					
						if (result.season.ind_game_stat==true)
							{
							pitchtable += '<tr><td><a href=\"pitcherstats.php?player=' + result.pitchers[x].player_id + '&season=' + result.season.season_id + '\">' + pitchername + '</a></td><td style="text-align:center">';
							}
						else
							{
							pitchtable += '<tr><td>' + pitchername + '</td><td style="text-align:center">';
							}
						pitchtable += result.pitchers[x].games + '</td><td style="text-align:center">' + result.pitchers[x].wins + '</td><td style="text-align:center">' + result.pitchers[x].losses;
						var winPercent = parseInt(result.pitchers[x].wins)/(parseInt(result.pitchers[x].wins)+parseInt(result.pitchers[x].losses));
						if ((parseInt(result.pitchers[x].wins)+parseInt(result.pitchers[x].losses)) == 0)
							winPercent = 0;
						pitchtable += '</td><td style="text-align:center">' + parseFloat(winPercent).toFixed(3);
						pitchtable += '</td><td style="text-align:center">' + parseFloat(result.pitchers[x].innings).toFixed(1);
						pitchtable += '</td><td style="text-align:center">' + result.pitchers[x].runsallowed;
						var runAvg = result.pitchers[x].runsallowed * 7 / result.pitchers[x].innings;
						pitchtable += '</td><td style="text-align:center">' + parseFloat(runAvg).toFixed(2);
						pitchtable += '</td><td style="text-align:center">' + result.pitchers[x].strikeout + '</td>';
						pitchtable += '</td><td style="text-align:center">' + result.pitchers[x].walks + '</td></tr>';
						}
						
					pitchtable += '<tfoot><tr><td>Totals</td><td>';
					pitchtable += gamesPlayed + '</td><td style="text-align:center">' + result.ptotals.wins + '</td><td style="text-align:center">' + result.ptotals.losses;
					var winPercent = parseInt(result.ptotals.wins)/(parseInt(result.ptotals.wins)+parseInt(result.ptotals.losses));
					if ((result.ptotals.wins+result.ptotals.losses) == 0)
						winPercent = 0;
					pitchtable += '</td><td style="text-align:center">' + parseFloat(winPercent).toFixed(3);
					pitchtable += '</td><td style="text-align:center">' + parseFloat(result.ptotals.innings).toFixed(1);
					pitchtable += '</td><td style="text-align:center">' + result.ptotals.runsallowed;
					var runAvg = result.ptotals.runsallowed * 7 / result.ptotals.innings;
					pitchtable += '</td><td style="text-align:center">' + parseFloat(runAvg).toFixed(2);
					pitchtable += '</td><td style="text-align:center">' + result.ptotals.strikeout + '</td>';
					pitchtable += '</td><td style="text-align:center">' + result.ptotals.walks + '</td></tr></tfoot>';
						
					document.getElementById('pitcherlist').innerHTML = firstPitchLine + pitchtable + lastline;
					sortTableNumber("pitcherlist",7,"asc");
					}
					
				// *** GAME SECTION ***
				if (result.games.length == 0) 
					{
					gametable += '<tr><td>No Data</td><td>No Data</td><td>No Data</td><td>No Data</td><td></td>';
					}
				else
					{
					var wins = 0;
					var losses = 0;
					var ties = 0;
					
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
							
							var winloss = result.games[x].result;
							if (winloss == 'W')
								wins = wins + 1;
							else if (winloss == 'L')
								losses = losses + 1;
							else 
								ties = ties + 1;
								
							var details = result.games[x].details;
							if (details == null)
								details = "";
							RunScored = parseInt(RunScored) + parseInt(result.games[x].run_scored);
							RunAllowed = parseInt(RunAllowed) + parseInt(result.games[x].run_allowed);
							//
							if (result.season.ind_game_stat==true)
								{
								gametable += '<tr><td><a href=\"singlegame.php?game=' + result.games[x].game_id + '\">' + datetime + '</a></td><td>';
								}
							else
								{
								gametable += '<tr><td>' + datetime + '</td><td>';
								}
							gametable += opponent + '</td><td style="text-align:center">' + result.games[x].run_scored + '</td><td style="text-align:center">' + result.games[x].run_allowed + '</td><td style="text-align:center">' + winloss + '</td><td>' + details + '</td></tr>';
							}
						}
					document.getElementById('gamelist').innerHTML = firstlinegame + gametable + lastline;
					
					winPct = ((wins)/(wins+losses+ties));
					winPct = parseFloat(winPct).toFixed(3);

					Pyth = ((RunScored*RunScored)/((RunScored*RunScored)+(RunAllowed*RunAllowed)));
					Pyth = parseFloat(Pyth).toFixed(3);
					standingtable = '<tr><td>' + wins + '</td><td>' + losses + '</td><td>' + ties + '</td><td>' + winPct + '</td><td style="text-align:center">' + RunScored; 
					standingtable += '</td><td style="text-align:center">' + RunAllowed + '</td><td>' + Pyth +'</td><tr>';
					document.getElementById('standings').innerHTML = firstlinestand + standingtable + lastline;
					}
					
				document.getElementById('pageheader').innerHTML = '<h2 id="pageheader">' + result.team + '<br>' + result.season.season_name + '</h2>';
				
				document.getElementById('updatetag').innerHTML = '<p class="text-right" id="updatetag">Last Updated: ' + result.updated + '</p>';
				
				delete XMLHttpRequestObject;
				XMLHttpRequestObject = null;					
				} 
			else
				{
                document.getElementById('hitterlist').innerHTML = firstHitLine + hittable + lastline;
                document.getElementById('gamelist').innerHTML = firstlinegame + gametable + lastline;
				}
			}
        XMLHttpRequestObject.send();
		}
	}

window.addEventListener('load', readSeasonStats);
