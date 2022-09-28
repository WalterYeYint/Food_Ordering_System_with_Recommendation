<?php
include 'headtag.php';
include 'header.php';
include 'sidebar.php';
include '../dbconnect.php';

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

function get_foodorder_arr_query($restaurant_arr, $connection){
	
	$restaurantID_list = array();
	foreach($restaurant_arr as $row){
		array_push($restaurantID_list, $row['restaurantID']);
	}
	
	if(count($restaurantID_list) <= 0){
		$restaurantID_list_implode = "(0)";
	}
	else{
		$restaurantID_list_implode = "(".implode(',', $restaurantID_list).")";
	}
	$select="SELECT * FROM cart
					WHERE restaurantID IN $restaurantID_list_implode
					ORDER BY cartID DESC";
	$result=mysqli_query($connection,$select);
	$count=mysqli_num_rows($result);
	$select_cart_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

	$cartID_list = array();
	foreach($select_cart_arr as $row){
		array_push($cartID_list, $row['cartID']);
	}

	if(count($cartID_list) <= 0){
		$cartID_list_implode = "(0)";
	}
	else{
		$cartID_list_implode = "(".implode(',', $cartID_list).")";
	}
	$foodorder_arr_query = "SELECT fo.*, f.*
													FROM foodorder fo, food f
													WHERE fo.foodID = f.foodID
													AND fo.cartID IN $cartID_list_implode
													ORDER BY fo.foodorderID DESC";
	return $foodorder_arr_query;
}

if(isset($_POST['btnsubmit'])){
	if($_POST['rdosearchtype'] == SEARCH_TYPE_ALL){
		// Restaurants Query that are used more than once
		$query = generate_restaurant_query($userID_sess, $userRoleName_sess);
		$restaurant_arr = get_restaurant_arr_info($connection, $query)[0];
		$foodorder_arr_query = get_foodorder_arr_query($restaurant_arr, $connection);
	}
	elseif($_POST['rdosearchtype'] == SEARCH_TYPE_RESTAURANT_ID){
		$sltrestaurantid = $_POST['sltrestaurantid'];
		$query = "SELECT * FROM restaurant
							WHERE restaurantID = '$sltrestaurantid'";
		$restaurant_arr = get_restaurant_arr_info($connection, $query)[0];
		$foodorder_arr_query = get_foodorder_arr_query($restaurant_arr, $connection);
	}
	elseif($_POST['rdosearchtype'] == SEARCH_TYPE_FOOD_ORDER_ID){
		$sltfoodorderid = $_POST['sltfoodorderid'];
		$foodorder_arr_query = "SELECT fo.*, f.*
														FROM foodorder fo, food f
														WHERE fo.foodID = f.foodID
														AND fo.foodorderID = '$sltfoodorderid'
														ORDER BY fo.foodorderID DESC";
	}
	else{
		$sltcartid = $_POST['sltcartid'];
		$foodorder_arr_query = "SELECT fo.*, f.*
														FROM foodorder fo, food f
														WHERE fo.foodID = f.foodID
														AND fo.cartID = '$sltcartid'
														ORDER BY fo.foodorderID DESC";
	}
}
else{
	$query = generate_restaurant_query($userID_sess, $userRoleName_sess);
	$restaurant_arr = get_restaurant_arr_info($connection, $query)[0];
	$foodorder_arr_query = get_foodorder_arr_query($restaurant_arr, $connection);
}
$result = mysqli_query($connection, $foodorder_arr_query);
$foodorder_arr_count = mysqli_num_rows($result);
$foodorder_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
?>
<div class="col-12 grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title">Manage Order in Cart</h4>
			<!-- In the following form tag, -->
			<!-- Without enctype="multipart/form-data", image file name doesn't get through POST -->
			<form class="forms-sample" action="managefoodorder.php" method="post" enctype="multipart/form-data">
				<div class="col-4 form-group">
					<input type="radio" name="rdosearchtype" value=<?php echo SEARCH_TYPE_ALL ?> id=<?php echo SEARCH_TYPE_ALL ?> checked="" onclick="EnableDisableTextBox()">
						<label for=<?php echo SEARCH_TYPE_ALL ?> class="form-label">All entries</label>
				</div>
				<div class="col-4 form-group">
					<input type="radio" name="rdosearchtype" value=<?php echo SEARCH_TYPE_RESTAURANT_ID ?> id=<?php echo SEARCH_TYPE_RESTAURANT_ID ?> onclick="EnableDisableTextBox()">
						<label for=<?php echo SEARCH_TYPE_RESTAURANT_ID ?> class="form-label">Search by restaurant</label>
						<select class="form-select" id="sltrestaurantid" name="sltrestaurantid" disabled="disabled">
							<option>-- Select Restaurant --</option>
							<?php
							$query = generate_restaurant_query($userID_sess, $userRoleName_sess);
							$restaurant_arr = get_restaurant_arr_info($connection, $query)[0];
							$restaurant_count = get_restaurant_arr_info($connection, $query)[1];
							
							for ($i=0; $i<$restaurant_count; $i++) { 
								$row=$restaurant_arr[$i];
								$restaurantID=$row['restaurantID'];
								$restaurantName=$row['restaurantName'];
							?>
								<option value=<?php echo $restaurantID ?>
							<?php
								if ($trestaurantID == $restaurantID) {
									echo "selected";
								}
								echo ">$restaurantName</option>";
							}
							?>
						</select>
				</div>  
				<div class="col-4 form-group">
					<input type="radio" name="rdosearchtype" value=<?php echo SEARCH_TYPE_FOOD_ORDER_ID ?> id=<?php echo SEARCH_TYPE_FOOD_ORDER_ID ?> onclick="EnableDisableTextBox()">
						<label for=<?php echo SEARCH_TYPE_FOOD_ORDER_ID ?> class="form-label">Search by foodorderID</label>
						<select class="form-select" aria-label="Default select example" id="sltfoodorderid" name="sltfoodorderid" disabled="disabled">
								<option >-- Choose foodorderID --</option>
								<?php 
									$restaurantID_list = array();
									foreach($restaurant_arr as $row){
										array_push($restaurantID_list, $row['restaurantID']);
									}
									if(count($restaurantID_list) <= 0){
										$restaurantID_list_implode = "(0)";
									}
									else{
										$restaurantID_list_implode = "(".implode(',', $restaurantID_list).")";
									}
									$select="SELECT * FROM cart
													WHERE restaurantID IN $restaurantID_list_implode
													ORDER BY cartID DESC";
									$result=mysqli_query($connection,$select);
									$count=mysqli_num_rows($result);
									$select_cart_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

									$cartID_list = array();
									foreach($select_cart_arr as $row){
										array_push($cartID_list, $row['cartID']);
									}

									$select = "SELECT * FROM foodorder
														WHERE cartID in (".implode(',', $cartID_list).")
														ORDER BY foodorderID DESC";
									$result=mysqli_query($connection,$select);
									$count=mysqli_num_rows($result);
									$select_foodorder_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

									for ($i=0; $i <$count ; $i++) { 
										$row=$select_foodorder_arr[$i];
										$foodorderID=$row['foodorderID'];

										echo "<option value='$foodorderID'>$foodorderID</option>";
									}
									?>                      
							</select>
				</div>   
				<div class="col-4 form-group">
					<input type="radio" name="rdosearchtype" value=<?php echo SEARCH_TYPE_CART_ID ?> id=<?php echo SEARCH_TYPE_CART_ID ?> onclick="EnableDisableTextBox()">
						<label for=<?php echo SEARCH_TYPE_CART_ID ?> class="form-label">Search by Cart ID</label>
						<select class="form-select" aria-label="Default select example" id="sltcartid" name="sltcartid" disabled="disabled">
								<option >-- Choose CartID --</option>
								<?php 
									$select="SELECT * FROM cart
													WHERE restaurantID IN $restaurantID_list_implode
													ORDER BY cartID DESC";
									$result=mysqli_query($connection,$select);
									$count=mysqli_num_rows($result);
									$select_cart_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

									for ($i=0; $i <$count ; $i++) { 
										$row=$select_cart_arr[$i];
										$cartID=$row['cartID'];
										echo "<option value='$cartID'>$cartID</option>";
									}
									?>                      
							</select>
				</div>   
				<button type="submit" class="btn btn-primary me-2" name="btnsubmit">Search</button>
				<button type="reset" class="btn btn-secondary" id="reset" name="btnreset">Cancel</button>
			</form>
		</div>
	</div>
