<?php
use Tygh\Registry;
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if ($mode == 'check_message')
	{
		if ($_REQUEST['phone_numbers']) $phones = explode(',', $_REQUEST['phone_numbers']);
		
		$phone_field_prefix = Registry::get('addons.csc_amocrm.phone_field' == 'billing') ? 'b_' : 's_';
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
			'send_method' => $_REQUEST['send_method']
		);
		$res = fn_send_amocrm_message($params);

		$_SESSION['message_params'] = $params;

		Registry::get('view')->assign('total_numbers', sizeof($phones));
		Registry::get('view')->assign('total_cost', $res['total_price']);
		$msg = Registry::get('view')->fetch('addons/csc_amocrm/components/accept_message_send.tpl');
		fn_set_notification('I', __("accept_message_send"), $msg);
		exit;
	}

	if ($mode == 'send_feedback')
	{
		fn_set_notification('N', __("success"), __("feedback_has_been_sent"));

		return array(CONTROLLER_STATUS_OK, 'addons.update?addon=csc_amocrm');
	}
}
else
{
	if ($mode == 'messages_report')
	{

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

			$params['mode'] = 'live';
			$res = fn_send_amocrm_message($params);

			Registry::get('view')->assign('total_numbers', sizeof($_SESSION['message_params']['phones']));
			unset($_SESSION['message_params']);
		}
		else fn_redirect('amocrm.send');
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
		Registry::get('view')->display('addons/csc_amocrm/components/balance_info.tpl');

		exit;
	}
}