<?php 
  include 'headtag.php';
  include 'header.php';
  include 'dbconnect.php';
	include 'send_email.php';

	function get_foodID_list_info($connection, $foodID_list){
		if(count($foodID_list) <= 0){
			$foodID_list_implode = "(0)";
		}
		else{
			$foodID_list_implode = "(".implode(',', $foodID_list).")";
		}
		$query = "SELECT * FROM food
					WHERE foodID IN $foodID_list_implode";
		$result = mysqli_query($connection, $query);
		$count = mysqli_num_rows($result);
		$food_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
		$foodName_list = array();
		$price_list = array();
		for($i=0; $i<$count; $i++){
			$row = $food_arr[$i];
			array_push($foodName_list, $row['foodName']);
			array_push($price_list, $row['price']);
		}
		
		$return_arr = array();
		array_push($return_arr, $food_arr);
		array_push($return_arr, $count);
		array_push($return_arr, $foodName_list);
		array_push($return_arr, $price_list);
		return $return_arr;
	}

	$restaurantID = $_SESSION['restaurantID'];
  $restaurantName = $_SESSION['restaurantName'];
	$restaurant_latitude = $_SESSION['restaurant_latitude'];
	$restaurant_longitude = $_SESSION['restaurant_longitude'];
	$KPayPhoneNo = $_SESSION['KPayPhoneNo'];
	$chosen_address = $_SESSION['chosen_address'];
	$chosen_latitude = $_SESSION['chosen_latitude'];
	$chosen_longitude = $_SESSION['chosen_longitude'];

	$distance = twopoints_on_earth($restaurant_latitude, $restaurant_longitude, $chosen_latitude, $chosen_longitude);
	$deliveryFee = calculate_deliveryFee($distance);

	if(isset($_POST['btnsubmit'])){
		$rdodelivery = $_POST['rdodelivery'];
		$rdopayment = $_POST['rdopayment'];
		$cartID = AutoID('cart', 'cartID');		// Cannot use the id in session since other users' carts might enter the db 
		$totalAmount = $_POST['txtgrandtotal'];
		$cart_checked_out = CART_CHECKED_OUT;
		$foodID_list_sess = $_SESSION['food_ID_list'];
		$quantity_list_sess = $_SESSION['quantity_list'];
		$foodID_list_count = count($foodID_list_sess);
		$date = date('Y-m-d');
		$time = date('h:i:s');

		// Insert a new cart
		$insert = "INSERT INTO cart
								(`cartID`, 
								`userID`, 
								`restaurantID`,
								`paymentTypeID`,
								`totalAmount`,
								`address`,
								`latitude`,
								`longitude`,
								`rating`,
								`deliveryType`,
								`cartStatus`,
								`paymentStatus`,
								`date`,
								`time`)
								VALUES
								('$cartID','$userID_sess', '$restaurantID', '$rdopayment', '$totalAmount', '$chosen_address', '$chosen_latitude', '$chosen_longitude', 3, '$rdodelivery', '$cart_checked_out', 0, '$date', '$time')";
		$result=mysqli_query($connection,$insert);
		if($result) {
			echo "<script>window.alert('Cart Added Successfully!')</script>";
		}
		else{
			echo "<p>Something went wrong in Cart Entry : " . mysqli_error($connection) . "</p>";
		}
		
		$foodID_list_info_arr = get_foodID_list_info($connection, $foodID_list_sess);
		$count = $foodID_list_info_arr[1];
		$foodName_list = $foodID_list_info_arr[2];
		$price_list = $foodID_list_info_arr[3];

		send_email('sam.wilson42@yahoo.com', $firstName_sess, $lastName_sess, $restaurantName, $cartID, $count, $foodName_list, $price_list, 
								$quantity_list_sess, $deliveryFee, $payment_type_str_arr[$rdopayment], $foodID_list_sess, $totalAmount, $chosen_address, $chosen_latitude, 
								$chosen_longitude, $delivery_type_str_arr[$rdodelivery]);
		echo "<script>window.alert('Email Successfully Sent!')</script>";

		// Loop and insert all food inside the cart
		for($i=0; $i<$foodID_list_count; $i++){
			$foodorderID = AutoID('foodorder', 'foodorderID');
			$foodID = $foodID_list_sess[$i];
			$quantity = $quantity_list_sess[$i];
			$insert = "INSERT INTO foodorder
								(`foodorderID`, `foodID`, `cartID`, `quantity`, `rating`)
								VALUES
								('$foodorderID', '$foodID', '$cartID', '$quantity', 5)";
			$result=mysqli_query($connection,$insert);
		}
		unset($_SESSION['restaurantID']);
		unset($_SESSION['restaurantName']);
		unset($_SESSION['cartID']);
		unset($_SESSION['food_ID_list']);
		unset($_SESSION['quantity_list']);
		unset($_SESSION['restaurant_latitude']);
		unset($_SESSION['restaurant_longitude']);
		unset($_SESSION['KPayPhoneNo']);
		$_SESSION['cart_item_count'] = 0;
		?>
		<script>document.getElementById('lblCartCount').innerText = <?php echo $_SESSION['cart_item_count'] ?></script>
		<?php
		echo "<script>window.alert('Cart checked out Successfully!')</script>";
		echo "<script>window.location='restaurantlist.php'</script>";
	}
