<?php
use Tygh\Registry;
if (!defined('BOOTSTRAP')) { die('Access denied'); }

require_once('app/addons/csc_amocrm/lib/BSG.php');

function fn_get_amocrm_balance()
{
	$bsg = new BSG();
	$balance = $bsg->getSMSClient()->getBalance();
	
	return $balance['limit'] ? $balance['limit'] : 0;
}

function fn_csc_amocrm_account_info()
{
	$balance = fn_get_amocrm_balance();
	return '
	<div>
		<a href="https://www.amocrm.ru/" target="_blank">' . __("register") . '</a>
		<a href="https://www.amocrm.ru/" target="_blank">' . __("account") . '</a>
		<a href="https://www.amocrm.ru/" target="_blank">' . __("forgot_password_question") . '</a>
		<div id="balance_info">
			'. __("balance") . ': ' . $balance . ' EUR.
		<!--balance_info--></div>
		<a class="btn" onclick="Tygh.$.ceAjax(\'request\', \'' . fn_url('amocrm.refresh_balance') . '\', {result_ids: \'balance_info\'});">' . __("update") . '</a>
	</div>
	';
}

function fn_csc_amocrm_feedback_info()
{
	$form = Registry::get('view')->fetch('addons/csc_amocrm/components/feedback_form.tpl');

	return $form;
}

function fn_csc_amocrm_help_info()
{
	return __("amocrm_help");
}

function fn_settings_variants_addons_csc_amocrm_shippings_condition()
{
	$shippings = db_get_array('select s.shipping_id, shipping from ?:shippings s inner join ?:shipping_descriptions d on s.shipping_id = d.shipping_id where lang_code = ?s', DESCR_SL);

	$result = array();
	foreach($shippings as $shipping)
	{
		$result[$shipping['shipping_id']] = $shipping['shipping'];
	}
	//fn_print_die($result, $shippings);

    return $result;
}

function fn_settings_variants_addons_csc_amocrm_order_status_condition()
{
	$statuses = db_get_array('select s.status, d.description from ?:statuses s inner join ?:status_descriptions d on s.status_id = d.status_id where s.type = "O" and d.lang_code = ?s', DESCR_SL);

	$result = array();
	foreach($statuses as $status)
	{
		$result[$status['status']] = $status['description'];
	}
	//fn_print_die($result, $statuses);

    return $result;
}

function fn_settings_variants_addons_csc_amocrm_customer_shippings_condition()
{
	$shippings = db_get_array('select s.shipping_id, shipping from ?:shippings s inner join ?:shipping_descriptions d on s.shipping_id = d.shipping_id where lang_code = ?s', DESCR_SL);

	$result = array();
	foreach($shippings as $shipping)
	{
		$result[$shipping['shipping_id']] = $shipping['shipping'];
	}
	//fn_print_die($result, $shippings);

    return $result;
}

function fn_settings_variants_addons_csc_amocrm_customer_order_status_condition()
{
	$statuses = db_get_array('select s.status, d.description from ?:statuses s inner join ?:status_descriptions d on s.status_id = d.status_id where s.type = "O" and d.lang_code = ?s', DESCR_SL);

	$result = array();
	foreach($statuses as $status)
	{
		$result[$status['status']] = $status['description'];
	}
	//fn_print_die($result, $statuses);

    return $result;
}

function fn_send_amocrm_message($params)
{
	$bsg = new BSG(Registry::get('settings.Company.company_name'));
	$addon = Registry::get('addons.csc_amocrm');
	if ($params['recipient'] == 'admin') $phones = explode(',', $addon['admin_phones']);
	else $phones = $params['recipient'];

	if ($addon['send_method'] == 'sms')
	{
		$smsClient = $bsg->getSmsClient();
		$sms_data = array();
		foreach($phones as $key => $phone)
		{
			$sms_data []= array(
				'msisdn' => trim($phone),
				'body' => $params['body'],
				'reference' => 'successSendM' . (string)time().$key
			);
		}
		$res = $smsClient->sendSmsMulti($sms_data);
		//fn_print_die($sms_data, $res);
	}

	return $res;
}

function fn_csc_amocrm_update_profile($action, $user_data, $current_user_data)
{
	if ($action == 'add' && $user_data['user_type'] == "C" && Registry::get('addons.csc_amocrm.new_user_registered') == "Y")
	{
		//сообщенька админу о новом пользователе
		$params = array(
			'recipient' => 'admin',
			'body' => __("new_user_registered"). '. ' . 'UserID: ' . $user_data['user_id'],
			'user_data' => $user_data
		);
		$res = fn_send_amocrm_message($params);
	}
}

function fn_csc_amocrm_update_product_amount($new_amount, $product_id, $cart_id, $tracking)
{
	$product_data = fn_get_product_data($product_id, $_SESSION['auth']);
	if ($new_amount < 0 && Registry::get('addons.csc_amocrm.stock_less_zero') == "Y")
	{
		//сообщенька админу об остатке меньше нуля
		$params = array(
			'recipient' => 'admin',
			'body' => __("stock_less_zero"). '. ' . $product_data['product'],
			'user_data' => $user_data
		);
		$res = fn_send_amocrm_message($params);
	}
}

function fn_csc_amocrm_place_order($order_id, $action, $order_status, $cart, $auth)
{
	$min_order_total = Registry::get('addons.csc_amocrm.order_total_more_than');
	$order_data = fn_get_order_info($order_id);

	$available_shippings = Registry::get('addons.csc_amocrm.shippings_condition');
	$available_statuses = Registry::get('addons.csc_amocrm.order_status_condition');
	if ($order_data['total'] > $min_order_total && (empty($available_shippings) || isset($available_shippings['N']) || $available_shippings[$order_data['shipping_ids']] == "Y") && (empty($available_statuses) || isset($available_statuses['N']) || $available_statuses[$_REQUEST['order_status']] == "Y"))
	{
		if ($order_id && Registry::get('addons.csc_amocrm.stock_less_zero') == "Y" && Registry::get('runtime.mode') == 'place_order' && $action != 'save')
		{
			fn_set_notification('N', '', 'СМС админа новый заказ');
		}

		if ($action == "save" && Registry::get('addons.csc_amocrm.order_updated') == 'Y')
		{
			fn_set_notification('N', '', 'СМС админа заказ обновлен');
		}
	}
}

function fn_csc_amocrm_change_order_status($status_to, $status_from, $order_info, $force_notification, $order_statuses, $place_order)
{
	if ($place_order == false)
	{
		$message = db_get_field('select d.amocrm_msg from ?:statuses s inner join ?:status_descriptions d on s.status_id = d.status_id where status = ?s and lang_code = ?s', $status_to, "ru");
		$content = str_replace(array('%ORDER_ID%', '%AMOUNT%', '%NAME%', '%LAST_NAME%', '%USER_EMAIL%', '%COUNTRY%', '%ADDRESS%', '%CITY%', '%STATE%'), array($order_info['order_id'], $order_info['total'], $order_info['firstname'], $order_info['lastname'], $order_info['email'], $order_info['s_country_descr'], $order_info['s_address'], $order_info['s_city'], $order_info['s_state_descr']), $message);
		if (Registry::get('addons.csc_amocrm.order_updated') == 'Y')
		{
			fn_set_notification('N', '', "СМС админу о смене статуса\n" . $content);
		}

		if (Registry::get('addons.csc_amocrm.customer_order_updated') == 'Y')
		{
			fn_set_notification('N', '', "СМС покупателю о смене статуса\n" . $content);
		}
	}
}