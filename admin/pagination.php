<?php
require_once '../constants.php';

function paginate($count){
	$pgNo = 1;
	$entry_count = ENTRY_COUNT;
	$pg_idx_length = PG_IDX_LENGTH;
	$pg_idx_start = 1;
	$pg_idx_end = 1;

	if(isset($_GET['pgNo'])){
		$pgNo = $_GET['pgNo'];
	}
	$page_count = ceil($count / $entry_count);
	$actual_entry_count = $entry_count;

	if($pgNo == $page_count){
		if($count%$entry_count == 0){
			$actual_entry_count = $entry_count;
		}
		else{
			$actual_entry_count = $count % $entry_count;
		}
	}

	$pointer_start = ceil($pg_idx_length/2);
	$pointer_end = $page_count - floor($pg_idx_length/2);
	if($pointer_end < $pointer_start){
		$pointer_end = $pointer_start;
		$range = $page_count - 1;
	}
	else{
		$range = $pg_idx_length - 1;
	}

	if($pgNo >= $pointer_end){
		$pointer = $pointer_end;
	}
	elseif($pgNo <= $pointer_start){
		$pointer = $pointer_start;
	}
	else{
		$pointer = $pgNo;
	}
	$pg_idx_start = $pointer - floor($pg_idx_length/2);
	$pg_idx_end = $pg_idx_start + $range;

	$return_arr = array();
	array_push($return_arr, $entry_count);
	array_push($return_arr, $actual_entry_count);
	array_push($return_arr, $page_count);
	array_push($return_arr, $pgNo);
	array_push($return_arr, $pg_idx_start);
	array_push($return_arr, $pg_idx_end);
	return $return_arr;
}
?>