<?php
	include 'headtag.php';
	include 'header.php';
	include 'sidebar.php';
	include '../dbconnect.php';

	include '../autoid.php'
?>
<?php
	// function insert_user($connection,$txtuserid,$txtname,$FileName,$txtemail,$txtphone,$txtusername,$txtpassword,$txtconfirm,$sltrole,$sltdepartment){
	// 	$insert="INSERT INTO user 
	// 					(userid,fullname,image,email,phonenumber,username,password,confirmpassword,userroleid
	// 					,departmentid) VALUES 
	// 					('$txtuserid','$txtname','$FileName','$txtemail','$txtphone','$txtusername','$txtpassword','$txtconfirm','$sltrole','$sltdepartment')";
	// 	$result=mysqli_query($connection,$insert);
	// 	return $result;
	// }
	if(isset($_GET['userID'])){
		if($_GET['mode'] == 'delete'){
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

	if(isset($_POST['btnsubmit'])) {
		$txtuserid = $_POST['txtuserid'];
		$txtfirstname = $_POST['txtfirstname'];
		$txtlastname = $_POST['txtlastname'];
		$txtaddress = $_POST['txtaddress'];
		$txtlatitude = 1;
		$txtlongitude = 1;
		$txtemail = $_POST['txtemail'];
		$txtpassword = $_POST['txtpassword'];
		$txtconfirm = $_POST['txtconfirm'];
		$sltuserrole=$_POST['sltuserrole'];

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
			//Check Validation
			$check="SELECT * FROM user WHERE userID='$txtuserid' OR email='$txtemail'";
			$result=mysqli_query($connection,$check);
			$count=mysqli_num_rows($result);

			if ($count>0) {
				echo "<script>window.alert('User Already Exist!')</script>";
				echo "<script>window.location='manageuser.php'</script>";
			}

			else {
				$insert="INSERT INTO user 
	 					(`userID`, `userRoleID`, `firstName`, `lastName`, `email`, `password`, `address`, `latitude`, `longitude`) 
						VALUES 
	 					('$txtuserid','$sltuserrole','$txtfirstname','$txtlastname','$txtemail','$txtpassword','$txtaddress','$txtlatitude','$txtlongitude')";
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
?>
<div class="col-12 grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title">User Register</h4>
			<form class="forms-sample" action="manageuser.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="id">UserID <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtuserid" id="id" value="<?php echo AutoID('user','userID') ?>" placeholder="ID" required="" readonly>
				</div>
				<div class="form-group">
					<label for="name">First Name <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtfirstname" id="firstname" placeholder="First Name" required="">
				</div>
				<div class="form-group">
					<label for="name">Last Name <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtlastname" id="lastname" placeholder="Last Name" required="">
				</div>
				<!-- <div class="form-group">
					<label for="photo">Photo <span style="color: red;">*</span></label><br>
					<input type="file" name="photo" id="photo" required="">
				</div> -->
				<div class="form-group">
					<label for="name">Address <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtaddress" id="address" placeholder="Address">
				</div>
				<div class="form-group">
					<label for="email">Email <span style="color: red;">*</span></label>
					<input type="email" class="form-control" name="txtemail" id="email" placeholder="Email" required="">
				</div>
				<!-- <div class="form-group">
					<label for="phone">Phone Number <span style="color: red;">*</span></label>
					<input type="number" class="form-control" id="phone" name="txtphone" placeholder="Phone Number" required="">
				</div> -->
				<div class="form-group">
					<label for="password">Password <span style="color: red;">*</span></label>
					<input type="password" class="form-control" name="txtpassword" id="password" placeholder="Password" required="">
				</div>
				<div class="form-group">
					<label for="confirmpassword">Confirm Password <span style="color: red;">*</span></label>
					<input type="password" class="form-control" name="txtconfirm" id="confirmpassword" placeholder="Confirm Password" required="">
				</div>
				<div class="form-group">
					<label for="role">Role <span style="color: red;">*</span></label>
					<select class="form-select" id="userrole" name="sltuserrole" required="">
						<option >-- Select Role --</option>
						<?php 
						$roledata="SELECT * FROM userRole";
						$result=mysqli_query($connection,$roledata);
						$count=mysqli_num_rows($result);

						for ($i=0; $i<$count; $i++) { 
							$row=mysqli_fetch_array($result);
							$userRoleID=$row['userRoleID'];
							$userRoleName=$row['userRoleName'];

							echo "<option value='$userRoleID'>$userRoleName</option>";
						}
						?>    
					</select>
				</div>

				<button type="submit" class="btn btn-primary me-2" name="btnsubmit">Submit</button>
				<button type="reset" class="btn btn-secondary" name="btnreset">Cancel</button>
			</form>
		</div>
	</div>
</div>

<?php
$query="SELECT u.*, ur.userRoleName
FROM user u INNER JOIN userRole ur ON u.userRoleID=ur.userRoleID
ORDER BY u.userID DESC";
$result=mysqli_query($connection,$query);
$count=mysqli_num_rows($result);

if($count<1){
	echo "<p>No Record Found!</p>";
}
else{
?>
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">User List:</h4>
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
								for($i=0;$i<15;$i++){
									$rows=mysqli_fetch_array($result);
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
											<a href="useredit.php?userID=<?=$userID?>&mode=edit"class="btn btn-success">Edit</a>
											<a href="manageuser.php?userID=<?=$userID?>&mode=delete" class="btn btn-danger">Delete</a>
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
<?php include 'footer.php'; ?>
