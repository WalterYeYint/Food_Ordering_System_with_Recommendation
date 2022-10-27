<?php
	include 'headtag.php';
	include 'header.php';
	include 'sidebar.php';
	include '../dbconnect.php';
?>
<?php
	$admin = ADMIN;
	$select = "SELECT u.*, ur.* 
						FROM user u, userRole ur
						WHERE u.userRoleID = ur.userRoleID
						AND ur.userRoleName = '$admin'";
	$result = mysqli_query($connection,$select);
	$user_count=mysqli_num_rows($result);
	$user_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

	if(isset($_GET['restaurantID'])){
		if($_GET['mode'] == 'edit'){
			$restaurantID=$_GET['restaurantID'];
	
			$query = "SELECT * FROM restaurant WHERE restaurantID='$restaurantID'";
			$result = mysqli_query($connection,$query);
			$arr = mysqli_fetch_array($result);

			$trestaurantID = $arr['restaurantID'];
			$tuserID = $arr['userID'];
			$trestaurantName = $arr['restaurantName'];
			$taddress = $arr['address'];
			$tlatitude = $arr['latitude'];
			$tlongitude = $arr['longitude'];
			$tkpayphoneno = $arr['KPayPhoneNo'];
		}
		elseif($_GET['mode'] == 'delete'){
			$restaurantID=$_GET['restaurantID'];

			$delete="DELETE FROM restaurant WHERE restaurantID='$restaurantID'";
			$result=mysqli_query($connection,$delete);
			if ($result) 
			{
				echo "<script>window.alert('Restaurant Deleted Successfully!')</script>";
				echo "<script>window.location='managerestaurant.php'</script>";
			}
			else
			{
				echo "<p>Something went wrong in Restaurant Delete : " . mysqli_error($connection) . "</p>";
			}
		}
	}
	else{
		// echo "<script>window.alert('Renewed!')</script>";
		$trestaurantID = AutoID('restaurant','restaurantID');
		$tuserID = $userID_sess;
		$trestaurantName = "";
		$taddress = "";
		$tlatitude = "";
		$tlongitude = "";
		$tkpayphoneno = "";
	}
	if (isset($_POST['btnsubmit']) OR isset($_POST['btnupdate'])) {
		$txtrestaurantID = $_POST['txtrestaurantid'];
		$sltuserID = $_POST['sltuserid'];
		$txtrestaurantName = $_POST['txtrestaurantname'];
		$txtaddress = $_POST['txtaddress'];
		$txtlatitude = $_POST['txtlatitude'];
		$txtlongitude = $_POST['txtlongitude'];
		$txtkpayphoneno = $_POST['txtkpayphoneno'];
		
		if(isset($_POST['btnupdate'])){
			$oldphoto=$_POST['oldphoto'];
		}
		else{
			$oldphoto="img/restaurants/default_img.jpg";
		}
		if ($_FILES['newphoto']['name']){
			$image = $_FILES['newphoto']['name'];
			$FolderName="img/restaurants/"; 
			$FileName=$FolderName . basename($image);
			$realdir="../img/restaurants/";
			$dirname=$realdir . basename($image);

			// $copied=copy($_FILES['newphoto']['tmp_name'], $FileName);
		}
		else{
			$FileName = $oldphoto;
		}

		// if (move_uploaded_file($_FILES["newphoto"]["tmp_name"], $dirname)) {
		// 	echo "<script>window.alert('The file ".htmlspecialchars( basename( $_FILES['newphoto']['name']))." has been uploaded.')</script>";
		// 	// "The file ". htmlspecialchars( basename( $_FILES['newphoto']['name'])). " has been uploaded.";
		// } else {
		// 	echo "<script>window.alert('Sorry, there was an error uploading your file.')</script>";
		// }	

		// Copying the uploaded image into specified directory
		$copied=copy($_FILES['newphoto']['tmp_name'], $dirname);
		echo $_FILES['newphoto']['tmp_name'];
		if(!$copied) 
		{
			echo "<p>Restaurant Photo Cannot Upload!</p>";
			exit();
		}

		if(isset($_POST['btnupdate'])){
			$update = "UPDATE restaurant SET 
								restaurantID = '$txtrestaurantID',
								userID = '$sltuserID',
								restaurantName = '$txtrestaurantName',
								address = '$txtaddress',
								latitude = '$txtlatitude',
								longitude = '$txtlongitude',
								image = '$FileName',
								KPayPhoneNo = '$txtkpayphoneno'
								WHERE restaurantID = '$txtrestaurantID'";
			$result = mysqli_query($connection,$update);

			if($result) 
			{
				echo "<script>window.alert('Restaurant Info Updated Successfully!')</script>";
				echo "<script>window.location='managerestaurant.php'</script>";
			}
			else
			{
				echo "<p>Something went wrong in Updating Restaurant Information : " . mysqli_error($connection) . "</p>";
			}
		}
		else{
			//Check Validation
			$check="SELECT * FROM restaurant WHERE restaurantID='$txtrestaurantID' OR restaurantName='$txtrestaurantName'";
			$result=mysqli_query($connection,$check);
			$count=mysqli_num_rows($result);
			if ($count>0) {
				echo "<script>window.alert('Restaurant Already Exists!')</script>";
				echo "<script>window.location='managerestaurant.php'</script>";
			}
			else{
				$insert="INSERT INTO restaurant 
						(`restaurantID`, `userID`, `restaurantName`, `address`, `latitude`, `longitude`, `image`, `KPayPhoneNo`)
						VALUES 
						('$txtrestaurantID','$sltuserID','$txtrestaurantName','$txtaddress','$txtlatitude','$txtlongitude','$FileName','$txtkpayphoneno')";
				$result=mysqli_query($connection,$insert);
				if ($result) {
					echo "<script>window.alert('Restaurant Added Successfully!')</script>";
					echo "<script>window.location='managerestaurant.php'</script>";
				}
		
				else{
					echo "<p>Something went wrong in Restaurant Entry : " . mysqli_error($connection) . "</p>";
				}
			}
		}
	}
