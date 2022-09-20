<?php
	include 'headtag.php';
	include 'header.php';
	include 'sidebar.php';
	include '../dbconnect.php';

	// Restaurants Query that are used more than once
	if($userRoleName_sess == ADMIN OR $userRoleName_sess == CUSTOMER){
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
			$foodID=$_GET['foodID'];

			$query = "SELECT * FROM food WHERE foodID='$foodID'";
			$result = mysqli_query($connection,$query);
			$arr = mysqli_fetch_array($result);

			$tfoodID = $arr['foodID'];
			$trestaurantID = $arr['restaurantID'];
			$tfoodName = $arr['foodName'];
			$tprice = $arr['price'];
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
	}
?>
<div class="col-12 grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title">Manage Cart</h4>
			<!-- In the following form tag, -->
			<!-- Without enctype="multipart/form-data", image file name doesn't get through POST -->
			<form class="forms-sample" action="managefood.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="id">Cart ID <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtcartid" id="id" value="<?php echo $tcartID ?>" placeholder="ID" required="" readonly>
				</div>
				<div class="form-group">
					<label for="id">User ID <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtuserid" id="id" value="<?php echo $tuserID ?>" placeholder="ID" required="" readonly>
				</div>
				<div class="form-group">
					<label for="id">Restaurant ID <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtrestaurantid" id="id" value="<?php echo $trestaurantID ?>" placeholder="ID" required="" readonly>
				</div>
				<div class="form-group">
					<label for="id">PaymentType ID <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtpaymenttypeid" id="id" value="<?php echo $tpaymentTypeID ?>" placeholder="ID" required="" readonly>
				</div>
				<?php
				if(isset($_GET['foodID'])){
				?>
					<button type="submit" class="btn btn-primary me-2" name="btnupdate">Update</button>	
				<?php
				}
				else{
				?>
					<button type="submit" class="btn btn-primary me-2" name="btnsubmit">Submit</button>
				<?php
				}
				?>
				<button type="reset" class="btn btn-secondary" id="reset" name="btnreset">Cancel</button>
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
							<th>Email</th>
							<th>Restaurant ID</th>
							<th>Restaurant Name</th>
							<th>Payment Type</th>
							<th>Total Amount</th>
							<th>Address</th>
							<th>Latitude</th>
							<th>Longitude</th>
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
						$email = $rows['email'];
						$restaurantID = $rows['restaurantID'];
						$restaurantName = $rows['restaurantName'];
						$paymentType = $rows['paymentType'];
						$totalAmount = $rows['totalAmount'];
						$address = $rows['address'];
						$latitude = $rows['latitude'];
						$longitude = $rows['longitude'];
					?>
						<tr>
								<th><?php echo $cartID ?></th>
								<th><?php echo $userID ?></th>
								<th><?php echo $fullName ?></th>
								<th><?php echo $email ?></th>
								<td><?php echo $restaurantID ?></td>
								<td><?php echo $restaurantName ?></td>
								<td><?php echo $paymentType ?></td>
								<td><?php echo $totalAmount ?></td>
								<td><?php echo $address ?></td>
								<td><?php echo $latitude ?></td>
								<td><?php echo $longitude ?></td>
								<td>
									<a href="managecart.php?cartID=<?=$cartID?>&mode=edit" class="btn btn-success">Edit</a>
									<a href="managecart.php?cartID=<?=$cartID?>&mode=delete" class="btn btn-danger" onclick="return confirm_delete('<?php echo $cartID ?>')">Delete</a>
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
