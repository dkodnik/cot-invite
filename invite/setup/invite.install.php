<?php defined('COT_CODE') or die('Wrong URL');

global $db_users;
require_once cot_incfile('invite', 'module');

if ($db->query("SHOW COLUMNS FROM `$db_users` WHERE `Field` = 'user_invite_expire'")->rowCount() == 0)
{
	$db->query("ALTER TABLE `".$db_users."` ADD COLUMN user_invite_expire VARCHAR(50)");
}

if ($db->query("SHOW COLUMNS FROM `$db_users` WHERE `Field` = 'user_invite_refcode'")->rowCount() == 0)
{
	$db->query("ALTER TABLE `".$db_users."` ADD COLUMN user_invite_refcode CHAR(32)");
	$db->query("ALTER TABLE `".$db_users."` ADD UNIQUE INDEX (`user_invite_refcode`)");
}