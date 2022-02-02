<?php
	session_start();
?>				
<!DOCTYPE html>
<html>

    <head>
        <title>Attendance Page</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="softball,proctor" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        
		<style type="text/css">
		   .centerText{
			   text-align: center;
			}
		</style>
		
		<script type="text/javascript" src="sorttable.js"></script>
		<script type="text/javascript" src="softball_standard.js"></script>
		<script type="text/javascript" src="attend_login.js"></script>
		<script type="text/javaScript">
			var currentPlayer_Id = <?php if(isset($_SESSION['player_id']) == true) { echo $_SESSION['player_id']; } else { echo -1; } ?>;
			var currentPlayer_Name = "<?php if(isset($_SESSION['full_name']) == true) {  echo $_SESSION['full_name']; } else { echo ""; } ?>";
			var activePlayer_Id = <?php if(isset($_SESSION['player_id']) == true) { echo $_SESSION['player_id']; } else { echo -1; } ?>;
			var updateActive = false;
			
			var gameNum = [];
			var lockedGame = [];
			var playerIdRow = [];
			var currentTeam = -1;

			function updateAttendance(player_id)
				{
				if (activePlayer_Id != -1)
					{
					var oldRow = document.getElementById('row_'+activePlayer_Id);
					oldRow.bgColor="";
					}
				var theRow = document.getElementById('row_'+player_id);
				theRow.bgColor="#00F8F8";
				
				activePlayer_Id = player_id;
				}
				
			function changeAttend(player_id,game_id,column_id,team_id,locked)
				{
				if (locked == true)
					{
					return;
					}
					
				if (player_id != activePlayer_Id)
					{
					return;
					}
					
				if (updateActive==true)
					{
					return;
					}
					
				updateActive = true;
					
				var theImage = document.getElementById('img_'+player_id+'_'+column_id);
				var theRef = document.getElementById('ref_'+player_id+'_'+column_id);
				var cntYes =  document.getElementById('yes_'+column_id);
				var cntNo  =  document.getElementById('no_'+column_id);
				var cntMay =  document.getElementById('maybe_'+column_id);
				var cntUn  =  document.getElementById('un_'+column_id);
				var count  = 0;
				var addNote = "";
				
				var oldCount;
				var newCount;
				var newSource;
				var newAlt;
				
				if (player_id != currentPlayer_Id)
					{
					addNote = "Updated by " + currentPlayer_Name;
					}				
				
				if (theImage.alt=='Yes')
					{						
					oldCount  = cntYes;
					newSource = 'img/blokkade-small-t2.png';
					newAlt    = 'No';
					newCount  = cntNo;
					}
				else if (theImage.alt=='No')
					{
					oldCount  = cntNo;
					newSource = 'img/sad-face-with-watch.gif';
					newAlt    = 'Late';
					newCount  = cntMay;
					}
				else if (theImage.alt=='Late')
					{						
					oldCount  = cntMay;
					newSource = 'img/yellowquestionmark-small-t.png';
					newAlt    = 'Unsure';
					newCount  = cntMay;
					}
				else
					{
					if (theImage.alt=='Unsure')
						{
						oldCount  = cntMay;
						}
					else if (theImage.alt=='?Unanswered?')
						{
						oldCount  = cntUn;
						}
					newSource = 'img/greencheck-small-t.png';
					newAlt    = 'Yes';
					newCount  = cntYes;
					}
					
				$.ajax
					({
					type:'post',
					url:'attend_members_db.php',
					data:
						{
						update_game:'game',
						player_id:player_id,
						game_id:game_id,
						status:newAlt,
						note:addNote
						},
					success:function(response) 
						{
						if (response != "success")
							{
							alert(response);
							}
						count = parseInt(oldCount.innerHTML);
						count = count - 1;
						oldCount.innerHTML = count;
						theImage.src = newSource;
						theImage.alt = newAlt;
						count = parseInt(newCount.innerHTML);
						count = count + 1;
						newCount.innerHTML = count;
						
						if (addNote=="")
							{
							theRef.innerHTML = "";
							theRef.name = "";
							}
						else
							{
							theRef.innerHTML = "*";
							theRef.name = addNote;
							}
						updateActive = false;
						},
					  error:function(jqXHR, textStatus, errorThrown) 
					    {
						alert(errorThrown);
						updateActive = false;
					    }
					});
				}
				
			function sendAttendEmail(teamid,gameid)
				{
				event.preventDefault();
				var XMLHttpRequestObject = false;
				XMLHttpRequestObject = new XMLHttpRequest();
				
				if (XMLHttpRequestObject) 
					{
					XMLHttpRequestObject.open("GET","http://"+ WEBPATH +"/attend_game_email.php?teamid=" + teamid + "&gameid=" + gameid);
				
					XMLHttpRequestObject.onreadystatechange=function()
						{
						if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
							{
							var result = XMLHttpRequestObject.responseText.split(":");
							
							if (result[0] == "success")
								{
								alert("Email Sent");
								}
							else
								{
								alert(XMLHttpRequestObject.responseText);
								}
				
							delete XMLHttpRequestObject;
							XMLHttpRequestObject = null;					
							} 
						}
					XMLHttpRequestObject.send();
					}
				}
				
			function updateAccount()
				{
				var fname = document.getElementById('in_name').value;
				var pwd1 = document.getElementById('in_pass1').value;
				var pwd2 = document.getElementById('in_pass2').value;
				var hash = SHA256(pwd1);
				var pchange = false;
				
				if (fname.length < 1)
					{
					alert("Full Name is too short");
					return;
					}
					
				if (pwd1!=pwd2)
					{
					alert("Passwords do not match");
					return;
					}
					
				if (pwd1.length>0)
					{
					pchange = true;
					}
					
				$.ajax
					({
					type:'post',
					url:'attend_members_db.php',
					data:
						{
						update_player:'update',
						fname:fname,
						pup:pchange,
						pwdhash:hash
						},
					success:function(response) 
						{
						var rets = response.split(":");
						if (rets[0] == "success")
							{
							$(location).attr('href', 'attendance_home.php?display=teams');
							}
						else
							{
							alert(response);
							}
						},
					  error:function(jqXHR, textStatus, errorThrown) 
					    {
						alert(errorThrown);
					    }
					});
					
				}
				
			function displayNote(playerId,columnId)
				{
				var theRef = document.getElementById('ref_'+playerId+'_'+columnId);
				if (theRef != null)
					{
					alert(theRef.name);
					}
				}
				
			function setComment(teamid,columnId)
				{
				var gid = gameNum[columnId];
				var lock = lockedGame[columnId];
				var theComment = document.getElementById('ref_'+activePlayer_Id+'_'+columnId);
					
				// should not ever happen, but just in case
				if (lock == true)
					return;
				
				if (theComment == null)
					{
					alert("Comment is NULL");
					return
					}
				
				var returnVal = prompt("Add Comment",theComment.name);
				
				if (returnVal != null)
					{
					$.ajax
						({
						type:'post',
						url:'attend_members_db.php',
						data:
							{
							update_comment:'comment',
							player_id:activePlayer_Id,
							game_id:gid,
							note:returnVal
							},
						success:function(response) 
							{
							if (response == "success")
								{
								if (returnVal=="")
									{
									theComment.innerHTML = "";
									theComment.name = "";
									}
								else
									{
									theComment.innerHTML = "*";
									theComment.name = returnVal;
									}
								}
							else
								{
								alert(response);
								}
							},
						  error:function(jqXHR, textStatus, errorThrown) 
							{
							alert(errorThrown);
							}
						});
					}				
				}
				
			function moveGames(value)
				{
				alterURLParameters("startgame",value);
				}
		</script>
    </head>
    <!-- slide-toggle-menu -->
    <body>
        <!--top-header-->
		<div class="row justify-content-md-center">
			<h1 id="attendheader">Team Attendance Page</h1>
			<BR><BR>
		</div>	
		
		<div class="row justify-content-md-center">
			<table width="80%" border='0' cellspacing='1' cellpadding='10' class='center' id='headertable'>
				<col width="20%">
				<col width="20%">
				<col width="20%">
				<col width="20%">
				<col width="20%">
				<tr>
					<td></td>
					<td style='text-align:center'><a href="attendance_home.php?display=teams">Home</a></td>
					<td style='text-align:center'><a href="attendance_home.php?display=attend">Team Attendance</a></td>
					<td style='text-align:center'><a href="attendance_home.php?display=account">Account</a></td>
					<td style='text-align:center'><a href="attend_logout.php">Log Out</a></td>
				</tr>
			</table>
		</div>
		
		<br>

			<?php
				include 'db_setup.php';
			
				if(isset($_SESSION['login_user']) == false)
					{
					// redirect to login if not logged in
					echo '<script type="text/javascript">window.location = "attend_login.html"</script>';
					exit();
					}
				
				$username = $_SESSION['login_user'];
				$player_id = $_SESSION['player_id'];
				
				if (isset($_GET["display"]) == false)
					{
					echo '<script type="text/javascript">window.location = "attendance_home.php?display=teams"</script>';
					exit();
					}
				?>
				
		<!-- Outer Table -->
		<div class="row justify-content-md-center">
			<table width="90%" border='0' cellspacing='1' cellpadding='10' class='center' id='headertable'>
				<col width="20%">
				<col width="60%">
				<col width="20%">
				<tr><td style="vertical-align:top">
				
			<?php
			
			$teamRole = 'X';
			$teamid = -1;
			
			if(isset($_GET['team']))
				{
				$teamid = $_GET["team"];
				$_SESSION['team_id'] = $teamid;
				}
			else if (isset($_SESSION['team_id']))
				{
				$teamid = $_SESSION['team_id'];
				}
				
			if ($teamid > -1)
				{					
				echo "\n<script type='text/javaScript'>currentTeam=$teamid;</script>\n";
				
				$findRole = "SELECT * FROM attend_members WHERE player_id = '$player_id' AND team_id = '$teamid'";
				if ($result = mysqli_query($link, $findRole) )
					{
					$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
											
					if ($row['role'] == 'M')
						{
						$teamRole = 'M';
						}
					
					$findStatsTeam = "SELECT at.stats_team_id, at.team_name, ss.season_name FROM attend_team at LEFT JOIN seasons ss ON at.stats_team_id=ss.season_id WHERE at.team_id = '$teamid'";
					if ($result = mysqli_query($link, $findStatsTeam))
						{
						if($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
							$seasonName = $row['season_name'];
													
							if ($seasonName!=null)
								{
								echo '<a href="season.php?season=' . $seasonName .'">Team Stats Pages</a><br>';
								}
							else
								{
								echo '<a href="softball_home.php">Team Stats Pages</a><br>';				
								}
							}
						else
							{
							echo '<a href="softball_home.php">Team Stats Pages</a><br>';				
							}
						}
					}
					
				$findActiveTeam = "SELECT * FROM attend_team WHERE team_id = '$teamid'";

				if ($result = mysqli_query($link, $findActiveTeam) )
					{					
					$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
					echo '<b>Active Team = ' . $row['team_name'] .  '</b><br>';
					}
										
				if ($teamRole == 'M')
					{
					echo '<BR><U>Manager Stuff</U><br>';
					echo '<a href="attend_games_main.php?team=' . $teamid . '">Update Team Games</a><br>';
					// might need a flag to show edit buttons on all players, except current player - normal state only show buttons for this player???
					echo '<a href="attend_members_main.php?team=' . $teamid . '">Update Team Members</a><br>';
					echo '<a href="attend_team_main.php">Update Team Settings</a><br><br>';
					echo '<a href="attend_players_contact_main.php">Player Contact Info</a><br>';
					}
				$_SESSION['role'] = $teamRole;
				}
			else
				{
				echo '<a href="softball_home.php">Team Stats Pages</a><br>';				
				}				
			?>
			
				</td>
				
			<?php
				$display = $_GET["display"];
				
				if ($display=="teams")
					{
					$table = '<td><div class="row justify-content-md-center">';
					$table .= '<table id="edittable" border="0" cellspacing="1" cellpadding="10" class="center">';
					$table .= '<tr><th><h4><u>Current Teams</u>';
					$table .= '</h4></th></tr>';	
					$table .= '<tr></tr>';	
				
					$findTeams = "SELECT * FROM attend_members m JOIN attend_team t ON m.team_id=t.team_id WHERE player_id = '$player_id'";

					if ($result = mysqli_query($link, $findTeams) )
						{					
						while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
							$teamid = $row['team_id'];
							$table .= '<tr id="row' . $teamid . '">';
							$table .= '<td id="team' . $teamid . '"><h4><a href="attendance_home.php?display=attend&team=' . $teamid . '">' . $row["team_name"] . ' (' . $row["season"] .')</a></h4></td>';
							$table .= '</tr><tr></tr>';
							}
						}
					$table .= '</table></div></td><td></td>';
					}
				else if ($display=="attend")
					{
					$showallgames = false;
					$displayGames = 5;
					$startGame = 0;

					if(isset($_GET['startgame']))
						{
						$startGame = intval($_GET['startgame']);
						}
					$endGame = $startGame+$displayGames;
						
					if(isset($_GET['team']))
						{
						$teamid = $_GET["team"];
						$_SESSION['team_id'] = $teamid;
						}
					else if (isset($_SESSION['team_id']))
						{
						$teamid = $_SESSION['team_id'];
						}
						
					$findGames = "SELECT * FROM attend_games g JOIN attend_team t ON g.team_id=t.team_id WHERE g.team_id = '$teamid' AND DATEDIFF(game_datetime,CURDATE())>=0 ORDER BY game_datetime";
						
					$gameNums = array();
					$lockGame = array();
					$attYes = array();
					$attNo = array();
					$attMaybe = array();
					$attUn = array();
					$lockouthours = $row['lockouthours'];
					
					$table = '<td colspan=2><table id="edittable" border="1" cellspacing="1" cellpadding="5">';

					if(isset($_GET['showall']))
						{
						$findGames = "SELECT * FROM attend_games WHERE team_id = '$teamid' ORDER BY game_datetime";
						$showallgames = true;
						$table .= '<a href="attendance_home.php?display=attend">Show Only Future Games</a><br>';
						}
					else
						{
						$table .= '<a href="attendance_home.php?display=attend&showall">Show All Team Games</a><br>';						
						}
						
					echo "\n";
						
					if ($result = mysqli_query($link, $findGames) )
						{
						$numgames = mysqli_num_rows($result);
						if ( $numgames > 0 )
							{
							$decGame = $startGame - $displayGames;
							$incGame = $startGame + $displayGames;
							if ($decGame < 0)
								$decGame = 0;
							
							//<button type='button' onclick='moveDisplayGames(0)'><-</button>
							$backLink = "<td rowspan='100%'><button type='button' onclick='moveGames($decGame)'><=</button></td>";
							$foreLink = "<td rowspan='100%'><button type='button' onclick='moveGames($incGame)'>=></button></td>";
							
							if ($endGame > $numgames)
								{
								$endGame = $numgames;
								}
								
							if ($startGame == 0)
								{
								$backLink = "";
								}
							if ($endGame == $numgames)
								{
								$foreLink = "";
								}
								
							$table .= '<tr>';
							$table .= $backLink;
							if ($teamRole == 'M')
								{
								$table .= '<td>Edit</td><td>Player</td>';
								}
							else
								{
								$table .= '<td>Player</td>';
								}
							$cnt = 0;
							$datenow = new DateTime();
							$datenow->setTimezone(new DateTimeZone('America/Detroit'));
							$todaystringz = $datenow->format('Y-m-d H:i:s');
							$today = strtotime($todaystringz);							
							
							while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
								{
								// Keep these at the top as we will store this for every game even if not displayed
								$gamedate = $row['game_datetime'];
								$gamedatetz = new DateTime($gamedate,new DateTimeZone('America/Detroit'));
								$gamedatestringz = $gamedatetz->format('Y-m-d H:i:s');
								$interval = strtotime($gamedatestringz) - $today;
								$intervalHours = $interval/(60*60);
								
								$lockGame[$cnt] = true;
								if($intervalHours > $lockouthours)
									$lockGame[$cnt] = false;
								$gameNums[$cnt] = $row['game_id'];
								$attYes[$cnt] 	= 0;
								$attNo[$cnt]    = 0;
								$attMaybe[$cnt] = 0;
								$attUn[$cnt]    = 0;
								
								if (($cnt >= $startGame) && ($cnt < $endGame))
									{									
									$table .= '<td style="text-align:center">';
									$datetime = date_create($row['game_datetime']);
									$table .= date_format($datetime,"M-d-Y G:i");
									$table .= '<br>';
									$table .= $row['opponent'];
									$table .= '<br>';
									$table .= $row['location'];
									$table .= '<br>';
									
									// Only update allow comments when the game is not locked to prevent late updates
									if ($lockGame[$cnt] == false)
										{
										$table .= "<button type='button' onclick='setComment($teamid,$cnt)'>Add Comment</button>";
										}
									$table .= '</td>';
									
									if($intervalHours > $lockouthours)
										echo "<script type='text/javaScript'>gameNum.push($gameNums[$cnt]);lockedGame.push(false);</script>\n";
									else
										echo "<script type='text/javaScript'>gameNum.push($gameNums[$cnt]);lockedGame.push(true);</script>\n";
									}								
								$cnt = $cnt + 1;
								}
							$table .= $foreLink;
							$table .= '</tr>';
								
							$getTeamMembers = "SELECT * FROM attend_members m JOIN attend_players p ON m.player_id=p.player_id WHERE team_id = '$teamid'";
							if ($result = mysqli_query($link, $getTeamMembers) )
								{
								$playerLine = "";
								$teamEntries = "";
								
								while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
									{
									$thisLine = "";
									$pid = $row['player_id'];
									
									echo "<script type='text/javaScript'>playerIdRow.push($pid)</script>\n";
									
									$thisLine .= '<tr id="row_' . $pid;
									if ($pid==$player_id )
										{
										$thisLine .= '" bgColor="#00F8F8">';
										}
									else
										{
										$thisLine .= '">';
										}
										
									// insert button - need to pass in player ID... probably need row or cell to be named for updating purposes
									if ($teamRole == 'M')
										{
										$thisLine .= '<td><button type="button" onclick="updateAttendance(' . $pid .')">Edit</button></td>';
										}
										
									$thisLine .= '<td>';
									$thisLine .= $row['name'];
									if ($row['role'] == 'M')
										{
										$thisLine .= " (M)";
										}
									else if ($row['role'] == 'S')
										{
										$thisLine .= " (S)";
										}
									$thisLine .= '</td>';
																		
									$getPlayerAttendance = "SELECT * FROM attendance t JOIN attend_games g ON t.game_id=g.game_id WHERE g.team_id='$teamid' AND t.player_id='$pid' AND DATEDIFF(game_datetime,CURDATE())>=0 ORDER BY game_datetime";
									
									if ($showallgames==true)
										{
										$getPlayerAttendance = "SELECT * FROM attendance t JOIN attend_games g ON t.game_id=g.game_id WHERE g.team_id='$teamid' AND t.player_id='$pid' ORDER BY game_datetime";
										}
									
									if ($result2 = mysqli_query($link, $getPlayerAttendance) )
										{
										$row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);
										for ($col = 0; $col < $numgames; $col++)
											{
											$game_id = $gameNums[$col];
											$lock = $lockGame[$col];
											$asterisk = "";
											
											if ($gameNums[$col] == $row2['game_id'])
												{
												// Only display the correct range of games
												if (($col >= $startGame) && ($col < $endGame))
													{
													$thisnote = $row2['notes'];
													if ($thisnote==null)
														$asterisk = "";
													else
														$asterisk = "*";
													
													if ($row2['attending']=='Y')
														{
														$thisLine .= "<td style='text-align:center'><h2><img src='img/greencheck-small-t.png' id='img_$pid"."_"."$col' alt='Yes' height='40' width='40'>";
														$thisLine .= "<a href='javascript:void(0);' id='ref_$pid"."_"."$col' onclick='displayNote($pid,$col)' name=\"$thisnote\">$asterisk</a></h2></td>";
														$attYes[$col] = $attYes[$col] + 1;
														}
													else if ($row2['attending']=='N')
														{
														$thisLine .= "<td style='text-align:center'><h2><img src='img/blokkade-small-t2.png' id='img_$pid"."_"."$col' alt='No' height='40' width='40'>";
														$thisLine .= "<a href='javascript:void(0);' id='ref_$pid"."_"."$col' onclick='displayNote($pid,$col)' name=\"$thisnote\">$asterisk</a></h2></td>";
														$attNo[$col] = $attNo[$col] + 1;
														}
													else if ($row2['attending']=='?')
														{
														$thisLine .= "<td style='text-align:center'><h2><img src='img/yellowquestionmark-small-t.png' id='img_$pid"."_"."$col' alt='Unsure' height='40' width='40'>";
														$thisLine .= "<a href='javascript:void(0);' id='ref_$pid"."_"."$col' onclick='displayNote($pid,$col)' name=\"$thisnote\">$asterisk</a></h2></td>";
														$attMaybe[$col] = $attMaybe[$col] + 1;
														}
													else if ($row2['attending']=='L')
														{
														$thisLine .= "<td style='text-align:center'><h2><img src='img/sad-face-with-watch.gif' id='img_$pid"."_"."$col' alt='Late' height='40' width='40'>";
														$thisLine .= "<a href='javascript:void(0);' id='ref_$pid"."_"."$col' onclick='displayNote($pid,$col)' name=\"$thisnote\">$asterisk</a></h2></td>";
														$attMaybe[$col] = $attMaybe[$col] + 1;
														}
													}
												$row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);
												}
											else
												{
												// Only display the correct range of games
												if (($col >= $startGame) && ($col < $endGame))
													{
													$thisLine .= "<td style='text-align:center'><h2><img src='img/bench-small-t.png' id='img_$pid"."_"."$col' alt='?Unanswered?' height='40' width='40'>";
													$thisLine .= "<a href='javascript:void(0);' id='ref_$pid"."_"."$col' onclick='displayNote($pid,$col)' name=\"\">$asterisk</a></h2></td>";
													$attUn[$col] = $attUn[$col] + 1;
													}													
												}
											}
										}
									else
										{
										echo "ERROR: Could not execute. " . mysqli_error($link) . "<BR>";
										}
									$thisLine .= "</tr>\n";
									
									if ($pid == $_SESSION['player_id'])
										{
										$playerLine = $thisLine;
										}
									else
										{
										$teamEntries .= $thisLine;
										}
									}
								}
								
							$table .= $playerLine;
							$table .= $teamEntries;

							if ($teamRole == 'M')
								{
								$rowStart = "<tr><td></td><td>";
								}
							else
								{
								$rowStart = "<tr><td>";
								}
								
//									if (($cnt >= $startGame) && ($cnt < ($startGame+$displayGames)))
												
							$table .= $rowStart . "Yes</td>";
							for ($col = $startGame; $col < $endGame; $col++)
								{
								$table .= "<td style='text-align:center' id='yes_$col'>$attYes[$col]</td>";
								}
							$table .= "</tr>" . $rowStart . "No</td>";
							for ($col = $startGame; $col < $endGame; $col++)
								{
								$table .= "<td style='text-align:center' id='no_$col'>$attNo[$col]</td>";
								}
							$table .= "</tr>" . $rowStart . "Unsure</td>";
							for ($col = $startGame; $col < $endGame; $col++)
								{
								$table .= "<td style='text-align:center' id='maybe_$col'>$attMaybe[$col]</td>";
								}
							$table .= "</tr>" . $rowStart . "No Reply</td>";
							for ($col = $startGame; $col < $endGame; $col++)
								{
								$table .= "<td style='text-align:center' id='un_$col'>$attUn[$col]</td>";
								}
							if ($teamRole == 'M')
								{
								$table .= "</tr>" . $rowStart . "</td>";
								for ($col = $startGame; $col < $endGame; $col++)
									{
									$table .= "<td style='text-align:center' id='btn_$col'><button type='button' onclick='sendAttendEmail($teamid,$gameNums[$col])'>Send Team Email</button>";
									$table .= "<br><a href='attend_game_email_screen.php?teamid=$teamid&gameNums=$gameNums[$col]'>Show Email</a>";
									$table .= "</td>";
									}
								}
							$table .= "</tr></table></div>";
							
							$table .= "<br>(M) = Manager<br>(S) = Sub<br>";

							}
						else
							{
							$table .= '<tr><td style="text-align:center">No Games Currently Planned</td><tr></table></td>';
							}
						}
					}
				else if ($display=="account")
					{
					$getPlayer = "SELECT * FROM attend_players WHERE player_id = '$player_id'";
					
					if ($result = mysqli_query($link, $getPlayer) )
						{
						$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
						
						$table = '<div class="row justify-content-md-center">';
						$table .= '<table id="edittable" border="1" cellspacing="1" cellpadding="20" class="center">';
						$table .= '<tr id="row_email"><td id="lbl_email">Email/UserID</td><td id="disp_email">' . $username . '</td></tr>';
						$table .= '<tr id="row_fullname"><td id="lbl_fullname">Full Name</td><td id="fullname"><input type="text" name="fname" id="in_name" value="' . $row['name'] . '"></td></tr>';
						$table .= '<tr id="row_password"><td id="lbl_password">Change Password</td><td id="input_pass"><input type="password" name="pass" id="in_pass1"><br><br>';
						$table .= '<input type="password" name="pass" id="in_pass2"></td></tr>';
						$table .= '<tr><td colspan="2" style="text-align:center"><button type="button" onclick="updateAccount()">Save Changes</button>';
						$table .= '</table></div>';
						}
					}
					
				echo $table;
			?>		
			</tr>
		</table>
		<BR><BR>
						
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="dist-js/popper.min.js"></script>
		<script type="text/javascript" src="dist-js/tether.min.js"></script>
