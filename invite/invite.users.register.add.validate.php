<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.add.validate
[END_COT_EXT]
==================== */

if(!cot_error_found())
{
	require_once cot_incfile('invite', 'module');
	$ruser['user_invite_refcode'] = invite_generate_refcode();
}