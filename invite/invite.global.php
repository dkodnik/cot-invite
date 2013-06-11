<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=global
[END_COT_EXT]
==================== */

if($usr['id'] > 0 && empty($usr['profile']['user_invite_refcode']))
{
	require_once cot_incfile('invite', 'module');
	$usr['profile']['user_invite_refcode'] = invite_generate_refcode();
	$db->update($db_users, array('user_invite_refcode' => $usr['profile']['user_invite_refcode']), "user_id=?", $usr['id']);
}

$invite_rfc = cot_import('rfc', 'G', 'ALP');

if(mb_strlen($invite_rfc) == 32)
{
	$_SESSION['inv_rfc_'.$sys['site_id']] = $invite_rfc;
	cot_setcookie('inv_rfc_'.$sys['site_id'], $invite_rfc, time()+2419200);
}

if(isset($_SESSION['inv_complete']))
{
	if(!empty($_SESSION['inv_rfc_'.$sys['site_id']]))
	{
		unset($_SESSION['inv_rfc_'.$sys['site_id']]);
	}
	if(!empty($_COOKIE['inv_rfc_'.$sys['site_id']]))
	{
		cot_setcookie('inv_rfc_'.$sys['site_id'], '', time()-9999999);
	}
	unset($_SESSION['inv_complete']);
}

if($usr['id'] > 0)
{
	$invite_user_ref_code = $usr['profile']['user_invite_refcode'];
	$invite_user_ref_url = $cfg['mainurl'].'/?rfc='.$invite_user_ref_code;
}
else
{
	$invite_user_ref_code = '';
	$invite_user_ref_url = '';
}
