<?php defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('forms');
require_once cot_langfile('invite', 'module');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('invite', 'any');
cot_block($usr['auth_read']);
cot_blockguests(); 

$a = cot_import('a', 'G', 'ALP');
$invite_limit_hit = invite_shield_protect();

$out['subtitle'] = $L['invite_title'];

foreach (cot_getextplugins('invite.main.first') as $pl)
{
	include $pl;
}

if($cfg['invite']['invite_expire'] != '0')
{
	$db->query("DELETE FROM $db_invite_sent WHERE ins_date<".((int)$sys['now']-(int)$cfg['invite']['invite_expire'])." LIMIT ".(int)$cfg['invite']['max_user_pair']);
}

if($invite_limit_hit)
{
	cot_error('invite_limit_hit');
}

if($a == 'list' && !cot_error_found())
{
	cot_shield_protect();
	cot_check_xp();
	$remaillist = cot_import('remaillist', 'P', 'TXT');
	$remailmessage = cot_import('remailmessage', 'P', 'TXT');

	foreach (cot_getextplugins('invite.list.import') as $pl)
	{
		include $pl;
	}

	if(empty($remaillist))
	{
		cot_error('invite_no_address');
	}

	if(!empty($cot_captcha))
	{
		$rverify = cot_captcha_validate(cot_import('rverify', 'P', 'TXT'));
		if(!$rverify)
		{
			cot_error('captcha_verification_failed', 'rverify');
		}
	}

	foreach (cot_getextplugins('invite.list.validate') as $pl)
	{
		include $pl;
	}

	if(!cot_error_found())
	{
		$emails = explode(',', $remaillist);
		$sent = invite_send($emails, $remailmessage);
		if($sent == 0)
		{
			cot_error('invite_valid_address_only');
		}
		cot_shield_update(30, "Invite send");
		cot_redirect(cot_url('invite'));
	}
}

require_once $cfg['system_dir'] . '/header.php';
$t = new XTemplate(cot_tplfile('invite', 'module'));

$disabled = $invite_limit_hit && $cfg['invite']['disable'] ? array('disabled' => 'disabled') : array();

require_once INVITE_OPENINVITER_INCLUDE;
$io_inviter = new OpenInviter();
$oi_services = $io_inviter->getPlugins();

foreach (cot_getextplugins('invite.main.main') as $pl)
{
	include $pl;
}

if(!empty($cot_captcha))
{
	$invite_captcha_input = cot_inputbox('input', 'rverify', '', $disabled);
	$t->assign(array(
		'INVITE_LIST_CAPTCHA' => cot_captcha_generate(),
		'INVITE_LIST_CAPTCHA_INPUT' => $invite_captcha_input,
	));
}

list($current_count, $current_expire) = invite_shield_get_data($usr['profile'][$invite_expire_field_full]);

$t->assign(array(
	'INVITE_LIST_FORM' => cot_url('invite', 'a=list'),
	'INVITE_LIST_EMAILS' => cot_textarea('remaillist', $remaillist, 5, 50, $disabled),
	'INVITE_LIST_MESSAGE' => cot_textarea('remailmessage', $remailmessage, 7, 50, $disabled),
	'INVITE_LIST_SUBMIT' => cot_inputbox('submit', 'submit', $L['invite_send_invite'], $disabled),
	'INVITE_CONTACTS_FORM' => cot_url('invite', 'm=contacts&a=retrieve'),
	'INVITE_CONTACTS_EMAIL' => cot_inputbox('input', 'remail', '', $disabled + array('size' => 27)),
	'INVITE_CONTACTS_PASSWORD' => cot_inputbox('password', 'rpassword', '', $disabled + array('size' => 27)),
	'INVITE_CONTACTS_PROVIDERS' => invite_generate_provider_select($oi_services, $io_inviter->pluginTypes),
	'INVITE_CONTACTS_SUBMIT' => cot_inputbox('submit', 'submit', $L['invite_import_contacts'], $disabled),
));

foreach (cot_getextplugins('invite.main.tags') as $pl)
{
	include $pl;
}

cot_display_messages($t);
$t->parse('MAIN');
$t->out('MAIN');

require_once $cfg['system_dir'] . '/footer.php';
