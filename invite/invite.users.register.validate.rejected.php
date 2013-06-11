<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.validate.rejected
[END_COT_EXT]
==================== */

$db_invite = isset($db_invite) ? $db_invite : $db_x.'invite';
$db->delete($db_invite, "inv_userid=?", $row['user_id']);