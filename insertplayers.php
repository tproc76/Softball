<?php
include 'db_setup.php';

header('Content-Type: application/json; charset=utf-8', true,200);
// $Create_PlayerTable = 'CREATE TABLE players(
								// player_id			MEDIUMINT		NOT NULL AUTO_INCREMENT,
								// nickname       		VARCHAR(25)		NOT NULL,
								// fullname			VARCHAR(50)		NOT NULL,
								// notes				VARCHAR(255),
								// CONSTRAINT PK_Games PRIMARY KEY (player_id))';

$insertPlayers 	 = "INSERT INTO players VALUES(default, 'Tim', 		 'Tim Proctor',     NULL);";
$insertPlayers	.= "INSERT INTO players VALUES(default, 'Jeremy',    'Jeremy Fathers',  NULL);";
$insertPlayers	.= "INSERT INTO players VALUES(default, 'Frank',     'Frank Voelker',   NULL);";
$insertPlayers	.= "INSERT INTO players VALUES(default, 'Matt',      'Matt Rothschild', NULL);";
$insertPlayers	.= "INSERT INTO players VALUES(default, 'TJ',        'TJ Ansley',       NULL);";
$insertPlayers	.= "INSERT INTO players VALUES(default, 'Joel',      'Joel Dix',        NULL);";
$insertPlayers	.= "INSERT INTO players VALUES(default, 'Kevin',     'Kevin Adams',     NULL);";
$insertPlayers	.= "INSERT INTO players VALUES(default, 'Phil',      'Phil W...',       NULL);";
$insertPlayers	.= "INSERT INTO players VALUES(default, 'Domingo',   'Domingo Neito',   NULL);";
$insertPlayers	.= "INSERT INTO players VALUES(default, 'Paul',      'Paul Wittkop',    NULL);";
$insertPlayers	.= "INSERT INTO players VALUES(default, 'Harry',     'Harry Stoddard',  'Everyone knows Harry');";
$insertPlayers	.= "INSERT INTO players VALUES(default, 'Gary',      'Gary Kenski',     NULL);";

