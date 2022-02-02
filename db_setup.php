<?php
define('DB_HOST',  'localhost');
define('DB_USER',  'hadegzik_softball');
define('DB_PASS',  'hadegzik_softball');
define('DB_DB',    'hadegzik_tprocsoftball');

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
if (mysqli_connect_errno()) 
	{
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
	}
	
mysqli_select_db($link, DB_DB);

/* return name of current default database */
if ($result = mysqli_query($link, "SELECT DATABASE()")) 
	{
    $row = mysqli_fetch_row($result);
    //printf("Default database is %s.\n", $row[0]);
    if ($row[0] != DB_DB) 
		{
        printf("Connect failed: Wrong Database %s\n");
        exit();
		}
    mysqli_free_result($result);
	}
?>