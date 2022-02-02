var WEBPATH = "proctorfamily.org/softball";

function getQueryVariable(variable) 
	{
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++) 
		{
		var pair = vars[i].split("=");
		if (pair[0] == variable) 
			{
			return pair[1];
			}
		} 
	return null;
	}

function convertMonthToString(numString)
	{
	var monthString = "None";
	
	switch(parseInt(numString))
		{
		case 1:
			monthString = "Jan";
			break;
		case 2:
			monthString = "Feb";
			break;
		case 3:
			monthString = "Mar";
			break;
		case 4:
			monthString = "Apr";
			break;
		case 5:
			monthString = "May";
			break;
		case 6:
			monthString = "Jun";
			break;
		case 7:
			monthString = "Jul";
			break;
		case 8:
			monthString = "Aug";
			break;
		case 9:
			monthString = "Sep";
			break;
		case 10:
			monthString = "Oct";
			break;
		case 11:
			monthString = "Nov";
			break;
		case 12:
			monthString = "Dec";
			break;
		}
	return monthString;
	}

function convertDateToString(databaseString)
	{
	var dateNTime = databaseString.split(' ');
	var datePcs = dateNTime[0].split('-');
	var timePcs = dateNTime[1].split(':');
	var monthString = "None";
	var returnString = "";
	
	monthString = convertMonthToString(datePcs[1]);
		
	returnString = monthString + '-' + datePcs[2]  + '-' + datePcs[0] + " " + timePcs[0] + ":" + timePcs[1];
	
	return returnString;
	}

function alterURLParameters(name, value)
	{
	var ua = window.navigator.userAgent.toUpperCase();
	var msie1 = ua.indexOf("MSIE ");		// older IE
	var msie2 = ua.indexOf("TRIDENT/");		// newer IE

	if (msie1 > 0 || msie2 > 0)
		{
		var TheAnchor = null;
		var newAdditionalURL = "";
		var theUrl = window.location.href;
		var tempArray = theUrl.split("?");
		var baseURL = tempArray[0];
		var additionalURL = tempArray[1];
		var temp = "";

		if (additionalURL) 
			{
			tempArray = additionalURL.split("&");

			for (var i=0; i<tempArray.length; i++)
				{
				if(tempArray[i].split('=')[0] != name)
					{
					newAdditionalURL += temp + tempArray[i];
					temp = "&";
					}
				}        
			}

		var rows_txt = temp + "" + name + "=" + value;
		window.location = baseURL + "?" + newAdditionalURL + rows_txt;

		}
	else		// Not IE
		{	
		var urlorg = new URL(window.location.href);

		var query_string = urlorg.search;

		var search_params = new URLSearchParams(query_string); 

		// new value of "id" is set to "101"
		search_params.set(name, value);

		// change the search property of the main url
		urlorg.search = search_params.toString();

		// the new url string
		var new_url = urlorg.toString();

		// output : http://demourl.com/path?id=101&topic=main
		window.location = new_url;
		}
	}