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
						<?php echo "user id is ", $userID_sess ?>
						<p class="f_400 w_color f_size_16 l_height26">Why I say old chap that is spiffing off his nut arse pear shaped plastered <br> Jeffrey bodge barney some dodgy.!!</p>
				</div>
		</div>
</section>

<section class="faq_area bg_color sec_pad">
		<div class="container">
				<div class="row">
						<div>
								<div class="tab-content faq_content" id="myTabContent">
										<div class="tab-pane fade show active" id="purchas" role="tabpanel" aria-labelledby="purchas-tab">
												<h3 class="f_p f_size_22 f_500 t_color3 mb_20">Your past Carts and Orders</h3>
												<div id="accordion">
														<?php
														for($i=0; $i<$count; $i++){
															$row = $cart_arr[$i];
															$cartID = $row['cartID'];
															$restaurantID = $row['restaurantID'];
															$paymentTypeID = $row['paymentTypeID'];
															$totalAmount = $row['totalAmount'];
															$address = $row['address'];

															$collapseID = 'collapse'.$i+1;
															$headingID = 'heading'.$i+1;
															?>
															<div class="card">
																	<div class="card-header" id="<?php echo $headingID ?>">
																		<h5 class="mb-0">
																			<button class="btn btn-link collapsed" data-toggle="collapse" data-target="<?php echo '#'.$collapseID ?>" aria-expanded="false" aria-controls="<?php echo $collapseID ?>">
																					ID:<?php echo $cartID ?><i class="ti-plus"></i><i class="ti-minus"></i>
																					From <?php echo $restaurantID ?>
																					Address: <?php echo $address ?>
																					Total: <?php echo $totalAmount ?> Kyats
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
										</div>
								</div>
						</div>
				</div>
		</div>
</section>
<?php include 'footer.php'; ?>