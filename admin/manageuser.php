<?php
	include 'headtag.php';
	include 'header.php';
	include 'sidebar.php';
	include '../dbconnect.php';
?>
<?php
	if(isset($_GET['userID'])){
		if($_GET['mode'] == 'edit'){
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
			$tuserRoleID = $arr['userRoleID'];
			$tuserRoleName = $arr['userRoleName'];
		}
		elseif($_GET['mode'] == 'delete'){
			$userID=$_GET['userID'];

			$delete="DELETE FROM user WHERE userID='$userID'";
			$result=mysqli_query($connection,$delete);
			if ($result) 
			{
				echo "<script>window.alert('User Deleted Successfully!')</script>";
				echo "<script>window.location='manageuser.php'</script>";
			}
			else
			{
				echo "<p>Something went wrong in User Delete : " . mysqli_error($connection) . "</p>";
			}
		}
	}
	else{
		// echo "<script>window.alert('Renewed!')</script>";
		$tuserID = AutoID('user','userID');
		$tfirstName = "";
		$tlastName = "";
		$taddress = "";
		$tlatitude = "";
		$tlongitude = "";
		$temail = "";
		$tpassword = "";
		$tuserRoleID = "";
		$tuserRoleName = "";
	}

	if(isset($_POST['btnsubmit']) OR isset($_POST['btnupdate'])) {
		$txtuserID = $_POST['txtuserid'];
		$txtfirstName = $_POST['txtfirstname'];
		$txtlastName = $_POST['txtlastname'];
		$txtaddress = $_POST['txtaddress'];
		$txtlatitude = $_POST['txtlatitude'];
		$txtlongitude = $_POST['txtlongitude'];
		$txtemail = $_POST['txtemail'];
		$txtpassword = $_POST['txtpassword'];
		$txtconfirm = $_POST['txtconfirm'];
		$sltuserRole=$_POST['sltuserrole'];

	// 	$photo=$_FILES['photo']['name'];
	// 	$FolderName="images/userimage/"; 
	// 	$FileName=$FolderName.'_'.$photo; 

	// 	$copied=copy($_FILES['photo']['tmp_name'], $FileName);

	// 	if(!$copied) 
	// 	{
	// 		echo "<p>User Photo Cannot Upload!</p>";
	// 		exit();
	// 	}

		if ($txtpassword != $txtconfirm) {
			echo "<script>window.alert('Password and Confirm Password did not match!')</script>"; 
		}
		else
		{
			// Check Validation
			$check="SELECT * FROM user WHERE userID != '$txtuserID' AND email='$email'";
			$result=mysqli_query($connection,$check);
			$count=mysqli_num_rows($result);
			if ($count>0) {
				echo "<script>window.alert('User Email Already Exists!')</script>";
				echo "<script>window.location='managerestaurant.php'</script>";
			}
			elseif(isset($_POST['btnupdate'])){
				$update = "UPDATE user SET 
								userID = '$txtuserID',
								userRoleID = '$sltuserRole',
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
					echo "<script>window.location='manageuser.php'</script>";
				}
				else
				{
					echo "<p>Something went wrong in Updating User Information : " . mysqli_error($connection) . "</p>";
				}
			}
			else{
				//ID Check Validation
				$check="SELECT * FROM user WHERE userID='$txtuserID'";
				$result=mysqli_query($connection,$check);
				$count=mysqli_num_rows($result);
				if ($count>0) {
					echo "<script>window.alert('User ID Already Exists!')</script>";
					echo "<script>window.location='manageuser.php'</script>";
				}
				else{
					$insert="INSERT INTO user 
							(`userID`, `userRoleID`, `firstName`, `lastName`, `email`, `password`, `address`, `latitude`, `longitude`) 
							VALUES 
							('$txtuserID','$sltuserRole','$txtfirstName','$txtlastName','$txtemail','$txtpassword','$txtaddress','$txtlatitude','$txtlongitude')";
					$result=mysqli_query($connection,$insert);
					if ($result) {
						echo "<script>window.alert('User Added Successfully!')</script>";
						echo "<script>window.location='manageuser.php'</script>";
					}
			
					else{
						echo "<p>Something went wrong in User Entry : " . mysqli_error($connection) . "</p>";
					}   
				}
			}
		}
	}
