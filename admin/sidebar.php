<div id="right-sidebar" class="settings-panel">
  <i class="settings-close ti-close"></i>
  <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="todo-tab" data-bs-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="chats-tab" data-bs-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
    </li>
  </ul>
  <div class="tab-content" id="setting-content">
    <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
      <div class="add-items d-flex px-3 mb-0">
        <form class="form w-100">
          <div class="form-group d-flex">
            <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
            <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
          </div>
        </form>
      </div>
      <div class="list-wrapper px-3">
        <ul class="d-flex flex-column-reverse todo-list">
          <li>
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox">
                Team review meeting at 3.00 PM
              </label>
            </div>
            <i class="remove ti-close"></i>
          </li>
          <li>
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox">
                Prepare for presentation
              </label>
            </div>
            <i class="remove ti-close"></i>
          </li>
          <li>
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox">
                Resolve all the low priority tickets due today
              </label>
            </div>
            <i class="remove ti-close"></i>
          </li>
          <li class="completed">
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox" checked>
                Schedule meeting for next week
              </label>
            </div>
            <i class="remove ti-close"></i>
          </li>
          <li class="completed">
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox" checked>
                Project review
              </label>
            </div>
            <i class="remove ti-close"></i>
          </li>
        </ul>
      </div>
      <h4 class="px-3 text-muted mt-5 fw-light mb-0">Events</h4>
      <div class="events pt-4 px-3">
        <div class="wrapper d-flex mb-2">
          <i class="ti-control-record text-primary me-2"></i>
          <span>Feb 11 2018</span>
        </div>
        <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
        <p class="text-gray mb-0">The total number of sessions</p>
      </div>
      <div class="events pt-4 px-3">
        <div class="wrapper d-flex mb-2">
          <i class="ti-control-record text-primary me-2"></i>
          <span>Feb 7 2018</span>
        </div>
        <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
        <p class="text-gray mb-0 ">Call Sarah Graves</p>
      </div>
    </div>
    <!-- To do section tab ends -->
    <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
      <div class="d-flex align-items-center justify-content-between border-bottom">
        <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
        <small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 fw-normal">See All</small>
      </div>
      <ul class="chat-list">
        <li class="list active">
          <div class="profile"><img src="images/faces/face1.jpg" alt="image"><span class="online"></span></div>
          <div class="info">
            <p>Thomas Douglas</p>
            <p>Available</p>
          </div>
          <small class="text-muted my-auto">19 min</small>
        </li>
        <li class="list">
          <div class="profile"><img src="images/faces/face2.jpg" alt="image"><span class="offline"></span></div>
          <div class="info">
            <div class="wrapper d-flex">
              <p>Catherine</p>
            </div>
            <p>Away</p>
          </div>
          <div class="badge badge-success badge-pill my-auto mx-2">4</div>
          <small class="text-muted my-auto">23 min</small>
        </li>
        <li class="list">
          <div class="profile"><img src="images/faces/face3.jpg" alt="image"><span class="online"></span></div>
          <div class="info">
            <p>Daniel Russell</p>
            <p>Available</p>
          </div>
          <small class="text-muted my-auto">14 min</small>
        </li>
        <li class="list">
          <div class="profile"><img src="images/faces/face4.jpg" alt="image"><span class="offline"></span></div>
          <div class="info">
            <p>James Richardson</p>
            <p>Away</p>
          </div>
          <small class="text-muted my-auto">2 min</small>
        </li>
        <li class="list">
          <div class="profile"><img src="images/faces/face5.jpg" alt="image"><span class="online"></span></div>
          <div class="info">
            <p>Madeline Kennedy</p>
            <p>Available</p>
          </div>
          <small class="text-muted my-auto">5 min</small>
        </li>
        <li class="list">
          <div class="profile"><img src="images/faces/face6.jpg" alt="image"><span class="online"></span></div>
          <div class="info">
            <p>Sarah Graves</p>
            <p>Available</p>
          </div>
          <small class="text-muted my-auto">47 min</small>
        </li>
      </ul>
    </div>
    <!-- chat tab ends -->
  </div>
</div>
<!-- partial -->
<!-- partial:partials/_sidebar.html -->
<?php 
if ($_SESSION['auth_rolename']) {
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="dashboard.php">
        <i class="mdi mdi-history menu-icon"></i>
        <span class="menu-title">Recent Orders</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="managerole.php">
        <i class="mdi mdi-account-star menu-icon"></i>
        <span class="menu-title">Role</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="manageuser.php">
        <i class="mdi mdi-account-plus menu-icon"></i>
        <span class="menu-title">User</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="managerestaurant.php">
        <i class="mdi mdi-silverware-fork-knife menu-icon"></i>
        <span class="menu-title">Restaurant</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="managefood.php">
        <i class="mdi mdi-food menu-icon"></i>
        <span class="menu-title">Food</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="managecart.php">
        <i class="mdi mdi-cart menu-icon"></i>
        <span class="menu-title">Cart</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="managefoodorder.php">
        <i class="mdi mdi-view-list menu-icon"></i>
        <span class="menu-title">Orders in Cart</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="statistics.php">
        <i class="mdi mdi-chart-bar-stacked menu-icon"></i>
        <span class="menu-title">Statistics</span>
      </a>
    </li>

  </ul>
</nav>
<?php }
elseif($_SESSION['auth_rolename'] == 'QA Coordinator'){
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="dashboard.php">
        <i class="mdi mdi-view-dashboard menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
   <!-- 
    <li class="nav-item">
      <a class="nav-link" href="academicyearadd.php">
        <i class="mdi mdi-brightness-auto menu-icon"></i>
        <span class="menu-title">Academic Year</span>
      </a>
    </li> -->
    <!-- <li class="nav-item">
      <a class="nav-link" href="categoryadd.php">
        <i class="mdi mdi-cube-outline menu-icon"></i>
        <span class="menu-title">Category</span>
      </a>
    </li> -->
   
    <li class="nav-item">
      <a class="nav-link" href="ideaview1.php">
        <i class="mdi mdi-assistant menu-icon"></i>
        <span class="menu-title">Idea</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="commentview1.php">
        <i class="mdi mdi-comment menu-icon"></i>
        <span class="menu-title">Comment</span>
      </a>
    </li>
   

  </ul>
</nav>
<?php 
}
else{
 ?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="dashboard.php">
        <i class="mdi mdi-view-dashboard menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
   <!-- 
    <li class="nav-item">
      <a class="nav-link" href="academicyearadd.php">
        <i class="mdi mdi-brightness-auto menu-icon"></i>
        <span class="menu-title">Academic Year</span>
      </a>
    </li> -->
    <li class="nav-item">
      <a class="nav-link" href="categoryadd.php">
        <i class="mdi mdi-cube-outline menu-icon"></i>
        <span class="menu-title">Category</span>
      </a>
    </li>
   
    <li class="nav-item">
      <a class="nav-link" href="ideaview.php">
        <i class="mdi mdi-assistant menu-icon"></i>
        <span class="menu-title">Idea</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="commentview.php">
        <i class="mdi mdi-comment menu-icon"></i>
        <span class="menu-title">Comment</span>
      </a>
    </li>
   

  </ul>
</nav>

<?php } ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">