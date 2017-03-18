<?php
define('AREA', 'A');
require ('init.php');

$schedule = db_get_array('select * from ?:amocrm_messages_schedule where send_time <= ?i', time());

$sms_data = array();
foreach($schedule as $key => $data)
{
	$phones = explode(',', $data['phones']);
	foreach($phones as $_key => $phone)
	{
		$sms_data []= array(
			'msisdn' => trim($phone),
			'body' => $data['body'],
			'reference' => 'successSendM' . (string)time().$key.$_key,
			'send_method' => $data['send_method']
		);
	}
}

$res = fn_send_amocrm_message($params);

echo 'done';