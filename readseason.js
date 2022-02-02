function readSeasonStats()
	{
	var seasonname = getQueryVariable("season");
	var datatype = getQueryVariable("stats");
	
	if (seasonname == null)
		seasonname = document.getElementById("seasonname").getAttribute("dataname");

	if (datatype=="trendall")
		{
		getSeasonTrend(seasonname,"all");
		}
	else if (datatype=="trendavg")
		{
		getSeasonTrend(seasonname,"avg");
		}
	else if (datatype=="trendslg")
		{
		getSeasonTrend(seasonname,"slg");
		}
	else if (datatype=="trendops")
		{
		getSeasonTrend(seasonname,"ops");
		}
	else if (datatype=="graphavg")
		{
		getSeasonTrendChart(seasonname,"avg");
		}
	else if (datatype=="graphops")
		{
		getSeasonTrendChart(seasonname,"ops");
		}
	else if (datatype=="time")
		{
		getSeasonSplits(seasonname,"time");
		}
	else if (datatype=="opp")
		{
		getSeasonSplits(seasonname,"opp");
		}
	else
		{
		getSeasonData(seasonname);
		}
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
			var BattingAverageColumn = 11;
			var OnbasePercentColumn = 12;
			var SluggingPercentColumn = 13;
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

				// Need to wait for response to set teh SAC column (or not)
				var firstHitLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='hitterlist' data-totals='true'><thead><tr><th style='text-align:center' onclick='sortTableLinkString(\"hitterlist\",0)'>Hitter</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",1)'>G</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",2)'>AB</th>";
					firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",3)'>R</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",4)'>H</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",5)'>2B</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",6)'>3B</th>";
					firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",7)'>HR</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",8)'>RBI</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",9)'>BB</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",10)'>K</th>";
				if 	(result.season.sac == "1")
					{
					firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",11)'>SAC</th>";
					BattingAverageColumn++;
					OnbasePercentColumn++;
					SluggingPercentColumn++;
					}
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\"," + BattingAverageColumn.toString() + ")'>AVG</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\"," + OnbasePercentColumn.toString() + ")'>OBP</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\"," + SluggingPercentColumn.toString() + ")'>SLG</th></tr></thead><tbody>";
				
				// *** HITTER SECTION ***
                if (result.hitters.length == 0) 
					{
					if 	(result.season.sac == "1")
						{
						hittable += '<tr><td>No Data</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
						}
					else
						{
						hittable += '<tr><td>No Data</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
						}
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
						if 	(result.season.sac == "1")
							{
							hittable += '</td><td style="text-align:center">' + result.hitters[x].sacrifice;
							}
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
					if 	(result.season.sac == "1")
						{
						hittable += '</td><td style="text-align:center">' + result.htotals.sacrifice;
						}
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
					sortTableNumber("hitterlist",BattingAverageColumn,"desc");
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
				
			document.getElementById('submenu1').innerHTML = "";
			document.getElementById('submenu2').innerHTML = "";
			document.getElementById('submenu3').innerHTML = "";
			document.getElementById('submenu4').innerHTML = "";
			document.getElementById('submenu5').innerHTML = "";
			document.getElementById('submenu6').innerHTML = "";
			}
        XMLHttpRequestObject.send();
		}
	}
	
function moveGames(value)
	{
	alterURLParameters("startgame",value);
	}

