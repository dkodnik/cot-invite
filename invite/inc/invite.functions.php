<?php defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('invite', 'module', 'resources');

$db_invite = (isset($db_invite)) ? $db_invite : $db_x.'invite';
$db_invite_sent = (isset($db_invite_sent)) ? $db_invite_sent : $db_x.'invite_sent';
$invite_expire_field = 'invite_expire';
$invite_expire_field_full = 'user_'.$invite_expire_field;
$invite_refcode_field = 'invite_refcode';
$invite_refcode_field_full = 'user_'.$invite_refcode_field;

define('INVITE_OPENINVITER_INCLUDE', $cfg['modules_dir'].'/invite/lib/OpenInviter/openinviter.php');

$invite_available_params = array(
	'{user_name}' => $usr['name'],
	'{user_email}' => $usr['profile']['user_email'],
	'{referral_code}' => $usr['profile'][$invite_refcode_field_full],
	'{site_title}' => $cfg['maintitle'],
	'{site_address}' => $cfg['mainurl'],
	'{domain}' => $sys['domain'],
	'{host}' => $sys['host'],
	'{date}' => $sys['day'],
	'{port}' => $sys['port'],
);

/**
* Match a user with the users he has invited 
*
* @param int $user_id Get the users this user ID has invited
*/

function invite_user_pair_fetch($user_id)
{
	global $db, $db_invite, $db_users, $cfg;
	if((int)$user_id == 0 || (int)$cfg['invite']['max_user_pair'] == 0)
	{
		return array();
	}
	$rows = $db->query("SELECT u.user_id,u.user_name,i.* FROM $db_invite AS i LEFT JOIN ".
		"$db_users AS u ON i.inv_userid=u.user_id WHERE i.inv_byuser=? AND i.inv_status=1 ".
		" ORDER BY i.inv_date DESC LIMIT ".(int)$cfg['invite']['max_user_pair'], $user_id);
	if($rows->rowCount() > 0)
	{
		return $rows->fetchAll();
	}
	else
	{
		return array();
	}
}

/**
* A wrapper for cot_mail() that prepares the message & subject
*
* @param string $to Email address to send to
* @param string $to_message The message to prepare and send
* @return bool
*/
function invite_mail($to, $to_message = '')
{
	global $cfg, $usr, $invite_available_params, $L;

	$title = isset($L['invite_send_subject']) ? $L['invite_send_subject'] : $cfg['invite']['title'];
	$message = isset($L['invite_send_message']) ? $L['invite_send_message'] : $cfg['invite']['message'];
	$title = invite_substitute($title);
	$message = invite_substitute($message);
	$message .= cot_rc('invite_email_register_link', array(
		'register_link' => $cfg['mainurl'].'/'.cot_url('users', 'm=register&rfc='.$invite_available_params['{referral_code}'], '', true),
	));
	if(!empty($to_message))
	{
		$to_message = preg_replace("/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/iu", '', $to_message);
		$message .= cot_rc('invite_message_from', array(
			'user_email' => $usr['profile']['user_email'],
			'user_name' => $usr['name'],
			'message' => trim($to_message),
		));
	}
	return cot_mail($to, $title, $message);
}

function invite_shield_get_data($input)
{
	$data = explode('::', $input);
	$count = !empty($data[0]) ? $data[0] : 0;
	$expire = isset($data[1]) ? $data[1] : 0;
	return array($count, $expire);
}

function invite_shield_protect()
{
	global $cfg, $sys, $usr, $db, $db_users, $invite_expire_field_full;
	list($count, $expire) = invite_shield_get_data($usr['profile'][$invite_expire_field_full]);
	if(!empty($expire) && $sys['now'] > $expire+$cfg['invite']['limit_expire'])
	{
		$db->update($db_users, array($invite_expire_field_full => '0'), 'user_id='.$usr['id']);
		$usr['profile'][$invite_expire_field_full] = 0;
		return FALSE;
	}
	if(empty($expire) && $count >= $cfg['invite']['limit_invite'])
	{ 
		$set_value = $count.'::'.$sys['now'];
		$db->update($db_users, array($invite_expire_field_full => $set_value), 'user_id='.$usr['id']);
		$usr['profile'][$invite_expire_field_full] = $set_value;
		return TRUE;
	}

	if(!empty($expire) && $count >= $cfg['invite']['limit_invite'])
	{
		return TRUE;
	}

	return FALSE;
}

