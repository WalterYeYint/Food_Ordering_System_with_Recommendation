<?php 
	include 'headtag.php';
	include 'header.php';
	include 'dbconnect.php';

	$query = "SELECT * FROM cart WHERE userID = '$userID_sess'";
	$result = mysqli_query($connection,$query);
	$count = mysqli_num_rows($result);
	$cart_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
?>

<section class="breadcrumb_area">
		<img class="breadcrumb_shap" src="img/breadcrumb/banner_bg.png" alt="">
		<div class="container">
				<div class="breadcrumb_content text-center">
						<h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20">My Orders</h1>
				</div>
		</div>
</section>

<section class="faq_area bg_color sec_pad">
		<div class="container">
				<div class="row">
						<div>
								<div class="tab-content faq_content" id="myTabContent">
										<div class="tab-pane fade show active" id="purchas" role="tabpanel" aria-labelledby="purchas-tab">
												<h3 class="f_p f_size_22 f_500 t_color3 mb_20">Your past Carts and Orders:</h3>
												<?php
												if($count <= 0){
													?>
													<br/>
													<h3>&emsp;No past orders.</h3>
													<?php
												}
												else{
													?>
														<div id="accordion">
															<?php
															for($i=0; $i<$count; $i++){
																$row = $cart_arr[$i];
																$cartID = $row['cartID'];
																$restaurantID = $row['restaurantID'];
																$paymentTypeID = $row['paymentTypeID'];
																$totalAmount = $row['totalAmount'];
																$address = $row['address'];
																$latitude = $row['latitude'];
																$longitude = $row['longitude'];

																$collapseID = 'collapse'.$i+1;
																$headingID = 'heading'.$i+1;

																$select = "SELECT * FROM restaurant WHERE restaurantID = '$restaurantID'";
																$result = mysqli_query($connection,$select);
																$restaurant_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
																$restaurantName = $restaurant_arr[0]['restaurantName'];
																$restaurant_latitude = $restaurant_arr[0]['latitude'];
																$restaurant_longitude = $restaurant_arr[0]['longitude'];

																$distance = twopoints_on_earth($restaurant_latitude, $restaurant_longitude, $latitude, $longitude);
																$deliveryFee = calculate_deliveryFee($distance)
																?>
																<div class="card">
																		<div class="card-header" id="<?php echo $headingID ?>">
																			<h5 class="mb-0">
																				<button class="btn btn-link collapsed" data-toggle="collapse" data-target="<?php echo '#'.$collapseID ?>" aria-expanded="false" aria-controls="<?php echo $collapseID ?>">
																						<b>ID:</b> <?php echo $cartID ?><i class="ti-plus"></i><i class="ti-minus"></i><br/>
																						<b>From:</b> <?php echo $restaurantName ?><br/>
																						<b>Address:</b> <?php echo $address ?><br/>
																						<b>Total:</b> <?php echo $totalAmount ?> Kyats
																				</button>
																			</h5>
																		</div>
																		<div id="<?php echo $collapseID ?>" class="collapse" aria-labelledby="<?php echo $$headingID ?>" data-parent="#accordion">
																			<div class="card-body">
																				<table>
																					<?php
																					$select = "SELECT fo.*, f.*
																											FROM foodorder fo, food f
																											WHERE fo.foodID = f.foodID
																											AND fo.cartID = '$cartID'
																											ORDER BY fo.foodorderID DESC";
																					$result = mysqli_query($connection, $select);
																					foreach($result as $row):
																						$foodorderID = $row['foodorderID'];
																						$foodName = $row['foodName'];
																						$price = $row['price'];
																						$quantity = $row['quantity'];
																						?>
																						<tr>
																							<td><?php echo "(".$foodorderID.")" ?></td>
																							<td><?php echo $foodName ?></td>
																							<td>&emsp;</td>
																							<td><?php echo $price ?></td>
																							<td><?php echo " x " ?></td>
																							<td><?php echo $quantity ?></td>
																							<td><?php echo " = " ?></td>
																							<td><?php echo $price*$quantity ?></td>
																						</tr>
																						<?php
																					endforeach;
																					?>
																					<tr>
																						<td></td>
																						<td colspan="5">Delivery Fee</td>
																						<td><?php echo " = " ?></td>
																						<td><?php echo $deliveryFee ?></td>
																					</tr>
																					<tr>
																						<td></td>
																						<td colspan="5"><?php echo "Grand Total" ?></td>
																						<td><?php echo " = " ?></td>
																						<td><?php echo $totalAmount ?></td>
																					</tr>
																				</table>
																			</div>
																		</div>
																</div>
															<?php
															}
															?>
														</div>
													<?php
												}
												?>
										</div>
								</div>
						</div>
				</div>
		</div>
</section>
<?php include 'footer.php'; ?>