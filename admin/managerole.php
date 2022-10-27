<?php
	include 'headtag.php';
	include 'header.php';
	include 'sidebar.php';
	include '../dbconnect.php';
?>
<?php
if(isset($_GET['userRoleID'])){
  if($_GET['mode'] == 'delete'){
    $userRoleID=$_GET['userRoleID'];

    $delete="DELETE FROM userRole WHERE userRoleID='$userRoleID'";
    $result=mysqli_query($connection,$delete);
    if ($result) 
    {
      echo "<script>window.alert('Role Deleted Successfully!')</script>";
      echo "<script>window.location='managerole.php'</script>";
    }
    else
    {
      echo "<p>Something went wrong in Role Delete : " . mysqli_error($connection) . "</p>";
    }
  }
}
else{
  // echo "<script>window.alert('Renewed!')</script>";
  $tuserRoleID = AutoID('userRole','userRoleID');
  $tuserRoleName = "";
}

if (isset($_POST['btnsubmit'])) {
  $txtuserRoleID = $_POST['txtuserroleid'];
  $txtuserRoleName = $_POST['txtuserrolename'];

  //Check Validation
  $check = "SELECT * FROM userRole WHERE userRoleID='$txtuserRoleID' OR userRoleName='$txtuserRoleName'";
  $result = mysqli_query($connection,$check);
  $count = mysqli_num_rows($result);

  if ($count>0) {
    echo "<script>window.alert('Role Already Exist!')</script>";
    echo "<script>window.location='managerole.php'</script>";
  }

  else {
    $insert = "INSERT INTO userRole
              (`userRoleID`, `userRoleName`)
              VALUES
              ('$txtuserRoleID', '$txtuserRoleName')";
    $result = mysqli_query($connection,$insert);
  }

  if ($result) {
    echo "<script>window.alert('Role Added Successfully!')</script>";
    echo "<script>window.location='managerole.php'</script>";
  }

  else{
    echo "<p>Something went wrong in Role Entry : " . mysqli_error($connection) . "</p>";
  }
}
?>
<div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Manage Role</h4>
          <form class="forms-sample" action="managerole.php" method="post">
            <div class="form-group">
              <label for="id">Role ID <span style="color: red;">*</span></label>
              <input type="text" class="form-control" name="txtuserroleid" id="id" value="<?php echo $tuserRoleID ?>" placeholder="ID" required="" readonly>
            </div>
            <div class="form-group">
              <label for="rname">Role Name <span style="color: red;">*</span></label>
              <input type="text" class="form-control" id="userrolename" name="txtuserrolename" placeholder="Role Name" required="">
            </div>             
            <button type="submit" class="btn btn-success me-2" name="btnsubmit">Submit</button>
            <button type="reset" class="btn btn-outline-dark" name="btnreset">Cancel</button>
          </form>
        </div>
      </div>
</div>

<?php 
$query = "SELECT * FROM userRole";
$result = mysqli_query($connection,$query);
$count = mysqli_num_rows($result);
$role_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

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
      <h4 class="card-title">Role List:</h4>
      <ul class="nav nav-tabs" role="tablist">
				<a href="managerole.php?pgNo=<?=1?>" class="nav-link"><<</a>
        <?php
        for($i=$pg_idx_start; $i<=$pg_idx_end; $i++){
        ?>
          <li class="nav-item">
            <a href="managerole.php?pgNo=<?=$i?>" class="nav-link 
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
				<a href="managerole.php?pgNo=<?=$page_count?>" class="nav-link">>></a>
      </ul>
      <div class="table-responsive pt-3">
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Role Name</th>
              <th>Action</th>
          </thead>
          <tbody>
          <?php  
          $idx = ($pgNo-1)*$entry_count;
          for($i=$idx; $i<$idx+$actual_entry_count; $i++){
            $rows = $role_arr[$i];
            $userRoleID = $rows['userRoleID'];
            $userRoleName = $rows['userRoleName'];   
          ?>
          <tr>
              <th><?php echo $userRoleID ?></th>
              <td><?php echo $userRoleName ?></td>
              <td>
                  <!--  <a href="roleedit.php?userroleid=<?=$userroleid?>"class="btn btn-success">Edit</a> -->
                  <a href="managerole.php?userRoleID=<?=$userRoleID?>&mode=delete" class="btn btn-danger btn-rounded" onclick="return confirm_delete('<?php echo $userRoleName ?>')">
                  Delete</a>
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
