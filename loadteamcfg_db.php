<?php
$uploadOk = 1;

function translateInt($val)
	{
	if ($val == "-1")
		return 'NULL';
	
	$theint = intval($val);
	
	return strval($theint);
	}

//Softball DB
include 'db_setup.php';

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
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
if($imageFileType != "txt" ) 
	{
    echo "Sorry, only txt files are allowed.";
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

// Read File Version
$buffer = fgets($handle, 4096);
$items = explode(",",$buffer);
$file_version = $items[1];

// Read Team Name
$team_name = rtrim(fgets($handle, 4096));

// Read Season Name
$season_name = rtrim(fgets($handle, 4096));

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
	$label_array = array();
	$data_array = array();
	$settarr = array();
	$section = 1;	// consider section 0 as the stuff above
	$stat_game = -1;
	$name_missing = false;

	while (!feof($handle)) // Loop til end of file.
		{
		$buffer = fgets($handle, 4096);
		
		if (strlen($buffer) > 1 )
			{
			$data = false;
			$items = explode(",",$buffer);
			
			if ((substr($items[0],0,13) == "[endsettings]") ||
			    (substr($items[0],0,9) ==  "[players]"))
				{
				$s_inn 		= $settarr["innings game"];
				$s_gamestat	= $settarr['ind game stat'];
				$s_numfield = $settarr['num of field'];
				$s_innscore = $settarr['ind inn scores'];
				$s_hbb		= $settarr['HBB'];
				$s_hk		= $settarr['HK'];
				$s_sac		= $settarr['SAC'];
				$s_sb		= $settarr['SB'];
				$s_cs		= $settarr['CS'];
				$s_hbp		= $settarr['HBP'];
				$s_roe		= $settarr['ROE'];
				$s_er		= $settarr['ER'];
				$s_sv		= $settarr['SV'];
				$s_bs		= $settarr['BS'];
				$s_loc		= $settarr['LOC'];
				$s_time		= $settarr['TIME'];
				$s_gamedet  = $settarr['GDET'];
				$s_gp		= $settarr['GP'];
				$s_lob		= $settarr['LOB'];
				$s_pcount	= $settarr['PCOUNT'];
				$s_batfcd	= $settarr['BATFACE'];
				$s_hall		= $settarr['HALL'];
				$s_wp		= $settarr['WP'];
				$s_ktype	= $settarr['strikeout type'];
				$s_gmtype	= $settarr['GAMETYPE'];
				
				if(count($lvars) == 1)
					{
												//  sid,    Season Name,     team name,inn,game_stat,#field,        league ,group,       year, inn score, hbb,   hk,  sac,steal,   cs,  hbp,  roe,   er,   sv,   bs,  loc, time, gdet,   gp,  lob, pcnt,batfcd,gametype,hall ,   wp,ktype,udpate time
					//$insertSeason   = "INSERT INTO seasons VALUES(default, '$season_name', \"$team_name\", 7,    false,                     10,    false,true,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false, false,   false,false,false,false,CURRENT_TIMESTAMP)";
																  //  sid,    Season Name,      team name,    i                       game_stat,     #field,  inn score,   hbb,   hk,   sac,steal,   cs,
					$insertSeason   = "INSERT INTO seasons VALUES(default, '$season_name', \"$team_name\", \"$league_Type\", null,$seasonYear, $s_inn,$s_gamestat,$s_numfield,$s_innscore,$s_hbb,$s_hk,$s_sac,$s_sb,$s_cs,";
					}
				else
					{
												//  sid,    Season Name,     team name,inn,game_stat,#field,        league ,     grouping,        year, inn score, hbb,   hk,  sac,steal,   cs,  hbp,  roe,   er,   sv,   bs,  loc, time, gdet,   gp,  lob, pcnt,batfcd,gametype,hall ,   wp,ktype,udpate time
					//$insertSeason   = "INSERT INTO seasons VALUES(default, '$season_name', \"$team_name\", 7,    false,                     10,    false,true,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false, false,   false,false,false,false,CURRENT_TIMESTAMP)";
																  //  sid,    Season Name,      team name,    i                       game_stat,     #field,  inn score,   hbb,   hk,   sac,steal,   cs,
					$insertSeason   = "INSERT INTO seasons VALUES(default, '$season_name', \"$team_name\", \"$league_Type\",\"$lvars[1]\", $seasonYear, $s_inn,$s_gamestat,$s_numfield,$s_innscore,$s_hbb,$s_hk,$s_sac,$s_sb,$s_cs,";
					}
								   //																			gametype is wrong in file
								   //  hbp,   roe,   er,   sv,   bs,   loc,   time,      gdet,  gp,   lob,     pcnt     batfcd, gametype,   hall,   wp,   ktype,udpate time
				$insertSeason   .= "$s_hbp,$s_roe,$s_er,$s_sv,$s_bs,$s_loc,$s_time,$s_gamedet,$s_gp,$s_lob,$s_pcount,$s_batfcd,$s_gmtype,$s_hall,$s_wp,$s_ktype,CURRENT_TIMESTAMP)";
				
				if(mysqli_query($link, $insertSeason))
					{
					if ($result2 = mysqli_query($link, $findSeason))
						{
						$rows = mysqli_fetch_array($result2,MYSQLI_BOTH);
						$season = $rows["season_id"];
						$section = $section + 1;
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
			else if ($items[0] != "[settings]")
				{
				if (count($items) > 1)
					{
					$key = $items[0];
					$settarr[$key] = $items[1];
					}
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
			}
		else
			{
			echo "Commit FAILED!!";
			}
		}	
	}

echo "<br><a href=\"loadteamcfg_main.php\">Return to Load Config File Page</a><br>";
echo "<br><a href=\"maintainance.php\">Return to Maintainance File Page</a><br>";
	
fclose($handle);
unlink($target_file);
?>
