
<?php
function generate_restaurant_query($userID_sess, $userRoleName_sess){
	if($userRoleName_sess == ADMIN OR $userRoleName_sess == CUSTOMER){
		$query = "SELECT * FROM restaurant
							WHERE userID = '$userID_sess'";
	}
	else{
		$query = "SELECT * FROM restaurant
							ORDER BY restaurantID DESC";
	}
	return $query;
}

function get_restaurant_arr_info($connection, $query){
	$restaurant_result = mysqli_query($connection, $query);
	$restaurant_count = mysqli_num_rows($restaurant_result);
	$restaurant_arr = mysqli_fetch_all($restaurant_result, MYSQLI_BOTH);
	
	$return_arr = array();
	array_push($return_arr, $restaurant_arr);
	array_push($return_arr, $restaurant_count);
	return $return_arr;
}
?>