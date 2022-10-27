<?php
include 'headtag.php';
include 'header.php';
include 'sidebar.php';
include '../dbconnect.php';

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

if(isset($_GET['mode'])){
	if($_GET['mode'] == 'edit'){
		$foodorderID = $_GET['foodorderID'];

		$select = "SELECT * FROM foodorder WHERE foodorderID='$foodorderID'";
		$result = mysqli_query($connection,$select);
		$arr = mysqli_fetch_array($result);

		$tcartID = $arr['cartID'];
		$tfoodorderID = $foodorderID;
		$tfoodID = $arr['foodID'];
		$tquantity = $arr['quantity'];
		$toldQuantity = $tquantity;
		$trating = $arr['rating'];
	}
	elseif($_GET['mode'] == 'delete'){
		$foodorderID = $_GET['foodorderID'];
		$foodID = $_GET['foodID'];
		$cartID = $_GET['cartID'];
		$quantity = $_GET['quantity'];

		$delete="DELETE FROM foodorder WHERE foodorderID='$foodorderID'";
		$result=mysqli_query($connection,$delete);
		if ($result)
		{
			echo "<script>window.alert('Order Deleted Successfully!')</script>";
		}
		else
		{
			echo "<p>Something went wrong in Order Deletion : " . mysqli_error($connection) . "</p>";
		}

		$select = "SELECT * FROM food WHERE foodID='$foodID'";
		$result = mysqli_query($connection,$select);
		$arr = mysqli_fetch_array($result);
		$price = $arr['price'];

		$select = "SELECT * FROM cart WHERE cartID='$cartID'";
		$result = mysqli_query($connection,$select);
		$arr = mysqli_fetch_array($result);
		$old_total_amount = $arr['totalAmount'];

		$new_total_amount = $old_total_amount - ($quantity * $price);

		$update = "UPDATE cart
							SET totalAmount = '$new_total_amount'
							WHERE cartID = '$cartID'";
		$result=mysqli_query($connection,$update);
		if ($result) {
			echo "<script>window.alert('Cart Updated Successfully!')</script>";
			echo "<script>window.location='managefoodorder.php?mode=form'</script>";
		}
		else{
			echo "<p>Something went wrong in Cart Update : " . mysqli_error($connection) . "</p>";
		}
	}
}

