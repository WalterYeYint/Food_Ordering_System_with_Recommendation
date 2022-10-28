<?php
	const CUSTOMER = "Customer";
	const ADMIN = "Admin";
	const SUPER_ADMIN = "Super Admin";

	// Constants for pagination.php
	const ENTRY_COUNT = 15;
	const PG_IDX_LENGTH = 25;		// This must be odd number

	// Delivery Type
	const DELIVERY = 0;
	const PICK_UP = 1;
	$delivery_type_arr = array(DELIVERY, PICK_UP);
	$delivery_type_str_arr = array("Delivery", "Pick Up");

	// Payment Type
	const CASH_ON_DELIVERY = "Cash on delivery";
	const KBZPAY = "KBZPay";
	$payment_type_str_arr = array("Cash on delivery", "KBZPay");

	// Cart Status
	const CART_CHECKED_OUT = 0;
	const CART_IN_PROGRESS = 1;
	const CART_COMPLETED = 2;
	const CART_CANCELLED= 3;
	$cart_status_arr = array(CART_CHECKED_OUT, CART_IN_PROGRESS, CART_COMPLETED, CART_CANCELLED);
	$cart_status_str_arr = array("Checked out", "In Progress", "Completed", "Cancelled");

	// Payment Status
	const UNPAID = 0;
	const PAID = 1;
	$payment_status_arr = array(UNPAID, PAID);
	$payment_status_str_arr = array("Unpaid", "Paid");

	// Search Types
	const SEARCH_TYPE_ALL = "1";
	const SEARCH_TYPE_RESTAURANT_ID = "2";
	const SEARCH_TYPE_FOOD_ORDER_ID = "3";
	const SEARCH_TYPE_CART_ID = "4";

	// Stock
	const OUT_OF_STOCK = 0;
	const IN_STOCK = 2;
	$stock_str_arr = array("Out of Stock", "foo", "In Stock");
?>