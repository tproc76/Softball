function readLeagueStats()
{
	var leaguename = getQueryVariable("league");
	
	if (leaguename != null)
		getAllTimeLeageData(leaguename);
}

var BattingAverageColumn = 11;

function sortInitialHitterTable() 
	{
	var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
	table = document.getElementById("hitterlist");
	switching = true;
	//Set the sorting direction to ascending:
	/*Make a loop that will continue until
	no switching has been done:*/
	while (switching) 
		{
		//start by saying: no switching is done:
		switching = false;
		rows = table.getElementsByTagName("tr");
		/*Loop through all table rows (except the
		first, which contains table headers):*/
		for (i = 1; i < (rows.length - 1); i++) 
			{
			//start by saying there should be no switching:
			shouldSwitch = false;
			/*Get the two elements you want to compare,
			one from current row and one from the next:*/
			x = rows[i].getElementsByTagName("TD")[BattingAverageColumn];
			y = rows[i + 1].getElementsByTagName("TD")[BattingAverageColumn];
			/*check if the two rows should switch place,
			based on the direction, asc or desc:*/
			var topRow = parseFloat(x.innerHTML);
			var botRow = parseFloat(y.innerHTML);

			if (topRow < botRow) 
				{
				//if so, mark as a switch and break the loop:
				shouldSwitch= true;
				break;
				}
			}
			
		if (shouldSwitch) 
			{
			/*If a switch has been marked, make the switch
			and mark that a switch has been done:*/
			rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
			switching = true;
			//Each time a switch is done, increase this count by 1:
			switchcount ++;      
			} 
		}
	}

function getAllTimeLeageData(datasource)
	{
    event.preventDefault();
    var XMLHttpRequestObject = false;
    XMLHttpRequestObject = new XMLHttpRequest();
    
    if (XMLHttpRequestObject) 
		{
		XMLHttpRequestObject.open("GET","http://"+ WEBPATH + "/getalltime.php?league=" + datasource);
    
        XMLHttpRequestObject.onreadystatechange=function()
			{
			var hitTableNm = "";
            var firstHitLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='hitterlist'><thead><tr><th style='text-align:center' onclick='sortTableLinkString(\"hitterlist\",0)'>Hitter</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",1)'>G</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",2)'>AB</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",3)'>R</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",4)'>H</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",5)'>2B</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",6)'>3B</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",7)'>HR</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",8)'>RBI</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",9)'>BB</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",10)'>K</th>";
				firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",11)'>AVG</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",12)'>OBP</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",13)'>SLG</th></tr></thead><tbody>";
			BattingAverageColumn = 11;
			var hittable = '';
            var lastline = '</table>';

            var firstPitchLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='pitcherlist'><thead><tr><th style='text-align:center' onclick='sortTableLinkString(\"pitcherlist\",0)'>Pitcher</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",1)'>G</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",2)'>W</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",3)'>L</th>";
				firstPitchLine += "<th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",4)'>PCT</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",5)'>INN</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",6)'>R</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",7)'>RA</th>";
				firstPitchLine += "<th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",8)'>K</th><th style='text-align:center' onclick='sortTableNumber(\"pitcherlist\",9)'>BB</th></tr></thead><tbody>";
			var pitchtable = '';
			
            if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
                var result = JSON.parse(XMLHttpRequestObject.responseText);
				var gamesPlayed = parseInt(result.season.wins)+parseInt(result.season.losses)+parseInt(result.season.ties);
				
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
						
						hittable += '<tr><td><a href=\"hitterstats.php?player=' + result.hitters[x].player_id + '&league=' + datasource + '\">' + hitter + '</a></td><td>';
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
					
						pitchtable += '<tr><td><a href=\"pitcherstats.php?player=' + result.pitchers[x].player_id + '&league=' + datasource + '\">' + pitchername + '</a></td><td style="text-align:center">';
						pitchtable += result.pitchers[x].games + '</td><td style="text-align:center">' + result.pitchers[x].wins + '</td><td style="text-align:center">' + result.pitchers[x].losses;
						var winPercent = parseInt(result.pitchers[x].wins)/ (parseInt(result.pitchers[x].wins)+parseInt(result.pitchers[x].losses));
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
				
				var winPct = parseInt(result.season.wins)/(gamesPlayed);
				var pyth = (parseInt(result.season.scored)*parseInt(result.season.scored))/((parseInt(result.season.scored)*parseInt(result.season.scored))+(parseInt(result.season.allowed)*parseInt(result.season.allowed)));
				
				document.getElementById('teamwins').innerHTML = result.season.wins;
				document.getElementById('teamloss').innerHTML = result.season.losses;
				document.getElementById('teamties').innerHTML = result.season.ties;
				document.getElementById('teampct').innerHTML = parseFloat(winPct).toFixed(3);
				document.getElementById('teamscored').innerHTML = result.season.scored;
				document.getElementById('teamallowed').innerHTML = result.season.allowed;
				document.getElementById('teampyth').innerHTML = parseFloat(pyth).toFixed(3);
					
				delete XMLHttpRequestObject;
				XMLHttpRequestObject = null;					
				} 
			else
				{
                document.getElementById('hitterlist').innerHTML = firstHitLine + hittable + lastline;
                document.getElementById('pitcherlist').innerHTML = firstPitchLine + pitchtable + lastline;
				}
			}
        XMLHttpRequestObject.send();
		}
	}

window.addEventListener('load', readLeagueStats);
