<?php defined('COT_CODE') or die('Wrong URL');

$R['invite_email_register_link'] = "\n\n".'{$register_link}';
$R['invite_message_from'] = "\n\nMessage from ".'{$user_email}:'."\n\n".'{$message}';
$R['invite_list_default'] = '<a href="{$url}">{$name}</a>';
$R['invite_list'] = '<a href="{$url}">{$name}</a> ';
$R['invite_provider_select_start'] = '<select $disabled name="{$name}"><option value=""></option>';
$R['invite_provider_select_end'] = '</select>';
$R['invite_provider_optgroup_start'] = '<optgroup label="{$label}"">';
$R['invite_provider_optgroup_end'] = '</optgroup>';
$R['invite_provider_option'] = '<option value="{$value}" {$selected}>{$title}</option>';
$R['invite_contacts_checkbox'] = '<input {$checked} type="checkbox" class="checkbox" name="{$name}" value="{$value}" />';