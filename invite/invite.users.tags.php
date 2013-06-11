<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.details.tags,users.profile.tags
Tags=users.details.tpl:{INVITE_USER_INVITED_LIST_DEFAULT},{INVITE_USER_INVITED_LIST},{INVITE_USER_INVITED_COUNT},{INVITE_USER_REFERRAL_CODE},{INVITE_USER_REFERRAL_URL};users.profile.tpl:{INVITE_USER_INVITED_LIST_DEFAULT},{INVITE_USER_INVITED_LIST},{INVITE_USER_INVITED_COUNT},{INVITE_USER_REFERRAL_CODE},{INVITE_USER_REFERRAL_URL}
[END_COT_EXT]
==================== */

require_once cot_langfile('invite', 'module');
require_once cot_incfile('invite', 'module');

$invite_user_pair = invite_user_pair_fetch($urr['user_id']);
$invite_user_pair_count = count($invite_user_pair);
$inv_iter = 0;

$invite_invited_default = '';
$invite_invited_list = '';
if($invite_user_pair_count > 0 && is_array($invite_user_pair))
{
	foreach($invite_user_pair as $inv_row => $inv_user)
	{
		$invite_invited_default .= cot_rc('invite_list_default', array(
			'name' => htmlspecialchars($inv_user['user_name']),
			'url' => cot_url('users', 'm=details&id='.$inv_user['user_id']),
			'id' => (int)$urr['user_id'],
		));

		$invite_invited_list .= cot_rc('invite_list', array(
			'name' => htmlspecialchars($inv_user['user_name']),
			'url' => cot_url('users', 'm=details&id='.$inv_user['user_id']),
			'id' => (int)$urr['user_id'],
		));

		if($invite_user_pair_count-1 != $inv_iter)
		{
			$invite_invited_default .= ", ";
		}

		$inv_iter++;
	}
}
else
{
	$invite_invited_default = $L['invite_list_empty'];
	$invite_invited_list = $L['invite_list_empty'];
}

if(empty($urr['user_invite_refcode']))
{
	$urr['user_invite_refcode'] = invite_generate_refcode();
	$db->update($db_users, array('user_invite_refcode' => $urr['user_invite_refcode']), "user_id=?", $urr['user_id']);
}

$t->assign(array(
	'INVITE_USER_REFERRAL_CODE' => htmlspecialchars($urr['user_invite_refcode']),
	'INVITE_USER_REFERRAL_URL' => htmlspecialchars($cfg['mainurl'].'/?rfc='.$urr['user_invite_refcode']),
	'INVITE_USER_INVITED_COUNT' => $invite_user_pair_count,
	'INVITE_USER_INVITED_LIST_DEFAULT' => $invite_invited_default,
	'INVITE_USER_INVITED_LIST' => $invite_invited_list,
));
