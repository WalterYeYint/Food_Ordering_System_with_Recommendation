<?php 
	session_start();
	include 'dbconnect.php'; 
?>

<?php
	if(isset($_SESSION['cartID'])){
		$cartID = $_SESSION['cartID'];
		$delete = "DELETE FROM cart
								WHERE cartID = '$cartID'";
		$result = mysqli_query($connection, $delete);
		if($result)
		{
			echo "<script>window.alert('Cart deleted!!')</script>";
		}
		else
		{
			echo "<p>Error in deleting cart: " .mysqli_error($connection). "</p>";
		}
	}
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