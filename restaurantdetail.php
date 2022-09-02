<?php 
	include 'headtag.php';
	include 'header.php';
	include 'dbconnect.php'; 

	if(isset($_GET['restaurantID'])){
		$restaurantID=$_GET['restaurantID'];

		$query = "SELECT * FROM restaurant WHERE restaurantID='$restaurantID'";
		$result = mysqli_query($connection,$query);
		$arr = mysqli_fetch_array($result);

		$restaurantID = $arr['restaurantID'];
		$restaurantName = $arr['restaurantName'];
		$restaurantImage = $arr['image'];

		if($restaurantImage == ""){
			$restaurantImage = "img/restaurants/default_img.jpg";
		}
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

			for($i=0; $i<$count; $i++) { 
				$rows=$food_arr[$i];
				$foodID = $rows['foodID'];
				$restaurantID = $rows['restaurantID'];
				$foodName = $rows['foodName'];  
				$price = $rows['price'];
				$image = $rows['image'];

				if($image == ""){
					$image = "img/food/default_img.jpeg";
				}

				?>
				<div class="row">
				<?php
				for($j=0; $j<2; $j++){
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
										<button class="ar_top" type="button"><i class="ti-angle-up"></i></button>
										<input type="number" name="qty" id="qty" value="1" title="Quantity:" class="manual-adjust">
										<button class="ar_down" type="button"><i class="ti-angle-down"></i></button>
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
</section>
<?php include 'footer.php'; ?>