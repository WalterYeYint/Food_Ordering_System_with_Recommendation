<?php 
include 'headtag.php';
include 'header.php';
include 'dbconnect.php';

if(isset($_POST['btnsubmit'])){
	$userID = AutoID('user', 'userID');
	
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
		$check="SELECT * FROM user WHERE email='$txtemail'";
		$result=mysqli_query($connection,$check);
		$count=mysqli_num_rows($result);
		if ($count>0) {
			echo "<script>window.alert('User with this email already exists!')</script>";
			echo "<script>window.location='register.php'</script>";
		}
		else{
			$insert="INSERT INTO user 
					(`userID`, `userRoleID`, `firstName`, `lastName`, `email`, `password`, `address`, `latitude`, `longitude`) 
					VALUES 
					('$userID','$userRoleID','$txtfirstName','$txtlastName','$txtemail','$txtpassword','$txtaddress','$txtlatitude','$txtlongitude')";
			$result=mysqli_query($connection,$insert);
			echo "Here!!";
			// if ($result) {
			// 	echo "<script>window.alert('Registered Successfully!')</script>";
			// 	echo "<script>window.location='index.php'</script>";
			// }
	
			// else{
			// 	echo "<p>Something went wrong in Register Process : " . mysqli_error($connection) . "</p>";
			// }   
		}
	}
}
?>
<section class="breadcrumb_area">
	<img class="breadcrumb_shap" src="img/breadcrumb/banner_bg.png" alt="">
	<div class="container">
		<div class="breadcrumb_content text-center">
			<h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20">Sign Up</h1>
		</div>
	</div>
</section>
<section class="sign_in_area bg_color">
	<div class="container">
		<div class="sign_info">
			<div class="row">
				<div class="col-lg-7">
					<div class="login_info">
						<h2 class="f_p f_600 f_size_24 t_color3 mb_40">Sign Up</h2>
						<form action="register.php" class="login-form sign-in-form" method="post" name="registerform" enctype="multipart/form-data">
							<div class="form-group text_box">
								<label class="f_p text_c f_400">First Name</label>
								<input type="text" name="txtfirstname" placeholder="First Name" required="">
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Last Name</label>
								<input type="text" name="txtlastname" placeholder="Last Name" required="">
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Full Address</label>
								<input type="text" name="txtaddress" placeholder="Full Address" required="">
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Latitude</label>
								<input type="text" name="txtlatitude" placeholder="Latitude" required="">
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Longitude</label>
								<input type="text" name="txtlongitude" placeholder="Longitude" required="">
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Email Address</label>
								<input type="text" name="txtemail" placeholder="JakeSully@gmail.com" required="">
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Password</label>
								<input type="password" name="txtpassword" placeholder="******" required="">
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Confirm Password</label>
								<input type="password" name="txtconfirm" placeholder="******" required="">
							</div>
							<div class="d-flex justify-content-between align-items-center">
								<button type="submit" name="btnsubmit" class="app_btn btn_hover">&emsp;&emsp; Sign Up for StrEats &emsp;&emsp;</button>
							</div>
						</form>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="sign_info_content">
						<h3 class="f_p f_600 f_size_24 t_color3 mb_40">Already have an account?</h3>
						<h2 class="f_p f_400 f_size_30 mb-30"><a href="login.php"><u>Login Now</u></a> and<br> <span class="f_700">Start Ordering</span></h2>
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