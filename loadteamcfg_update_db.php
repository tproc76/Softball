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

/* disable autocommit */
mysqli_autocommit($link, FALSE);

if (mysqli_num_rows($result) == 0)
	{
	echo "Season Not Found " . $season_name;
	exit();
	}
	
$rows = mysqli_fetch_array($result,MYSQLI_BOTH);
	
if ($team_name != $rows["team_name"])
	{
	echo "Team Name in file ". $team_name . " did  NOT match DB name " . $rows["team_name"];
	exit();	
	}

$settarr = array();
$section = 1;	// consider section 0 as the stuff above

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
			
			
			$updateSeason = "UPDATE seasons SET innings_game=$s_inn,ind_game_stat=$s_gamestat,num_fielders=$s_numfield,ind_inning_score=$s_innscore,hit_walk=$s_hbb,hit_k=$s_hk,sac=$s_sac,";
			$updateSeason .= "Steals=$s_sb,caught_steal=$s_cs,hbp=$s_hbp,roe=$s_roe,earned_runs=$s_er,saves=$s_sv,blown_saves=$s_bs,location=$s_loc,time=$s_time,game_details=$s_gamedet,";
			$updateSeason .= "game_pitched=$s_gp,lob=$s_lob,pitch_count=$s_pcount,bat_faced=$s_batfcd,game_type=$s_gmtype,hits_allow=$s_hall,wild_pitch=$s_wp,strikeout_type=$s_ktype";
						
			if(mysqli_query($link, $updateSeason))
				{
				$section = $section + 1;
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


echo "<br><a href=\"loadteamcfg_update.php\">Return to Update Config File Page</a><br>";
echo "<br><a href=\"maintainance.php\">Return to Maintainance File Page</a><br>";
	
fclose($handle);
unlink($target_file);
?>
