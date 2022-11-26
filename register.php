<?php 
include 'headtag.php';
include 'header.php';
include 'dbconnect.php';
if(isset($_GET['userID'])){

	$txtbanner = "Edit Profile";
	$txt_1 = "Edit Profile Info";
	$txt_2 = "";
	$txt_3 = "";
	$disabled = "disabled='disabled'";
	$readonly = "readonly";

	$userID=$_GET['userID'];
	$query = "SELECT * FROM user WHERE userID='$userID'";
	$result = mysqli_query($connection,$query);
	$arr = mysqli_fetch_array($result);

	$tuserID = $arr['userID'];
	$tfirstName = $arr['firstName'];
	$tlastName = $arr['lastName'];
	$taddress = $arr['address'];
	$tlatitude = $arr['latitude'];
	$tlongitude = $arr['longitude'];
	$temail = $arr['email'];
	$tpassword = $arr['password'];
}
else{
	$txtbanner = "Edit Profile";
	$txt_1 = "Sign Up";
	$txt_2 = "Already have an account?";
	$txt_3 = "<a href='login.php'><u>Login Now</u></a> and<br>";
	$disabled = "disabled='disabled'";
	$readonly = "";

	$tuserID = AutoID('user','userID');
	$tfirstName = "";
	$tlastName = "";
	$taddress = "";
	$tlatitude = "";
	$tlongitude = "";
	$temail = "";
	$tpassword = "";
}

if(isset($_POST['btnsubmit']) OR isset($_POST['btnupdate'])){
	$txtuserID = $_POST['txtuserid'];
	
	$userRoleName = CUSTOMER;
	$check="SELECT * FROM userRole WHERE userRoleName='$userRoleName'";
	$result=mysqli_query($connection,$check);
	$userRole_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
	$userRoleID = $userRole_arr[0]["userRoleID"];
	
	$txtfirstName = $_POST['txtfirstname'];
	$txtlastName = $_POST['txtlastname'];
	$txtaddress = $_POST['txtaddress'];
	$txtlatitude = $_POST['txtlatitude'];
	$txtlongitude = $_POST['txtlongitude'];
	$txtemail = $_POST['txtemail'];
	$txtpassword = $_POST['txtpassword'];
	$txtconfirm = $_POST['txtconfirm'];

	// echo $userID,$userRole,$txtfirstName,$txtlastName,$txtemail,$txtpassword,$txtaddress,$txtlatitude,$txtlongitude;

	if ($txtpassword != $txtconfirm) {
		echo "<script>window.alert('Password and Confirm Password did not match!')</script>"; 
	}
	else
	{
		//Check Validation
		$check="SELECT * FROM user WHERE userID != '$txtuserID' AND email='$email'";
		$result=mysqli_query($connection,$check);
		$count=mysqli_num_rows($result);
		if ($count>0) {
			echo "<script>window.alert('User with this email already exists!')</script>";
			echo "<script>window.location='register.php'</script>";
		}
		elseif(isset($_POST['btnupdate'])){
			$update = "UPDATE user SET 
							userID = '$txtuserID',
							firstName = '$txtfirstName',
							lastName = '$txtlastName',
							email = '$txtemail',
							password = '$txtpassword',
							address = '$txtaddress',
							latitude = '$txtlatitude',
							longitude = '$txtlongitude'      
							WHERE userID = '$txtuserID'";
			$result = mysqli_query($connection,$update);

			if($result) 
			{
				echo "<script>window.alert('User Info Updated Successfully!')</script>";
				echo "<script>window.location='index.php'</script>";
			}
			else
			{
				echo "<p>Something went wrong in Updating User Information : " . mysqli_error($connection) . "</p>";
			}
		}
		else{
			$insert="INSERT INTO user 
					(`userID`, `userRoleID`, `firstName`, `lastName`, `email`, `password`, `address`, `latitude`, `longitude`) 
					VALUES 
					('$txtuserID','$userRoleID','$txtfirstName','$txtlastName','$txtemail','$txtpassword','$txtaddress','$txtlatitude','$txtlongitude')";
			$result=mysqli_query($connection,$insert);
			if ($result) {
				echo "<script>window.alert('Registered Successfully!')</script>";
				echo "<script>window.location='index.php'</script>";
			}
	
			else{
				echo "<p>Something went wrong in Register Process : " . mysqli_error($connection) . "</p>";
			}   
		}
	}
}
?>
<section class="breadcrumb_area">
	<img class="breadcrumb_shap" src="img/breadcrumb/banner_bg.png" alt="">
	<div class="container">
		<div class="breadcrumb_content text-center">
			<h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20"><?php echo $txtbanner ?></h1>
		</div>
	</div>