?>
<section class="breadcrumb_area">
	<img class="breadcrumb_shap" src="img/breadcrumb/banner_bg.png" alt="">
	<div class="container">
		<div class="breadcrumb_content text-center">
			<h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20">Checkout</h1>
			<p class="f_400 w_color f_size_16 l_height26">Why I say old chap that is spiffing off his nut arse pear shaped plastered<br> Jeffrey bodge barney some dodgy.!!</p>
		</div>
	</div>
</section>

<!--============= Shopping Cart ===============-->
<section class="checkout_area bg_color sec_pad">
	<div class="container">
		<form action="checkout.php" method="post">
			<div class="row">
				<div class="col-md-5">
					<div class="checkout_content">
						<!-- <div class="return_customer">
							<i class="icon_error-circle_alt"></i>
							Returning customer? <a data-toggle="collapse" href="#coupon" aria-expanded="false" class="collapsed">Click here to login</a>
						</div>
						<div class="collapse tab_content" id="coupon">
							<p class="f_p f_400">If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing & Shipping section.</p>
							<div class="login_form">
								<div class="row">
									<div class="col-lg-6">
										<input type="text" name="text" class="form-control" placeholder="Username or Email">
									</div>
									<div class="col-lg-6">
										<input type="email" name="EMAIL" class="form-control" placeholder="Password">
									</div>
									<div class="col-lg-12">
										<div class="login_button">
											<input type="checkbox" value="None" id="squared1" name="check">
											<label class="l_text" for="squared1">Remember Me</label>
											<button class="btn login_btn" type="submit">Login</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="return_customer">
							<i class="icon_error-circle_alt"></i>
							Have a coupon? <a data-toggle="collapse" href="#coupon_two" aria-expanded="false" class="collapsed">Click here to enter your code</a>
						</div>
						<div class="collapse tab_content" id="coupon_two">
							<p class="f_p f_400">If you have a coupon code, please apply it below.</p>
							<div class="login_form coupon_form">
								<input type="text" name="text" class="form-control" placeholder="Coupon code">
								<button class="btn login_btn" type="submit">Applycoupon</button>
							</div>
						</div> -->
						<h3 class="checkout_title f_p f_600 f_size_20 mb_40">
							Personal Info
						</h3>
						<div class="row">
							<div class="col-md-6">
								First Name
								<input type="text" value=<?php echo $firstName_sess ?> placeholder="First Name" readonly>
							</div>
							<div class="col-md-6">
								Last Name
								<input type="text" value=<?php echo $lastName_sess ?> placeholder="Last Name" readonly>
							</div>
							<!-- <div class="col-md-12">
								Company Name (Optional)
								<input type="text" placeholder="Company Name (optional)">
							</div> -->
						</div>
						<div class="row">
							<div class="col-lg-12">
								Email
								<input type="text" value=<?php echo $email_sess ?> placeholder="Email address">
							</div>
						</div>
						<!-- <div class="row">
							<div class="col-lg-12">
								Phone
								<input type="text" value=<?php echo $lastName_sess ?> placeholder="Phone">
							</div>
						</div> -->
						<!-- <div class="row">
							<div class="col-md-12">
								<label>Country<abbr class="required" title="required">*</abbr></label>
								<select class="selectpickers">
									<option value="menu_order">United Kingdom (UK)</option>
									<option value="popularity">United Kingdom (UK)</option>
									<option value="rating">Usa</option>
									<option value="date">Rsa</option>
									<option value="date">Canada</option>
								</select>
							</div>
						</div> -->
						<div class="row">
							<div class="col-md-12">
								<label>Delivery Address<abbr class="required" title="required">*</abbr></label>
								Full Address
								<input type="text" value=<?php echo $chosen_address ?> placeholder="Full Address" readonly>
								<!-- <select class="selectpickers">
									<option value="menu_order">District</option>
									<option value="popularity">Dhaka</option>
									<option value="rating">Usa</option>
									<option value="date">Rsa</option>
									<option value="date">Canada</option>
								</select> -->
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								Latitude
								<input type="text" value=<?php echo $chosen_latitude ?> placeholder="Latitude" readonly>
							</div>
							<div class="col-lg-6">
							  Longitude	
							  <input type="text" value=<?php echo $chosen_longitude ?> placeholder="Longitude" readonly>
							</div>
						</div>
						<div class="row">
							<iframe
							id="map"
							width="400"
							height="300"
							style="border:0"
							loading="lazy"
							allowfullscreen
							referrerpolicy="no-referrer-when-downgrade"
							src="https://www.google.com/maps/embed/v1/view?key=AIzaSyAC7Rj163G5vNrR5_1AEncatw3OHcjTock&center=<?php echo $chosen_latitude.",".$chosen_longitude ?>&zoom=18"
							>
							</iframe>
						</div>
						<!-- <div class="row">
							<div class="col-lg-6 mt-20 mb-20">
								<input type="checkbox" value="None" id="squared2" name="check">
								<label class="l_text" for="squared2">create an account?</label>
							</div>
						</div> -->
						<!-- <div class="row">
							<div class="col-lg-12">
								<label>Order Notes</label>
								<textarea placeholder="Notes about your arder, e.g. special notes for delivery"></textarea>
							</div>
						</div> -->
					</div>
				</div>
				<div class="col-md-7">
					<div class="cart_total_box">
						<h3 class="checkout_title f_p f_600 f_size_20 mb_20">
							Your order to: <?php echo $restaurantName ?>
						</h3>
						<div id="order_review" class="woocommerce-checkout-review-order">
							<table class="shop_table woocommerce-checkout-review-order-table">
								<tbody>
								  <?php
								  $foodID_list_sess = $_SESSION['food_ID_list'];
								  $quantity_list_sess = $_SESSION['quantity_list'];

									$foodID_list_info_arr = get_foodID_list_info($connection, $foodID_list_sess);
									$food_arr = $foodID_list_info_arr[0];
									$count = $foodID_list_info_arr[1];
									$foodName_list = $foodID_list_info_arr[2];
									$price_list = $foodID_list_info_arr[3];

								  for($i=0; $i<$count; $i++){
									$foodName = $foodName_list[$i];
									$price = $price_list[$i];
									$quantity = $quantity_list_sess[$i];
									$total = $price * $quantity;
									$sub_total += $total;
									?>
									<tr class="order_item">
										<td><?php echo $foodName ?></td>
										<td class="price"><?php echo $price ?></td>
										<td>x</td>
										<td><?php echo $quantity ?></td>
										<td>=</td>
										<td class="price"><?php echo $total ?> Ks</td>
									</tr>
								  <?php
								  }
								  ?>
									<tr class="order_item">
										<td><hr></td>
									</tr>
									<tr class="order_item">
										<td>Subtotal</td>
										<td></td>
										<td></td>
										<td></td>
										<td>=</td>
										<td><?php echo $sub_total ?> Ks</td>
									</tr>
									<?php
									$grand_total = $sub_total + $deliveryFee;
									?>
									<tr class="order_item">
										<td>Delivery Fees</td>
										<td></td>
										<td></td>
										<td></td>
										<td>=</td>
										<td><?php echo $deliveryFee ?> Ks</td>
									</tr>
									<tr class="order_item">
										<td><b>Grand Total</b></td>
										<td></td>
										<td></td>
										<td></td>
										<td><b>=</b></td>
										<td><b><?php echo $grand_total ?> Ks</b></td>
										<input type="hidden" name="txtgrandtotal" value="<?php echo $grand_total; ?>">
									</tr>
								</tbody>
							</table>
							<br/>
							<h5>Delivery Type:</h5>
							<ul class="list-unstyled payment_list">
								<li class="payment">
									<div class="radio-btn">
										<input type="radio" value=<?php echo DELIVERY ?> id="deli" name="rdodelivery" checked>
										<label for="deli"></label>
									</div>
									<h6>Delivery</h6>
								</li>
								<li class="payment">
									<div class="radio-btn">
										<input type="radio" value=<?php echo PICK_UP ?> id="pickup" name="rdodelivery">
										<label for="pickup"></label>
									</div>
									<h6>Pick Up</h6>
								</li>
							</ul>
							<br/>
							<h5>Payment Type:</h5>
							<ul class="list-unstyled payment_list">
								<?php
								$select="SELECT *
										FROM paymentType";
								$result=mysqli_query($connection,$select);
								$count=mysqli_num_rows($result);
								$paymentType_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
								for($i=0; $i<$count; $i++){
									$row = $paymentType_arr[$i];
									$paymentTypeID = $row['paymentTypeID'];
									$paymentType = $row['paymentType'];
								?>
									<li class="payment">
										<div class="radio-btn">
											<input type="radio" value=<?php echo $paymentTypeID ?> id=<?php echo $paymentTypeID ?> name="rdopayment" 
															<?php
															if($paymentType == CASH_ON_DELIVERY){
																echo "checked";
															}
															?>>
											<label for=<?php echo $paymentTypeID ?>></label>
										</div>
										<h6><?php echo $paymentType ?></h6>
										<?php
										if($paymentType == KBZPAY){
											?>
											<div>
												<b>Pay to this KBZPay number: <?php echo $KPayPhoneNo ?></b>
											</div>
										<?php
										}
										?>
									</li>
								<?php
								}
								?>
								<br/>
								<div class="note">
									Please insert your name in notes. For online payments, your order won't be processed until the 
									payment has arrived.
								</div>
								<!-- <li class="payment">
									<div class="radio-btn">
										<input type="radio" value="None" id="squaredseven" name="check">
										<label for="squaredseven"></label>
									</div>
									<h6>Check Payment</h6>
								</li> -->
							</ul>
							<div class="condition">
								<p>Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our privacy policy.</p>
								<!-- <input type="checkbox" value="None" id="squarednine" name="check"> -->
								<!-- <label class="l_text" for="squarednine">I have read and agree to the website terms and conditions <span>*</span></label> -->
							</div>
							<button type="submit" class="button" name="btnsubmit">Place Order</button>
						</div>
					</div>
				</div>
			</div>
		</form>

	</div>
</section>
<!--============= Shopping Cart ===============-->
<?php include 'footer.php'; ?>