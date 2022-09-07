<?php
	session_start();
	if(isset($_GET['foodID'])){
		$foodID = $_GET['foodID'];
		// $quantity = $_GET['quantity'];
		echo "From server, foodID is $foodID";
		
		$_SESSION['cart_item_count'] += 1;
		array_push($_SESSION['food_ID_list'], $foodID);
		array_push($_SESSION['quantity_list'], 1);
	}
?>