<!--		<script type="text/javascript" src="dist-js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    -->    
		<script type="text/javaScript">
		
			function handlerFunction ()
				{
				var startgameoffset = getQueryVariable("startgame");
				var sgoffset = 0;
				if (startgameoffset != null)
					sgoffset = parseInt(startgameoffset);
				var theImage = this;
				var imgIdParts = theImage.id.split("_");
				var pid = imgIdParts[1];
				var col = imgIdParts[2];
				var gid = gameNum[col-sgoffset];
				var lock = lockedGame[col-sgoffset];
				
				changeAttend(pid,gid,col,currentTeam,lock);
				
				return false;
				}
											
			window.addEventListener('load', function()
					{              
					var startgameoffset = getQueryVariable("startgame");
					var sgoffset = 0;
					if (startgameoffset != null)
						sgoffset = parseInt(startgameoffset);
					
					for (var row = 0; row < playerIdRow.length; row++)
						{
						var pid = playerIdRow[row];
						for (var col = 0; col < gameNum.length; col++)
							{
							var colnum = (col+sgoffset);
							var thepic = "img_" + pid + "_" + colnum;
							var imgobj = document.getElementById(thepic);
							if (imgobj != null)
								{
	//										imgobj.addEventListener("touchend",handlerFunction,false);
								imgobj.addEventListener("click",handlerFunction,false);	
								}
							}
						}
					}
				)
		</script>

		<p style='text-align:center'>Updated: April 15, 2019</p>
    </body>
</html>
