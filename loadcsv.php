<?php
include 'db_setup.php';

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if file already exists
if (file_exists($target_file)) 
	{
    echo "File already exists - deleting existing file.<br>";
	unlink($target_file);
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	}

// Allow certain file formats
if($imageFileType != "csv" ) 
	{
    echo "Sorry, only CSV files are allowed.";
    exit();
	}

if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
	{
	echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";
	} 
else 
	{
	echo "Sorry, there was an error uploading your file.<br>";
	exit();
	}
	
$league_value = $_POST["league"];
echo $league_value . "<br>";
$lvars = explode("#",$league_value);

$league_Type = $lvars[0];

$handle = @fopen($target_file, "r");

$items = array();

// Read Team Name
$buffer = fgets($handle, 4096);
$items = explode(",",$buffer);
$team_name = $items[0];

// Read Season Name
$buffer = fgets($handle, 4096);
$items = explode(",",$buffer);
$season_name = $items[0];

// Check if nice_name exists to avoid duplicates
$findSeason   	= "SELECT * FROM seasons WHERE season_name = '$season_name'";
$result = mysqli_query($link, $findSeason);

$expName = explode(" ",$season_name);
$expNum = count($expName);
$seasonYear = $expName[$expNum-1];
echo $seasonYear . "<br>";

$season = 0;
$hittercnt = 0;
$gamecnt = 0;
$pitchercnt = 0;

/* disable autocommit */
mysqli_autocommit($link, FALSE);

if (mysqli_num_rows($result) > 0)
	{
	echo "Season Already Exists " . $season_name;
	exit();
	}
else
	{
	if(count($lvars) == 1)
		{
													//  sid,    Season Name,     team name,    league      ,              year,inn,game_stat,#field,inn score, hbb,   hk,  sac,steal,   cs,  hbp,  roe,   er,   sv,   bs,  loc, time, gdet,   gp,  lob, pcnt,batfcd,gametype,hall ,   wp,ktype,udpate time
		$insertGame   = "INSERT INTO seasons VALUES(default, '$season_name', \"$team_name\", \"$league_Type\",null,$seasonYear, 7,    false,    10,    false,true,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false, false,   false,false,false,false,CURRENT_TIMESTAMP)";
		}
	else
		{
													//  sid,    Season Name,     team name,    league      ,       grouping,       year,inn,game_stat,#field,inn score, hbb,   hk,  sac,steal,   cs,  hbp,  roe,   er,   sv,   bs,  loc, time, gdet,   gp,  lob, pcnt,batfcd,gametype,hall ,   wp,ktype,udpate time
		$insertGame   = "INSERT INTO seasons VALUES(default, '$season_name', \"$team_name\", \"$league_Type\",\"$lvars[1]\",$seasonYear, 7,    false,    10,    false,true,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false, false,   false,false,false,false,CURRENT_TIMESTAMP)";
		}
	if(mysqli_query($link, $insertGame))
		{
		if ($result2 = mysqli_query($link, $findSeason))
			{
			$rows = mysqli_fetch_array($result2,MYSQLI_BOTH);
			$season = $rows["season_id"];			
			}
		else
			{
			echo "ERROR: Could not execute get season. " . mysqli_error($link);
			mysqli_rollback ($link);
			exit();
			}
		}
	else
		{
		echo "ERROR: Could not execute insert season. " . mysqli_error($link);
		mysqli_rollback ($link);
		exit();
		}
	}
													 	
// The Game below is a Dummy game that is the place holder for the whole seasons stats
$insertStatGame   	= "INSERT INTO games VALUES($season,default, '2000-04-01 00:00:00', 'Z', \"Place Holder\", 'N', NULL,false,-1,-1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,CURRENT_TIMESTAMP)";
$findStatGame		= "SELECT * FROM games WHERE season_id = '$season'";
if(mysqli_query($link, $insertStatGame))
	{
	if ($result2 = mysqli_query($link, $findStatGame))
		{
		$rows = mysqli_fetch_array($result2,MYSQLI_BOTH);
		$stat_game = $rows["game_id"];			
		}
	}
else
	{
	echo "<b>ERROR: Could not execute $insertStatGame. ->" . mysqli_error($link) . "</b><br>";
	$uploadOk = 0;
	}
	
$section = 1;	// consider section 1 as the stuff above
$data = false;

