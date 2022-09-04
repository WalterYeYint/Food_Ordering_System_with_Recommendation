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

	// Cart Status
	const CART_IN_PROGRESS = 0;
	const CART_CHECKED_OUT = 1;
	const CART_BEING_PROCESSED = 2;
	const CART_COMPLETED = 3;

	// Payment Status
	const UNPAID = 0;
	const PAID = 1;
?>