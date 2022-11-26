<?php 
	include 'headtag.php';
	include 'header.php';
	include 'dbconnect.php'; 

	$user_address = $_SESSION['auth_user']['address'];
	$user_latitude = $_SESSION['auth_user']['user_latitude'];
	$user_longitude = $_SESSION['auth_user']['user_longitude'];

	if(!isset($_SESSION['auth_user'])){
		echo "<script>window.alert('Please login first!')</script>";
		echo "<script>window.location='login.php'</script>";
	}
	elseif(isset($_POST['btnsubmit'])){
		$_SESSION['chosen_address'] = $_POST['txtaddress'];
		$_SESSION['chosen_latitude'] = $_POST['txtlatitude'];
		$_SESSION['chosen_longitude'] = $_POST['txtlongitude'];
	}
	elseif(isset($_POST['btnownsubmit'])){
		$_SESSION['chosen_address'] = $user_address;
		$_SESSION['chosen_latitude'] = $user_latitude;
		$_SESSION['chosen_longitude'] = $user_longitude;
	}

	if(isset($_SESSION['chosen_latitude'])){
		$chosen_address = $_SESSION['chosen_address'];
		$chosen_latitude = $_SESSION['chosen_latitude'];
		$chosen_longitude = $_SESSION['chosen_longitude'];
	}
	else{
		$chosen_address = $user_address;
		$chosen_latitude = $user_latitude;
		$chosen_longitude = $user_longitude;
		$_SESSION['chosen_address'] = $chosen_address;
		$_SESSION['chosen_latitude'] = $chosen_latitude;
		$_SESSION['chosen_longitude'] = $chosen_longitude;
	}
?>
<section class="breadcrumb_area">
	<img class="breadcrumb_shap" src="img/breadcrumb/banner_bg.png" alt="">
	<div class="container">
		<div class="breadcrumb_content text-center">
			<!-- <h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20"><?php echo ($recommended_food_dict) ?></h1> -->
			<form class="row apply_form" action="restaurantlist.php" method="post" enctype="multipart/form-data">
				<div class="form-group col-lg-6">
					<input type="text" name="txtaddress" value="<?php echo $chosen_address ?>" placeholder="Enter an address" required>
					<br/><br/>
					<input type="text" name="txtlatitude" id="latitude" value="<?php echo $chosen_latitude ?>" placeholder="Enter latitudee" required>
					<br/><br/>
					<input type="text" name="txtlongitude" id="longitude" value="<?php echo $chosen_longitude ?>" placeholder="Enter longitude" onchange="reloadMap()" required>
				</div>
				<div class="form-group col-lg-6">
					<iframe
						id="map"
						width="400"
						height="300"
						style="border:0"
						loading="lazy"
						allowfullscreen
						referrerpolicy="no-referrer-when-downgrade"
						src="https://maps.google.com/maps?q=<?php echo $chosen_latitude.",".$chosen_longitude ?>&z=18&output=embed"
						>
					</iframe>
				</div>
				<div class="form-group col-lg-6">
					<button type="submit" name="btnsubmit" class="about_btn cus_mb-10">Search</button>
					<button type="submit" name="btnownsubmit" class="about_btn cus_mb-10">Search using your Address</button>
				</div>
			</form>
			<!-- <p class="f_400 w_color f_size_16 l_height26">Why I say old chap that is spiffing off his nut arse pear shaped plastered<br> Jeffrey bodge barney some dodgy.!!</p> -->
		</div>
	</div>
</section>
<section class="shop_grid_area sec_pad">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6 col-sm-5">
				<div class="shop_menu_left">
					<p>Showing results:</p>
				</div>
			</div>
			<!-- <div class="col-lg-6 col-sm-7">
				<div class="shop_menu_right d-flex align-items-center justify-content-end">
					<h5>Sort by </h5>
					<form method="get" action="#">
						<select class="selectpickers">
							<option value="menu_order">Default Sorting</option>
							<option value="popularity">Popularity</option>
							<option value="rating">Average rating</option>
							<option value="date">Feature</option>
							<option value="date">Newness</option>
						</select>
					</form>
					<div class="view-style shop_grid">
						<div class="list-style">
							<a href="#"><i class="ti-layout-grid2"></i></a>
						</div>
						<div class="grid-style active">
							<a href="#"><i class="ti-menu-alt"></i></a>
						</div>
					</div>

				</div>
			</div> -->
		</div>
		<div class="row">
			<?php
				$index = 0;
				# Get restaurants from database
				$select="SELECT *
						FROM restaurant";
				$query=mysqli_query($connection,$select);
				$count=mysqli_num_rows($query);
				for($i=0; $i<$count; $i++){
					$row = mysqli_fetch_array($query);
					$restaurantID = $row['restaurantID'];
					$restaurantName = $row['restaurantName'];
					$restaurantImage = $row['image'];
					$restaurant_latitude = $row['latitude'];
					$restaurant_longitude = $row['longitude'];
					$distance = twopoints_on_earth($restaurant_latitude, $restaurant_longitude, $chosen_latitude, $chosen_longitude);
					if($distance >= 2.5){
						continue;
					}
					if($index >= 30){
						break;
					}
					$index += 1;
			?>
					<a href="restaurantdetail.php?restaurantID=<?=$restaurantID?>">
						<div class="col-lg-3 col-sm-4">
							<div class="single_product_item">
								<div class="product_img">
									<?php 
										if($restaurantImage == ""){
											$restaurantImage = "img/restaurants/default_img.jpg";
										}
									?>
									<img class="img-fluid" style="width:300px; height:300px; object-fit: contain;" src=<?php echo $restaurantImage ?> alt="">
									<!-- <div class="hover_content">
										<a href="#"><i class="ti-heart"></i></a>
										<a href="#" title="Add to cart"><i class="ti-bag"></i></a>
										<a href="#"><i class="ti-eye"></i></a>
									</div> -->
								</div>
								<div class="single_pr_details">
									<h3 class="f_p f_500 f_size_16"><?php echo $restaurantName ?></h3>
									<!-- <div class="price">
										<del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>18.00</span></del>
										<ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>16.00</span></ins>
									</div> -->
									<div>
										<a href="#"></a>
									</div>
								</div>
							</div>
						</div>
					</a>
			<?php
				}
			?>
			<!-- <div class="col-lg-12">
				<ul class="list-unstyled page-numbers shop_page_number">
					<li><span aria-current="page" class="page-numbers current">1</span></li>
					<li><a class="page-numbers" href="#">2</a></li>
					<li><a class="next page-numbers" href="#"><i class="ti-arrow-right"></i></a></li>
				</ul>
			</div> -->
		</div>
	</div>
</section>

<?php include 'footer.php'; ?>