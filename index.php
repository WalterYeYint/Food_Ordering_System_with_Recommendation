<?php 
	include 'headtag.php';
	include 'header.php';
	include 'dbconnect.php'; 


	if(isset($_SESSION['auth_user'])){
		$select = "SELECT fo.*, c.*, f.*
				FROM foodorder fo, cart c, food f
				WHERE fo.cartID = c.cartID
				AND fo.foodID = f.foodID
				AND c.userID = $userID_sess
				ORDER BY fo.foodorderID DESC LIMIT 1";
		$result=mysqli_query($connection,$select);
		$count=mysqli_num_rows($result);
		$prev_foodorder_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
		if($count <= 0){
			$recommended_food_dict = 0;
		}
		else{
			$prev_foodID = $prev_foodorder_arr[0]["foodID"];
			$prev_foodName = $prev_foodorder_arr[0]["foodName"];

			$id = 4924;
			$url = "http://127.0.0.1:5000/get_recommendation/{$prev_foodID}";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			$result = curl_exec($ch);
			$recommended_food_dict = json_decode($result, $assoc=true);
			curl_close($ch);
		}
	}
?>
<section class="breadcrumb_area">
	<img class="breadcrumb_shap" src="img/breadcrumb/banner_bg.png" alt="">
	<div class="container">
		<div class="breadcrumb_content text-center">
			<h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20">Welcome to StrEats !</h1>
			<p class="f_400 w_color f_size_16 l_height26">What can we get you?</p>
		</div>
	</div>
</section>
<section class="shop_grid_area sec_pad">
	<div class="container">
		<div>
			<h2>Order food from the best restaurants in Yangon</h2>
			<br/>
			<p>Fancy some Kyay Oh? How about a scrumptious bowl of Shan noodles and roasted duck?
				We have plenty of restaurants in Yangon available for you to order food online and delivered straight to your location.
			</p>
			<a href="restaurantlist.php"><button class="btn_four">Start Shopping Now !</button></a>
			<br/><br/>
		</div>
		<?php
		if(isset($_SESSION['auth_user']) AND $recommended_food_dict != 0){
			?>
			<div class="hr"></div>
			<h3>You might also like (Recommendations for <?php echo $prev_foodName ?>):</h3>
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
					$restaurantID = $row['restaurantID'];
					$foodImage = $row['image'];
			?>
				<a href="restaurantdetail.php?restaurantID=<?=$restaurantID?>&mode=recommend">
					<div class="col-lg-3 col-sm-4">
						<div class="single_product_item">
							<div class="product_img">
								<?php 
									if($foodImage == ""){
										$foodImage = "img/food/default_img.jpeg";
									}
								?>
								<img class="img-fluid" src=<?php echo $foodImage ?> alt="">
							</div>
							<div class="single_pr_details">
								<h3 class="f_p f_500 f_size_16"><?php echo $foodName ?></h3>
								<div class="price">
									<!-- <del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>20.00</span></del> -->
									<!-- <ins><span class="woocommerce-Price-amount amount"><?php echo $price ?> Kyats</span></ins> -->
								</div>
								<!-- the <div> below is required for right format -->
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
		</div>
			<?php
		}
		?>
	</div>
</section>
<?php include 'footer.php'; ?>