function invite_send($emails, $message = '')
{
	global $cfg, $invite_ref_code, $usr, $L, $invite_expire_field_full, $sys, $db_invite_sent, $db;

	list($current_count, $current_expire) = invite_shield_get_data($usr['profile'][$invite_expire_field_full]);
	$emails_count = count($emails);
	$address_index = array();
	$address_index['failed_send'] = array();
	$address_index['failed_cap'] = array();
	$address_index['sent'] = array();
	$address_index['all'] = array();
	$sent_count = 0;
	$failed_count = 0;
	foreach($emails as $email)
	{
		$email = trim($email);
		if(!isset($address_index['all'][$email]) && cot_check_email($email) && 
			$sent_count+$current_count < $cfg['invite']['limit_invite'])
		{
			$address_index['sent'][$email] = htmlspecialchars($email);
			$address_index['all'][$email] = $address_index['sent'][$email];

			// Check to see if the email exists, but don't let the user know the status, just ghost send if anything
			$email_exists = $db->query("SELECT COUNT(*) FROM $db_invite_sent WHERE ins_email=?", $email)->fetchColumn();
			if(!$email_exists)
			{
				if(invite_mail($email, $message))
				{
					$db->insert($db_invite_sent, array(
						'ins_email' => $email,
						'ins_byuser' => $usr['id'],
						'ins_date' => $sys['now'],
					));
				}
				else
				{
					$address_index['failed_send'][$email] = $address_index['sent'][$email];
					unset($address_index['sent'][$email]);
				}
			}
			$sent_count++;
		}
		elseif(!isset($address_index['all'][$email]) && cot_check_email($email) && 
			$sent_count+$current_count >= $cfg['invite']['limit_invite'])
		{
			$address_index['failed_cap'][$email] = htmlspecialchars($email);
			$address_index['all'][$email] = $address_index['failed_cap'][$email];
		}
	}

	$failed_cap_count = count($address_index['failed_cap']);
	$failed_send_count = count($address_index['failed_send']);
	if($failed_send_count > 0)
	{
		cot_error(sprintf($L['invite_send_error'], $failed_send_count, implode(',', array_values($address_index['failed_send']))));
	}
	if($failed_cap_count > 0)
	{
		cot_log(htmlspecialchars($usr['name']).' hit invite limit', 'sec');
		cot_message(sprintf($L['invite_send_limit_hit'], $failed_cap_count, implode(', ', array_values($address_index['failed_cap']))));
	}
	if(count($address_index['sent']) > 0)
	{
		cot_message(sprintf($L['invite_sent_success'], $sent_count, implode(', ',array_values($address_index['sent']))));
	}

	invite_shield_update($sent_count);

	return (int)$sent_count;
}

function invite_shield_update($add_count)
{
	global $cfg, $usr, $db, $sys, $db_users, $invite_expire_field_full;
	list($count, $expire) = invite_shield_get_data($usr['profile'][$invite_expire_field_full]);
	if((int)$add_count>0)
	{
		$count = $count+$add_count;
		$expire = empty($expire) ? $sys['now'] : $expire;
		$db->update($db_users, array($invite_expire_field_full => $count.'::'.$expire), 'user_id='.$usr['id']);
	}
	return $count;
}

function invite_substitute($text)
{
	global $invite_available_params;
	return str_replace(array_keys($invite_available_params), array_values($invite_available_params), $text);
}

function invite_match_contacts($emails, $names)
{
	$contacts = array();
	if(is_array($emails))
	{
		$count = count($emails);
		for($i = 0; $i < $count; $i++)
		{
			$contacts[$emails[$i]] = $names[$i];
		}
	}
	return $contacts;
}

function invite_empty_input_array_values($array)
{

	$empty = TRUE;
	if(is_array($array) && !empty($array))
	{
		$values = array_values($array);
		foreach($values as $value)
		{
			if(!empty($value))
			{
				$empty = FALSE;
				break;
			}
		}
	}
	return $empty;
}

function invite_generate_refcode()
{
	global $db_users, $db;
	$refcode = md5(microtime().cot_randomstring(20));
	$exists = (bool)$db->query("SELECT COUNT(*) FROM $db_users WHERE user_invite_refcode=?", $refcode)->fetchColumn();
	if(!$exists)
	{
		return $refcode;
	}
	else
	{
		invite_generate_refcode();
	}
}

function invite_generate_provider_select($services, $types)
{
	$provider_select = cot_rc('invite_provider_select_start', array(
		'disabled' => ($disabled && $cfg['invite']['disable']) ? ' disabled="disabled" ' : '',
		'name' => 'rprovider',
	));
	foreach($services as $type => $providers)
	{
		$provider_select .= cot_rc('invite_provider_optgroup_start', array(
			'label' => $types[$type],
		));
		foreach($providers as $provider => $details)
		{
			$provider_select .= cot_rc('invite_provider_option', array(
				'value' => $provider,
				'selected' => ($_POST['rprovider']==$provider) ? ' selected':'',
				'title' => htmlspecialchars($details['name']),
			));
		}
		$provider_select .= cot_rc('invite_provider_optgroup_end', array());
	}
	$provider_select .= cot_rc('invite_provider_select_end', array());
	return $provider_select;	
}

function invite_get_invite_expire()
{
	global $L;
	$expire = array(
		'0' => $L['invite_param_never'],
		'86400' => '1 '.$L['Days'],
		'172800' => '2 '.$L['Days'],
		'604800' => '1 '.$L['Week'],
		'1209600' => '2 '.$L['Weeks'],
		'2419200' => '1 '.$L['Months'],
		'4838400' => '2 '.$L['Months'],
		'9676800' => '4 '.$L['Months'],
		'19353600' => '8 '.$L['Months'],
		'31449600' => '12 '.$L['Months'],
	);
	$L['cfg_invite_expire_params'] = array_values($expire);
	return array_keys($expire);
}

function invite_get_expirations()
{
	global $L;
	$expire = array(
		// '0' => $L['invite_param_never'],
		'300' => '5 '.$L['Minutes'],
		'600' => '10 '.$L['Minutes'],
		'900' => '15 '.$L['Minutes'],
		'1800' => '30 '.$L['Minutes'],
		'2700' => '45 '.$L['Minutes'],
		'3600' => '1 '.$L['Hours'],		
		'7200' => '2 '.$L['Hours'],
		'10800' => '3 '.$L['Hours'],
	);
	$L['cfg_limit_expire_params'] = array_values($expire);
	return array_keys($expire);
}