?>
<div class="col-12 grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title">Manage User</h4>
			<form class="forms-sample" action="manageuser.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="id">User ID <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtuserid" id="id" value="<?php echo $tuserID ?>" placeholder="ID" required="" readonly>
				</div>
				<div class="form-group">
					<label for="name">First Name <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtfirstname" id="firstname" value="<?php echo $tfirstName ?>" placeholder="First Name" required="">
				</div>
				<div class="form-group">
					<label for="name">Last Name <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtlastname" id="lastname" value="<?php echo $tlastName ?>" placeholder="Last Name" required="">
				</div>
				<!-- <div class="form-group">
					<label for="photo">Photo <span style="color: red;">*</span></label><br>
					<input type="file" name="photo" id="photo" required="">
				</div> -->
				<div class="form-group">
					<label for="name">Address <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtaddress" id="address" value="<?php echo $taddress ?>" placeholder="Address" required="">
				</div>
				<div class="form-group">
					<label for="name">Latitude <span style="color: red;">*</span></label>
					<input type="number" step="any" class="form-control" name="txtlatitude" id="latitude" value="<?php echo $tlatitude ?>" placeholder="Latitude" required="">
				</div>
				<div class="form-group">
					<label for="name">Longitude <span style="color: red;">*</span></label>
					<input type="number" step="any" class="form-control" name="txtlongitude" id="longitude" value="<?php echo $tlongitude ?>" placeholder="Longitude" onchange="reloadMap()" required="">
					<i class="mdi mdi-help-circle-outline" style="font-size:15px"><a href="img/map_tutorial.png">Don't know how to get these? Check here&emsp;&emsp;</a></i>
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
				<div class="form-group">
					<label for="email">Email <span style="color: red;">*</span></label>
					<input type="email" class="form-control" name="txtemail" id="email" value="<?php echo $temail ?>" placeholder="Email" required="">
				</div>
				<!-- <div class="form-group">
					<label for="phone">Phone Number <span style="color: red;">*</span></label>
					<input type="number" class="form-control" id="phone" name="txtphone" placeholder="Phone Number" required="">
				</div> -->
				<div class="form-group">
					<label for="password">Password <span style="color: red;">*</span></label>
					<input type="password" class="form-control" name="txtpassword" id="password" value="<?php echo $tpassword ?>" placeholder="Password" required="">
				</div>
				<div class="form-group">
					<label for="confirmpassword">Confirm Password <span style="color: red;">*</span></label>
					<input type="password" class="form-control" name="txtconfirm" id="confirmpassword" value="<?php echo $tpassword ?>" placeholder="Confirm Password" required="">
				</div>
				<div class="form-group">
					<label for="role">Role <span style="color: red;">*</span></label>
					<select class="form-select" id="userrole" name="sltuserrole" required="">
						<?php 
						$roledata="SELECT * FROM userRole";
						$result=mysqli_query($connection,$roledata);
						$count=mysqli_num_rows($result);

						for ($i=0; $i<$count; $i++) { 
							$row=mysqli_fetch_array($result);
							$userRoleID=$row['userRoleID'];
							$userRoleName=$row['userRoleName'];
						?>
							<option value=<?php echo $userRoleID ?>
						<?php
							if ($tuserRoleID == $userRoleID) {
								echo "selected";
							}
							echo ">$userRoleName</option>";
						}
						?>    
					</select>
				</div>

				<?php
				if(isset($_GET['userID'])){
				?>
					<button type="submit" class="btn btn-success me-2" name="btnupdate">Update</button>	
				<?php
				}
				else{
				?>
					<button type="submit" class="btn btn-success me-2" name="btnsubmit">Submit</button>
				<?php
				}
				?>
				<button type="reset" class="btn btn-outline-dark" name="btnreset">Cancel</button>
			</form>
		</div>
	</div>
