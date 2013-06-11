<!-- BEGIN: MAIN -->

<div class="block">
{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
	<div style="float: left; width: 48%; margin-right: 30px;">
		<form method="post" action="{INVITE_LIST_FORM}">
		<h2>{PHP.L.invite_by_email}</h2>
		<div>
			<strong>{PHP.L.invite_emails} <span style="color: red;">*</span></strong>
			<p>{PHP.L.invite_addresses_comma_separated}</p>
			{INVITE_LIST_EMAILS}
		</div>
		<div style="margin-top: 15px;">
			<strong>{PHP.L.invite_message_short_title}</strong>
			<p>{PHP.L.invite_optional_short_message}</p>
			{INVITE_LIST_MESSAGE}
		</div>
		<div style="margin-top: 10px;">
			<p>{INVITE_LIST_CAPTCHA} <span style="font-weight: bold; color: red;">*</span></p>
			<p>{INVITE_LIST_CAPTCHA_INPUT}</p>
		</div>
		<div style="margin-top: 10px;">
			{INVITE_LIST_SUBMIT}
		</div>
		</form>
	</div>
	<div style="float: left; width: 48%;">
		<form method="post" action="{INVITE_CONTACTS_FORM}">
		<h2>{PHP.L.invite_from_contacts}</h2>
		<div>
			<strong>{PHP.L.Email} <span style="color: red;">*</span></strong>
			<p>{PHP.L.invite_email_address_login}</p>
			{INVITE_CONTACTS_EMAIL}
		</div>
		<div>
			<strong>{PHP.L.Password} <span style="color: red;">*</span></strong>
			<p>{PHP.L.invite_password_login}</p>
			{INVITE_CONTACTS_PASSWORD}
		</div>
		<div>
			<strong>{PHP.L.invite_provider} <span style="color: red;">*</span></strong>
			<p>{PHP.L.invite_email_provider}</p>
			{INVITE_CONTACTS_PROVIDERS}
		</div>
		<div style="margin-top: 10px;">
			{INVITE_CONTACTS_SUBMIT}
		</div>
		</form>
	</div>
</div>

<!-- END: MAIN -->