$insertCoed		 = "INSERT INTO players VALUES(default, 'John',  	'John B.', 			'Co-ed Pitcher not sure of last name');";
$insertCoed		.= "INSERT INTO players VALUES(default, 'John B.', 	'John B.', 			'I think same as CO-Ed John pitcher');";
$insertCoed		.= "INSERT INTO players VALUES(default, 'Janice',  	'Janice Fronczak',	'Co-ed Second Base');";
$insertCoed		.= "INSERT INTO players VALUES(default, 'Clark',  	'Jason Clark',		NULL);";
$insertCoed		.= "INSERT INTO players VALUES(default, 'Ann',  	'Ann Proctor',		'Awesome wife');";
$insertCoed		.= "INSERT INTO players VALUES(default, 'Nicole',  	'Nicole (Snigowski) Rothschild',NULL);";
$insertCoed		.= "INSERT INTO players VALUES(default, 'Becky',  	'Becky Proctor',	NULL);";
$insertCoed		.= "INSERT INTO players VALUES(default, 'Cory',  	'Cory',				'Co-ed Solid DH');";
$insertCoed		.= "INSERT INTO players VALUES(default, 'Cindy',  	'Cindy Clark',		'Married Jason Clark');";
$insertCoed		.= "INSERT INTO players VALUES(default, 'Sheri',  	'Sheri',			'Valeries Friend');";
$insertCoed		.= "INSERT INTO players VALUES(default, 'Valerie',  'Valerie Wittkop',	NULL);";
$insertCoed		.= "INSERT INTO players VALUES(default, 'Cyndi',  	'Cyndi (Shelton) Adams',	NULL);";
$insertCoed		.= "INSERT INTO players VALUES(default, 'Brian J.', 'Brian Jackson',	NULL);";
$insertCoed		.= "INSERT INTO players VALUES(default, 'Jorie',  	'Jorie Jackson',    NULL);";
$insertCoed		.= "INSERT INTO players VALUES(default, 'April',  	'April',            'Co-ed Sub 2003');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Joe',  	'Joe',              'Co-ed 2007');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Missy',  	'Missy',            'Co-ed Sub 2007');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Kellie',  	'Kellie',           'Co-ed Sub 2007');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Caryn',  	'Caryn',            'Co-ed Sub 2007');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Caesar',  	'Nicole',           'Co-ed 2007');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Ken',	  	'Ken',              'Co-ed Sub 2007');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Erica',  	'Erica',            'Co-ed 2007');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Sarah',  	'Sarah',            'Co-ed 2007');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Courtney',	'Courtney Fathers', NULL);";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Mich',		'Mich Raulis', 		NULL);";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Non-Roster','Non Roster Sub',   NULL);";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Tiff',		'Tiff Mara', 		NULL);";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Nikki',	'Nikki', 			'Co-ed 2007');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Kate',		'Kate', 			'Co-ed 2007');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Jason',	'Jason Duford', 	'Co-ed 2000');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Liz',		'Elizabeth (Rothschild) Simpson',NULL);";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Shauna',	'Shauna (Pausell) Brown',NULL);";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Beth',		'Beth',				'Co-ed 2000');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Tom',		'Tom/Kelly Z...ski',NULL);";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Michele',	'Michele',			'Maybe Michelle Hill or not');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Michelle',	'Michelle Hill',	'Married Bob');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Jill',		'Jill',				'Co-ed Sub 2000');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Stephanie','Stephanie',		'Co-ed Sub 2000');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Amy',		'Amy',				'Co-ed Sub/Pitched 2000');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Korri',	'Korri',			'Beckys Friend');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Misc. Girl','Misc. Girl',		NULL);";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Bob',		'Bob Hill',			'Married Michele');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Lee',		'Lee Acervo',		'Co-ed Sub 2001');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Keri',		'Keri',				'Co-ed Sub 2001');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Tim C.',	'Tim C',			'Co-ed Sub 2001');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Matt S.',	'Matt S.',			'Co-ed Sub 2001');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Jamie',	'Jamie',			'Co-ed Sub 2001');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Kim',		'Kim Strickland',	'Co-ed 2003');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Jennifer',	'Jennifer',			'Co-ed Sub 2003');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Katie',	'Katie',			'Co-ed partial 2003');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Carrie',	'Carrie Kenski',	'Co-ed Sub 2003');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Sara',		'Sara',				'Co-ed 2004');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'T. T.',	'T. T.',			'Co-ed Sub 2004');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Lisa',		'Lisa',				'Co-ed Sub 2004');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Pam',		'Pam',				'Co-ed Sub 2004');";
$insertCoed     .= "INSERT INTO players VALUES(default, 'Andrea',	'Andrea',			'Co-ed Sub 2004');";