</div>

<?php
$query = "SELECT u.*, ur.userRoleName
					FROM user u INNER JOIN userRole ur ON u.userRoleID=ur.userRoleID
					ORDER BY u.userID DESC";
$result=mysqli_query($connection,$query);
$count=mysqli_num_rows($result);
$user_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

// Defining required variables for pagination
$paginate_array = paginate($count);
$entry_count = $paginate_array[0];
$actual_entry_count = $paginate_array[1];
$page_count = $paginate_array[2];
$pgNo = $paginate_array[3];
$pg_idx_start = $paginate_array[4];
$pg_idx_end = $paginate_array[5];

if($count<1){
	echo "<p>No Record Found!</p>";
}
else{
?>
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">User List:</h4>
				<ul class="nav nav-tabs" role="tablist">
					<a href="manageuser.php?pgNo=<?=1?>" class="nav-link"><<</a>
					<?php
					for($i=$pg_idx_start; $i<=$pg_idx_end; $i++){
					?>
						<li class="nav-item">
							<a href="manageuser.php?pgNo=<?=$i?>" class="nav-link 
							<?php
							if($pgNo == $i){
								echo "active";
							}
							?>
							"><?php echo $i ?></a>
						</li>
					<?php
					}
					?>
					<a href="manageuser.php?pgNo=<?=$page_count?>" class="nav-link">>></a>
				</ul>
				<div class="table-responsive pt-3">
					<table class="table datatable table-bordered table-striped table-hover">
						<thead style="">
							<tr>
								<th>ID</th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Email</th>
								<th>Address</th>
								<th>Latitude</th>
								<th>Longitude</th>
								<th>Role</th>
								<th>Action</th>
							</tr>
						</thead>
							<tbody>
								<?php  
								$idx = ($pgNo-1)*$entry_count;
								for($i=$idx; $i<$idx+$actual_entry_count; $i++){
									$rows=$user_arr[$i];
									$userID=$rows['userID'];
									$firstName=$rows['firstName'];
									$lastName=$rows['lastName'];
									$email=$rows['email']; 
									$address=$rows['address'];
									$latitude=$rows['latitude'];
									$longitude=$rows['longitude'];
									$userRoleName=$rows['userRoleName'];
								?>
									<tr>
										<th><?php echo $userID ?></th>
										<td><?php echo $firstName ?></td>
										<td><?php echo $lastName ?></td>
										<td><?php echo $email ?></td>
										<td><?php echo $address ?></td>
										<td><?php echo $latitude ?></td>
										<td><?php echo $longitude ?></td>
										<td><?php echo $userRoleName ?></td>
										<td>
											<a href="manageuser.php?userID=<?=$userID?>&mode=edit"class="btn btn-info btn-rounded">Edit</a>
											<a href="manageuser.php?userID=<?=$userID?>&mode=delete" class="btn btn-danger btn-rounded" onclick="return confirm_delete('<?php echo $email ?>')">Delete</a>
										</td>
									</tr>
								<?php 
								}
								?>
							</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php 
} 
?>
<!-- <script>
	var lblLong = document.getElementById("longitude");
	lblLong.addEventListener("change", function(event){
		var target = event.target;
		var 
		// alert(value);
		var xml = new XMLHttpRequest();
		xml.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				alert(this.responseText);
			}
		}
		// Note that $_SESSION['food_ID_list'] and $_SESSION['quantity_list'] are separate.
		// E.g. food_ID_list = [3,6,4] comes out as food_arr = [3,4,6] after SELECT statement;
		// 			Below, the code adds quantity by taking food_arr's index;
		// ***Thus, quantity_list arrangement is according to food_arr, not food_ID_list;***
		xml.open("GET", "changequantity.php?idx="+index+"&quantity="+value, true);
		xml.send();
	})
</script> -->
<?php include 'footer.php'; ?>
