<?php
	include 'headtag.php';
	include 'header.php';
	include 'sidebar.php';
	include '../dbconnect.php';

	include '../autoid.php';
?>
<?php
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
		$tuserID = "";
		$trestaurantName = "";
		$taddress = "";
		$tlatitude = "";
		$tlongitude = "";
	}
	if (isset($_POST['btnsubmit']) OR isset($_POST['btnupdate'])) {
		$txtrestaurantID = $_POST['txtrestaurantid'];
		$txtuserID = $_POST['txtuserid'];
		$txtrestaurantName = $_POST['txtrestaurantname'];
		$txtaddress = $_POST['txtaddress'];
		$txtlatitude = $_POST['txtlatitude'];
		$txtlongitude = $_POST['txtlongitude'];
		
		if(isset($_POST['btnupdate'])){
			$oldphoto=$_POST['oldphoto'];
		}
		else{
			$oldphoto="img/restaurants/default_img.jpg";
		}
		if ($_FILES['newphoto']['name']){
			$image = $_FILES['photo']['name'];
			$FolderName="img/restaurants/"; 
			$FileName=$FolderName . $image;

			// $copied=copy($_FILES['newphoto']['tmp_name'], $FileName);
		}
		else{
			$FileName = $oldphoto;
		}

	// // Copying the uploaded image into specified directory
	// $copied=copy($_FILES['photo']['tmp_name'], $FileName);
	// echo $_FILES['photo']['tmp_name'];
	// if(!$copied) 
	// {
	//   echo "<p>Restaurant Photo Cannot Upload!</p>";
	//   exit();
	// }

		if(isset($_POST['btnupdate'])){
			$update = "UPDATE restaurant SET 
								restaurantID = '$txtrestaurantID',
								userID = '$txtuserID',
								restaurantName = '$txtrestaurantName',
								address = '$txtaddress',
								latitude = '$txtlatitude',
								longitude = '$txtlongitude',
								image = '$FileName'    
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
						(`restaurantID`, `userID`, `restaurantName`, `address`, `latitude`, `longitude`, `image`)
						VALUES 
						('$txtrestaurantID','$txtuserID','$txtrestaurantName','$txtaddress','$txtlatitude','$txtlongitude','$FileName')";
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
					<input type="text" class="form-control" name="txtuserid" id="id" value="<?php echo $tuserID ?>" placeholder="ID" required="">
				</div>
				<div class="form-group">
					<label for="name">Restaurant Name <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtrestaurantname" id="restaurantname" value="<?php echo $trestaurantName ?>" placeholder="Restaurant Name" required="">
				</div>
				<div class="form-group">
					<label for="name">Address <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtaddress" id="address" value="<?php echo $taddress ?>" placeholder="Address">
				</div>
				<div class="form-group">
					<label for="name">Latitude <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtlatitude" id="latitude" value="<?php echo $tlatitude ?>" placeholder="Latitude">
				</div>
				<div class="form-group">
					<label for="name">Longitude <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtlongitude" id="longitude" value="<?php echo $tlongitude ?>" placeholder="Longitude">
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
					<label for="photo">Photo <span style="color: red;">*</span></label><br>
					<input type="file" name="photo" id="photo">
				</div>
				<?php
				if(isset($_GET['restaurantID'])){
				?>
					<button type="submit" class="btn btn-primary me-2" name="btnupdate">Update</button>	
				<?php
				}
				else{
				?>
					<button type="submit" class="btn btn-primary me-2" name="btnsubmit">Submit</button>
				<?php
				}
				?>
				<button type="reset" class="btn btn-secondary" name="btnreset">Cancel</button>
			</form>
		</div>
	</div>
</div>
<?php
$query = "SELECT * FROM restaurant
					ORDER BY restaurantID DESC";
$result = mysqli_query($connection, $query);
$count = mysqli_num_rows($result);

if($count<1){
	echo "<p>No Record Found!</p>";
}
else{
?>
<div class="col-lg-12 stretch-card">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title">Restaurant List:</h4>
			<div class="table-responsive pt-3">
				<table class="table datatable table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>User ID</th>
							<th>Restaurant Name</th>
							<th>Address</th>
							<th>Latitude</th>
							<th>Longitude</th>
							<th>Image</th>
							<th>Action</th>
					</thead>
					<tbody>
					<?php  
					for($i=0; $i<15; $i++) 
					{ 
						$rows=mysqli_fetch_array($result);
						$restaurantID = $rows['restaurantID'];
						$userID = $rows['userID'];
						$restaurantName = $rows['restaurantName'];  
						$address = $rows['address'];   
						$latitude = $rows['latitude'];  
						$longitude = $rows['longitude'];  
						$image = $rows['image'];  
					?>
						<tr>
								<th><?php echo $restaurantID ?></th>
								<th><?php echo $userID ?></th>
								<td><?php echo $restaurantName ?></td>
								<td><?php echo $address ?></td>
								<td><?php echo $latitude ?></td>
								<td><?php echo $longitude ?></td>
								<td><?php echo $image ?></td>
								<td>
										<a href="managerestaurant.php?restaurantID=<?=$restaurantID?>&mode=edit"class="btn btn-success">Edit</a>
										<a href="managerestaurant.php?restaurantID=<?=$restaurantID?>&mode=delete" class="btn btn-danger" onclick="return confirm_delete('<?php echo $restaurantName ?>')">Delete</a>
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
