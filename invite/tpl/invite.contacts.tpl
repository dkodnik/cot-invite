<!-- BEGIN: MAIN -->

<div class="block">
	<h2>{PHP.L.invite_select_contact_invite}</h2>
	{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

<!-- IF {PHP.has_contacts} -->

	<form method="post" action="{INVITE_CONTACTS_FORM}">
		<div style="margin-bottom: 10px;">
			<input type="checkbox" onclick="$('.checkbox').attr('checked', this.checked)" /> {PHP.L.invite_checkall}
		</div>
		<hr />
		<ul style="margin-top: 10px; overflow: hidden;">
			<!-- BEGIN: CONTACTS_ROW -->
			<li style="float: left; margin-right: 20px;">
				{INVITE_CONTACTS_ROW_SELECT} {INVITE_CONTACTS_ROW_NAME} ({INVITE_CONTACTS_ROW_EMAIL})
			</li>
			<!-- END: CONTACTS_ROW -->
		</ul>

	</div>
	<div class="block">
		<div>
			<strong>{PHP.L.invite_message_short_title}</strong>
			<p>{PHP.L.invite_optional_short_message}</p>
			{INVITE_CONTACTS_MESSAGE}
		</div>
		<div style="margin-top: 10px;">
			<p>{INVITE_CONTACTS_CAPTCHA} <span style="font-weight: bold; color: red;">*</span></p>
			<p>{INVITE_CONTACTS_CAPTCHA_INPUT}</p>
		</div>
		<div style="margin-top: 15px;">
			{INVITE_CONTACTS_SUBMIT}
		</div>
	</div>
	</form>

<!-- ELSE -->
	<p style="margin-bottom: 15px;">{PHP.L.invite_no_contacts}</p>
	<a href="{PHP|cot_url('invite')}"> >> {PHP.L.invite_goback}</a>
<!-- ENDIF -->
</div>

<!-- END: MAIN -->