</section>
<section class="sign_in_area bg_color">
	<div class="container">
		<div class="sign_info">
			<div class="row">
				<div class="col-lg-7">
					<div class="login_info">
						<h2 class="f_p f_600 f_size_24 t_color3 mb_40"><?php echo $txt_1 ?></h2>
						<form action="register.php" class="login-form sign-in-form" method="post" name="registerform" enctype="multipart/form-data">
							<input type="hidden" name="txtuserid" value="<?php echo $tuserID ?>" required="">
							<div class="form-group text_box">
								<label class="f_p text_c f_400">First Name</label>
								<input type="text" name="txtfirstname" placeholder="First Name" value="<?php echo $tfirstName ?>" required="">
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Last Name</label>
								<input type="text" name="txtlastname" placeholder="Last Name" value="<?php echo $tlastName ?>" required="">
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Full Address</label>
								<input type="text" name="txtaddress" placeholder="Full Address" value="<?php echo $taddress ?>" required="">
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Latitude</label>
								<input type="number" step="any" name="txtlatitude" id="latitude" placeholder="Latitude" value="<?php echo $tlatitude ?>" required="">
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Longitude</label>
								<input type="number" step="any" name="txtlongitude" id="longitude" placeholder="Longitude" value="<?php echo $tlongitude ?>" onchange="reloadMap()" required="">
								<i class="fa fa-question-circle" style="font-size:12px"><a href="img/map_tutorial.png">Don't know how to get these? Check here&emsp;&emsp;</a></i>
								<button type="button" onclick="getCurrentLocation()">Get Current Location</button>
							</div>
							<iframe
								id="map"
								width="400"
								height="300"
								style="border:0"
								loading="lazy"
								allowfullscreen
								referrerpolicy="no-referrer-when-downgrade"
								src=""
								hidden>
							</iframe>
							<br/><br/>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Email Address</label>
								<input type="email" name="txtemail" placeholder="JakeSully@gmail.com" value="<?php echo $temail ?>" required="" <?php echo $readonly?>>
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Password</label>
								<input type="password" name="txtpassword" placeholder="******" value="<?php echo $tpassword ?>" required="">
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Confirm Password</label>
								<input type="password" name="txtconfirm" placeholder="******" value="<?php echo $tpassword ?>" required="">
							</div>
							<div class="d-flex justify-content-between align-items-center">
								<?php
								if(isset($_GET['userID'])){
									?>
									<button type="submit" name="btnupdate" class="app_btn btn_hover">&emsp;&emsp; Update Info &emsp;&emsp;</button>
									<?php
								}
								else{
									?>
									<button type="submit" name="btnsubmit" class="app_btn btn_hover">&emsp;&emsp; Sign Up for StrEats &emsp;&emsp;</button>
									<?php
								}
								?>
							</div>
						</form>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="sign_info_content">
						<h3 class="f_p f_600 f_size_24 t_color3 mb_40"><?php echo $txt_2 ?></h3>
						<h2 class="f_p f_400 f_size_30 mb-30"><?php echo $txt_3 ?> <span class="f_700">Start Ordering</span></h2>
						<ul class="list-unstyled mb-0">
							<li><i class="ti-check"></i> Order from more than 40 different restaurants across Yangon</li>
							<li><i class="ti-check"></i> Order from Anywhere</li>
							<li><i class="ti-check"></i> Choose your Preferred payment method</li>
						</ul>
						<!-- <img src="img/stock_food.jpeg" srcset="img/stock_food.jpeg" alt="food_photo"> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php include 'footer.php'; ?>