function getSeasonTrend(datasource,statvalue)
	{
    event.preventDefault();
    var XMLHttpRequestObject = false;
    XMLHttpRequestObject = new XMLHttpRequest();
    
    if (XMLHttpRequestObject) 
		{
		XMLHttpRequestObject.open("GET","http://"+ WEBPATH + "/getseasondetails.php?season=" + datasource);
    
        XMLHttpRequestObject.onreadystatechange=function()
			{
            if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
                var result = JSON.parse(XMLHttpRequestObject.responseText);

				var firstHitLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='hitterlist' data-totals='false'>";
				var hittable = "";
				var lastline = '</table>';
				var gameNumArray = Array();
				
                if (result.hitters.length == 0 || result.games.length == 0) 
					{
					hittable += '<tr><td>No Data</td><td>0</td><td>0</td><td>0</td><td>0.000</td><td>0.0</td><td>0</td><td>0.00</td><td>0</td></tr>';
					}
				else
					{
					var sGamePerPage = 10;
					var startGameVar = getQueryVariable("startgame");
					var sStartGame = 0
					if (startGameVar != null)
						{
						sStartGame = parseInt(startGameVar);
						}
					else
						{
						sStartGame = result.games.length - sGamePerPage;
						}
					var sLastGame = result.games.length;
					if ((sStartGame + sGamePerPage) < sLastGame)
						sLastGame = sStartGame + sGamePerPage;
					var	decGame = sStartGame - sGamePerPage;
					var incGame = sStartGame + sGamePerPage;
					if (decGame < 0)
						decGame = 0;
					
					var backLink = "<td rowspan='100%'><button type='button' onclick='moveGames(" + decGame + ")'><=</button></td>";
					var foreLink = "<td rowspan='100%'><button type='button' onclick='moveGames(" + incGame + ")'>=></button></td>";
					
					// Create Header
					if (sStartGame > 0)
						{
						firstHitLine += backLink;
						}
					firstHitLine += '<td>Hitter</td>';
					for (var g = 0; g < sLastGame; g++)
						{
						if (g >= sStartGame)
							{
							firstHitLine += '<td>';
							firstHitLine += result.games[g].game_datetime + '\n' + result.games[g].opponent;
							firstHitLine += '</td>';
							}
						gameNumArray[g] = result.games[g].game_id;
						}
					if (sLastGame != result.games.length)
						{
						firstHitLine += foreLink;
						}

					for (var sCurrHit = 0; sCurrHit < result.players.length; sCurrHit++)
						{
						// Loop and fill in games
						var currName = result.players[sCurrHit].nickname;
						var plateAppearances = 0;
						var theBases = 0;
						var exBases = 0;
						var obsBases = 0;
						var obsAppearances = 0;
						var gCnt = 0;
						var avg = 0.000;
						
						hittable += '<tr><td>' + currName + '</td>';
											
						for (var x=0, gCnt=0; gCnt < sLastGame && x < result.hitters.length; )
							{
							// Same Name
							if (currName == result.hitters[x].nickname)
								{
								if (result.hitters[x].game_id == gameNumArray[gCnt])
									{
									plateAppearances += parseInt(result.hitters[x].atbats);
									theBases += parseInt(result.hitters[x].hits);
									if (statvalue == "all")
										{
										avg = theBases / plateAppearances;
										obsBases += parseInt(result.hitters[x].walks);
										exBases += parseInt(result.hitters[x].doubles);
										exBases += (parseInt(result.hitters[x].triples)*2);
										exBases += (parseInt(result.hitters[x].homeruns)*3);
										obsAppearances += parseInt(result.hitters[x].plateapp);
										if (gCnt >= sStartGame) 
											{
											hittable += '<td>'; 	
											hittable += parseFloat(avg).toFixed(3) + '<br>';
											avg = (theBases+exBases)/plateAppearances;
											hittable += parseFloat(avg).toFixed(3) + '<br>';
											avg += ((theBases+obsBases)/obsAppearances);
											hittable += parseFloat(avg).toFixed(3);
											hittable += '</td>';
											}
										}
									else if (statvalue == "ops")
										{
										theBases += parseInt(result.hitters[x].doubles);
										theBases += (parseInt(result.hitters[x].triples)*2);
										theBases += (parseInt(result.hitters[x].homeruns)*3);
										avg = theBases / plateAppearances;
										
										obsBases += parseInt(result.hitters[x].hits);
										obsBases += parseInt(result.hitters[x].walks);
										obsAppearances += parseInt(result.hitters[x].plateapp);
										avg += (obsBases/obsAppearances);
										if (gCnt >= sStartGame) 
											hittable += '<td>' + parseFloat(avg).toFixed(3) + '</td>';
										}
									else
										{
										if (statvalue == "slg")
											{
											theBases += parseInt(result.hitters[x].doubles);
											theBases += (parseInt(result.hitters[x].triples)*2);
											theBases += (parseInt(result.hitters[x].homeruns)*3);
											}
										avg = theBases / plateAppearances;
										if (gCnt >= sStartGame) 
											hittable += '<td>' + parseFloat(avg).toFixed(3) + '</td>';
										}
									x++;
									gCnt++;
									}
								else
									{
									if (gCnt >= sStartGame) 
										hittable += '<td> --- </td>';
									//	hittable += '<td>' + parseFloat(avg).toFixed(3) + '</td>';
									// Decrement - loop will increment - then repeat
									gCnt++;
									}
								}
							else
								{
								x++;
								}
							}
						hittable += '</tr>';
						}					
					// Post it to the page
					document.getElementById('hitterlist').innerHTML = firstHitLine + hittable + lastline;
					}


				// Header Footer
				document.getElementById('pageheader').innerHTML = '<h2 id="pageheader">' + result.team + '<br>' + result.season.season_name + '</h2>';				
				document.getElementById('updatetag').innerHTML = '<p class="text-right" id="updatetag">Last Updated: ' + result.updated + '</p>';					
				// Remove other tables
				document.getElementById('pitcherlist').innerHTML = "";
				document.getElementById('standings').innerHTML = "";				
				document.getElementById('gamelist').innerHTML = "";
				}
			else
				{
					
				}
			
			document.getElementById('submenu1').innerHTML = "";
			document.getElementById('submenu2').innerHTML = "";
			document.getElementById('submenu3').innerHTML = "<a href=\"javascript:void(0)\"; onclick=\"alterURLParameters('stats','trendall')\">Trend-ALL</a>";
			document.getElementById('submenu4').innerHTML = "<a href=\"javascript:void(0)\"; onclick=\"alterURLParameters('stats','graphavg')\">Trend-AVG</a>";
			document.getElementById('submenu5').innerHTML = "<a href=\"javascript:void(0)\"; onclick=\"alterURLParameters('stats','graphops')\">Trend-OPS</a>";
			document.getElementById('submenu6').innerHTML = "";
			}
        XMLHttpRequestObject.send();
		}
	}

