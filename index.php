<?php 
	include 'headtag.php';
	include 'header.php';
	include 'dbconnect.php'; 

	$id = 4924;
	$url = "http://127.0.0.1:5000/get_recommendation/{$id}";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	$result = curl_exec($ch);
	$recommended_food_dict = json_decode($result, $assoc=true);
	curl_close($ch);
?>
<section class="breadcrumb_area">
	<img class="breadcrumb_shap" src="img/breadcrumb/banner_bg.png" alt="">
	<div class="container">
		<div class="breadcrumb_content text-center">
			<h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20"><?php echo ($recommended_food_dict) ?></h1>
			<p class="f_400 w_color f_size_16 l_height26">Why I say old chap that is spiffing off his nut arse pear shaped plastered<br> Jeffrey bodge barney some dodgy.!!</p>
		</div>
	</div>
</section>
<section class="shop_grid_area sec_pad">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6 col-sm-5">
				<div class="shop_menu_left">
					<p>Showing 1–14 of 22 results</p>
				</div>
			</div>
			<div class="col-lg-6 col-sm-7">
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
			</div>
		</div>
		<div class="row">
			<?php
				# Get restaurants from database
				$select="SELECT *
						FROM restaurant";
				$query=mysqli_query($connection,$select);
				$count=mysqli_num_rows($query);
				for($i=0; $i<9; $i++){
					$row = mysqli_fetch_array($query);
					$restaurantName = $row['restaurantName'];
					$restaurantImage = $row['image'];
			?>
					<div class="col-lg-3 col-sm-4">
						<div class="single_product_item">
							<div class="product_img">
								<?php 
									if($restaurantImage == ""){
										$restaurantImage = "img/restaurants/default_img.jpg";
									}
								?>
								<img class="img-fluid" src=<?php echo $restaurantImage ?> alt="">
								<div class="hover_content">
									<a href="#"><i class="ti-heart"></i></a>
									<a href="#" title="Add to cart"><i class="ti-bag"></i></a>
									<a href="#"><i class="ti-eye"></i></a>
								</div>
							</div>
							<div class="single_pr_details">
								<a href="#">
									<h3 class="f_p f_500 f_size_16"><?php echo $restaurantName ?></h3>
								</a>
								<div class="price">
									<del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>18.00</span></del>
									<ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>16.00</span></ins>
								</div>
								<div class="ratting">
									<a href="#"></a>
								</div>
							</div>
						</div>
					</div>
			<?php
				}
			?>
			<div class="hr"></div>
			<h3>You might also like:</h3>
			<br/>
			<div class="row">
				<?php
					# Get recommended foodID from database and display them
					$select="SELECT *
							FROM food
							WHERE foodID IN (".implode(',', $recommended_food_dict).")";
					$query=mysqli_query($connection,$select);
					$count=mysqli_num_rows($query);
					for($i=0; $i<$count; $i++){
						$row = mysqli_fetch_array($query);
						$foodName = $row['foodName'];
						$foodImage = $row['image']
				?>
					<div class="col-lg-3 col-sm-4">
							<div class="single_product_item">
								<div class="product_img">
									<?php 
										if($foodImage == ""){
											$foodImage = "img/food/default_img.jpeg";
										}
									?>
									<img class="img-fluid" src=<?php echo $foodImage ?> alt="">
									<div class="hover_content">
										<a href="#"><i class="ti-heart"></i></a>
										<a href="#" title="Add to cart"><i class="ti-bag"></i></a>
										<a href="#"><i class="ti-eye"></i></a>
									</div>
								</div>
								<div class="single_pr_details">
									<a href="#">
										<h3 class="f_p f_500 f_size_16"><?php echo $foodName ?></h3>
									</a>
									<div class="price">
										<del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>18.00</span></del>
										<ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>16.00</span></ins>
									</div>
									<div class="ratting">
										<a href="#"></a>
									</div>
								</div>
							</div>
						</div>
				<?php
					}
				?>
			</div>
			<div class="col-lg-12">
				<ul class="list-unstyled page-numbers shop_page_number">
					<li><span aria-current="page" class="page-numbers current">1</span></li>
					<li><a class="page-numbers" href="#">2</a></li>
					<li><a class="next page-numbers" href="#"><i class="ti-arrow-right"></i></a></li>
				</ul>
			</div>
		</div>
	</div>
</section>

<?php include 'footer.php'; ?>