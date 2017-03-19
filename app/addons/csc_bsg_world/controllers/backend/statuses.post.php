<?php
use Tygh\Registry;
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

}
else
{
	if ($mode == 'update')
	{
		$message = db_get_field('select amocrm_msg from ?:statuses s inner join ?:status_descriptions d on s.status_id = d.status_id where status = ?s and lang_code = ?s and type="O"', $_REQUEST['status'], DESCR_SL);
		//fn_print_die($message);

		Registry::get('view')->assign('amocrm_msg', $message);
	}
}