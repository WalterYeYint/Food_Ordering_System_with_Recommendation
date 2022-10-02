<?php
include 'headtag.php';
include 'header.php';
include 'sidebar.php';
include '../dbconnect.php';
?>
<div class="col-lg-12 stretch-card" id="table">
</div>
<script type="text/javascript">
  function table(){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function(){
      document.getElementById("table").innerHTML = this.responseText;
    }
    xhttp.open("GET", "orderdisplay.php");
    xhttp.send();
  }
  setInterval(function(){
    table();
  }, 3000);

  table();
</script>
<?php include 'footer.php'; ?>