<?php
	session_start();
?>
<body>
	<!-- <div id="preloader">
		<div id="ctn-preloader" class="ctn-preloader">
			<div class="animation-preloader">
				<div class="spinner"></div>
				<div class="txt-loading">
					<span data-text-preloader="Y" class="letters-loading">
						Y
					</span>
					<span data-text-preloader="W" class="letters-loading">
						W
					</span>
					<span data-text-preloader="S" class="letters-loading">
						S
					</span>
					<span data-text-preloader="-" class="letters-loading">
						-
					</span>
					<span data-text-preloader="U" class="letters-loading">
						U
					</span>
					<span data-text-preloader="N" class="letters-loading">
						N
					</span>
					<span data-text-preloader="I" class="letters-loading">
						I
					</span>
					<span data-text-preloader="V" class="letters-loading">
						V
					</span>
					<span data-text-preloader="E" class="letters-loading">
						E
					</span>
					<span data-text-preloader="R" class="letters-loading">
						R
					</span>
					<span data-text-preloader="S" class="letters-loading">
						S
					</span>
					<span data-text-preloader="I" class="letters-loading">
						I
					</span>
					<span data-text-preloader="T" class="letters-loading">
						T
					</span>
					<span data-text-preloader="Y" class="letters-loading">
						Y
					</span>
				</div>
				<p class="text-center">Loading</p>
			</div>
			<div class="loader">
				<div class="row">
					<div class="col-3 loader-section section-left">
						<div class="bg"></div>
					</div>
					<div class="col-3 loader-section section-left">
						<div class="bg"></div>
					</div>
					<div class="col-3 loader-section section-right">
						<div class="bg"></div>
					</div>
					<div class="col-3 loader-section section-right">
						<div class="bg"></div>
					</div>
				</div>
			</div>
		</div>
	</div> -->
	<div class="body_wrapper">
		<header class="header_area">
			<nav class="navbar navbar-expand-lg menu_one menu_four">
				<div class="container">
					<a class="navbar-brand sticky_logo" href="index.php"><img src="img/logo2.png" srcset="img/logo2x-2.png 2x" alt="logo"><img src="img/logo.png" srcset="img/logo2x.png 2x" alt=""></a>
					<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="menu_toggle">
							<span class="hamburger">
								<span></span>
								<span></span>
								<span></span>
							</span>
							<span class="hamburger-cross">
								<span></span>
								<span></span>
							</span>
						</span>
					</button>

					<div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
						<ul class="navbar-nav menu w_menu ml-auto mr-auto">
							<li class="nav-item ">
								<a class="nav-link " href="index.php" role="button">
									Home
								</a>
							</li>

							<li class="nav-item ">
								<a class="nav-link " href="restaurantlist.php" role="button">
									Restaurants
								</a>
							</li>

							<li class="nav-item ">
								<a class="nav-link " href="idea.php" role="button">
									Idea
								</a>
							</li>
						</ul>
						<?php 
						if(isset($_SESSION['auth_user']))
						{
							$userID_sess=$_SESSION['auth_user']['userID'];
							$firstName_sess=$_SESSION['auth_user']['firstName'];
							$lastName_sess=$_SESSION['auth_user']['lastName'];
							$userRoleID_sess=$_SESSION['auth_user']['userRoleID'];
							$userRoleName_sess=$_SESSION['auth_user']['userRoleName'];
							if($_SESSION['auth_rolename'] =="Staff" || $_SESSION['auth_rolename'] =="Teacher"){
								echo "<li class='nav-item dropdown submenu active' style='list-style-type: none;'>";
								echo "<a class=\"btn_get btn_hover hidden-sm hidden-xs nav-link dropdown-toggle\" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' href='logout.php'>$firstName_sess $lastName_sess</a>";
								echo"<ul class=\"dropdown-menu\"> 
										<li class='nav-item'><a href='logout.php' class='nav-link' style='color:black; font-size:13px;'>Logout</a></li> 
										 </ul>
								</li>                                             
									</li>
								</li>                                             
								";
							}
							else{
								 //echo "<b style='color:black'>Hi, $username_sess</b>";
								 
								echo "<li class='nav-item dropdown submenu active' style='list-style-type: none;'>";
								echo "<a class=\"btn_get btn_hover hidden-sm hidden-xs nav-link dropdown-toggle\" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' href='logout.php'>$firstName_sess $lastName_sess</a>";
								echo"<ul class=\"dropdown-menu\"> 
										<li class='nav-item'>$userID_sess </li> 
										<li class='nav-item'>$userRoleName_sess </li> 
										<li class='nav-item'><a href='admin/dashboard.php' class='nav-link' style='color:black; font-size:13px;'>Admin Panel </a></li> 
										<li class='nav-item'><a href='logout.php' class='nav-link' style='color:black; font-size:13px;'>Logout</a></li> 
										 </ul>
								</li>                                             
									</li>
								</li>                                             
								";
							}
							 
						}
						else
						{
							echo "<a class=\"btn_get btn_hover hidden-sm hidden-xs\" href='login.php'>Sign In</a>";
							// echo "<a href='login.php'>Log In</a>";
						}
					?>
					<!-- <a class="btn_get btn_hover hidden-sm hidden-xs" href="#get-app">Sign In</a> -->
					<a href="cart.php?userID=<?=$userID_sess?>"><i class="fas fa-shopping-cart" style="font-size:40px;color:black"></i></i></a>
					</div>
				</div>
			</nav>
		</header>