// Trend Chart
function getSeasonTrendChart(datasource,statvalue)
	{
    event.preventDefault();
    var XMLHttpRequestObject = false;
    XMLHttpRequestObject = new XMLHttpRequest();
    
    if (XMLHttpRequestObject) 
		{
		XMLHttpRequestObject.open("GET","http://"+ WEBPATH + "/getseasondetails.php?season=" + datasource);
    
        XMLHttpRequestObject.onreadystatechange=function()
			{
            if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
                var result = JSON.parse(XMLHttpRequestObject.responseText);

				var firstHitLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='hitterlist' data-totals='false'>";
				var hittable = "";
				var lastline = '</table>';
				var gameNumArray = Array();
				
                if (result.hitters.length == 0 || result.games.length == 0) 
					{
					hittable += '<tr><td>No Data</td><td>0</td><td>0</td><td>0</td><td>0.000</td><td>0.0</td><td>0</td><td>0.00</td><td>0</td></tr>';
					}
				else
					{
					var txtGameList = [];
					
					for (var g = 0; g < result.games.length; g++)
						{
						var txtGameName = "G" + g;
						txtGameList.push(txtGameName);
						gameNumArray[g] = result.games[g].game_id;
						}
					
					var sNumRows = (result.players.length/2);
					var sHitter = 0;
					var currentName = "";
					
					for (var sRow = 0; sRow < sNumRows; sRow++)
						{
						hittable += '<tr><td width="10%"></td>';
						// No array checking here, because if there is a row, there should be at least 1 name in it.
						hittable += '<td width="10%">' + result.players[sHitter].nickname + '</td>';
						hittable += '<td width="20%"><canvas id="myChart' + sHitter;
						hittable += '" width="400" height="150"style="border:1px solid #c3c3c3;max-width:600px">Your browser does not support the canvas element.</canvas></td>';
						hittable += '<td width="20%"></td>';
						sHitter++;

						if (sHitter < result.players.length)
							{
							currentName = result.players[sHitter].nickname;
							hittable += '<td width="10%">' + currentName + '</td>';
							hittable += '<td width="20%"><canvas id="myChart' + sHitter;
							hittable += '" width="400" height="150"style="border:1px solid #c3c3c3;max-width:600px">Your browser does not support the canvas element.</canvas></td>';
							hittable += '<td width="10%"></td></tr>';
							}
						else
							{
							hittable += '<td width="10%"></td>';
							hittable += '<td width="20%"></td>';
							hittable += '<td width="10%"></td></tr>';
							}
						sHitter++;
						}
					
					// This will display the table and the canvases
					document.getElementById('hitterlist').innerHTML = firstHitLine + hittable + lastline;
	
					// This loop will update the canvas for each player
					for (var sCurrHit = 0; sCurrHit < result.players.length; sCurrHit++)
						{
						// Loop and fill in games
						var currName = result.players[sCurrHit].nickname;
						var plateAppearances = 0;
						var theBases = 0;
						var exBases = 0;
						var obsBases = 0;
						var obsAppearances = 0;
						var gCnt = 0;
						var avg = 0.000;
						var group1Label = "AVG";
						var group1Points = [];
						var group2Label = "OBP";
						var group2Points = [];
						var minValue = 0.300;
						var maxValue = 0.800;
						var stepSize = 0.1;
																	
						for (var x=0, gCnt=0; gCnt < result.games.length && x < result.hitters.length; )
							{
							// Same Name
							if (currName == result.hitters[x].nickname)
								{
								if (result.hitters[x].game_id == gameNumArray[gCnt])
									{
									plateAppearances += parseInt(result.hitters[x].atbats);
									theBases += parseInt(result.hitters[x].hits);
									if (statvalue == "avg")
										{
										obsBases += parseInt(result.hitters[x].walks);
										obsAppearances += parseInt(result.hitters[x].plateapp);
										
										avg = parseFloat(theBases / plateAppearances).toFixed(3);
										group1Points.push(avg);
										
										avg = parseFloat((theBases+obsBases) / obsAppearances).toFixed(3);
										group2Points.push(avg);
										}
									else if (statvalue == "ops")
										{
										group1Label = "SLG";
										group2Label = "OPS"
										minValue = 0.400;
										maxValue = 2.000;
										stepSize = 0.2

										theBases += parseInt(result.hitters[x].doubles);
										theBases += (parseInt(result.hitters[x].triples)*2);
										theBases += (parseInt(result.hitters[x].homeruns)*3);
										avg = parseFloat(theBases / plateAppearances).toFixed(3);
										group1Points.push(avg);
										
										obsBases += parseInt(result.hitters[x].hits);
										obsBases += parseInt(result.hitters[x].walks);
										obsAppearances += parseInt(result.hitters[x].plateapp);
										avg = parseFloat((theBases / plateAppearances)+(obsBases/obsAppearances)).toFixed(3);
										group2Points.push(avg);
										}
									x++;
									gCnt++;
									}
								else
									{
									group1Points.push(null);
									group2Points.push(null);
									gCnt++;
									}
								}
							else
								{
								x++;
								}
							}
							
						var chartName = "myChart" + sCurrHit;
						
						var myChart = new Chart(chartName, {
												  type: "line",
												  data: {
													labels: txtGameList,
													datasets: [{
													  label: group1Label,
													  fill: false,
													  lineTension: 0,
													  backgroundColor: "rgba(0,0,0,1.0)",
													  borderColor: "red",
													  data: group1Points
													  },
													  {
													  label: group2Label,
													  fill: false,
													  lineTension: 0,
													  backgroundColor: "rgba(0,0,0,1.0)",
													  borderColor: "blue",
													  data: group2Points
													  }]		  
													},
												  options: {
													legend: {display: true},
													title: {
															  display: false,
															  text: "Game Trend"
															},
													scales: { 
														yAxes: [{
															ticks: {
																	min: minValue,
																	max: maxValue,
																	stepSize: stepSize
																	},
																}]
															}	
													}
												});
						}
					
					// Post it to the page
					}


				// Header Footer
				document.getElementById('pageheader').innerHTML = '<h2 id="pageheader">' + result.team + '<br>' + result.season.season_name + '</h2>';				
				document.getElementById('updatetag').innerHTML = '<p class="text-right" id="updatetag">Last Updated: ' + result.updated + '</p>';					
				// Remove other tables
				document.getElementById('pitcherlist').innerHTML = "";
				document.getElementById('standings').innerHTML = "";				
				document.getElementById('gamelist').innerHTML = "";
				}
			else
				{
					
				}
			
			document.getElementById('submenu1').innerHTML = "";
			document.getElementById('submenu2').innerHTML = "";
			document.getElementById('submenu3').innerHTML = "<a href=\"javascript:void(0)\"; onclick=\"alterURLParameters('stats','trendall')\">Trend-ALL</a>";
			document.getElementById('submenu4').innerHTML = "<a href=\"javascript:void(0)\"; onclick=\"alterURLParameters('stats','graphavg')\">Trend-AVG</a>";
			document.getElementById('submenu5').innerHTML = "<a href=\"javascript:void(0)\"; onclick=\"alterURLParameters('stats','graphops')\">Trend-OPS</a>";
			document.getElementById('submenu6').innerHTML = "";
			}
        XMLHttpRequestObject.send();
		}
	}
	
