<?php
use Tygh\Registry;
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if ($mode == 'request' || $_REQUEST['product_data']) 
	{
		$product = fn_get_product_data($_REQUEST['call_data']['product_id'], $_SESSION['auth']);
		$addon = Registry::get('addons.csc_bsg_world');
		//сообщенька админу новый заказ
		$params = array(
			'recipient' => 'admin',
			'body' => __("call_requests") . '. Product: ' . $product['product'] . '. Name:  ' . $_REQUEST['call_data']['name'] . '. Phone:  ' . $_REQUEST['call_data']['phone'] . '. Email:  ' . $_REQUEST['call_data']['email'],
			'event' => 'new_order'
		);
		$res = fn_send_amocrm_message($params);
		
		if ($_REQUEST['call_data']['phone'])
		{
			//сообщенька кастомеру новый заказ
			$params = array(
				'phones' => array($_REQUEST['call_data']['phone']),
				'body' => __("call_requests"). '. Product: ' . $product['product'] . '. Name:  ' . $_REQUEST['call_data']['name'] . '. Phone:  ' . $_REQUEST['call_data']['phone'] . '. Email:  ' . $_REQUEST['call_data']['email'],
				'event' => 'new_order'
			);
			$res = fn_send_amocrm_message($params);
		}
    }
}