<?php
	session_start();
	if(isset($_GET['idx'])){
		$idx = $_GET['idx'];
		$quantity = $_GET['quantity'];
		echo "From server, index is $idx, quantity is $quantity";

		$_SESSION['quantity_list'][$idx] = $quantity;
	}
?>