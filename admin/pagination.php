<?php
function paginate($count){
	$pgNo = 1;
	$entry_count = 15;
	if(isset($_GET['pgNo'])){
		$pgNo = $_GET['pgNo'];
	}
	$page_count = ceil($count / $entry_count);
	$actual_entry_count = $entry_count;

	if($pgNo == $page_count){
		$actual_entry_count = $count % $entry_count;
	}

	$return_arr = array();
	array_push($return_arr, $entry_count);
	array_push($return_arr, $actual_entry_count);
	array_push($return_arr, $page_count);
	array_push($return_arr, $pgNo);
	return $return_arr;
}
?>