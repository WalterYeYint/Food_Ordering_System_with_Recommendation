<?php 
	include 'headtag.php';
	include 'header.php';
	include 'dbconnect.php';

	$restaurantName = $_SESSION['restaurantName'];
	$restaurant_latitude = $_SESSION['restaurant_latitude'];
	$restaurant_longitude = $_SESSION['restaurant_longitude'];
	$chosen_latitude = $_SESSION['chosen_latitude'];
	$chosen_longitude = $_SESSION['chosen_longitude'];
?>
<section class="breadcrumb_area">
		<img class="breadcrumb_shap" src="img/breadcrumb/banner_bg.png" alt="">
		<div class="container">
				<div class="breadcrumb_content text-center">
						<h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20">Shopping Cart</h1>
						<p class="f_400 w_color f_size_16 l_height26">Why I say old chap that is spiffing off his nut arse pear shaped plastered<br> Jeffrey bodge barney some dodgy.!!</p>
				</div>
		</div>
</section>
<!--============= Shopping Cart ===============-->
<section class="shopping_cart_area sec_pad bg_color">
	<div class="container">
			<h5 class="f_700 t_color3 mb_40 wow">Cart for: <?php echo $restaurantName ?></h5>
			<div class="cart_title">
					<div class="row">
							<div class="col-md-2 col-2">
									<h6 class="f_p">ID</h6>
							</div>
							<div class="col-md-4 col-2">
									<h6 class="f_p">PRODUCT</h6>
							</div>
							<div class="col-md-2 col-3">
									<h6 class="f_p">PRICE</h6>
							</div>
							<div class="col-md-2 col-3">
									<h6 class="f_p"> QUANTITY</h6>
							</div>
							<div class="col-md-2 col-2">
									<h6 class="f_p">TOTAL</h6>
							</div>
							<!-- <div class="col-md-2 col-2">
									<h6 class="f_p">_____</h6>
							</div> -->
					</div>
			</div>
	</div>
	<div class="container">
		<form action="#" class="woocommerce-cart-form">
				<div class="table-responsive">
						<table class="row table cart_table mb-0">
								<tbody>
									<?php
									$quantity_list = $_SESSION['quantity_list'];
									$foodID_list = $_SESSION['food_ID_list'];
									$foodID_list_count = count($foodID_list);
									if($foodID_list_count <=0){
										echo "<h4>No order in cart</h4>";
									}
									// $query = "SELECT * FROM food
									// 					WHERE foodID IN (".implode(',', $foodID_list).")";
									// $result = mysqli_query($connection, $query);
									// $count = mysqli_num_rows($result);
									// $food_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
									for($i=0; $i<$foodID_list_count; $i++){
										$foodID = $foodID_list[$i];
										$query = "SELECT * FROM food
															WHERE foodID = '$foodID'";
										$result = mysqli_query($connection, $query);
										$count = mysqli_num_rows($result);
										$food_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
										$row = $food_arr[0];
										$foodID = $row['foodID'];
										$foodName = $row['foodName'];
										$price = $row['price'];
										$image = $row['image'];
										$quantity = $quantity_list[$i];
										$total = $price * $quantity;
										$sub_total += $total;

										if($image == ""){
											$image = "img/food/default_img.jpeg";
										}
									?>
										<tr>
												<td class="col-lg-2 col-md-2 col-sm-2 col-xs-2" data-title="ID">
														<div class="total"><?php echo $_SESSION['cartID'] ?></div>
												</td>
												<td class="product col-lg-6 col-md-5 col-sm-5 col-xs-5" data-title="PRODUCT">
														<div class="media">
																<div class="media-left">
																		<img src=<?php echo $image ?> alt="">
																</div>
																<div class="media-body">
																		<h5 class="mb-0"><?php echo $foodName ?></h5>
																</div>
														</div>
												</td>
												<td class="col-lg-2 col-md-2 col-sm-2 col-xs-2" data-title="PRICE">
														<div class="total" id=<?php echo "price_$i" ?>><?php echo $price ?></div>
												</td>
												<td class="col-lg-2 col-md-2 col-sm-2 col-xs-2" data-title="QUANTITY">
														<div class="quantity">
																<div class="product-qty">
																		<!-- <button class="ar_top" type="button" onclick="incrementValue(<?php echo $i ?>)"><i class="ti-angle-up"></i></button> -->
																		<input type="number" name="qty" id=<?php echo "qty_$i" ?> value=<?php echo $quantity ?> onchange="calculateTotal(<?php echo $i.','.$foodID_list_count ?>)" title="Quantity:" class="manual-adjust">
																		<!-- <button class="ar_down" type="button" onclick="decrementValue(<?php echo $i ?>)"><i class="ti-angle-down"></i></button> -->
																</div>
														</div>

												</td>
												<td class="col-lg-2 col-md-3 col-sm-3 col-xs-3" data-title="TOTAL">
														<div class="del-item">
															<i class="total" id=<?php echo "total_$i" ?>><?php echo $total ?></i>
															<!-- <button id=<?php echo "del_$i" ?> onclick="removefromcart(<?php echo $i ?>)"><i class="icon_close"></i></button> -->
															<a href="" id=<?php echo "del_$i" ?> onclick="removefromcart(<?php echo $i ?>)"><i class="icon_close"></i></a>
															<!-- <a href="" class="del_btn" id=<?php echo "del_$i" ?>><i class="icon_close"></i></a> -->
															<!-- <i class="icon_close" id=<?php echo "del_$i" ?> onclick="removefromcart(<?php echo $i ?>)"></i> -->
															<!-- <a href="#" class="del_btn" id=<?php echo "del_$i" ?>><i class="icon_close"></i></a> -->
														</div>
												</td>
										</tr>
									<?php
									}
									?>
								</tbody>
						</table>
				</div>
				<div class="hr"></div>
				<div class="row">
						<div class="col-lg-8 col-md-6 actions">
								<div class="action_btn">
								<a href="restaurantdetail.php?restaurantID=<?=$_SESSION['restaurantID']?>" class="btn_hover agency_banner_btn cus_mb-10">Back to Shopping</a> <br>
										<!-- <button type="submit" class="cart_btn" name="update_cart" value="Update cart">Continue Shopping</button> -->
										<!-- <button type="submit" class="cart_btn cart_btn_two" name="update_cart" value="Update cart">Update cart</button> -->
								</div>
								<!-- <h5 class="f_p f_600 f_size_18 mt_60 mb_20">Discount Code</h5>
								<div class="coupon">
										<input type="text" name="coupon_code" class="input_text" id="coupon_code" value="" placeholder="Enter your coupon code">
										<button type="submit" class="button" name="apply_coupon" value="Apply coupon">Apply</button>
								</div> -->
						</div>
						<div class="col-lg-4 col-md-6 actions">
								<div class="cart_box">
										<table class="shop_table">
												<tbody>
														<tr class="cart-subtotal">
																<th>Subtotal</th>
																<td data-title="Subtotal" id="sub_total"><span class="amount"><?php echo $sub_total ?></span></td>
														</tr>
														<tr class="cart-subtotal">
																<th>Delivery Fee</th>
																<?php
																$distance = twopoints_on_earth($restaurant_latitude, $restaurant_longitude, $chosen_latitude, $chosen_longitude);
																$deliveryFee = calculate_deliveryFee($distance);
																$grand_total = $sub_total + $deliveryFee;
																?>
																<td data-title="Subtotal" id="delivery_fee"><span class="amount"><?php echo $deliveryFee ?></span></td>
														</tr>
														<tr class="order-total">
																<th>Order totals</th>
																<td data-title="Total" class="total" id="grand_total"><?php echo $grand_total ?></td>
														</tr>
												</tbody>
										</table>
								</div>
								<a href="checkout.php" class="checkout_button"> Proceed to checkout</a>
						</div>
				</div>
		</form>
	</div>
