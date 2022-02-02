<!DOCTYPE html>
<html>

    <head>
        <title>Test Page</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="softball,proctor" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        
		<style type="text/css">
		   .centerText{
			   text-align: center;
			}
		</style>
		
		<script type="text/javaScript">
			var theloop = 1;
			
			function handlerFunction(event)
			{
				for(i=0; i<theloop;i++)
					alert("Handler");
			}
						
		</script>
    </head>
    <!-- slide-toggle-menu -->
    <body>
        <!--top-header-->
		<div class="row justify-content-md-center">
			<h1 id="attendheader">Test Click/Touch Page</h1>
			<BR><BR>
		</div>	
		
	
		<br>

			
		<!-- Outer Table -->
		<div class="row justify-content-md-center">
			<table width="90%" border='0' cellspacing='1' cellpadding='10' class='center' id='headertable'>
				<col width="20%">
				<col width="60%">
				<col width="20%">
				<tr><td style="vertical-align:top">
				
			<?php
			
													echo "<h2><img src='img/bench-small-t.png' id='thepic' alt='Yes' height='40' width='40'>\n";
													echo "<script type='text/javaScript'>theloop = 2;</script>";
//													echo "<script type='text/javaScript'>var imgobj = document.getElementById('thepic');imgobj.addEventListener('touchend',sawItHappen(),false);</script>";
					
			?>		
			</table>
		</div>
		<BR><BR>
						
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="dist-js/popper.min.js"></script>
		<script type="text/javascript" src="dist-js/tether.min.js"></script>
<!--		<script type="text/javascript" src="dist-js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    -->    
		<script type="text/javaScript">
				window.addEventListener('load', function()
				{	
					var imgobj = document.getElementById('thepic');
					imgobj.addEventListener("touchend",handlerFunction,false);
					imgobj.addEventListener("click",handlerFunction,false);
				}
			)
		</script>
    </body>
</html>
