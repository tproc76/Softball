function sortTableNumber(tableClass, n, frcDirect) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById(tableClass);
  var skipRows = 1;
 
  if (table.dataset.totals != null)
	{
	if (table.dataset.totals == "true")
		{
		skipRows = 2;
		}
	}
  switching = true;
  //Set the sorting direction to ascending:
  dir = frcDirect || "desc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("tr");
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - skipRows); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
	  var topRow = parseFloat(x.innerHTML);
	  var botRow = parseFloat(y.innerHTML);
	  
      if (dir == "asc") {
        if (topRow > botRow) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (topRow < botRow) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "desc",
      set the direction to "asc" and run the while loop again.*/
      if (switchcount == 0 && dir == "desc" && frcDirect == null) {
        dir = "asc";
        switching = true;
      }
    }
  }
}

function sortTableString(tableClass, n, frcDirect) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById(tableClass);
  var skipRows = 1;
 
  if (table.dataset.totals != null)
	{
	if (table.dataset.totals == "true")
		{
		skipRows = 2;
		}
	}
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("tr");
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - skipRows); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc" && frcDirect == null) {
        dir = "desc";
        switching = true;
      }
    }
  }
}

function sortTableLinkString(tableClass, n, frcDirect) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById(tableClass);
  var skipRows = 1;
 
  if (table.dataset.totals != null)
	{
	if (table.dataset.totals == "true")
		{
		skipRows = 2;
		}
	}
  switching = true;
  //Set the sorting direction to ascending:
  dir = frcDirect || "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("tr");
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - skipRows); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerText.toLowerCase() > y.innerText.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerText.toLowerCase() < y.innerText.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc" && frcDirect == null) {
        dir = "desc";
        switching = true;
      }
    }
  }
}

function convertSeasontoNumber(season)
{
  if (season.toLowerCase() == "spring")
	return 1;

  if (season.toLowerCase() == "summer")
	return 2;
	
  if (season.toLowerCase() == "fall")
	return 3;
	
  if (season.toLowerCase() == "winter")
	return 4;
	
  return 0;
}

function sortTableLinkSeason(tableClass, n, frcDirect) 
	{
	var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
	table = document.getElementById(tableClass);
	var skipRows = 1;

	if (table.dataset.totals != null)
	{
	if (table.dataset.totals == "true")
		{
		skipRows = 2;
		}
	}
	switching = true;
	//Set the sorting direction to ascending:  -- if (hostname === undefined) 
	dir = frcDirect || "asc"; 
	/*Make a loop that will continue until
	no switching has been done:*/
	while (switching) 
		{
		//start by saying: no switching is done:
		switching = false;
		rows = table.getElementsByTagName("tr");
		/*Loop through all table rows (except the
		first, which contains table headers):*/
		for (i = 1; i < (rows.length - skipRows); i++) 
			{
			//start by saying there should be no switching:
			shouldSwitch = false;
			/*Get the two elements you want to compare,
			one from current row and one from the next:*/
			x = rows[i].getElementsByTagName("TD")[n];
			y = rows[i + 1].getElementsByTagName("TD")[n];
			/*check if the two rows should switch place,
			based on the direction, asc or desc:*/
			xPos = x.innerText.lastIndexOf(" 20");
			xYear = x.innerText.substr(xPos+1);
			xSeas = convertSeasontoNumber(x.innerText.substr(0,xPos));
			yPos = y.innerText.lastIndexOf(" 20");
			yYear = y.innerText.substr(yPos+1);
			ySeas = convertSeasontoNumber(y.innerText.substr(0,yPos));

			if (dir == "asc") 
				{
				if (xYear > yYear) 
					{
					//if so, mark as a switch and break the loop:
					shouldSwitch= true;
					break;
					}
				else if (xYear == yYear) 
					{
					if (xSeas > ySeas)
						{
						//if so, mark as a switch and break the loop:
						shouldSwitch= true;
						break;
						}
					}
				} 
			else if (dir == "desc") 
				{
				if (xYear < yYear) 
					{
					//if so, mark as a switch and break the loop:
					shouldSwitch= true;
					break;
					}
				else if (xYear == yYear) 
					{
					if (xSeas < ySeas)
						{
						//if so, mark as a switch and break the loop:
						shouldSwitch= true;
						break;
						}
					}
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
		else 
			{
			/*If no switching has been done AND the direction is "asc",
			set the direction to "desc" and run the while loop again.*/
			if (switchcount == 0 && dir == "asc" && frcDirect == null) 
				{
				dir = "desc";
				switching = true;
				}
			}
		}
	}

function sortTableDate(tableClass, n, frcDirect) 
	{
	var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
	table = document.getElementById(tableClass);
	var skipRows = 1;

	if (table.dataset.totals != null)
	{
	if (table.dataset.totals == "true")
		{
		skipRows = 2;
		}
	}
	switching = true;
	//Set the sorting direction to ascending:  -- if (hostname === undefined) 
	dir = frcDirect || "asc"; 
	/*Make a loop that will continue until
	no switching has been done:*/
	while (switching) 
		{
		//start by saying: no switching is done:
		switching = false;
		rows = table.getElementsByTagName("tr");
		/*Loop through all table rows (except the
		first, which contains table headers):*/
		for (i = 1; i < (rows.length - skipRows); i++) 
			{
			//start by saying there should be no switching:
			shouldSwitch = false;
			/*Get the two elements you want to compare,
			one from current row and one from the next:*/
			x = rows[i].getElementsByTagName("TD")[n];
			y = rows[i + 1].getElementsByTagName("TD")[n];
			/*check if the two rows should switch place,
			based on the direction, asc or desc:*/
			xDate = new Date(x.innerText);
			yDate = new Date(y.innerText);

			if (dir == "asc") 
				{
				if (xDate > yDate) 
					{
					//if so, mark as a switch and break the loop:
					shouldSwitch= true;
					break;
					}
				} 
			else if (dir == "desc") 
				{
				if (xDate < yDate) 
					{
					//if so, mark as a switch and break the loop:
					shouldSwitch= true;
					break;
					}
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
		else 
			{
			/*If no switching has been done AND the direction is "asc",
			set the direction to "desc" and run the while loop again.*/
			if (switchcount == 0 && dir == "asc" && frcDirect == null) 
				{
				dir = "desc";
				switching = true;
				}
			}
		}
	}
