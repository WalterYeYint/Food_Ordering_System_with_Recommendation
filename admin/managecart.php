<?php
	include 'headtag.php';
	include 'header.php';
	include 'sidebar.php';
	include '../dbconnect.php';

	// Restaurants Query that are used more than once
	if($userRoleName_sess == RESTAURANT_ADMIN OR $userRoleName_sess == CUSTOMER){
		$query = "SELECT * FROM restaurant
							WHERE userID = '$userID_sess'";
	}
	else{
		$query = "SELECT * FROM restaurant
							ORDER BY restaurantID DESC";
	}
	$restaurant_result = mysqli_query($connection, $query);
	$restaurant_count = mysqli_num_rows($restaurant_result);
	$restaurant_arr = mysqli_fetch_all($restaurant_result, MYSQLI_BOTH);

	if(isset($_GET['cartID'])){
		if($_GET['mode'] == 'edit'){
			$cartID=$_GET['cartID'];

			$query = "SELECT * FROM cart WHERE cartID='$cartID'";
			$result = mysqli_query($connection,$query);
			$arr = mysqli_fetch_array($result);

			$tcartID = $arr['cartID'];
			$tuserID = $arr['userID'];
			$trestaurantID = $arr['restaurantID'];
			$tpaymentTypeID = $arr['paymentTypeID'];
			$ttotalAmount = $arr['totalAmount'];
			$taddress = $arr['address'];
			$tlatitude = $arr['latitude'];
			$tlongitude = $arr['longitude'];
			$trating = $arr['rating'];
			$tdeliveryType = $arr['deliveryType'];
			$tcartStatus = $arr['cartStatus'];
			$tpaymentStatus = $arr['paymentStatus'];
		}
		elseif($_GET['mode'] == 'delete'){
			$cartID = $_GET['cartID'];

			$delete="DELETE FROM cart WHERE cartID='$cartID'";
			$result=mysqli_query($connection,$delete);
			if ($result) 
			{
				echo "<script>window.alert('Cart Deleted Successfully!')</script>";
			}
			else
			{
				echo "<p>Something went wrong in Cart Delete : " . mysqli_error($connection) . "</p>";
			}
			$delete = "DELETE FROM foodorder
									WHERE cartID = '$cartID'";
			$result=mysqli_query($connection,$delete);
			if ($result) 
			{
				echo "<script>window.alert('Orders Deleted Successfully!')</script>";
				echo "<script>window.location='managecart.php'</script>";
			}
			else
			{
				echo "<p>Something went wrong in Cart Delete : " . mysqli_error($connection) . "</p>";
			}
		}
	}
	else{
		// echo "<script>window.alert('Renewed!')</script>";
		$tcartID = "";
		$tuserID = "";
		$trestaurantID = "";
		$tpaymentTypeID = "";
		$tdeliveryType = "";
		$tcartStatus = "";
		$tpaymentStatus = "";
	}

	if (isset($_POST['btnsubmit']) OR isset($_POST['btnupdate'])) {
		$txtcartID = $_POST['txtcartid'];
		$sltrestaurantid = $_POST['sltrestaurantid'];
		$sltpaymenttype = $_POST['sltpaymenttype'];
		$sltdeliverytype = $_POST['sltdeliverytype'];
		$sltcartstatus = $_POST['sltcartstatus'];
		$sltpaymentstatus = $_POST['sltpaymentstatus'];

		if(isset($_POST['btnupdate'])){
			$update = "UPDATE cart SET
								paymentTypeID = '$sltpaymenttype',
								deliveryType = '$sltdeliverytype',
								cartStatus = '$sltcartstatus',
								paymentStatus = '$sltpaymentstatus'
								WHERE cartID = '$txtcartID'";
			$result = mysqli_query($connection,$update);

			if($result) 
			{
				echo "<script>window.alert('Cart Info Updated Successfully!')</script>";
				echo "<script>window.location='managecart.php'</script>";
			}
			else
			{
				echo "<p>Something went wrong in Updating Cart Information : " . mysqli_error($connection) . "</p>";
			}
		}
		else{
		}
	}