?>
<div class="col-12 grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title">Manage Restaurant</h4>
			<!-- In the following form tag, -->
			<!-- Without enctype="multipart/form-data", image file name doesn't get through POST -->
			<form class="forms-sample" action="managerestaurant.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="id">Restaurant ID <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtrestaurantid" id="id" value="<?php echo $trestaurantID ?>" placeholder="ID" required="" readonly>
				</div>
				<div class="form-group">
					<label for="id">User ID <span style="color: red;">*</span></label>
					<select class="form-select" id="sltuserid" name="sltuserid" required="">
						<?php
						for ($i=0; $i<$user_count; $i++) { 
							$row=$user_arr[$i];
							$userID=$row['userID'];
							$email=$row['email'];
						?>
							<option value=<?php echo $userID ?>
						<?php
							if ($tuserID == $userID) {
								echo "selected";
							}
							echo ">$email (ID-$userID)</option>";
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<label for="name">Restaurant Name <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtrestaurantname" id="restaurantname" value="<?php echo $trestaurantName ?>" placeholder="Restaurant Name" required="">
				</div>
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
					<label for="name">KPay Phone No <span style="color: red;">*</span></label>
					<input type="number" class="form-control" name="txtkpayphoneno" id="kpayphoneno" value="<?php echo $tkpayphoneno ?>" placeholder="kpayphoneno" required="">
				</div>
				<?php
				if(isset($_GET['restaurantID'])){
				?>
					<div class="form-group">
						<label>Old Photo</label><br>
						<input type="hidden" name="oldphoto" value="<?= $arr['image']?>">
						<img src="<?='../'.$arr['image']?>" width=100px; heigt=100px;>
					</div>
				<?php
				}
				?>
				<div class="form-group">
					<label for="photo">Photo</label><br>
					<input type="file" name="newphoto" id="photo">
				</div>
				<?php
				if(isset($_GET['restaurantID'])){
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
if($userRoleName_sess == ADMIN){
	$query = "SELECT r.*,
						u.userID, u.email
						FROM restaurant r, user u
						WHERE r.userID = u.userID
						AND r.userID = '$userID_sess'
						ORDER BY r.restaurantID DESC";
}
else{
	$query = "SELECT r.*,
						u.userID, u.email 
						FROM restaurant r, user u
						WHERE r.userID = u.userID
						ORDER BY r.restaurantID DESC";
}
$result = mysqli_query($connection, $query);
$count = mysqli_num_rows($result);
$restaurant_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

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
			<h4 class="card-title">Restaurant List:</h4>
			<ul class="nav nav-tabs" role="tablist">
				<a href="managerestaurant.php?pgNo=<?=1?>" class="nav-link"><<</a>
				<?php
				for($i=$pg_idx_start; $i<=$pg_idx_end; $i++){
				?>
					<li class="nav-item">
            <a href="managerestaurant.php?pgNo=<?=$i?>" class="nav-link 
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
				<a href="managerestaurant.php?pgNo=<?=$page_count?>" class="nav-link">>></a>
			</ul>
			<div class="table-responsive pt-3">
				<table class="table datatable table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>User Email</th>
							<th>Restaurant Name</th>
							<th>Address</th>
							<th>Latitude</th>
							<th>Longitude</th>
							<th>Image</th>
							<th>KPayPhoneNo</th>
							<th>Action</th>
					</thead>
					<tbody>
					<?php
					$idx = ($pgNo-1)*$entry_count;
					for($i=$idx; $i<$idx+$actual_entry_count; $i++){
						$rows=$restaurant_arr[$i];
						$restaurantID = $rows['restaurantID'];
						$userID = $rows['userID'];
						$restaurantName = $rows['restaurantName'];  
						$email = $rows['email'];
						$address = $rows['address'];   
						$latitude = $rows['latitude'];  
						$longitude = $rows['longitude'];  
						$image = $rows['image'];  
						$kpayphoneno = $rows['KPayPhoneNo'];
					?>
						<tr>
								<th><?php echo $restaurantID ?></th>
								<td><?php echo $email.'(ID-'.$userID.')' ?></td>
								<td><?php echo $restaurantName ?></td>
								<td><?php echo $address ?></td>
								<td><?php echo $latitude ?></td>
								<td><?php echo $longitude ?></td>
								<td><?php echo $image ?></td>
								<td><?php echo $kpayphoneno ?></td>
								<td>
										<a href="managerestaurant.php?restaurantID=<?=$restaurantID?>&mode=edit"class="btn btn-info btn-rounded">Edit</a>
										<a href="managerestaurant.php?restaurantID=<?=$restaurantID?>&mode=delete" class="btn btn-danger btn-rounded" onclick="return confirm_delete('<?php echo $restaurantName ?>')">Delete</a>
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
