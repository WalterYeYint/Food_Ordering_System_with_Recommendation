<?php 
	include 'headtag.php';
	include 'header.php';
	include 'dbconnect.php'; 

	if(isset($_GET['restaurantID'])){
		$restaurantID=$_GET['restaurantID'];
		$select = "SELECT * FROM restaurant
							WHERE restaurantID = '$restaurantID'";
		$result = mysqli_query($connection, $select);
		$count=mysqli_num_rows($result);
		$restaurant_data_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
		$restaurantName = $restaurant_data_arr[0]["restaurantName"];
		$restaurant_latitude = $restaurant_data_arr[0]["latitude"];
		$restaurant_longitude = $restaurant_data_arr[0]["longitude"];
		$KPayPhoneNo = $restaurant_data_arr[0]["KPayPhoneNo"];
		$restaurantImage = $arr['image'];

		if($restaurantImage == ""){
			$restaurantImage = "img/restaurants/default_img.jpg";
		}

		if(isset($_SESSION['restaurantID']) AND ($_SESSION['restaurantID'] != $restaurantID)){
			$cartID = $_SESSION["cartID"];
			$update = "UPDATE cart
									SET restaurantID = '$restaurantID'
									WHERE cartID = '$cartID'";
			$result=mysqli_query($connection,$update);
			if($result) {
				$_SESSION['restaurantID'] = $restaurantID;
				$_SESSION['restaurantName'] = $restaurantName;
				$_SESSION['cartID'] = $cartID;
				$_SESSION['food_ID_list'] = array();
				$_SESSION['quantity_list'] = array();
				$_SESSION['cart_item_count'] = 0;
				$_SESSION['restaurant_latitude'] = $restaurant_latitude;
				$_SESSION['restaurant_longitude'] = $restaurant_longitude;
				$_SESSION['KPayPhoneNo'] = $KPayPhoneNo;
				?>
				<script>document.getElementById('lblCartCount').innerText = <?php echo $_SESSION['cart_item_count'] ?></script>
				<?php
				echo "<script>window.alert('Restaurant in cart Updated Successfully!')</script>";
			}
			else{
				echo "<p>Something went wrong in Cart Update : " . mysqli_error($connection) . "</p>";
			}
		}
		
		if(isset($_SESSION['cartID'])){
			$cartID = $_SESSION['cartID'];
		}
		else{
			$select = "SELECT cartID
									FROM cart
									ORDER BY cartID DESC
									LIMIT 1";
			$result=mysqli_query($connection,$select);
			$count=mysqli_num_rows($result);
			$cartID = mysqli_fetch_all($result, MYSQLI_BOTH)[0]['cartID'];

			if($count <= 0){
				$cartID = 1;
			}
			else{
				$cartID += 1;
			}
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
									`paymentStatus`)
									VALUES
									('$cartID','$userID_sess', '$restaurantID', 1, 0, '', 1, 1, 3, 0, 0, 0)";
			$result=mysqli_query($connection,$insert);
			if($result) {
				echo "<script>window.alert('Cart Added Successfully!')</script>";
			}
			else{
				echo "<p>Something went wrong in Cart Entry : " . mysqli_error($connection) . "</p>";
			}
			$_SESSION['restaurantID'] = $restaurantID;
			$_SESSION['restaurantName'] = $restaurantName;
			$_SESSION['cartID'] = $cartID;
			$_SESSION['food_ID_list'] = array();
			$_SESSION['quantity_list'] = array();
			$_SESSION['restaurant_latitude'] = $restaurant_latitude;
			$_SESSION['restaurant_longitude'] = $restaurant_longitude;
		}

		// $list = $_SESSION['food_ID_list'];
		// $li = $_SESSION['quantity_list'];
		// $x = $_SESSION['restaurantID'];
		// print_r("cartID is $cartID, $restaurantID, $x");
		// echo "<br>";
		// print_r($list);
		// echo "<br>";
		// print_r($li);
	}