?>
<div class="col-12 grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title">Manage Cart</h4>
			<!-- In the following form tag, -->
			<!-- Without enctype="multipart/form-data", image file name doesn't get through POST -->
			<form class="forms-sample" action="managecart.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="id">Cart ID <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtcartid" id="id" value="<?php echo $tcartID ?>" placeholder="ID" required="" readonly>
				</div>
				<div class="form-group">
					<label for="id">User ID <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtuserid" id="id" value="<?php echo $tuserID ?>" placeholder="ID" required="" readonly>
				</div>
				<div class="col-4 form-group">
					<label for="id">Restaurant Name <span style="color: red;">*</span></label>
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
					<label for="id">Payment Type <span style="color: red;">*</span></label>
					<select class="form-select" id="sltpaymenttype" name="sltpaymenttype">
						<option>-- Select Payment Type --</option>
						<?php
						$select = "SELECT * FROM paymentType";
						$result=mysqli_query($connection,$select);
						$count = mysqli_num_rows($result);
						$paymentType_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
						for ($i=0; $i<$count; $i++) { 
							$row=$paymentType_arr[$i];
							$paymentTypeID = $row['paymentTypeID'];
							$paymentType = $row['paymentType'];
							?>
							<option value=<?php echo $paymentTypeID ?>
							<?php
							if ($tpaymentTypeID == $paymentTypeID) {
								echo "selected";
							}
							echo ">$paymentType</option>";
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<label for="id">Total Amount <span style="color: red;">*</span></label>
					<input type="number" class="form-control" name="txttotalamount" id="id" value="<?php echo $ttotalAmount ?>" placeholder="ID" required="" readonly>
				</div>
				<div class="form-group">
					<label for="name">Address <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtaddress" id="address" value="<?php echo $taddress ?>" placeholder="Address" required="" readonly>
				</div>
				<div class="form-group">
					<label for="name">Latitude <span style="color: red;">*</span></label>
					<input type="number" class="form-control" name="txtlatitude" id="latitude" value="<?php echo $tlatitude ?>" placeholder="Latitude" required="" readonly>
				</div>
				<div class="form-group">
					<label for="name">Longitude <span style="color: red;">*</span></label>
					<input type="number" class="form-control" name="txtlongitude" id="longitude" value="<?php echo $tlongitude ?>" placeholder="Longitude" onchange="reloadMap()" required="" readonly>
				</div>
				<div class="form-group">
					<label for="name">Rating <span style="color: red;">*</span></label>
					<input type="number" class="form-control" name="txtrating" id="rating" value="<?php echo $trating ?>" placeholder="Rating" required="">
				</div>
				<div class="col-4 form-group">
					<label for="id">Delivery Type <span style="color: red;">*</span></label>
					<select class="form-select" id="sltdeliverytype" name="sltdeliverytype">
						<option>-- Select Delivery Type --</option>
						<?php
						for ($i=0; $i<count($delivery_type_arr); $i++) { 
							$deliveryType=$delivery_type_arr[$i];
							?>
							<option value=<?php echo $deliveryType ?>
							<?php
							if ($tdeliveryType == $deliveryType) {
								echo "selected";
							}
							echo ">$delivery_type_str_arr[$i]</option>";
						}
						?>
					</select>
				</div> 
				<div class="col-4 form-group">
					<label for="id">Cart Status <span style="color: red;">*</span></label>
					<select class="form-select" id="sltcartstatus" name="sltcartstatus">
						<option>-- Select Cart Status --</option>
						<?php
						for ($i=0; $i<count($cart_status_arr); $i++) { 
							$cartStatus=$cart_status_arr[$i];
							?>
							<option value=<?php echo $cartStatus ?>
							<?php
							if ($tcartStatus == $cartStatus) {
								echo "selected";
							}
							echo ">$cart_status_str_arr[$i]</option>";
						}
						?>
					</select>
				</div> 
				<div class="col-4 form-group">
					<label for="id">Payment Status <span style="color: red;">*</span></label>
					<select class="form-select" id="sltpaymentstatus" name="sltpaymentstatus">
						<option>-- Select Payment Status --</option>
						<?php
						for ($i=0; $i<count($payment_status_arr); $i++) { 
							$paymentStatus=$payment_status_arr[$i];
							?>
							<option value=<?php echo $paymentStatus ?>
							<?php
							if ($tpaymentStatus == $paymentStatus) {
								echo "selected";
							}
							echo ">$payment_status_str_arr[$i]</option>";
						}
						?>
					</select>
				</div> 
				
				<?php
				if(isset($_GET['cartID'])){
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
			</form>
		</div>
	</div>
</div>
<?php
$restaurantID_list = array();
foreach($restaurant_arr as $row){
	array_push($restaurantID_list, $row['restaurantID']);
}

// user and restaurant tables are not called with * cause same-column-name addresses overlap
// and only the last column address is showed;
$query = "SELECT 
					c.*, 
					u.userID, u.firstName, u.lastName, u.email, 
					r.restaurantID, r.restaurantName, 
					p.* 
					FROM cart c, user u, restaurant r, paymentType p
					WHERE c.userID = u.userID
					AND c.restaurantID = r.restaurantID
					AND c.paymentTypeID = p.paymentTypeID
					AND c.restaurantID IN (".implode(',', $restaurantID_list).")
					ORDER BY c.cartID DESC";

$result = mysqli_query($connection, $query);
$count = mysqli_num_rows($result);
$cart_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

// Defining required variables for pagination
$paginate_array = paginate($count);
$entry_count = $paginate_array[0];
$actual_entry_count = $paginate_array[1];
$page_count = $paginate_array[2];
$pgNo = $paginate_array[3];
$pg_idx_start = $paginate_array[4];
$pg_idx_end = $paginate_array[5];

if($count<1){
	echo "<p>No Record Found!</p>";
}
else{
?>
<div class="col-lg-12 stretch-card">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title">Cart List:</h4>
			<ul class="nav nav-tabs" role="tablist">
				<a href="managecart.php?pgNo=<?=1?>" class="nav-link"><<</a>
				<?php
				for($i=$pg_idx_start; $i<=$pg_idx_end; $i++){
				?>
					<li class="nav-item">
            <a href="managecart.php?pgNo=<?=$i?>" class="nav-link 
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
				<a href="managecart.php?pgNo=<?=$page_count?>" class="nav-link">>></a>
			</ul>
			<div class="table-responsive pt-3">
				<table class="table datatable table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Cart ID</th>
							<th>User ID</th>
							<th>User Name</th>
							<th>Restaurant ID</th>
							<th>Restaurant Name</th>
							<th>Payment Type</th>
							<th>Total Amount</th>
							<th>Address</th>
							<th>Latitude</th>
							<th>Longitude</th>
							<th>Rating</th>
							<th>Delivery Type</th>
							<th>Cart Status</th>
							<th>Payment Status</th>
					</thead>
					<tbody>
					<?php
					$idx = ($pgNo-1)*$entry_count;
					for($i=$idx; $i<$idx+$actual_entry_count; $i++){
						$rows=$cart_arr[$i];
						$cartID = $rows['cartID'];
						$userID = $rows['userID'];
						$firstName = $rows['firstName'];
						$lastName = $rows['lastName'];
						$fullName = $firstName . " " . $lastName;
						// $email = $rows['email'];
						$restaurantID = $rows['restaurantID'];
						$restaurantName = $rows['restaurantName'];
						$paymentType = $rows['paymentType'];
						$totalAmount = $rows['totalAmount'];
						$address = $rows['address'];
						$latitude = $rows['latitude'];
						$longitude = $rows['longitude'];
						$rating = $rows['rating'];
						$deliveryType = $rows['deliveryType'];
						$cartStatus = $rows['cartStatus'];
						$paymentStatus = $rows['paymentStatus'];
					?>
						<tr>
								<th><?php echo $cartID ?></th>
								<th><?php echo $userID ?></th>
								<th><?php echo $fullName ?></th>
								<td><?php echo $restaurantID ?></td>
								<td><?php echo $restaurantName ?></td>
								<td><?php echo $paymentType ?></td>
								<td><?php echo $totalAmount ?></td>
								<td><?php echo $address ?></td>
								<td><?php echo $latitude ?></td>
								<td><?php echo $longitude ?></td>
								<td><?php echo $rating ?></td>
								<td><?php echo $delivery_type_str_arr[$deliveryType] ?></td>
								<td><?php echo $cart_status_str_arr[$cartStatus] ?></td>
								<td><?php echo $payment_status_str_arr[$paymentStatus] ?></td>
								<td>
									<a href="managecart.php?cartID=<?=$cartID?>&mode=edit" class="btn btn-info btn-rounded">Edit</a>
									<a href="managecart.php?cartID=<?=$cartID?>&mode=delete" class="btn btn-danger btn-rounded" onclick="return confirm_delete('<?php echo $cartID ?>')">Delete</a>
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