$insertMens  	 = "INSERT INTO players VALUES(default, 'Ethan A.', 'Ethan Drouillard', 'Started in 2017');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Chuck J.', 'Chuck Jarrell',    NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Cody R.',  'Cody Radcliff',    'Barrys Nephew');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Cody C.',  'Cody Chamberlin',  NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Brian',    'Brian Kwapis',  	NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Kyle',     'Kyle Green',  		NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'David',    'David Emmons',  	NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Aaron S.', 'Aaron Surman', 	'Giraffe');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Brian S.', 'Brian S.',  		'Fall partial 2002');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Aaron C.', 'Aaron Cowell', 	NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Will', 	'Will', 			NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Mike E.', 	'Mike Emmons', 		'Summer 2002');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Matt T.', 	'Matt Truax', 		'Fall 2001');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Jeff', 	'Jeff', 			'Fall 2001');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Greg', 	'Greg', 			'Fall 2005');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Matt C.', 	'Matt Chapin',		NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Kevin D.', 'Kevin DiCola',		NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Jeremiah', 'Jeremiah',			'Sub Fall 2005');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Chris', 	'Chris',			'Fall 2006');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Steve', 	'Steve',			'Fall 2006');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Bryan', 	'Bryan',			'Fall 2007');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Nate', 	'Nate',				'Fall 2007');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Justin', 	'Justin',			'Fall 2007');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Joe2', 	'Joe2',				'Fall 2007-8 Third Base');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Jim', 		'Jim Murad',		NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Mike', 	'Mike Shiela',		NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Don', 		'Don',				NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Shane', 	'Shane',			NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'MG', 		'MG',				NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Steve P.',	'Steve Prokopchak',	NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Sean',		'Sean',				'Sub Mens 2006');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Larry',	'Larry Weathers',	'Sub Mens 2006');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Brian C.',	'Brian Cronin',		NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Jordan K.','Jordan Kindshoven',NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Vinny',    'Vinny Craig',      'Cody C friend/Erics Brother');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Frank P.', 'Frank Petroski',   NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Alex P.',  'Alex Petroski',    'Frank Ps  Son');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Barry K.', 'Barry Kilgore',    NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Casey B.', 'Casey Bell',       NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Joe3',     'Joe',              'Young Friend of Cody C');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Joe N.',   'Joe Najduk',       NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Eric C.',  'Eric Craig',       'Vinny Brother');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Andrew',   'Andrew',           'Sub Spring 2017');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Adam S.',  'Adam Seiter',      NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Jim H.',   'Jim Hart',         NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Joe C.',   'Joe Check',        NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Jordan G.','Jordan Garcia',    NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Don M.',   'Don McLane',       NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Dwayne P.','Dwayne Pachota',   NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Nick D.',  'Nick DeVos',       NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Russ',     'Russ',             'Barrys Friend');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Dan M.',   'Dan M.',           'Fall 2013');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Eric S.',  'Eric Shingles',    NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Matt D.',  'Matt Dudek',       NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Ed H.',    'Ed Harrison',      'Johns Williams Father-in-Law');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Jason S.', 'Jason S.',         'Summer 2013');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'John W.',  'John Williams',    NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Mark L.',  'Mark L',           NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Joe T.',   'Joe Ts..',         NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Brett J.', 'Brett Jackson',    NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Brian Camp.', 'Brian Campbell',   'Whats his Name');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Bryan P.', 'Bryan Pane SR',    NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Bryan P2.','Bryan Pane JR',    NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Martin',   'Martin',           'Franks Nephew');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Dale',     'Dale',             'Sub Summer 2012');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Paul P.',  'Paul P.',          'Summer 2012');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Ryan T.',  'Ryan Taylor',      NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Mike M.',  'Mike Mason',       NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Mike P.',  'Mike Paparella',   NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Ron G.',   'Ron Gibson',       NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Aaron B.', 'Aaron Basler',     NULL);";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Jim A.',   'Jim A.',           'Fall 2010');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'John N.',  'John N...',        'Fall 2010');";
$insertMens   	.= "INSERT INTO players VALUES(default, 'Rodney',   'Rodney Knucho',    NULL);";

$insertMpg  	 = "INSERT INTO players VALUES(default, 'An.Seiter', 'Andrew Seiter',   'Adams Brother');";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'B.Porto',   'Brian Porto',     NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'D.McLane',  'Don McLane',      NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'D.McLemore','Darius McLemore', 'Joined 2017');";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Draper',    'Dan Draper',      NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Fiore',     'Brian Fiore',     NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'H.Janczak', 'Hillary Janczak', NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'J.Janczak', 'John Janczak',    NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Jousma',    'Jason Jousma',    'Joined 2017');";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Kilgore',   'Barry Kilgore',   NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Kociba',    'Mike Kociba',     'Joined 2017');";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'L.Porto',   'Laura Porto',     NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Martini',   'Ryan Martini',    NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Mooney',    'Tim Mooney',      NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'O. McLemore','Olandis McLemore','Joined 2017');";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Mooney',    'Tim Mooney',      NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Packard',   'Jon Packard',     NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Proctor',   'Tim Proctor',     'With Bakers');";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Seiter',    'Adam Seiter',     NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Stewart',   'Joe Stewart',     NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Sullivan',  'Stan Sullivan',   NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Vibbert',   'Bill Vibbert',    NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Wesley',    'Wesley',          NULL);";

