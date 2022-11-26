<?php
session_start();
include '../constants.php';
include 'pagination.php';
include '../dbconnect.php';

$userID_sess=$_SESSION['auth_user']['userID'];
$firstName_sess=$_SESSION['auth_user']['firstName'];
$lastName_sess=$_SESSION['auth_user']['lastName'];
$userRoleID_sess=$_SESSION['auth_user']['userRoleID'];
$userRoleName_sess=$_SESSION['auth_user']['userRoleName'];

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

$restaurantID_list = array();
foreach($restaurant_arr as $row){
  array_push($restaurantID_list, $row['restaurantID']);
  if(count($restaurantID_list) <= 0){
    $restaurantID_list_implode = "(0)";
  }
  else{
    $restaurantID_list_implode = "(".implode(',', $restaurantID_list).")";
  }
}
?>
<?php
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
					AND c.restaurantID IN $restaurantID_list_implode
					ORDER BY c.cartID DESC";

$result = mysqli_query($connection, $query);
$count = mysqli_num_rows($result);
$cart_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

$restaurantName = $cart_arr[0]['restaurantName'];

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
	<div class="card">
		<div class="card-body">
			<h4 class="card-title">Order Display for <b><?php echo $restaurantName ?></b></h4>
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
							<!-- <th>Restaurant Name</th> -->
							<th>Payment Type</th>
							<th>Total Amount</th>
							<th>Address</th>
							<th>Payment Status</th>
							<th>Cart Status</th>
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
						// $restaurantName = $rows['restaurantName'];
						$paymentType = $rows['paymentType'];
						$totalAmount = $rows['totalAmount'];
						$address = $rows['address'];
						$wrappedaddress = wordwrap($address, 20, "<br/>\n", true);
						$paymentStatus = $rows['paymentStatus'];
						$cartStatus = $rows['cartStatus'];
						$lblPaymentStatus = 'togglePaymentStatus';
						?>
						<tr>
								<th><?php echo $cartID ?></th>
								<th><?php echo $userID ?></th>
								<th><?php echo $fullName ?></th>
								<!-- <td><?php echo $restaurantName ?></td> -->
								<td><?php echo $paymentType ?></td>
								<td><?php echo $totalAmount ?></td>
								<td><?php echo $wrappedaddress ?></td>
								<td><?php echo $paymentStatus ?></td>
								<td><?php echo $cartStatus ?></td>
								<td>
									<button class="btn btn-primary me-2" id=<?php echo "paystat_$i" ?> onclick="updateStatus(<?php echo $cartID ?>, '<?php echo $lblPaymentStatus ?>')">Set Paid</button>
									<!-- <a href="updatestatus.php?cartID=<?=$cartID?>&mode=togglePaymentStatus" class="btn btn-success">Set Paid</a> -->
									<br/><br/>
									<?php
									$lblCartStatus = "";
									$disbled = "";
									switch($cartStatus){
										case CART_CHECKED_OUT:
											$lblCartStatus = "Set InProgress";
											break;
										case CART_IN_PROGRESS:
											$lblCartStatus = "Set Completed";
											break;
										case CART_COMPLETED:
											$lblCartStatus = "Set Completed";
											$disabled = "disabled='disabled'";
											break;
										case CART_CANCELLED:
											$lblCartStatus = "Cart Cancelled";
											// $disabled = "disabled='disabled'";
											break;
									}
									?>
									<button class="btn btn-success" id=<?php echo "cartstat_$i" ?> <?php echo $disabled ?> onclick="updateStatus(<?php echo $cartID ?>, 'toggleCartStatus')"><?php echo $lblCartStatus ?></button>
									<button class="btn btn-danger" id=<?php echo "cartcancel_$i" ?> onclick="updateStatus(<?php echo $cartID ?>, 'cancel')">Cancel Order</button>
									<!-- <a href="updatestatus.php?cartID=<?=$cartID?>&mode=toggleCartStatus" class="btn btn-success" <?php echo $disabled ?>><?php echo $lblCartStatus ?></a> -->
									<!-- <a href="updatestatus.php?cartID=<?=$cartID?>&mode=cancel" class="btn btn-danger">Cancel Order</a> -->
								</td>
						</tr>

						<?php
						$select = "SELECT fo.*, f.* 
											FROM foodorder fo, food f
											WHERE fo.foodID = f.foodID
											AND fo.cartID = '$cartID'";
						$result = mysqli_query($connection, $select);
						foreach($result as $row):
							$foodorderID = $row['foodorderID'];
							$foodName = $row['foodName'];
							$price = $row['price'];
							$quantity = $row['quantity'];
							?>
							<tr>
								<td colspan="5"><?php echo "(".$foodorderID.")".$foodName." x ".$quantity ?></td>
							</tr>
							<?php
						endforeach;
					}
					?>
					</tbody>
				</table>
		</div>
	</div>
	<?php
	}
	?>
<script>

</script>