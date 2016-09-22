<?php defined('COT_CODE') or die('Wrong URL');

$L['cfg_title'] = 'Invite email subject format';
$L['cfg_message'] = 'Invite email message format<br />Possible substitutions for message and subject:<br /> {user_email}, {user_name}, {referral_code}, 
{site_address}, {domain}, {host}, {date}, {site_title}';
$L['cfg_limit_invite'] = 'Limit users to send only this many emails ......';
$L['cfg_limit_expire'] = '...... in this amount of time (reset limit)';
$L['cfg_disable'] = 'Disable fields when limit is reached ?';
$L['cfg_invite_expire'] = 'Expire duplicate email prevention for an email address logged older than';
$L['cfg_max_user_pair'] = 'Maximum number of users to show connected to a user';

$L['invite_title'] = 'Send Invitations';
$L['invite_title_contacts'] = 'Select Contacts to Invite';
$L['invite_param_never'] = 'Never';
$L['invite_send_invite'] = 'Send invite';
$L['invite_limit_hit'] = 'You have sent as many invites as you were allocated for a given amount of time. Please try again in a little while.';
$L['invite_no_address'] = 'You must enter a email address in order to invite someone';
$L['invite_sent_success'] = 'You have successfully sent %d invites to: %s';
$L['invite_send_error'] = 'Failed to send %d invites because a error was encountered when sending: %s';
$L['invite_send_limit_hit'] = 'Failed to send %d invites because invite limit was reached: %s';
$L['invite_import_contacts'] = 'Import contacts';
$L['invite_valid_address_only'] = 'You must provide valid email addresses';
$L['invite_list_empty'] = 'None';
$L['invite_by_email'] = 'Invite by Email';

$L['invite_emails'] = 'Emails';
$L['invite_addresses_comma_separated'] = 'Email addresses comma separated';
$L['invite_optional_short_message'] = 'A optional short message to send to invitees';
$L['invite_message_short_title'] = 'Message';
$L['invite_from_contacts'] = 'Invite from contacts';
$L['invite_email_address_login'] = 'Email address for account log in';
$L['invite_password_login'] = 'Password for account log in';
$L['invite_provider'] = 'Provider';
$L['invite_email_provider'] = 'Email provider';
$L['invite_select_contact_invite'] = 'Select contacts to invite';
$L['invite_checkall'] = 'Check all';
$L['invite_no_contacts'] = 'You have no contacts';
$L['invite_goback'] = 'Go back';

$L['invite_contacts_empty_email'] = 'You must enter a email address';
$L['invite_contacts_empty_emails'] = 'You must select at least one contact to invite';
$L['invite_contacts_empty_password'] = 'You must enter a password';
$L['invite_contacts_empty_provider'] = 'You must select a provider';
$L['invite_contacts_internal_error'] = 'An unexpected has occurred';
$L['invite_contacts_login_failed'] = 'Login failed. Please check the email and password you have provided and try again later!';
$L['invite_contacts_cant_get_contacts'] = 'Can\'t get contacts!';

$L['invite_need_agree'] = 'Need agree invite';
$L['invite_need'] = 'Invite';