// Continue MPG - Pizza
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Joe C',     'Joe Check',       NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Kevin L',   'Kevin Learman',   NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Jon S',     'Jon Stec',        NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Tim P',     'Tim Proctor',     'Pizza Years');";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Steve E',   'Steve E???',      NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Jeff H',    'Jeff Heck',       'Asst Mgr when I started');";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'John H',    'John Hohman',     NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Daryl M',   'Daryl M',         NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Steve L',   'Steve Labuta',    NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Don S',     'Don S',           NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Paul B',    'Paul B',          NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Jeanette A','Jeanette A',      NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Jim P',     'Jim Pinket?',     NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Dave C',    'Dave Clark',      NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Fred M',    'Fred M',          NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Chris O',   'Chris O Brian',   NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Mike F',    'Mike Finch',      NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Aaron H',   'Aaron H',         NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Al M',      'Al M',            NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Cara L',    'Cara Learman',    NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Dave V',    'Dave V',          'Married to Lori');";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Lori V',    'Lori V',          'Married to Dave');";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Mike N',    'Mike Nassar',     NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'T. Daryl A','T. Daryl Armstrong', NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Tony W',    'Tony Womack',     NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Wayne M',   'Wayne Minnick',   NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Dan B',     'Dan B',           NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Dan D',     'Dan D',           NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Neil D',    'Neil Dersha',     NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Dimitri K', 'Dimitri K',       NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Gina K',    'Gina K',          NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Eric M',    'Eric M',          'Played 3B after I left');";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Jerry',     'Jerry',           NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'John O',    'John O',          NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Matt K',    'Matt K',          NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Todd D',    'Todd D',          NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Christina L.','Christina Lawler',NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Justin C',  'Justin C',        NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Scott C',   'Scott C',          NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Seth L',    'Seth L',          NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Tom M',     'Tom M',           NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Amy R',     'Amy R',           NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Bill E',    'Bill E',          NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Brent D',   'Brent D',         NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Hasan A',   'Hasan A',         NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Eric S',    'Eric S',          NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Tom J',     'Tom J',           NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Tony C',    'Tony C',          NULL);";
$insertMpg  	.= "INSERT INTO players VALUES(default, 'Barry K',   'Barry Kilgore',   'With Pizza');";

if(mysqli_multi_query($link, $insertPlayers) == false){
    echo "ERROR: Could not execute. " . mysqli_error($link) . "<br>";
}
do{} while(mysqli_more_results($link) && mysqli_next_result($link)); // flush multi_queries

if(mysqli_multi_query($link, $insertCoed) == false){
    echo "ERROR: Could not execute. " . mysqli_error($link) . "<br>";
}
do{} while(mysqli_more_results($link) && mysqli_next_result($link)); // flush multi_queries

if(mysqli_multi_query($link, $insertMens) == false){
    echo "ERROR: Could not execute. " . mysqli_error($link) . "<br>";
}
do{} while(mysqli_more_results($link) && mysqli_next_result($link)); // flush multi_queries

if(mysqli_multi_query($link, $insertMpg) == false){
    echo "ERROR: Could not execute. " . mysqli_error($link) . "<br>";
}
do{} while(mysqli_more_results($link) && mysqli_next_result($link)); // flush multi_queries
	
echo "Complete";
 
// Close connection
mysqli_close($link);
?>
