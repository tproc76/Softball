	<div id="menusidenav" class="col-2" style="background-color:#D2D2D2">
		<br><br><br><br>
		<p id="currentyear" class="menuheader">
		<?php $curYear = date('Y');echo $curYear; ?>
		</p>
		<p id="currslots" class="menuitemlink">
		<?php
			include 'db_setup.php';
			
			$seasonsFound = false;
			$selectCurr = "SELECT * FROM seasons WHERE year=$curYear AND league='MPG'";
			$result =mysqli_query($link, $selectCurr);
			while ($row=mysqli_fetch_array($result, MYSQLI_ASSOC)) 
				{
				$seasonsFound=true;
				$seasonName = $row['season_name'];
				echo '<a href="season.php?season=' . $seasonName . '">MPG Stats</a><br><br>';
				}
			$selectCurr = "SELECT * FROM seasons WHERE year=$curYear AND league='Mens'";
			$result =mysqli_query($link, $selectCurr);
			while ($row=mysqli_fetch_array($result, MYSQLI_ASSOC)) 
				{
				$seasonsFound=true;
				$seasonName = $row['season_name'];
				$seasonID = $row['season_id'];
				echo '<a href="season.php?season=' . $seasonName . '">' . $seasonName . '</a><br><br>';
				}
				
			if ($seasonsFound==false)
				{
				echo 'None<br><br>';
				}					
		?>
		</p><br>
		<p id="lastyear" class="menuheader"><?php echo $curYear-1;?></p>
		<p id="lastslots" class="menuitemlink">
		<?php
			$seasonsFound = false;
			$prevYear = $curYear-1;
			$selectCurr = "SELECT * FROM seasons WHERE year=$prevYear AND league='MPG'";
			$result =mysqli_query($link, $selectCurr);
			while ($row=mysqli_fetch_array($result, MYSQLI_ASSOC)) 
				{
				$seasonsFound=true;
				$seasonName = $row['season_name'];
				echo '<a href="season.php?season=' . $seasonName . '">MPG Stats</a><br><br>';
				}
			$selectCurr = "SELECT * FROM seasons WHERE year=$prevYear AND league='Mens'";
			$result =mysqli_query($link, $selectCurr);
			while ($row=mysqli_fetch_array($result, MYSQLI_ASSOC)) 
				{
				$seasonsFound=true;
				$seasonName = $row['season_name'];
				$seasonID = $row['season_id'];
				echo '<a href="season.php?season=' . $seasonName . '">' . $seasonName . '</a><br><br>';
				}
				
			if ($seasonsFound==false)
				{
				echo 'None<br><br>';
				}					
		?>
		</p><br>
		<p class="menuheader">All Time</p>
		<p class="menuitemlink"><a href="statsalltime.php?league=Mens">Career Stats</a></p>
		<p class="menuitemlink"><a href="statsalltime_hitter.php?league=Mens">Hitter Only Stats</a></p>
		<p class="menuitemlink"><a href="seasonlist.php">All Season List</a></p>
		<br>
		<p class="menuheader">Other Pages</p>
		<p class="menuitemlink"><a href="attendance_home.php">Attendance</a></p>
		<br><br><br>
	</div>