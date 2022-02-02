function readLeagueSeasons()
{
	var leaguename = getQueryVariable("league");
//	gethitterdata('Co-ed Fall 2003');

	if (leaguename == null)
		leaguename = "Mens"
	getLeagueData(leaguename);
}

function getLeagueData(datasource)
	{
    event.preventDefault();
    var XMLHttpRequestObject = false;
    XMLHttpRequestObject = new XMLHttpRequest();
    
    if (XMLHttpRequestObject) 
		{
		XMLHttpRequestObject.open("GET","http://" + WEBPATH + "/getleague.php?league=" + datasource);
    
        XMLHttpRequestObject.onreadystatechange=function()
			{
			var seasonTable = '';
            var firstSeasonLine = "<table border='1' cellspacing='1' cellpadding='8' class='center' id='seasonlist'><thead><tr><th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",0)'>Year</th><th style='text-align:left' onclick='sortTableLinkSeason(\"seasonlist\",1)'>Season</th>";
				firstSeasonLine += "<th style='text-align:left' onclick='sortTableLinkString(\"seasonlist\",2)'>Team Name</th><th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",3)'>Wins</th><th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",4)'>Losses</th>";
				firstSeasonLine += "<th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",5)'>Ties</th><th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",6)'>PCT</th><th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",7)'>Scored</th>";
				firstSeasonLine += "<th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",8)'>RS/G</th><th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",9)'>Allowed</th><th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",10)'>RA/G</th>";
				firstSeasonLine += "<th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",11)'>PYT</th><th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",12)'>AVG</th>";
				firstSeasonLine += "<th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",13)'>OBP</th><th style='text-align:center' onclick='sortTableNumber(\"seasonlist\",14)'>SLG</th></tr></thead><tbody>";
            var lastline = '</table>';
			
            if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
                var result = JSON.parse(XMLHttpRequestObject.responseText);
				
				// *** DATA SECTION ***
                if (result.length == 0) 
					{
					firstSeasonLine += '<tr><td>No Data</td><td>No Data</td><td>No Data</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
					}
				else
					{
					var winPct = '0.999';
					var Pyth = '0.998';
									
					for (var x = 0; x < result.season.length; x++ )
						{
						seasonTable += '<tr><td style=\"text-align:center\">'+ result.season[x].year + '<td><a href=\"season.php?season=' + result.season[x].season_name + '\">' + result.season[x].season_name + '</a></td><td>';
						seasonTable += result.season[x].team_name + '</td><td style="text-align:center">' + result.season[x].wins + '</td><td style="text-align:center">' + result.season[x].losses;
						seasonTable += '</td><td style="text-align:center">' + result.season[x].ties + '</td><td style="text-align:center">';
						winPct = (parseInt(result.season[x].wins)/(parseInt(result.season[x].wins)+parseInt(result.season[x].losses)+parseInt(result.season[x].ties)));
						winPct = parseFloat(winPct).toFixed(3);
						seasonTable += winPct + '</td><td style="text-align:center">';
						var RSG = (parseInt(result.season[x].scored))/(parseInt(result.season[x].wins)+parseInt(result.season[x].losses)+parseInt(result.season[x].ties));
						var RAG = (parseInt(result.season[x].allowed))/(parseInt(result.season[x].wins)+parseInt(result.season[x].losses)+parseInt(result.season[x].ties));
						seasonTable += result.season[x].scored + '</td><td style="text-align:center">' + parseFloat(RSG).toFixed(1) + '</td><td style="text-align:center">';
						seasonTable += result.season[x].allowed  + '</td><td style="text-align:center">' + parseFloat(RAG).toFixed(1);
						Pyth = ((parseInt(result.season[x].scored)*parseInt(result.season[x].scored))/((parseInt(result.season[x].scored)*parseInt(result.season[x].scored))+(parseInt(result.season[x].allowed)*parseInt(result.season[x].allowed))));
						Pyth = parseFloat(Pyth).toFixed(3);
						seasonTable += '</td><td style="text-align:center">' + Pyth;
						var bavg = 0;
						if (parseInt(result.hitting[x].atbats) > 0)
							bavg = parseInt(result.hitting[x].hits)/parseInt(result.hitting[x].atbats);
						seasonTable += '</td><td style="text-align:center">' + parseFloat(bavg).toFixed(3);
						var obp = 0;
						if ((parseInt(result.hitting[x].atbats)+parseInt(result.hitting[x].walks)+parseInt(result.hitting[x].sacrifice)) > 0)
							obp = (parseInt(result.hitting[x].hits)+parseInt(result.hitting[x].walks))/(parseInt(result.hitting[x].atbats)+parseInt(result.hitting[x].walks)+parseInt(result.hitting[x].sacrifice));
						seasonTable += '</td><td style="text-align:center">' + parseFloat(obp).toFixed(3);
						var slg = 0;
						if (parseInt(result.hitting[x].atbats) > 0)
							slg = (parseInt(result.hitting[x].hits)+parseInt(result.hitting[x].doubles)+parseInt(result.hitting[x].triples)*2+parseInt(result.hitting[x].homeruns)*3)/parseInt(result.hitting[x].atbats);
						seasonTable += '</td><td style="text-align:center">' + parseFloat(slg).toFixed(3);
						seasonTable += '</td></tr>';
						}
					document.getElementById('seasonlist').innerHTML = firstSeasonLine + seasonTable + lastline;					
					}
				
				delete XMLHttpRequestObject;
				XMLHttpRequestObject = null;					
				} 
			else
				{
                document.getElementById('seasonlist').innerHTML = firstSeasonLine + seasonTable + lastline;
				}
			}
        XMLHttpRequestObject.send();
		}
	}

window.addEventListener('load', readLeagueSeasons);
