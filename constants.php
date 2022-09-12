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

	// Payment Type
	const CASH_ON_DELIVERY = "Cash on delivery";
	const KBZPAY = "KBZPay";

	// Cart Status
	const CART_CHECKED_OUT = 0;
	const CART_IN_PROGRESS = 1;
	const CART_COMPLETED = 2;

	// Payment Status
	const UNPAID = 0;
	const PAID = 1;
?>