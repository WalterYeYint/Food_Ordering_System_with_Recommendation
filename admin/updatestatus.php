<?php
session_start();
include '../constants.php';
include '../dbconnect.php';

if(isset($_GET['mode'])){
	$cartID = $_GET['cartID'];
	$select = "SELECT * FROM cart
						WHERE cartID = '$cartID'";
	$result = mysqli_query($connection, $select);
	$result_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
	if($_GET['mode'] == "togglePaymentStatus"){
		$paymentStatus = $result_arr[0]['paymentStatus'];
		if($paymentStatus == UNPAID){
			$newPaymentStatus = PAID;
		}
		else{
			$newPaymentStatus = UNPAID;
		}

		$update = "UPDATE cart
							SET paymentStatus = '$newPaymentStatus'
							WHERE cartID = '$cartID'";
		$result = mysqli_query($connection, $update);
		if($result) {
			echo 'Updated payment Status Successfully!';
			// echo "<script>window.location='dashboard.php'</script>";
		}
	}
	elseif($_GET['mode'] == "toggleCartStatus"){
		$cartStatus = $result_arr[0]['cartStatus'];
		switch($cartStatus){
			case CART_CHECKED_OUT:
				$newCartStatus = CART_IN_PROGRESS;
				break;
			case CART_IN_PROGRESS:
				$newCartStatus = CART_COMPLETED;
				break;
			case CART_COMPLETED:
				$newCartStatus = CART_CHECKED_OUT;
				break;
			case CART_CANCELLED:
				$newCartStatus = CART_CHECKED_OUT;
				break;
		}

		$update = "UPDATE cart
							SET cartStatus = '$newCartStatus'
							WHERE cartID = '$cartID'";
		$result = mysqli_query($connection, $update);
		if($result) {
			echo 'Updated cart Status Successfully!';
			// echo "<script>window.location='dashboard.php'</script>";
		}
	}
	else{
		$newCartStatus = CART_CANCELLED;
		$update = "UPDATE cart
							SET cartStatus = '$newCartStatus'
							WHERE cartID = '$cartID'";
		$result = mysqli_query($connection, $update);
		if($result) {
			echo 'Cancelled cart Successfully!';
			// echo "<script>window.location='dashboard.php'</script>";
		}
	}
}
?>