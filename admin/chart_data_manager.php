<?php
	class ChartDataManager
	{
		public $connection;
		public $dataPoints;
		public $dataPointsPercent;
		public $profitDataPoints;
		function __construct($connection, $userID_sess) {
			$this->connection = $connection;
			$this->userID_sess = $userID_sess;
		}
		
		function set_chart_data()
		{
			$query = "SELECT * FROM restaurant
						WHERE userID = '$this->userID_sess'
						ORDER BY restaurantID DESC";
			$result = mysqli_query($this->connection, $query);
			$count = mysqli_num_rows($result);
			$restaurant_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

			$restaurantID = $restaurant_arr[2]['restaurantID'];
			
			$query = "SELECT * FROM food
								WHERE restaurantID = '$restaurantID'
								ORDER BY foodID DESC";
			$result = mysqli_query($this->connection, $query);
			$count = mysqli_num_rows($result);
			$food_arr = mysqli_fetch_all($result, MYSQLI_BOTH);

			$food_count_list = array();
			$foodName_list = array();
			for ($i=0; $i <$count ; $i++) { 
				$row = $food_arr[$i];
				$foodID = $row['foodID'];
				$foodName = $row['foodName'];
				array_push($foodName_list, $foodName);

				$select = "SELECT COUNT(foodID)
									FROM foodorder
									WHERE foodID = '$foodID'
									ORDER BY foodorderID DESC";
				$result = mysqli_query($this->connection, $select);
				$food_count = mysqli_fetch_array($result)[0];
				array_push($food_count_list, $food_count);
				$dataPoints[$i]=array("y"=>$food_count, "label"=>$foodName);
			}

			$this->dataPoints = $dataPoints;
			// $this->dataPoints = array_slice($dataPoints, 25);
		}

		function set_income_data()
		{
			$today = date('Y-m-d');
			$date_to = date_create($today);
			// $date_from = date_sub($date_to, date_interval_create_from_date_string("1 day"));
			// $past_day = date_format($date_from, 'Y-m-d');
			// echo "Today is $today.";
			// echo "Prev day is $past_day.";

			// $select="SELECT * FROM cart
			// 				WHERE restaurantID = '$restaurantID'
			// 				AND date between '$past_day' and '$today'
			// 				ORDER BY cartID DESC";
			$restaurantID = 39;
			$desired_date = $today;
			for($i=0; $i<7; $i++){
				$select="SELECT * FROM cart
								WHERE restaurantID = '$restaurantID'
								AND date = '$desired_date'
								ORDER BY cartID DESC";
				$result=mysqli_query($this->connection,$select);
				$count=mysqli_num_rows($result);
				$select_cart_arr = mysqli_fetch_all($result, MYSQLI_BOTH);
				$totalAmount = 0;
				for ($j=0; $j <$count ; $j++) { 
					$row = $select_cart_arr[$j];
					$cartID = $row['cartID'];
					$totalAmount += $row['totalAmount'];
				}
				$dataPoints[$i]=array("y"=>$totalAmount, "label"=>$desired_date);
				$date_from = date_sub($date_to, date_interval_create_from_date_string("1 day"));
				$desired_date = date_format($date_from, 'Y-m-d');
			}
			$dataPoints = array_reverse($dataPoints);
			$this->profitDataPoints = $dataPoints;
		}

		function get_chart_data()
		{
			return $this->dataPoints;
		}

		function get_ideas_per_department_percent()
		{
			return $this->dataPointsPercent;
		}

		function get_income_data()
		{
			return $this->profitDataPoints;
		}
	}
 
?>