</div>
<?php
// Defining required variables for pagination
$paginate_array = paginate($foodorder_arr_count);
$entry_count = $paginate_array[0];
$actual_entry_count = $paginate_array[1];
$page_count = $paginate_array[2];
$pgNo = $paginate_array[3];
$pg_idx_start = $paginate_array[4];
$pg_idx_end = $paginate_array[5];

if($foodorder_arr_count<1){
	echo "<p>No Record Found!</p>";
}
else{
?>
<div class="col-lg-12 stretch-card">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title">List of Food Orders in Carts:</h4>
			<ul class="nav nav-tabs" role="tablist">
				<a href="managefoodorder.php?pgNo=<?=1?>" class="nav-link"><<</a>
				<?php
				for($i=$pg_idx_start; $i<=$pg_idx_end; $i++){
				?>
					<li class="nav-item">
						<a href="managefoodorder.php?pgNo=<?=$i?>" class="nav-link 
						<?php
						if($pgNo == $i){
							echo "active";
						}
						?>
						"><?php echo $i ?></a>
          </li>
				<?php
				}
				?>
				<a href="managefoodorder.php?pgNo=<?=$page_count?>" class="nav-link">>></a>
			</ul>
			<div class="table-responsive pt-3">
				<table class="table datatable table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Food Order ID</th>
							<!-- <th>Food ID</th> -->
							<th>Food Name</th>
							<th>Cart ID</th>
							<th>Quantity</th>
							<th>Rating</th>
					</thead>
					<tbody>
					<?php
					$idx = ($pgNo-1)*$entry_count;
					for($i=$idx; $i<$idx+$actual_entry_count; $i++){
						$rows=$foodorder_arr[$i];
						$foodorderID = $rows['foodorderID'];
						// $foodID = $rows['foodID'];
						$foodName = $rows['foodName'];
						$cartID = $rows['cartID'];
						$quantity = $rows['quantity'];
						$rating = $rows['rating'];
					?>
						<tr>
								<th><?php echo $foodorderID ?></th>
								<!-- <th><?php echo $foodID ?></th> -->
								<th><?php echo $foodName ?></th>
								<th><?php echo $cartID ?></th>
								<td><?php echo $quantity ?></td>
								<td><?php echo $rating ?></td>
								<td>
									<a href="managefoodorder.php?foodorderID=<?=$foodorderID?>&mode=edit" class="btn btn-success">Edit</a>
									<a href="managefoodorder.php?foodorderID=<?=$foodorderID?>&mode=delete" class="btn btn-danger" onclick="return confirm_delete('<?php echo $foodorderID ?>')">Delete</a>
								</td>
						</tr>
					<?php 
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php
}
?>
<?php include 'footer.php'; ?>
