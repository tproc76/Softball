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

$hittercnt = 0;
$gamecnt = 0;
$pitchercnt = 0;

/* disable autocommit */
mysqli_autocommit($link, FALSE);

if (mysqli_num_rows($result) == 0)
	{
	echo "Season Does Not Exist " . $season_name;
	exit();
	}
else
	{
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$season = $row['season_id'];
	$label_array = array();
	$data_array = array();
	$settarr = array();
	$section = 3;	// consider section 3 as the stuff because why start at 1 and I could just copy the orginal file.
	$stat_game = -1;
	$name_missing = false;

	while (!feof($handle)) // Loop til end of file.
		{
		$buffer = fgets($handle, 4096);
		
		if (strlen($buffer) > 1 )
			{
			$data = false;
			$items = explode(",",$buffer);
			
			// Section 3 is the game - one line but will come back to
			if ($section == 3)
				{
				if (substr($items[0],0,9) == "[hitters]")
					{
					$label_array = $items;
					$section = $section + 1;
					}
				else if ($items[0] == "[game]")
					{
					$label_array = $items;
					}					
				else
					{
					if (count($items) > 30)
						{
						$date_string = $items[0] . " " . $items[1];
						$newDate = date("Y-m-d H:i:s", strtotime($date_string));
						//                   0        2        3           6                9         11  12  13  14  15  16  17  18  19  20  21  22  23  24  25  26  27  28  29   30   31
						// FILE -   [game],DATE,TIME,LOC,     OPP,RS,RA,STATUS,ERR,ERROPP,LOB,LOBOPP,RS1,RA1,RS2,RA2,RS3,RA3,RS4,RA4,RS5,RA5,RS6,RA6,RS7,RA7,RS8,RA8,RS9,RA9,RSEX,RAEX,GDET,
						$loc = $items[2];
						$opp = $items[3];
						$RS  = $items[4];
						$RA  = $items[5];
						$result = $items[6];
						$errors = translateInt($items[7]);
						$opperr = translateInt($items[8]);
						$lob = translateInt($items[9]);
						$opplob = translateInt($items[10]);
						$rs1 = translateInt($items[11]);
						$rs2 = translateInt($items[13]);
						$rs3 = translateInt($items[15]);
						$rs4 = translateInt($items[17]);
						$rs5 = translateInt($items[19]);
						$rs6 = translateInt($items[21]);
						$rs7 = translateInt($items[23]);
						$rs8 = translateInt($items[25]);
						$rs9 = translateInt($items[27]);
						$rsex= translateInt($items[29]);
						$ra1 = translateInt($items[12]);
						$ra2 = translateInt($items[14]);
						$ra3 = translateInt($items[16]);
						$ra4 = translateInt($items[18]);
						$ra5 = translateInt($items[20]);
						$ra6 = translateInt($items[22]);
						$ra7 = translateInt($items[24]);
						$ra8 = translateInt($items[26]);
						$ra9 = translateInt($items[28]);
						$raex= translateInt($items[30]);
						$gdetails = NULL;
						if (strlen($items[31]) > 1)
							$gdetails = $items[31];
						$tourn = false;
						if (strlen($items[32]) > 1)
							{
							if($items[32] == "Tourn")
								$tourn = true;
							}								 //DB - season_id,game_id,   datetime,loc,opponent,   result,      details,tournament,run_scored,run_allowed,rs1-9,rsex,lob,error,ra1-9,raex, opplob, opperr,updated
						$insertGame   	=  "INSERT INTO games VALUES($season,default, '$newDate','$loc',\"$opp\",'$result',\"$gdetails\",";
						if($tourn==true)
							{
							$insertGame   	.= "true,";
							}
						else
							{
							$insertGame   	.= "false,";
							}
							
						$insertGame   	.= "$RS,$RA,$rs1,$rs2,$rs3,$rs4,$rs5,$rs6,$rs7,$rs8,$rs9,$rsex,$lob,$errors,";
						$insertGame   	.= "$ra1,$ra2,$ra3,$ra4,$ra5,$ra6,$ra7,$ra8,$ra9,$raex,$opplob,$opperr,CURRENT_TIMESTAMP)";
						
						$findStatGame		= "SELECT * FROM games WHERE season_id = '$season' AND game_datetime = '$newDate'";
						
						// Check if a game already exists at this time.
						if ($result3 = mysqli_query($link, $findStatGame))
							{
							if (mysqli_num_rows($result3) == 0)
								{						
								if(mysqli_query($link, $insertGame))
									{							
									if ($result2 = mysqli_query($link, $findStatGame))
										{
										if (mysqli_num_rows($result2) > 0)
											{
											$rows = mysqli_fetch_array($result2,MYSQLI_BOTH);
											
											$stat_game = $rows["game_id"];

											$gamecnt = $gamecnt + 1;
											}
										else
											{
											echo "<b>ERROR: Could not find matches to query -> $findStatGame.</b><br>";
											$uploadOk = 0;
											}
										}
									else
										{
										echo "<b>ERROR: Could not execute $findStatGame. ->" . mysqli_error($link) . "</b><br>";
										$uploadOk = 0;
										}							
									}
								else
									{
									echo "<b>ERROR: Could not execute $insertGame. ->" . mysqli_error($link) . "</b><br>";
									$uploadOk = 0;
									}
								}
							else
								{
								echo "<b>ERROR: Game already exists for this season at that Date/Time. ->" . mysqli_error($link) . "</b><br>";
								$uploadOk = 0;
								}
							}
						else
							{
							echo "<b>ERROR: Could not execute $findStatGame. ->" . mysqli_error($link) . "</b><br>";
							$uploadOk = 0;
							}							
						}
					}
				}
			// Section 4 is the hitters - maybe need to store off array in previous step
			else if ($section == 4)
				{
				if (substr($items[0],0,10) == "[pitchers]")
					{
					$section = $section + 1;
					}
				else if (count($items) > 21)
					{
					$xp = 0;
					//               0      1   2   3 4  5  6 7 8  9    11     13    15   17     19    21  22
					// FILE - [hitters],order,sub,pos,G,PA,AB,R,H,1B,2B,3B,HR,RBI,BB,K,KS,KL,SAC,SB,CS,HBP,ROE,
					// TODO Have Nulls on items that are disabled? - maybe in translateInt()?
					$name = $items[0];
					$order = $items[1];
					$sub = $items[2];
					$pos = "NULL";
					if ($items[3] != "NA")
						$pos = $items[3];
					$xp = 4;
					$games = translateInt($items[$xp++]);
					if ($label_array[5]=="PA")
						$appearances = translateInt($items[$xp++]);
					else
						$appearances = -1;
					$atbat = translateInt($items[$xp++]);
					$runs = translateInt($items[$xp++]);
					$hits = translateInt($items[$xp++]);
					$singles = translateInt($items[$xp++]);
					$doubles = translateInt($items[$xp++]);
					$triples = translateInt($items[$xp++]);
					$homers = translateInt($items[$xp++]);
					$rbis = translateInt($items[$xp++]);
					$walks = translateInt($items[$xp++]);
					$strikeout = translateInt($items[$xp++]);
					$kswing = translateInt($items[$xp++]);
					$klooking = translateInt($items[$xp++]);
					$sacs = translateInt($items[$xp++]);
					$steals = translateInt($items[$xp++]);
					$caught = translateInt($items[$xp++]);
					$hbp = translateInt($items[$xp++]);
					$roe = translateInt($items[$xp++]);
					if ($appearances==-1)
						$appearances = $atbat + $walks + $sacs;
					
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
											 //                          gid,      pid, order, sub, pos,     g,          pa,    ab,    r,    h,      1B,      2B,      3B,     HR,  rbi,    bb,         k,     ks,       kl,  sac,     sb,     cs, hbp,roe
						$inserthit = "INSERT INTO hitters VALUES($stat_game,$player_id,$order,$sub,$pos,$games,$appearances,$atbat,$runs,$hits,$singles,$doubles,$triples,$homers,$rbis,$walks,$strikeout,$kswing,$klooking,$sacs,$steals,$caught,$hbp,$roe)";
						
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
				}
			else if ($section == 5)
				{			
				if (substr($items[0],0,6) == "[game]")
					{
					$section = 3;
					}
				else if (substr($items[0],0,10) == "[endgames]")
					{
					$section = $section + 1;
					}
				else
					{
						//          0   ,  1,2,3,4,  5, 6,   8   10    12      14             16   17
					// FILE - [pitchers],sub,G,W,L,INN,SV,R,ER,K,BB,H,HBP,WP,HOLD,BATFACE,PCOUNT,HALL,

					// TODO Have Nulls on items that are disabled? - maybe in translateInt()?
					$name = $items[0];
					$sub = translateInt($items[1]);
					$games = translateInt($items[2]);
					$win = translateInt($items[3]);
					$loss = translateInt($items[4]);
					$IP = translateInt($items[5]);
					$saves = translateInt($items[6]);
					$runs = translateInt($items[7]);
					$earned = translateInt($items[8]);
					$strikeouts = translateInt($items[9]);
					$walks = translateInt($items[10]);
					$wild  = translateInt($items[11]);
					$hbp = translateInt($items[12]);
					$hold  = translateInt($items[14]);
					$batface  = translateInt($items[15]);
					$pcount  = translateInt($items[16]);
					$hits = translateInt($items[17]);
									
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
																//           gid,    pid,    sub,     g,   w,    l,inn,    sv,   ra,     er,          k,    bb,   ha, hbp,   wp,  hld,batface,pcnt
						$insertpitch = "INSERT INTO pitchers VALUES($stat_game,$player_id,$sub,$games,$win,$loss,$IP,$saves,$runs,$earned,$strikeouts,$walks,$hits,$hbp,$wild,$hold,$batface,$pcount)";
						
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
			else if ($section != 10)
				{
//				echo "extra line read $$buffer. <br>";
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
			echo $hittercnt . " Hitters Added (each game is 1 per player)<br>";
			echo $pitchercnt . " Pitchers Added (each game is 1 per player)<br>";
			echo $gamecnt . " Games Added<br><br>";
			echo "Send Stat Email <a href='stat_email.php?seasonid=$season'>Click to Send<a><br>";
			}
		else
			{
			echo "Commit FAILED!!";
			}
		}	
	}

echo "<br><a href=\"loadgamefile_main.php\">Return to Load Game File Page</a><br>";
	
fclose($handle);
unlink($target_file);
?>
