<?php
use Tygh\Registry;
define('AREA', 'A');
require ('init.php');

if (!$_REQUEST['cron_pass'] || $_REQUEST['cron_pass'] != Registry::get('addons.csc_bsg_world.cron_pass')) die('Access denied');

$schedule = db_get_hash_array('select * from ?:amocrm_messages_schedule where send_time <= ?i', 'schedule_id', time());

$sms_data = array();
foreach($schedule as $key => $data)
{
	$phones = explode(',', $data['phones']);
	$params = array(
		'phones' => $phones,
		'body' => $data['body'],
		'send_method' => $data['send_method'],
		'event' => 'scheduled_message',
		'button_url' => $data['button_url'],
		'button_label' => $data['button_label'],
		'image_url' => $data['image_url']
	);
	$res = fn_send_amocrm_message($params);
}

$res = fn_send_amocrm_message($params);

db_query('delete from ?:amocrm_messages_schedule where schedule_id in (?n)', array_keys($schedule));

echo 'done';
