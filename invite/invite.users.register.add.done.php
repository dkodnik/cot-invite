<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.add.done
[END_COT_EXT]
==================== */

$invite_rfc_session = $_SESSION['inv_rfc_'.$sys['site_id']];
$invite_rfc_cookie = cot_import('inv_rfc_'.$sys['site_id'], 'C', 'ALP');
$invite_rfc_input = cot_import('rinviterfc', 'P', 'ALP');

if(!empty($invite_rfc_input))
{
	$invite_rfc = $invite_rfc_input;
}
elseif(!empty($invite_rfc_cookie))
{
	$invite_rfc = $invite_rfc_cookie;
}
elseif(!empty($invite_rfc_session))
{
	$invite_rfc = $invite_rfc_session;
}
else
{
	$invite_rfc = cot_import('rfc', 'G', 'ALP');
}

foreach (cot_getextplugins('invite.register.add.import') as $pl)
{
	include $pl;
}

if(mb_strlen($invite_rfc) == 32)
{
	$db_invite = (isset($db_invite)) ? $db_invite : $db_x.'invite';
	$matched = $db->query("SELECT user_id FROM $db_users WHERE user_invite_refcode=? LIMIT 1", $invite_rfc)->fetchColumn();
	if($matched)
	{
		$invite_status = ($cfg['users']['regnoactivation']) ? 1 : 0;
		$db->insert($db_invite, 
			array(
				'inv_byuser' => $matched,
				'inv_userid' => $userid,
				'inv_date' => $sys['now'],
				'inv_status' => $invite_status,
		));
		$_SESSION['inv_complete'] = 1;
		
		if($invite_status == 1)
		{
			foreach (cot_getextplugins('invite.register.add.done') as $pl)
			{
				include $pl;
			}
		}

		unset($_SESSION['inv_rfc_'.$sys['site_id']]);
	}
}