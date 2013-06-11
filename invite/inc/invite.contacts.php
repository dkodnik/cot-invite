<?php defined('COT_CODE') or die('Wrong URL');

cot_blockguests(); 
list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('invite', 'any');
cot_block($usr['auth_read']);

cot_shield_protect();

require_once cot_incfile('forms');
require_once cot_langfile('invite', 'module');

$a = cot_import('a', 'G', 'ALP');

foreach (cot_getextplugins('invite.contacts.import') as $pl)
{
	include $pl;
}

if(!in_array($a, array('retrieve', 'invite')))
{
	cot_die_message(404);
}

$invite_limit_hit = invite_shield_protect();
if($invite_limit_hit)
{
	cot_redirect(cot_url('invite'));
}

$out['subtitle'] = $L['invite_title_contacts'];

require_once INVITE_OPENINVITER_INCLUDE;
$io_inviter = new OpenInviter();
$is_post = $_SERVER['REQUEST_METHOD'] == 'POST' ? TRUE : FALSE;

foreach (cot_getextplugins('invite.contacts.first') as $pl)
{
	include $pl;
}

if($a == 'retrieve' && $is_post)
{
	cot_check_xp();
	$remail = cot_import('remail', 'P', 'TXT');
	$rpassword = cot_import('rpassword', 'P', 'TXT');
	$rprovider = cot_import('rprovider', 'P', 'TXT');

	foreach (cot_getextplugins('invite.contacts.retrieve.import') as $pl)
	{
		include $pl;
	}

	cot_check(empty($remail), 'invite_contacts_empty_email', 'remail');
	cot_check(empty($rpassword), 'invite_contacts_empty_password', 'rpassword');
	cot_check(empty($rprovider), 'invite_contacts_empty_provider', 'rprovider');

	foreach (cot_getextplugins('invite.contacts.retrieve.validate') as $pl)
	{
		include $pl;
	}

	if(cot_error_found())
	{
		cot_redirect(cot_url('invite'));
	}

	foreach (cot_getextplugins('invite.contacts.retrieve.main') as $pl)
	{
		include $pl;
	}

	cot_shield_update(30, "Invite login");

	$io_inviter->startPlugin($rprovider);
	cot_check(!$io_inviter->login($remail, $rpassword), 'invite_contacts_login_failed');
	$contacts = $io_inviter->getMyContacts();
}
elseif($a == 'invite' && $is_post)
{
	cot_check_xp();
	$remails_checked = cot_import('remails_checked', 'P', 'ARR');
	$remails = cot_import('remails', 'P', 'ARR');
	$rnames = cot_import('rnames', 'P', 'ARR');
	$contacts = invite_match_contacts($remails, $rnames);
	$rmessage = cot_import('rmessage', 'P', 'TXT');
	$rprovider = cot_import('provider', 'G', 'TXT');

	foreach (cot_getextplugins('invite.contacts.invite.import') as $pl)
	{
		include $pl;
	}

	$io_inviter->startPlugin($rprovider);
	cot_check(invite_empty_input_array_values($remails_checked), 'invite_contacts_empty_emails');

	if(!empty($cot_captcha))
	{
		$rverify = cot_captcha_validate(cot_import('rverify', 'P', 'TXT'));
		if(!$rverify)
		{
			cot_error('captcha_verification_failed', 'rverify');
		}
	}

	foreach (cot_getextplugins('invite.contacts.invite.validate') as $pl)
	{
		include $pl;
	}

	if(!cot_error_found())
	{
		foreach (cot_getextplugins('invite.contacts.invite.send') as $pl)
		{
			include $pl;
		}
		invite_send($remails_checked, $rmessage);
		cot_shield_update(30, "Invite send");
		cot_redirect(cot_url('invite'));
	}
}
else
{
	cot_die_message(404);
}

if(!cot_error_found())
{
	$internal = $io_inviter->getInternalError();

	foreach (cot_getextplugins('invite.contacts.internal') as $pl)
	{
		include $pl;
	}

	if($internal)
	{
		cot_error('invite_contacts_internal_error');
	}
}
// Check against the new possible errors as well
if(cot_error_found() && $a != 'invite')
{	
	cot_redirect(cot_url('invite'));
}

require_once $cfg['system_dir'] . '/header.php';
$t = new XTemplate(cot_tplfile('invite.contacts', 'module'));
$has_contacts = FALSE;

if(is_array($contacts) && !empty($contacts))
{
	$has_contacts = TRUE;
	$extp = cot_getextplugins('invite.contacts.loop');

	foreach($contacts as $email => $name)
	{
		$invite_email = htmlspecialchars($email);
		$invite_name = htmlspecialchars($name);
		$checked = is_array($remails_checked) && in_array($invite_email, $remails_checked) ? ' checked="checked" ' : '';
		$invite_checkbox_resource = cot_rc('invite_contacts_checkbox', array(
			'name' => 'remails_checked[]',
			'value' => $invite_email,
			'checked' => $checked,

		));
		$t->assign(array(
			// All checkboxes needed to carry over state
			'INVITE_CONTACTS_ROW_SELECT' => $invite_checkbox_resource.cot_inputbox('hidden', 'rnames[]', $name).cot_inputbox('hidden', 'remails[]', $invite_email),
			'INVITE_CONTACTS_ROW_NAME' => $invite_name,
			'INVITE_CONTACTS_ROW_EMAIL' => $invite_email,
		));
		$t->parse('MAIN.CONTACTS_ROW');

		foreach($extp as $pl)
		{
			include $pl;
		}
	}
}

if(!empty($cot_captcha))
{
	$t->assign(array(
		'INVITE_CONTACTS_CAPTCHA' => cot_captcha_generate(),
		'INVITE_CONTACTS_CAPTCHA_INPUT' => cot_inputbox('input', 'rverify'), 
	));
}

$t->assign(array(
	'INVITE_CONTACTS_FORM' => cot_url('invite', 'm=contacts&a=invite&provider='.$rprovider),
	'INVITE_CONTACTS_MESSAGE' => cot_textarea('rmessage', $rmessage, 7, 50),
	'INVITE_CONTACTS_SUBMIT' => cot_inputbox('submit', 'submit', $L['invite_send_invite']),
));

foreach (cot_getextplugins('invite.contacts.tags') as $pl)
{
	include $pl;
}

cot_display_messages($t);
$t->parse('MAIN');
$t->out('MAIN');

require_once $cfg['system_dir'] . '/footer.php';
