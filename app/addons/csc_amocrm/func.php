<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_csc_amocrm_account_info()
{
	return '
	<div>
		<a href="https://www.amocrm.ru/">' . __("register") . '</a>
		<a href="https://www.amocrm.ru/">' . __("account") . '</a>
		<a href="https://www.amocrm.ru/">' . __("forgot_password_question") . '</a>
		<div id="balance_info">
			'. __("balance") . ': 100р.
		<!--balance_info--></div>
		<a class="btn" onclick="Tygh.$.ceAjax(\'request\', \'' . fn_url('amocrm.refresh_balance') . '\', {result_ids: \'balance_info\'});">' . __("update") . '</a>
	</div>
	';
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