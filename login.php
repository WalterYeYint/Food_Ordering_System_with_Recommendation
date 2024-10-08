<?php 
	include 'headtag.php';
	include 'header.php';
	include 'dbconnect.php';
	
	$incorrect=False;
	if(isset($_POST['btnLogin'])){
	$txtemail = $_POST['txtemail'];
	// $password = md5($_POST['txtpassword']);
	$txtpassword = $_POST['txtpassword'];

	// $select = "SELECT u.*, ur.userRoleName as rolename
	// 						FROM user u
	// 						INNER JOIN userRole ur ON u.userRoleID=r.userRoleID 
	// 						WHERE u.email='$email'
	// 						AND u.password='$password'";
	$select = "SELECT u.*, ur.userRoleName
				FROM user u, userRole ur
				WHERE u.userRoleID = ur.userRoleID
				AND u.email='$txtemail'
				AND u.password='$txtpassword'";
	$query = mysqli_query($connection, $select);
	$count = mysqli_num_rows($query);

	if($count > 0){
		foreach($query as $data){
			$userID = $data['userID'];
			$userRoleID = $data['userRoleID'];
			$userRoleName = $data['userRoleName'];
			$firstName = $data['firstName'];
			$lastName = $data['lastName'];
			$email= $data['email'];
			$address = $data['address'];
			$latitude = $data['latitude'];
			$longitude = $data['longitude'];
		}
		// Authenticating Logged In User
		$_SESSION['auth'] = true;
		$_SESSION['auth_role'] = "$userRoleID";
		$_SESSION['auth_rolename'] = "$userRoleName";

		// Storing Authenticated User data in Session
		$_SESSION['auth_user'] = [
			'userID'=>$userID,
			'userRoleID'=>$userRoleID,
			'userRoleName'=>$userRoleName,
			'email'=>$email,
			'firstName'=>$firstName,
			'lastName'=>$lastName,
			'address'=>$address,
			'user_latitude'=>$latitude,
			'user_longitude'=>$longitude,
			// 'userimage'=>$userimage,
			// 'departmentid'=>$departmentid,
		];

		$_SESSION['cart_item_count'] = 0;

		$temp_rolename = $_SESSION['auth_rolename'];
		echo "<script>window.alert('Welcome, $temp_rolename $firstName $lastName!!')</script>";
		echo '<script>window.location="index.php"</script>';
		exit(0);
	}
	else{
		$incorrect=True;
		echo "<script>window.alert('Invalid Email or Password!!')</script>";
		echo '<script>window.location="login.php"</script>';
	exit(0);
	}
	}
?>
<section class="breadcrumb_area">
	<img class="breadcrumb_shap" src="img/breadcrumb/banner_bg.png" alt="">
	<div class="container">
		<div class="breadcrumb_content text-center">
			<h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20">Sign in to StrEats</h1>
		</div>
	</div>
</section>
<!-- <section class="sign_in_area bg_color sec_pad"> -->
<section class="sign_in_area bg_color">
	<div class="container">
		<div class="sign_info">
			<div class="row">
				<div class="col-lg-7">
					<div class="login_info">
						<h2 class="f_p f_600 f_size_24 t_color3 mb_40">Sign In</h2>
						<form action="login.php" class="login-form sign-in-form" method="post" name="loginform" enctype="multipart/form-data">
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Email</label>
								<input type="email" name="txtemail" placeholder="JakeSully@gmail.com" required>
							</div>
							<div class="form-group text_box">
								<label class="f_p text_c f_400">Password</label>
								<input type="password" name="txtpassword" placeholder="******" required>
							</div>
							<!-- <div class="extra mb_20">
								<div class="checkbox remember">
									<label>
										<input type="checkbox"> Keep me Signed in
									</label>
								</div>
								<div class="forgotten-password">
									<a href="#">Forgot Password?</a>
								</div>
							</div> -->
							<div class="d-flex justify-content-between align-items-center">
								<button type="submit" name="btnLogin" class="btn_three">Sign in</button>
								<!-- <div class="social_text d-flex ">
									<ul class="list-unstyled social_tag mb-0">
										<li><a href="#"><i class="ti-facebook"></i></a></li>
										<li><a href="#"><i class="ti-twitter-alt"></i></a></li>
										<li><a href="#"><i class="ti-google"></i></a></li>
									</ul>
								</div> -->
							</div>
							<div class="extra mb_20">
								<div class="lead-text">New User?&emsp;<a href="register.php">Create a New Account</a></div>
							</div>
						</form>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="sign_info_content">
						<h3 class="f_p f_600 f_size_24 t_color3 mb_40">Welcome to StrEats !<br/> What can we get you?</h3>
						<h2 class="f_p f_400 f_size_30 mb-30">- Order from more than<br> 40 different restaurants across Yangon<br/><br/> 
																									- Order from Anywhere<br/><br/>
																									- Choose your Preferred payment method</h2>
						<!-- <ul class="list-unstyled mb-0">
							<li><i class="ti-check"></i> Gonna Put Logo Here</li>
							<li><i class="ti-check"></i> Free Testing Tools</li>
							<li><i class="ti-check"></i> Unlimited User Accounts</li>
						</ul> -->
						<!-- <button type="submit" class="btn_three sign_btn_transparent">Sign Up</button> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php include 'footer.php'; ?>