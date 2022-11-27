<?php
session_start();
if(isset($_GET['idx'])){
	$idx = $_GET['idx'];
	echo "Removing food entry.";

	$_SESSION['cart_item_count'] -= 1;
	array_splice($_SESSION['food_ID_list'], $idx, 1);
	array_splice($_SESSION['quantity_list'], $idx, 1);
}
?>