while (!feof($handle)) // Loop til end of file.
	{
	$buffer = fgets($handle, 4096);
	
	if (strpos($buffer,",") > 1 )
		{
		$data = false;
		$items = explode(",",$buffer);
		// Section 2 is the hitters
		if ($section == 2)
			{
			$name = $items[0];
			$game = intval($items[1]);
			$atbat = intval($items[2]);
			$runs = intval($items[3]);
			$hits = intval($items[4]);
			$doubles = intval($items[5]);
			$triples = intval($items[6]);
			$homers = intval($items[7]);
			$rbis = intval($items[8]);
			$walks = intval($items[9]);
			
			$appearances = $atbat + $walks;
			$singles = $hits - ($doubles+$triples+$homers);
			
			$sqlline  = "SELECT * FROM players WHERE nickname = '$name'";
			$result = mysqli_query($link, $sqlline);

			if ($result == false)
				{
				echo "Players/Hitters name query failed - $sqlline";
				$uploadOk = 0;
				}
			else if (mysqli_num_rows($result) > 0)
				{
				$prows = mysqli_fetch_array($result,MYSQLI_ASSOC);
				$player_id = $prows["player_id"];
				
								     //   gid,pid,order,sub, pos,g,pa,ab,r,h,1B,2B,3B,HR,rbi,bb,k,ks,kl,sac,sb,cs,hbp,roe
									 //                          gid,pid,order,sub,pos,    g,          pa,     ab,    r,   h,      1B,      2B,      3B,     HR,  rbi,    bb,k,ks,kl,sac,sb,cs,hbp,roe
				$inserthit = "INSERT INTO hitters VALUES($stat_game,$player_id,0,0,NULL,$game,$appearances,$atbat,$runs,$hits,$singles,$doubles,$triples,$homers,$rbis,$walks,0, 0, 0,  0, 0, 0,  0,  0)";
				
				if(mysqli_query($link, $inserthit) == false)
					{
					echo "<b>ERROR: Could not execute $inserthit. ->" . mysqli_error($link) . "</b><br>";
					$uploadOk = 0;
					}
				else
					{
					$hittercnt = $hittercnt + 1;
					}
				}
			else
				{
				echo "Players/Hitters name not found - $name<br>";
				$uploadOk = 0;
				}
			}
		else if ($section == 3)
			{
			$name = $items[0];
			
			if ($name != "Pitcher")
				{
				$win = intval($items[1]);
				$loss = intval($items[2]);
				// position 3 in "PCT" which is calculated
				$IP = intval($items[4]);
				$runs = intval($items[5]);
				// position 6 is the Runs Allowed calculation
				$strikeouts = intval($items[7]);
				
				$games = $win + $loss;
				if ($games == 0)
				{
					$games = 1;
				}
				
				$sqlline  = "SELECT * FROM players WHERE nickname = '" . $name ."'";
				$result = mysqli_query($link, $sqlline);

				if ($result == false)
					{
					echo "Players/Pitchers name query failed - $sqlline";
					$uploadOk = 0;
					}
				else if (mysqli_num_rows($result) > 0)
					{
					$prows = mysqli_fetch_array($result,MYSQLI_ASSOC);
					$player_id = $prows["player_id"];
										 //                    gid,    pid,sub,     g,   w,    l,inn,sv,   ra,er,          k,bb,ha,hbp,wp,hld,batface,pcnt
					$insertpitch = "INSERT INTO pitchers VALUES($stat_game,$player_id,0,$games,$win,$loss,$IP, 0,$runs, 0,$strikeouts, 0, 0,  0, 0,  0,      0,   0)";
					
					if(mysqli_query($link, $insertpitch) == false)
						{
						echo "<b>ERROR: Could not execute $insertpitch. ->" . mysqli_error($link) . "</b><br>";
						$uploadOk = 0;
						}
					else
						{
						$pitchercnt = $pitchercnt + 1;
						}
					}
				else
					{
					echo "Players/Pitcher name not found - $name<br>";
					$uploadOk = 0;
					}
				}
			}
		else if ($section == 4)
			{
			$date = $items[0];
			
			if ($date != "Date")
				{
				$opponent = $items[1];
				$scored = $items[2];
				$allowed = $items[3];
				$result =  $items[4];
				
				$newDate = date("Y-m-d H:i:s", strtotime($date . "-" .$seasonYear));
				$insertGame   	= "INSERT INTO games VALUES($season,default, '$newDate', 'N', \"$opponent\", '$result', NULL,false,$scored,$allowed,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,CURRENT_TIMESTAMP)";
				
				if(mysqli_query($link, $insertGame))
					{
					$gamecnt = $gamecnt + 1;
					}
				else
					{
					echo "<b>ERROR: Could not execute $insertGame. ->" . mysqli_error($link) . "</b><br>";
					$uploadOk = 0;
					}
				}
			}
		else
			{
//			echo $buffer;
			}
		}
	else
		{
		if ($data == false)
			{
			$section = $section + 1;
			$data = true;
			}
		}
	}
	
if ($uploadOk == 0)
	{
	if (mysqli_rollback ($link))
		{
		echo "Data Not Saved";
		}
	else
		{
		echo "Data Rolled Back Failed";
		}
	}
else
	{
	/* commit insert */
	if (mysqli_commit($link))
		{
		echo "<br>**Success**<br>";
		echo $hittercnt . " Hitters Added<br>";
		echo $pitchercnt . " Pitchers Added<br>";
		echo $gamecnt . " Games Added<br>";
		}
	else
		{
		echo "Commit FAILED!!";
		}
	}		
	
echo "<br><a href=\"loadcsv_main.php\">Return to Load CSV Page</a><br>";

fclose($handle);
unlink($target_file);
?>