</section>
<!--============= Shopping Cart ===============-->
<script>
	// Ajax script for changing quantity values
	var lblQty = document.getElementsByClassName("manual-adjust");
	for(var i=0; i<lblQty.length; i++){
		lblQty[i].addEventListener("change", function(event){
			var target = event.target;
			var id_str = target.getAttribute("id");
			var index = id_str.substr(4)
			var value = target.value;
			// var quantity = parseInt(document.getElementById('rd_qty_'+i).value, 10);
			// alert(quantity);
			// alert(value);
			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					alert(this.responseText);
				}
			}
			xml.open("GET", "changequantity.php?idx="+index+"&quantity="+value, true);
			xml.send();
		})
	}

	// // Ajax script for removing order from cart
	// var del_btn = document.getElementsByClassName("del_btn");
	// for(var i=0; i<del_btn.length; i++){
	// 	del_btn[i].addEventListener("click", function(event){
	// 		var target = event.target;
	// 		event.preventDefault();
	// 		var id_str = target.getAttribute("id");
	// 		var index = id_str.substr(4)
	// 		var xml = new XMLHttpRequest();
	// 		xml.onreadystatechange = function(){
	// 			if(this.readyState == 4 && this.status == 200){
	// 				alert(this.responseText);
	// 			}
	// 		}
	// 		xml.open("GET", "removefromcart.php?idx="+index, true);
	// 		xml.send();
	// 	})
	// }

</script>
<?php include 'footer.php'; ?>