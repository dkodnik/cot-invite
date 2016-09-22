<?php defined('COT_CODE') or die('Wrong URL');

$L['cfg_title'] = 'Формат темы для приглашения по электронной почте';
$L['cfg_message'] = 'Формат сообщения для приглашения по электронной почте<br />Возможные замены для сообщения и темы:<br /> {user_email}, {user_name}, {referral_code}, 
{site_address}, {domain}, {host}, {date}, {site_title}';
$L['cfg_limit_invite'] = 'Ограничить количество писем отправляемых пользователем ......';
$L['cfg_limit_expire'] = '...... в это количество времени (сброс ограничения)';
$L['cfg_disable'] = 'Отключение полей при достижении предела?';
$L['cfg_invite_expire'] = 'Срок действия повторяющихся сообщений-предупреждений для адреса электронной почты регистрируемых раньше';
$L['cfg_max_user_pair'] = 'Максимальное количество пользователей, чтобы показать, подключенных к пользователю';

$L['invite_title'] = 'Отправить приглашения';
$L['invite_title_contacts'] = 'Выберите контакт для приглашения';
$L['invite_param_never'] = 'Никогда';
$L['invite_send_invite'] = 'Отправить приглашение';
$L['invite_limit_hit'] = 'Вы уже послали столько приглашений, сколько было выделено для данного периода времени. Пожалуйста, повторите попытку через некоторое время.';
$L['invite_no_address'] = 'Вы должны ввести адрес электронной почты, чтобы пригласить кого-то';
$L['invite_sent_success'] = 'Вы успешно отправили %d приглашений для: %s';
$L['invite_send_error'] = 'Не удалось отправить %d приглашений, потому что ошибка при отправке: %s';
$L['invite_send_limit_hit'] = 'Не удалось отправить %d приглашений, потому что достигнут предел по количеству приглашений: %s';
$L['invite_import_contacts'] = 'Импорт контактов';
$L['invite_valid_address_only'] = 'Вы должны предоставить действительные адреса электронной почты';
$L['invite_list_empty'] = 'Никто';
$L['invite_by_email'] = 'Пригласить по электронной почте';

$L['invite_emails'] = 'Письма';
$L['invite_addresses_comma_separated'] = 'Адреса электронной почты разделенные запятой';
$L['invite_optional_short_message'] = 'Опционально короткое сообщение, чтобы отправить его приглашенным';
$L['invite_message_short_title'] = 'Сообщение';
$L['invite_from_contacts'] = 'Пригласить из списка контактов';
$L['invite_email_address_login'] = 'Адрес электронной почты для входа в аккаунт';
$L['invite_password_login'] = 'Пароль для входа в аккаунт.';
$L['invite_provider'] = 'Провайдер';
$L['invite_email_provider'] = 'Провайдер электронной почты';
$L['invite_select_contact_invite'] = 'Выберите контакты для приглашения';
$L['invite_checkall'] = 'Проверить все';
$L['invite_no_contacts'] = 'У вас нет контактов';
$L['invite_goback'] = 'Вернуться назад';

$L['invite_contacts_empty_email'] = 'Вы должны ввести адрес электронной почты';
$L['invite_contacts_empty_emails'] = 'Вы должны выбрать, по крайней мере, один контакт для приглашения';
$L['invite_contacts_empty_password'] = 'Вы должны ввести пароль';
$L['invite_contacts_empty_provider'] = 'Вы должны выбрать поставщика';
$L['invite_contacts_internal_error'] = 'Произошла неожиданная ошибка';
$L['invite_contacts_login_failed'] = 'Ошибка входа. Пожалуйста, проверьте электронную почту и пароль, которую вы предоставили и повторите попытку позже!';
$L['invite_contacts_cant_get_contacts'] = 'Невозможно получить контакты!';

$L['invite_send_subject'] = 'Приглашение от {user_email}';
$L['invite_send_message'] = '{user_email} пригласил в {site_title}. Нажмите на ссылку ниже, чтобы присоединиться.';

$L['invite_need_agree'] = 'Не введен код-приглашение';
$L['invite_need'] = 'Код приглашения';