<?php
use Tygh\Registry;
use Tygh\Mailer;
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if ($mode == 'check_message')
	{
		if ($_REQUEST['phone_numbers']) $phones = explode(',', $_REQUEST['phone_numbers']);
		if ($phones == '') $phones = array();
		
		$phone_field_prefix = Registry::get('addons.csc_bsg_world.phone_field' == 'billing') ? 'b_' : 's_';
		$user_groups_phones = db_get_fields('select ' . $phone_field_prefix . 'phone from ?:user_profiles where user_id in (select user_id from ?:usergroup_links where usergroup_id in (?n) and status = "A")', $_REQUEST['user_groups']);

		$picker_phones = db_get_fields('select ' . $phone_field_prefix . 'phone from ?:user_profiles where user_id in (?n)', $_REQUEST['users']);

		$order_phones = db_get_fields('select ' . $phone_field_prefix . 'phone from ?:user_profiles where user_id in (select user_id from ?:orders where timestamp >= ?i and timestamp <= ?i)', strtotime($_REQUEST['order_date_range_from']), strtotime($_REQUEST['order_date_range_to']));
		$phones = array_unique(array_merge($phones, $user_groups_phones, $picker_phones, $order_phones));
		//$phones []= '+79095854034';

		$params = array(
			'send_time_type' => $_REQUEST['send_time_type'],
			'send_time' => strtotime($_REQUEST['send_date'] . ' ' . $_REQUEST['send_hour'] . ':' . $_REQUEST['send_min']),
			'phones' => $phones,
			'mode' => 'test',
			'body' => $_REQUEST['sms_text'],
			'send_method' => $_REQUEST['send_method'],
			'button_url' => $_REQUEST['button_url'],
			'button_label' => $_REQUEST['button_label'],
			'image_url' => $_REQUEST['image_url']
		);
		$res = fn_send_amocrm_message($params);
		$total_price = $res['total_price'] ? $res['total_price'] : $res['result']['price'];
		$_SESSION['message_params'] = $params;
		Registry::get('view')->assign('total_numbers', sizeof($phones));
		Registry::get('view')->assign('total_cost', $total_price);
		$msg = Registry::get('view')->fetch('addons/csc_bsg_world/components/accept_message_send.tpl');
		fn_set_notification('I', __("accept_message_send"), $msg);
		exit;
	}

	if ($mode == 'send_feedback')
	{
		$mailer = Tygh::$app['mailer'];
		$mailer->send(array(
            'to' => BSG_FEEDBACK_MAIL,
            'from' => 'default_company_users_department',
            'data' => array(
                'feedback' => $_REQUEST['feedback']
            ),
            'tpl' => 'addons/csc_bsg_world/feedback.tpl',
        ), 'A');

        fn_set_notification("N", __("successful"), __("text_email_sent"));

		return array(CONTROLLER_STATUS_OK, 'addons.update?addon=csc_bsg_world');
	}
}
else
{
	if ($mode == 'log')
	{
		$items_per_page = $_REQUEST['items_per_page'] ? $_REQUEST['items_per_page'] : 10;
		$page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
		$total_msg = db_get_field('select count(message_id) from ?:amocrm_messages_log');

		$limit = db_paginate($page, $items_per_page, $total_msg);

		$messages = db_get_array("select * from ?:amocrm_messages_log $limit");

		$search['total_items'] = $total_msg;
		$search['items_per_page'] = $items_per_page;
		$search['page'] = $page;

		Registry::get('view')->assign('messages', $messages);
		Registry::get('view')->assign('search', $search);
	}
	
	if ($mode == 'send_message')
	{
		if ($_SESSION['message_params'])
		{
			$params = $_SESSION['message_params'];
			if ($params['send_time_type'] == 'lazy')
			{
				unset($params['send_time_type']);
				unset($params['mode']);
				$params['phones'] = implode(',', $params['phones']);
				db_query('insert into ?:amocrm_messages_schedule ?e', $params);
			}
			else 
			{
				$params['mode'] = 'live';
				$params['event'] = 'instant_message';
				$res = fn_send_amocrm_message($params);
			}

			Registry::get('view')->assign('total_numbers', sizeof($_SESSION['message_params']['phones']));
			unset($_SESSION['message_params']);
		}
		fn_set_notification('N', __("success"), __("messages_successfully_sent"));
		fn_redirect('amocrm.send');
	}

	if ($mode == 'send')
	{
		$tabs = array(
			'sms' => array(
	            'title' => __('sms'),
	            'js' => true
	        ),
	        'viber' => array (
	            'title' => __('viber'),
	            'js' => true
	        ),
	        'omni' => array (
	            'title' => __('omni'),
	            'js' => true
	        )
		);

		Registry::set('navigation.tabs', $tabs);
	}
	if ($mode == 'refresh_balance')
	{
		$balance = fn_get_amocrm_balance();

		Registry::get('view')->assign('balance', $balance);
		Registry::get('view')->display('addons/csc_bsg_world/components/balance_info.tpl');

		exit;
	}
}