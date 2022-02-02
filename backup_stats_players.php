<?php
session_start();

// do we keep these?  If so, I can't schedule the back up, so that seems bad, but if I do it by manual trigger, this is desired
if(isset($_SESSION['login_user']) == false)
	{
	// redirect to login if not logged in
	echo '<script type="text/javascript">window.location = "attend_login.html"</script>';
	exit();
	}
if (($_SESSION['login_user']!="proc@comcast.net") &&
	($_SESSION['login_user']!="timothy.proctor@gm.com"))
	{
	// redirect to login if not logged in
	echo '<script type="text/javascript">window.location = "attend_members_main.php"</script>';
	exit();
	}

include 'db_setup.php';

function checkFieldNames($theRow,$expectNames)
	{
	$arrayDiff = array_diff_key($theRow,$expectNames);
	$unknwonFields = array_keys($arrayDiff);
				
	if (sizeof($unknwonFields) > 0)
		{
		echo "ERROR MISSING FIELDS!! - Fields not included in table: ";
		//var_dump(array_keys($arrayDiff));
		for ($inx = 0; $inx < sizeof($unknwonFields); $inx++)
			{
			if ($inx > 0)
				echo ", ";
			echo $unknwonFields[$inx];
			}
		echo "\n\r";
		}
	}
	
$rows = array();
$resultstatus = "Failed: no path";

header('Content-Type: application/json; charset=utf-8', true,200);

$nameList = array('player_id' => false,'nickname' => true,'fullname' => true,'notes' => true);

$SQLquery = "SELECT * FROM players";
$firstTimeFlag = true;

// create/open file (overwrite) - https://www.w3schools.com/php/php_file_create.asp
$backupFile = fopen("backup/stat_players.php", "w") or die("Unable to open file!");

fwrite($backupFile,"<?php\n");
fwrite($backupFile,"include '..\\db_setup.php';\n\n");

fwrite($backupFile,"\$insertPlayers = \"\";\n");

$rowcount = 0;
if ($result = mysqli_query($link, $SQLquery))
	{
   while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
		$rowcount = $rowcount+1;
		if ($firstTimeFlag == true)
			{
			checkFieldNames($row,$nameList);
			$firstTimeFlag = false;
			}
		$rows[] = $row;
		// Write data to file
		fwrite($backupFile,"\$insertPlayers .= \"INSERT INTO players VALUES(");
		foreach($nameList as $key => $value)
			{
			if ($key != 'player_id')
				fwrite($backupFile,", ");
			if ($row[$key] == NULL)
				{
				fwrite($backupFile,"NULL");
				}
			else if ($value == true)
				{
				fwrite($backupFile,"'" . $row[$key] . "'");
				}
			else
				{
				fwrite($backupFile,$row[$key]);
				}
			}
		fwrite($backupFile,");\";\n");		// Yes, there really are 3 semicolons needed
		}
		
	fwrite($backupFile,"\nif(mysqli_multi_query(\$link, \$insertPlayers) == false){\n");
    fwrite($backupFile,"echo \"ERROR: Could not execute. \" . mysqli_error(\$link) . \"<br>\";\n}\n");
    fwrite($backupFile,"while (mysqli_next_result(\$link)) {;} // flush multi_queries\n\n");
	fwrite($backupFile,"echo \"Complete\";\n");
	$resultstatus = "Success";

	}
else
	{
	echo "ERROR: " . mysqli_error($link) . "<BR>";								
	$resultstatus = "Failed: SQL Error";
	}
	 
fwrite($backupFile,"// Close connection\n");
fwrite($backupFile,"mysqli_close(\$link);\n");
fwrite($backupFile,"?");
fwrite($backupFile,">\n");

fclose($backupFile);
	
//header("Access-Control-Allow-Origin: *");
//echo json_encode($rows);
echo "Players saved = $rowcount\n";
echo $resultstatus;

//echo "\n\n<a href='maintainance.php'>Return to Maintainance Page</a>\n";

// Close connection
mysqli_close($link);
?>
