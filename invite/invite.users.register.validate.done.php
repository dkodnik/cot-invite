<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.validate.done
[END_COT_EXT]
==================== */

$db_invite = isset($db_invite) ? $db_invite : $db_x.'invite';
$db->update($db_invite, array('inv_status' => '1'), "inv_userid=?", $row['user_id']);
$_SESSION['inv_complete'] = 1;

foreach (cot_getextplugins('invite.register.validate.done') as $pl)
{
	include $pl;
}