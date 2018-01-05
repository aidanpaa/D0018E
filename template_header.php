<div id="pageHeader"><table width="100%" border="0" cellspacing="12">

	<ul class="topnav">
  	<li><a class="active" href="http://server.beldrama.com/index.php">Home</a></li>
  	<li><a href="http://server.beldrama.com/storeadmin/index.php">Admin</a></li>
  	<li><a href="http://server.beldrama.com/login/index.php">User Login</a></li>
	<?php  
	if(isset($_SESSION["customer"])){
		echo "<li><a href='index.php?action=Logout'>Logout?</a><br /></li>";
	} else if(isset($_SESSION["manager"])){
		echo "<li><a href='index.php?action=Logout'>Logout?</a><br /></li>";
	}
	?> 
	</ul>
	</div>
