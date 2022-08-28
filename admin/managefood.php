<?php
	include 'headtag.php';
	include 'header.php';
	include 'sidebar.php';
	include '../dbconnect.php';

	include '../autoid.php';
	include '../constants.php';
?>
<?php
	// Restaurants Query that are used more than once
	if($userRoleName_sess == ADMIN){
		$query = "SELECT * FROM restaurant
							WHERE userID = '$userID_sess'";
	}
	else{
		$query = "SELECT * FROM restaurant
							ORDER BY restaurantID DESC";
	}
	$restaurant_result = mysqli_query($connection, $query);
	$restaurant_count = mysqli_num_rows($restaurant_result);
	$restaurant_arr = mysqli_fetch_all($restaurant_result, MYSQLI_BOTH);

	if(isset($_GET['foodID'])){
		if($_GET['mode'] == 'edit'){
			$foodID=$_GET['foodID'];

			$query = "SELECT * FROM food WHERE foodID='$foodID'";
			$result = mysqli_query($connection,$query);
			$arr = mysqli_fetch_array($result);

			$tfoodID = $arr['foodID'];
			$trestaurantID = $arr['restaurantID'];
			$tfoodName = $arr['foodName'];
			$tprice = $arr['price'];
		}
		elseif($_GET['mode'] == 'delete'){
			$foodID=$_GET['foodID'];

			$delete="DELETE FROM food WHERE foodID='$foodID'";
			$result=mysqli_query($connection,$delete);
			if ($result) 
			{
				echo "<script>window.alert('Food Deleted Successfully!')</script>";
				echo "<script>window.location='managefood.php'</script>";
			}
			else
			{
				echo "<p>Something went wrong in Food Delete : " . mysqli_error($connection) . "</p>";
			}
		}
	}
	else{
		// echo "<script>window.alert('Renewed!')</script>";
		$tfoodID = AutoID('food','foodID');
		$trestaurantID = "";
		$tfoodName = "";
		$tprice = "";
	}
	if (isset($_POST['btnsubmit']) OR isset($_POST['btnupdate'])) {
		$txtfoodID = $_POST['txtfoodid'];
		$txtrestaurantID = $_POST['sltrestaurant'];
		$txtfoodName = $_POST['txtfoodname'];
		$txtprice = $_POST['txtprice'];
		
		if(isset($_POST['btnupdate'])){
			$oldphoto=$_POST['oldphoto'];
		}
		else{
			$oldphoto="img/restaurants/default_img.jpg";
		}
		if ($_FILES['newphoto']['name']){
			$image = $_FILES['photo']['name'];
			$FolderName="img/food/"; 
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
			$update = "UPDATE food SET 
								foodID = '$txtfoodID',
								restaurantID = '$txtrestaurantID',
								foodName = '$txtfoodName',
								price = '$txtprice',
								image = '$FileName'    
								WHERE foodID = '$txtfoodID'";
			$result = mysqli_query($connection,$update);

			if($result) 
			{
				echo "<script>window.alert('Food Info Updated Successfully!')</script>";
				echo "<script>window.location='managefood.php'</script>";
			}
			else
			{
				echo "<p>Something went wrong in Updating Food Information : " . mysqli_error($connection) . "</p>";
			}
		}
		else{
			//Check Validation
			$check="SELECT * FROM food 
							WHERE foodID = '$txtfoodID'
							OR (restaurantID = '$txtrestaurantID'
							AND foodName = '$txtfoodName')";
			$result=mysqli_query($connection,$check);
			$count=mysqli_num_rows($result);
			if ($count>0) {
				echo "<script>window.alert('Food Already Exists!')</script>";
				echo "<script>window.location='managefood.php'</script>";
			}
			else{
				$insert="INSERT INTO food 
						(`foodID`, `restaurantID`, `foodName`, `price`, `image`)
						VALUES 
						('$txtfoodID','$txtrestaurantID','$txtfoodName','$txtprice','$FileName')";
				$result=mysqli_query($connection,$insert);
				if ($result) {
					echo "<script>window.alert('Food Added Successfully!')</script>";
					echo "<script>window.location='managefood.php'</script>";
				}
		
				else{
					echo "<p>Something went wrong in Food Entry : " . mysqli_error($connection) . "</p>";
				}
			}
		}
	}
?>
<div class="col-12 grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title">Manage Food</h4>
			<!-- In the following form tag, -->
			<!-- Without enctype="multipart/form-data", image file name doesn't get through POST -->
			<form class="forms-sample" action="managefood.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="id">Food ID <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtfoodid" id="id" value="<?php echo $tfoodID ?>" placeholder="ID" required="" readonly>
				</div>
				<div class="form-group">
					<label for="role">Restaurant Name <span style="color: red;">*</span></label>
					<select class="form-select" id="restaurantid" name="sltrestaurant" required="">
						<option >-- Select Restaurant --</option>
						<?php
						for ($i=0; $i<$restaurant_count; $i++) { 
							$row=$restaurant_arr[$i];
							$restaurantID=$row['restaurantID'];
							$restaurantName=$row['restaurantName'];
						?>
							<option value=<?php echo $restaurantID ?>
						<?php
							if ($trestaurantID == $restaurantID) {
								echo "selected";
							}
							echo ">$restaurantName</option>";
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<label for="name">Food Name <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtfoodname" id="foodname" value="<?php echo $tfoodName ?>" placeholder="Food Name" required="">
				</div>
				<div class="form-group">
					<label for="name">Price <span style="color: red;">*</span></label>
					<input type="text" class="form-control" name="txtprice" id="price" value="<?php echo $tprice ?>" placeholder="Price" required="">
				</div>
				<?php
				if(isset($_GET['foodID'])){
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
				if(isset($_GET['foodID'])){
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
				<button type="reset" class="btn btn-secondary" id="reset" name="btnreset">Cancel</button>
			</form>
		</div>
	</div>
</div>
<?php
if($userRoleName_sess == ADMIN){
	$restaurantName_list = array();
	foreach($restaurant_arr as $row){
		array_push($restaurantName_list, $row['restaurantID']);
	}

	$query = "SELECT * FROM food
						WHERE restaurantID IN (".implode(',', $restaurantName_list).")
						ORDER BY foodID DESC";
}
else{
	$query = "SELECT * FROM food
						ORDER BY foodID DESC";
}
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
			<h4 class="card-title">Food List:</h4>
			<div class="table-responsive pt-3">
				<table class="table datatable table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Restaurant ID</th>
							<th>Food Name</th>
							<th>Price</th>
							<th>Image</th>
							<th>Action</th>
					</thead>
					<tbody>
					<?php  
					for($i=0; $i<15; $i++) 
					{ 
						$rows=mysqli_fetch_array($result);
						$foodID = $rows['foodID'];
						$restaurantID = $rows['restaurantID'];
						$foodName = $rows['foodName'];  
						$price = $rows['price'];
						$image = $rows['image'];  
					?>
						<tr>
								<th><?php echo $foodID ?></th>
								<th><?php echo $restaurantID ?></th>
								<td><?php echo $foodName ?></td>
								<td><?php echo $price ?></td>
								<td><?php echo $image ?></td>
								<td>
										<a href="managefood.php?foodID=<?=$foodID?>&mode=edit"class="btn btn-success">Edit</a>
										<a href="managefood.php?foodID=<?=$foodID?>&mode=delete" class="btn btn-danger" onclick="return confirm_delete('<?php echo $foodName ?>')">Delete</a>
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
