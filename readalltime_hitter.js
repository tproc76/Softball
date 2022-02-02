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
	
function updateHitterTable(result,league)
	{
	var firstHitLine = "<table border='1' cellspacing='1' cellpadding='10' class='center' id='hitterlist'><thead><tr><th style='text-align:center' onclick='sortTableLinkString(\"hitterlist\",0)'>Hitter</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",1)'>G</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",2)'>AB</th>";
		firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",3)'>R</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",4)'>H</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",5)'>2B</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",6)'>3B</th>";
		firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",7)'>HR</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",8)'>RBI</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",9)'>BB</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",10)'>K</th>";
		firstHitLine += "<th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",11)'>AVG</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",12)'>OBP</th><th style='text-align:center' onclick='sortTableNumber(\"hitterlist\",13)'>SLG</th></tr></thead><tbody>";
	var hittable = '';
	var lastline = '</table>';
	
	// *** HITTER SECTION ***
	if (result.hitters.length == 0) 
		{
		hittable += '<tr><td>No Data</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
		}
	else
		{								
		for (var x = 0; x < result.hitters.length; x++ )
			{
			var hitter = result.hitters[x].nickname;

			hittable += '<tr><td><a href=\"hitterstats.php?player=' + result.hitters[x].player_id + '&league=' + league + '\">' + hitter + '</a></td><td>';
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
								
		document.getElementById('hitterlist').innerHTML = firstHitLine + hittable + lastline;
		sortTableNumber("hitterlist",11,"desc");
		}
	}

function getAllTimeLeageData(datasource)
	{
    event.preventDefault();
    var XMLHttpRequestObject = false;
    XMLHttpRequestObject = new XMLHttpRequest();
    
    if (XMLHttpRequestObject) 
		{
		XMLHttpRequestObject.open("GET","http://"+ WEBPATH + "/getalltime_hitter.php?league=" + datasource);
    
        XMLHttpRequestObject.onreadystatechange=function()
			{
			var groupList = '';
			
            if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
                var result = JSON.parse(XMLHttpRequestObject.responseText);
				
				updateHitterTable(result,datasource);
					
				groupList = "Groups<br><select id='groupfilter'><option value=''>No Filter</option>";
                if (result.seasons.length > 0) 
					{	
					var groupSet = new Set();
					for (var x = 0; x < result.seasons.length; x++ )
						{
						groupSet.add(result.seasons[x].grouping);
						}
					
					groupSet.forEach(function (thisGroup) {  
						groupList += "<option value='" + thisGroup + "'>" + thisGroup + "</option>";
						});
					}
				groupList += "</select>";
				document.getElementById('cellgroupfilter').innerHTML = groupList;
					
				delete XMLHttpRequestObject;
				XMLHttpRequestObject = null;					
				} 
			}
        XMLHttpRequestObject.send();
		}
	}
	
function filterData()
	{
    event.preventDefault();
    var XMLHttpRequestObject = false;
    XMLHttpRequestObject = new XMLHttpRequest();
    
	var leaguename = getQueryVariable("league");
	
	var atBatFilter = document.getElementById('abfilter');
	var abSelInx = atBatFilter.selectedIndex;
	var abSelValue = atBatFilter.options[abSelInx].value;
	var groupBatFilter = document.getElementById('groupfilter');
	var groupSelInx = groupBatFilter.selectedIndex;
	var groupSelValue = groupBatFilter.options[groupSelInx].value;
	
    if (XMLHttpRequestObject) 
		{
		if (groupSelValue.length>0)
			{
			XMLHttpRequestObject.open("GET","http://"+ WEBPATH + "/getalltime_hitter.php?league=" + leaguename + "&ab=" + abSelValue + "&group=" + groupSelValue);
			}
		else
			{
			XMLHttpRequestObject.open("GET","http://"+ WEBPATH + "/getalltime_hitter.php?league=" + leaguename + "&ab=" + abSelValue);
			}
    
        XMLHttpRequestObject.onreadystatechange=function()
			{
			var groupList = '';
			
            if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
				{
                var result = JSON.parse(XMLHttpRequestObject.responseText);
				
				updateHitterTable(result,leaguename);
				}
			}
        XMLHttpRequestObject.send();
		}			
	}

window.addEventListener('load', readLeagueStats);