function getSeasonSplits(datasource,statsplit)
	{
    event.preventDefault();
    var XMLHttpRequestObject = false;
    XMLHttpRequestObject = new XMLHttpRequest();
    
    if (XMLHttpRequestObject) 
		{
		XMLHttpRequestObject.open("GET","http://"+ WEBPATH + "/getseasondetails.php?season=" + datasource);
    
        XMLHttpRequestObject.onreadystatechange=function()
			{
            if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
                var result = JSON.parse(XMLHttpRequestObject.responseText);

				var firstHitLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='hitterlist' data-totals='false'>";
				var hittable = "";
				var lastline = '</table>';
				
                if (result.hitters.length == 0 || result.games.length == 0) 
					{
					hittable += '<tr><td>No Data</td><td>0</td><td>0</td><td>0</td><td>0.000</td><td>0.0</td><td>0</td><td>0.00</td><td>0</td></tr>';
					}
				else
					{
					var gameTypeArray = new Set();
					
					firstHitLine += '<tr><td>Hitter</td>';
					for (var g = 0; g < result.games.length; g++)
						{
						if (statsplit == "time")
							{
							var tempDate = new Date(result.games[g].game_datetime);
							gameTypeArray.add(tempDate.toLocaleTimeString('en-US'));
							}
						else // opponent
							{
							gameTypeArray.add(result.games[g].opponent);
							}
						}
						
					for (var it = gameTypeArray.values(), val= null; val=it.next().value; )
						{
						firstHitLine += '<td>';
						firstHitLine += val;
						firstHitLine += '</td>';
						}
					firstHitLine += '</tr>';

					for (var sCurrHit = 0; sCurrHit < result.players.length; sCurrHit++)
						{
						// Loop and fill in games
						var currName = result.players[sCurrHit].nickname;
						var plateAppearances = new Map();
						var theBases = new Map();
						var exBases = new Map();
						var obsBases = new Map();
						var obsAppearances = new Map();
						var gCnt = 0;
						var avg = 0.000;
						
						hittable += '<tr><td>' + currName + '</td>';
											
						for (var x=0, gCnt=0; gCnt < result.games.length && x < result.hitters.length; )
							{
							// Same Name
							if (currName == result.hitters[x].nickname)
								{
								var tempDate = new Date(result.hitters[x].game_datetime);
								var gameDetail = "";
								var tempVar = 0;
								
								if (statsplit == "time")
									{
									gameDetail = tempDate.toLocaleTimeString('en-US');
									}
								else
									{	
									gameDetail = result.hitters[x].opponent;
									}
								
								if (plateAppearances.has(gameDetail))
									{
									tempVar = plateAppearances.get(gameDetail);
									}
								else 
									{
									tempVar = 0;
									}
								tempVar += parseInt(result.hitters[x].atbats);
								plateAppearances.set(gameDetail,tempVar);
								
								if (theBases.has(gameDetail))
									{
									tempVar = theBases.get(gameDetail);
									}
								else 
									{
									tempVar = 0;
									}
								tempVar += parseInt(result.hitters[x].hits);
								theBases.set(gameDetail,tempVar);

								if (obsBases.has(gameDetail))
									{
									tempVar = obsBases.get(gameDetail);
									}
								else 
									{
									tempVar = 0;
									}
								tempVar += parseInt(result.hitters[x].walks);
								obsBases.set(gameDetail,tempVar);
								
								if (exBases.has(gameDetail))
									{
									tempVar = exBases.get(gameDetail);
									}
								else 
									{
									tempVar = 0;
									}
								tempVar += (parseInt(result.hitters[x].doubles));
								tempVar += (parseInt(result.hitters[x].triples)*2);
								tempVar += (parseInt(result.hitters[x].homeruns)*3);
								exBases.set(gameDetail,tempVar);

								if (obsAppearances.has(gameDetail))
									{
									tempVar = obsAppearances.get(gameDetail);
									}
								else 
									{
									tempVar = 0;
									}
								tempVar += parseInt(result.hitters[x].plateapp);
								obsAppearances.set(gameDetail,tempVar);
					
								x++;
								gCnt++;
								}
							else
								{
								x++;
								}
							}
							
						for (var it = gameTypeArray.values(), val= null; val=it.next().value; )
							{
							var numerator = 0;
							var denominator = 0;
							var obp = 0.000;
							
							hittable += '<td>';
							if (plateAppearances.has(val))
								{
								numerator = theBases.get(val);
								denominator = plateAppearances.get(val);
								hittable += parseFloat(numerator/denominator).toFixed(3) + '<br>';
								
								numerator = theBases.get(val) + obsBases.get(val);
								denominator = obsAppearances.get(val);
								obp = numerator/denominator;				
								hittable += parseFloat(obp).toFixed(3) + '<br>';
								
								numerator = theBases.get(val) + exBases.get(val);
								denominator = plateAppearances.get(val);
								hittable += parseFloat(numerator/denominator).toFixed(3) + '<br>';
								
								hittable += parseFloat(obp+(numerator/denominator)).toFixed(3) + '<br>';


								}
							else
								{
								hittable += "---";
								}

							hittable += '</td>';
							}
							
						hittable += '</tr>';
						}					
					// Post it to the page
					document.getElementById('hitterlist').innerHTML = firstHitLine + hittable + lastline;
					}


				// Header Footer
				document.getElementById('pageheader').innerHTML = '<h2 id="pageheader">' + result.team + '<br>' + result.season.season_name + '</h2>';				
				document.getElementById('updatetag').innerHTML = '<p class="text-right" id="updatetag">Last Updated: ' + result.updated + '</p>';					
				// Remove other tables
				document.getElementById('pitcherlist').innerHTML = "";
				document.getElementById('standings').innerHTML = "";				
				document.getElementById('gamelist').innerHTML = "";
				}
			else
				{
					
				}
			
			document.getElementById('submenu1').innerHTML = "";
			document.getElementById('submenu2').innerHTML = "";
			document.getElementById('submenu3').innerHTML = "<a href=\"javascript:void(0)\"; onclick=\"alterURLParameters('stats','trendall')\">Trend-ALL</a>";
			document.getElementById('submenu4').innerHTML = "<a href=\"javascript:void(0)\"; onclick=\"alterURLParameters('stats','graphavg')\">Trend-AVG</a>";
			document.getElementById('submenu5').innerHTML = "<a href=\"javascript:void(0)\"; onclick=\"alterURLParameters('stats','graphops')\">Trend-OPS</a>";
			document.getElementById('submenu6').innerHTML = "";
			}
        XMLHttpRequestObject.send();
		}
	}

window.addEventListener('load', readSeasonStats);