if(isset($_POST['btnsearch'])){
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
elseif(isset($_POST['btncartsearch'])){
	$tcartID = $_POST['sltcartid'];
	$tfoodorderID = AutoID('foodorder', 'foodorderID');
	$tquantity = 1;
	$toldQuantity = 1;
	$trating = 5;
	$query = generate_restaurant_query($userID_sess, $userRoleName_sess);
	$restaurant_arr = get_restaurant_arr_info($connection, $query)[0];
	$foodorder_arr_query = get_foodorder_arr_query($restaurant_arr, $connection);
}
elseif(isset($_POST['btnsubmit']) or isset($_POST['btnupdate'])){
	$txtfoodorderid = $_POST['txtfoodorderid'];
	$sltfoodid = $_POST['sltfoodid'];
	$txtcartid = $_POST['txtcartid'];
	$txtquantity = $_POST['txtquantity'];
	$txtoldquantity = $_POST['txtoldquantity'];
	$txtrating = $_POST['txtrating'];

	if(isset($_POST['btnupdate'])){
		$update = "UPDATE foodorder SET
						foodID = '$sltfoodid',
						cartID = '$txtcartid',
						quantity = '$txtquantity',
						rating = '$txtrating'    
						WHERE foodorderID = '$txtfoodorderid'";
		$result = mysqli_query($connection,$update);

		if($result) 
		{
			echo "<script>window.alert('Order Info Updated Successfully!')</script>";
		}
		else
		{
			echo "<p>Something went wrong in Updating Order Info : " . mysqli_error($connection) . "</p>";
		}

		$select = "SELECT * FROM food WHERE foodID='$sltfoodid'";
		$result = mysqli_query($connection,$select);
		$arr = mysqli_fetch_array($result);
		$price = $arr['price'];

		$select = "SELECT * FROM cart WHERE cartID='$txtcartid'";
		$result = mysqli_query($connection,$select);
		$arr = mysqli_fetch_array($result);
		$old_total_amount = $arr['totalAmount'];

		$quantity_difference = $txtquantity - $txtoldquantity;
		$new_total_amount = $old_total_amount + ($quantity_difference * $price);

		$update = "UPDATE cart
							SET totalAmount = '$new_total_amount'
							WHERE cartID = '$txtcartid'";
		$result=mysqli_query($connection,$update);
		if ($result) {
			echo "<script>window.alert('Cart Updated Successfully!')</script>";
			echo "<script>window.location='managefoodorder.php?mode=form'</script>";
		}
		else{
			echo "<p>Something went wrong in Cart Update : " . mysqli_error($connection) . "</p>";
		}
	}
	else{
		$insert = "INSERT INTO foodorder 
							(`foodorderID`, `foodID`, `cartID`, `quantity`, `rating`)
							VALUES 
							('$txtfoodorderid','$sltfoodid','$txtcartid','$txtquantity','$txtrating')";
		echo $txtfoodorderid." ".$sltfoodid." ".$txtcartid." ".$txtquantity." ".$txtrating;
		$result=mysqli_query($connection,$insert);
		echo "right here";
		if ($result) {
			echo "<script>window.alert('Order Added Successfully!')</script>";
		}
		else{
			echo "<p>Something went wrong in Order Entry : " . mysqli_error($connection) . "</p>";
		}

		$select = "SELECT * FROM food WHERE foodID='$sltfoodid'";
		$result = mysqli_query($connection,$select);
		$arr = mysqli_fetch_array($result);
		$price = $arr['price'];

		$select = "SELECT * FROM cart WHERE cartID='$txtcartid'";
		$result = mysqli_query($connection,$select);
		$arr = mysqli_fetch_array($result);
		$old_total_amount = $arr['totalAmount'];

		$new_total_amount = $old_total_amount + ($txtquantity * $price);

		$update = "UPDATE cart
							SET totalAmount = '$new_total_amount'
							WHERE cartID = '$txtcartid'";
		$result=mysqli_query($connection,$update);
		if ($result) {
			echo "<script>window.alert('Cart Updated Successfully!')</script>";
			echo "<script>window.location='managefoodorder.php?mode=form'</script>";
		}
		else{
			echo "<p>Something went wrong in Cart Update : " . mysqli_error($connection) . "</p>";
		}
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
			<?php
			if(isset($_GET['mode']) or isset($_POST['btncartsearch'])){
				?>
				<form class="forms-sample" action="managefoodorder.php" method="post" enctype="multipart/form-data">
					<div class="col-4 form-group">
						<label for="id">Cart ID <span style="color: red;">*</span></label>
						<select class="form-select" aria-label="Default select example" id="sltcartid" name="sltcartid"
						<?php
						if($_GET['mode'] == 'edit' or isset($_POST['btncartsearch'])){
							echo "disabled='disabled'";
						}
						?>
						>
							<option >-- Choose CartID --</option>
								<?php
								$query = generate_restaurant_query($userID_sess, $userRoleName_sess);
								$restaurant_arr = get_restaurant_arr_info($connection, $query)[0];
								$restaurant_count = get_restaurant_arr_info($connection, $query)[1];

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

								for ($i=0; $i <$count ; $i++) { 
									$row=$select_cart_arr[$i];
									$cartID=$row['cartID'];
									echo "<option value='$cartID' ";
									if ($tcartID == $cartID) {
										echo "selected";
									}
									echo ">$cartID</option>";
								}
								?>
						</select>
						<input type="hidden" class="form-control" name="txtcartid" id="txtcartid" value="<?php echo $tcartID ?>" placeholder="ID" required="" readonly>
						<br/>
						<?php
						if($_GET['mode'] == 'edit' or isset($_POST['btncartsearch'])){
							?>
							<a href="managefoodorder.php?mode=form" class="btn btn-dark"><i class="ti-lock"></i>Unlock Cart ID</a>
							<?php
						}
						else{
							?>
							<button type="submit" class="btn btn-success me-2" name="btncartsearch">Search</button>
						<?php
						}
						?>
					</div>
					<div
					<?php
					if($_GET['mode'] == 'edit'){
						//do nothing
					}
					elseif(!isset($_POST['btncartsearch'])){
						echo "hidden";
					}
					?>
					>
						<div class="form-group">
							<label for="id">FoodOrder ID <span style="color: red;">*</span></label>
							<input type="text" class="form-control" name="txtfoodorderid" id="txtfoodorderid" value="<?php echo $tfoodorderID ?>" placeholder="ID" required="" readonly>
						</div>
						<div class="col-4 form-group">
							<label for="food" class="form-label">Food Name </label>
							<select class="form-select" id="foodid" name="sltfoodid" required="">
								<option >-- FoodName (FoodID) --</option>
								<?php
									$select="SELECT * FROM cart
													WHERE cartID = '$tcartID'
													ORDER BY cartID DESC";
									$result=mysqli_query($connection,$select);
									$count=mysqli_num_rows($result);
									$select_cart_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
									$restaurantID = $select_cart_arr[0]['restaurantID'];

									$select = "SELECT * FROM food
														WHERE restaurantID = '$restaurantID'
														ORDER BY foodID DESC";
									$result=mysqli_query($connection,$select);
									$count=mysqli_num_rows($result);
									$select_food_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

									for ($i=0; $i<$count ; $i++) { 
										$row = $select_food_arr[$i];
										$foodID = $row['foodID'];
										$foodName = $row['foodName'];
										$price = $row['price'];

										echo "<option value='$foodID' ";
										if ($tfoodID == $foodID) {
											echo "selected";
										}
										echo ">$foodName ($foodID) $price</option>";
									}
									?>                      
							</select>
						</div>
						<div class="form-group">
							<label for="id">Quantity <span style="color: red;">*</span></label>
							<input type="number" class="form-control" name="txtquantity" id="txtquantity" value="<?php echo $tquantity ?>" placeholder="Quantity" required=""
							<?php
							if($_GET['mode'] == 'edit'){
								//do nothing
							}
							elseif(!isset($_POST['btncartsearch'])){
								echo "readonly";
							}
							?>
							>
							<input type="hidden" class="form-control" name="txtoldquantity" id="txtoldquantity" value="<?php echo $toldQuantity ?>" placeholder="Old Quantity" readonly>
						</div>
						<div class="form-group">
							<label for="id">Rating <span style="color: red;">*</span></label>
							<input type="number" class="form-control" name="txtrating" id="txtrating" value="<?php echo $trating ?>" placeholder="Rating" required=""
							<?php
							if($_GET['mode'] == 'edit'){
								//do nothing
							}
							elseif(!isset($_POST['btncartsearch'])){
								echo "readonly";
							}
							?>
							>
						</div>
						<?php
						if(isset($_GET['foodorderID'])){
							?>
							<button type="submit" class="btn btn-success me-2" name="btnupdate">Update</button>	
						<?php
						}
						else{
							?>
							<button type="submit" class="btn btn-success me-2" name="btnsubmit">Submit</button>
						<?php
						}
						?>
						<button type="reset" class="btn btn-outline-dark" id="reset" name="btnreset">Cancel</button>
					</div>
					<br/><br/>
					<a href="managefoodorder.php" class="btn btn-primary">Search View</a>
				</form>
			<?php
			}
			else{
			?>
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
				<button type="submit" class="btn btn-success me-2" name="btnsearch">Search</button>
				<button type="reset" class="btn btn-outline-dark" id="reset" name="btnreset">Cancel</button>
				<br/><br/>
				<a href="managefoodorder.php?mode=form" class="btn btn-primary">Form View</a>
			</form>
			<?php
			}
			?>
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
						$foodID = $rows['foodID'];
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
									<a href="managefoodorder.php?foodorderID=<?=$foodorderID?>&mode=edit" class="btn btn-info btn-rounded">Edit</a>
									<a href="managefoodorder.php?foodorderID=<?=$foodorderID?>&foodID=<?=$foodID?>&cartID=<?=$cartID?>&quantity=<?=$quantity?>&mode=delete" class="btn btn-danger btn-rounded" onclick="return confirm_delete('<?php echo $foodorderID ?>')">Delete</a>
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
