<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if (!$_SESSION['bsg_ver_checked'])
{
	$bsg_ver = db_get_field('select version from ?:addons where addon = "csc_bsg_world"');
	$info = json_decode(fn_get_contents(BSG_VERSION_CHECK_URL));
	
	if (version_compare($bsg_ver, $info->version, '<')) 
	{
    	fn_set_notification("w", __("new_version"), __("bsg_new_version") . ' <a href="' . $info->download_link . '">' . __("here") . '</a>');
	}
	$_SESSION['bsg_ver_checked'] = true;
}