<?php 
	session_start();
	include 'dbconnect.php'; 
?>

<?php
	$sess=session_destroy();
	// session_regenerate_id();
	if($sess)
	{
		echo "<script>window.alert('Successfully Logged Out!!')</script>";
		echo "<script>window.location='index.php'</script>";
	}
	else
	{
		echo "<p>Error in Logging Out: " .mysqli_error($connection). "</p>";
	}
?>