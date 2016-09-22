<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.tags
Tags=user.register.tpl:{INVITE_REGISTER_REFERRAL_CODE}
[END_COT_EXT]
==================== */

$invite_rfc_cookie = $_COOKIE['inv_rfc_'.$sys['site_id']];
$invite_rfc_session = $_SESSION['inv_rfc_'.$sys['site_id']];

if(!empty($invite_rfc_cookie))
{
	$invite_rfc = $invite_rfc_cookie; 
}
else if(!empty($invite_rfc_session))
{
	$invite_rfc = $invite_rfc_session;
}
else
{
	$invite_rfc = cot_import('rfc', 'G', 'ALP');
}

$t->assign(array(
	'INVITE_REGISTER_REFERRAL_CODE' => cot_inputbox('input', 'rinviterfc', $invite_rfc, array('size' => 27, 'maxlength' => 100)),
));