?>
<section class="breadcrumb_area">
	<img class="breadcrumb_shap" src=<?php echo $restaurantImage?> alt="">
	<div class="container">
		<!-- <div class="breadcrumb_content text-center">
				<h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20"><?php echo $trestaurantName ?></h1>
				<p class="f_400 w_color f_size_16 l_height26">Why I say old chap that is spiffing off his nut arse pear shaped plastered<br> Jeffrey bodge barney some dodgy.!!</p>
		</div> -->
	</div>
</section>
<section class="product_details_area bg_color sec_pad">
	<div class="container">
		<h1 class="f_700 t_color3 mb_40 wow"><?php echo $restaurantName ?></h1>
			<?php
			$query = "SELECT * FROM food
								WHERE restaurantID = '$restaurantID'";
			$result = mysqli_query($connection, $query);
			$count = mysqli_num_rows($result);
			$food_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

			$index = 0;
			for($i=0; $i<ceil($count/2); $i++) {
				?>
				<div class="row">
				<?php
				for($j=0; $j<2; $j++){
					if($index >= $count){
						break;
					}
					$rows=$food_arr[$index];
					$foodID = $rows['foodID'];
					$restaurantID = $rows['restaurantID'];
					$foodName = $rows['foodName'];  
					$price = $rows['price'];
					$image = $rows['image'];

					$index += 1;

					if($image == ""){
						$image = "img/food/default_img.jpeg";
					}
					?>
					<div class="col-lg-6">
						<img src=<?php echo $image ?> alt="" width="100" height="100">
						<div class="pr_details">
								<a href="#" class="pr_title f_size_18 f_500 f_p">
										<?php echo $foodName ?>
								</a>
								<div class="price">
										<!-- <del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>18.00</span></del> -->
										<ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $price ?></span></ins>
								</div>
								<span class="stock">in stock</span>
								<p class="f_300 f_size_15">The full monty brilliant young delinquent burke naff 
									baking cakes the wireless argy-bargy smashing!</p>
								<div class="product-qty">
									<!-- <button class="ar_top" type="button"><i class="ti-angle-up"></i></button> -->
									<!-- <input type="number" name="qty" id=<?php echo "rd_qty_$index" ?> value=<?php echo $price ?> title="Quantity:" class="manual-adjust"> -->
									<!-- <button class="ar_down" type="button"><i class="ti-angle-down"></i></button> -->
								</div>
								<div class="cart_button">
										<?php
										if(in_array($foodID, $_SESSION['food_ID_list'])){
											?>
											<button class="cart_btn" data-id="<?php echo $foodID ?>" disabled>Already in Cart</button>
										<?php
										}
										else{
											?>
											<button class="cart_btn" data-id="<?php echo $foodID ?>">Add to Cart</button>
										<?php
										}
										?>
										<a href="#" class="wish_list" data-toggle="tooltip" data-placement="top" title="ADD WISH LIST"><i class="ti-heart"></i></a>
								</div>
						</div>
					</div>
				<?php
				}
				?>
				</div>
				<br/><br/>
			<?php
			}
			?>
	</div>
</section>
<script>
	var food_id = document.getElementsByClassName("cart_btn");
	for(var i=0; i<food_id.length; i++){
		food_id[i].addEventListener("click", function(event){
			var target = event.target;
			var id = target.getAttribute("data-id");
			// var quantity = parseInt(document.getElementById('rd_qty_'+i).value, 10);
			// alert(quantity);
			// alert(i);
			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					alert(this.responseText);
					target.innerText = "Already in cart";
					target.disabled = true;
					var cart_count = parseInt(document.getElementById('lblCartCount').innerText, 10);
					cart_count += 1;
					document.getElementById('lblCartCount').innerText = cart_count;
				}
			}
			xml.open("GET", "addtocart.php?foodID="+id, true);
			xml.send();
		})
	}
</script>
<?php include 'footer.php'; ?>