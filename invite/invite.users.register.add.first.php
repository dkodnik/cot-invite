<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=users.register.add.first
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL.');

require_once cot_langfile('invite', 'module');

$rinviterfc = cot_import('rinviterfc', 'P', 'ALP');

// проверка только для групп: фрилансеров(4)
$ruserusergroup = cot_import('ruserusergroup', 'P', 'INT');

// TODO: настройка запрашивать как обязательный параметр реф.код
if($ruserusergroup == 4)
{
	if ($rinviterfc=="")
	{
		cot_error($L['invite_need_agree'], 'rinviterfc');
	}
}
?>