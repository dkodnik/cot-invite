<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=module
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL.');

$env['location'] = 'invite';

require_once cot_incfile('invite', 'module');

if (!in_array($m, array('contacts')))
{
	$m = 'main';
}

include cot_incfile('invite', 'module', $m);