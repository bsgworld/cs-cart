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
        if (version_compare(PRODUCT_VERSION, '4.3.6', '<'))
        {
            $on = 's.status = d.status';
            $condition = db_quote('and d.type = "O"');
        }
        else
        {
            $on = 's.status_id = d.status_id';
        }
		$message = db_get_field("select amocrm_msg from ?:statuses s inner join ?:status_descriptions d on $on where s.status = ?s and lang_code = ?s and s.type='O' $condition", $_REQUEST['status'], DESCR_SL);
		//fn_print_die($message);

		Registry::get('view')->assign('amocrm_msg', $message);
	}
}