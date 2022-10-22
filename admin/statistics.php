<?php
  include 'headtag.php';
  include 'header.php';
  include 'sidebar.php';
  include '../dbconnect.php';

  include 'chart_data_manager.php';
?>
<?php
  $chart=new ChartDataManager($connection, $userID_sess);
  $chart->set_chart_data();
  $chart->set_income_data();

  // $query="SELECT * FROM idea";
  // $result=mysqli_query($connection,$query);
  // $count=mysqli_num_rows($result);

  // $query1="SELECT * FROM idea WHERE anonymous='Yes'";
  // $result1=mysqli_query($connection,$query1);
  // $count1=mysqli_num_rows($result1);

  // $query2="SELECT DISTINCT userid FROM idea";
  // $result2=mysqli_query($connection,$query2);
  // $count2=mysqli_num_rows($result2);

  // $query3="SELECT i.*,u.fullname as fullname, u.image as image, d.departmentname as departmentname FROM idea i, user u, department d WHERE i.userid=u.userid AND u.departmentid=d.departmentid ORDER BY i.date DESC LIMIT 5 ";
  // $result3=mysqli_query($connection,$query3);
  // $count3=mysqli_num_rows($result3);

  // $query4="SELECT * FROM comment WHERE anonymous='Yes'";
  // $result4=mysqli_query($connection,$query4);
  // $count4=mysqli_num_rows($result4);

  // $query5="SELECT * FROM comment";
  // $result5=mysqli_query($connection,$query5);
  // $count5=mysqli_num_rows($result5);

  // $query6="SELECT c.*,u.image as image, u.fullname as fullname FROM comment c, idea i, user u WHERE c.ideaid=i.ideaid AND c.userid=u.userid ORDER BY c.date DESC LIMIT 10";
  // $result6=mysqli_query($connection,$query6);
  // $count6=mysqli_num_rows($result6);
?>

<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
          <div class="row">
            <div class="d-flex flex-column">
              <div class="row flex-grow">
                <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                  <div class="card card-rounded">
                    <div class="card-body">
                      <?php
                        $dataPoints=$chart->get_chart_data();
                      ?>
                      <script>
                        window.addEventListener("load",function()
                        {
                          var bar_chart = new CanvasJS.Chart("barchartContainer", {
                            animationEnabled: true,
                            title:{
                              text: "Number of Orders per Food Entry"
                            },
                            axisY: {
                              title: "Number of Orders",
                              includeZero: true,
                            },
                            data: [{
                              type: "column",
                              yValueFormatString: "#",
                              indexLabel: "{y}",
                              indexLabelPlacement: "inside",
                              indexLabelFontWeight: "bolder",
                              indexLabelFontColor: "white",
                              dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                            }]
                          });
                        bar_chart.render();
                        },false
                        );
                      </script>
                      <div id="barchartContainer" style="height: 370px; width: 100%;"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="d-flex flex-column">
              <div class="row flex-grow">
                <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                  <div class="card card-rounded">
                    <div class="card-body">
                      <?php
                        // echo '<pre>'; print_r($chart->get_contributors_per_department()); echo '</pre>';
                        $dataPointsPercent=$chart->get_chart_data();
                      ?>
                      <script>
                        window.addEventListener("load",function()
                        {
                          var pie_chart = new CanvasJS.Chart("piechartContainer", {
                            animationEnabled: true,
                            title: {
                              text: "Number of Orders per Food Entry"
                            },
                            // subtitles: [{
                            //   text: "November 2017"
                            // }],
                            // data: [{
                            //   type: "pie",
                            //   yValueFormatString: "#,##0.00\"%\"",
                            //   indexLabel: "{label} ({y})",
                            //   dataPoints: <?php echo json_encode($dataPointsPercent, JSON_NUMERIC_CHECK); ?>
                            // }]
                            data: [{
                              type: "pie",
                              indexLabel: "{y}",
                              yValueFormatString: "#,##0.00",
                              indexLabelPlacement: "inside",
                              indexLabelFontColor: "#36454F",
                              indexLabelFontSize: 18,
                              indexLabelFontWeight: "bolder",
                              showInLegend: true,
                              legendText: "{label}",
                              dataPoints: <?php echo json_encode($dataPointsPercent, JSON_NUMERIC_CHECK); ?>
                            }]
                          });
                          pie_chart.render();
                        
                        },false
                        );
                      </script>
                      <div id="piechartContainer" style="height: 370px; width: 100%;"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row flex-grow">
                <!-- <div class="col-12 grid-margin stretch-card">
                  <div class="card card-rounded table-darkBGImg"> -->
                    <div class="card-body">
                    <?php
                      $profitDataPoints=$chart->get_income_data();
                    ?>
                    <script>
                        window.addEventListener("load",function()
                        {
                          var income_line_chart = new CanvasJS.Chart("incomelinechartContainer", {
                            title: {
                              text: "Incomes Over a Week"
                            },
                            axisY: {
                              title: "Income (Kyats)"
                            },
                            data: [{
                              type: "line",
                              dataPoints: <?php echo json_encode($profitDataPoints, JSON_NUMERIC_CHECK); ?>
                            }]
                          });
                          income_line_chart.render();
                        },false
                        );
                      </script>
                      <div id="incomelinechartContainer" style="height: 370px; width: 100%;"></div>
                    </div>
                  <!-- </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>