<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }
require_once('app/addons/csc_bsg_world/lib/BSG.php');
fn_register_hooks(
	'update_profile',
	'update_product_amount',
	'change_order_status'
);