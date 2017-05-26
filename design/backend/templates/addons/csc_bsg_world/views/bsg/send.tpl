{capture name="mainbox"}
	{capture name="tabsbox"}
		<div id="content_sms">
			<form action="{""|fn_url}" method="POST" class="form-horizontal cm-ajax">
				<input type="hidden" name="send_method" value="sms">
				<div class="control-group">
					<label for="sms_text" class="control-label">{__("sms_text")}</label>
					<div class="controls">
						<textarea name="sms_text" maxlength="765" id="sms_text"></textarea>
					</div>
				</div>

				<div class="control-group">
					<label for="send_time" class="control-label">{__("send_time")}</label>
					<div class="controls">
						{__("instant")} <input type="radio" name="send_time_type" value="instant" checked>
						&nbsp;
						{__("lazy")} <input type="radio" name="send_time_type" value="lazy">
						<br>
						{include file="common/calendar.tpl" date_id="send_time_sms" date_name="send_date"}
						&nbsp;
						<select name="send_hour" class="input-small">
						    <option value="0" selected>0</option>
							{for $hr=1 to 23}
                            	<option value="{$hr}">{$hr}</option>
                            {/for}
						</select>
						:
						<select name="send_min" class="input-small">
						    <option value="0" selected class="input-small">0</option>
							{for $mn=1 to 59}
                            	<option value="{$mn}">{$mn}</option>
                            {/for}
						</select>
					</div>
				</div>

				<div class="control-group">
					<label for="phone_numbers" class="control-label">{__("phone_numbers")}<br>({__("phone_numbers_tip")})</label>
					<div class="controls">
						<input name="phone_numbers" id="phone_numbers"></textarea>
					</div>
				</div>

				<div class="control-group">
					<label for="user_groups" class="control-label">{__("send_user_groups")}</label>
					<div class="controls">
						<select name="user_groups" multiple>
							{assign var="user_groups" value=""|fn_get_usergroups}
							{foreach from=$user_groups item="user_group"}
								<option value="{$user_group.usergroup_id}">{$user_group.usergroup}</option>
							{/foreach}
						</select>
					</div>
				</div>

				<div class="control-group">
					<label for="users" class="control-label">{__("users")}</label>		
					<div class="controls">
						{include file="pickers/users/picker.tpl" but_text=__("add") data_id="users" but_meta="btn" input_name="users" placement="right"}
					</div>
				</div>

				<div class="control-group">
					<label for="order_date_range" class="control-label">{__("order_date_range")}
						<br>
						{__("order_date_range_tip")}
					</label>
					<div class="controls">
						{include file="common/calendar.tpl" date_id="order_date_range_from_sms" date_name="order_date_range_from"} 
						{__("to")}
						{include file="common/calendar.tpl" date_id="order_date_range_to_sms" date_name="order_date_range_to"} 
					</div>
				</div>

				<input type="submit" class="btn cm-ajax" name="dispatch[amocrm.check_message]" value="{__("send")}">
				
				<br><br>
				
				{*<div clss="controls">
					<b>{__("delivery_status")}</b>: 
				</div>*}
			</form>
		</div>

		<div id="content_viber">
			<form action="{""|fn_url}" method="POST" class="form-horizontal">
				<input type="hidden" name="send_method" value="omni">
				<div class="control-group">
					<label for="sms_text" class="control-label">{__("sms_text")}</label>
					<div class="controls">
						<textarea name="sms_text" maxlength="1000" id="sms_text"></textarea>
					</div>
				</div>

				<div class="control-group">
					<label for="button_url" class="control-label">{__("button_url")}</label>
					<div class="controls">
						<input type="text" name="button_url" id="button_url">
					</div>
				</div>

				<div class="control-group">
					<label for="button_label" class="control-label">{__("button_label")}</label>
					<div class="controls">
						<input type="text" name="button_label" id="button_label">
					</div>
				</div>

				<div class="control-group">
					<label for="image_url" class="control-label">{__("image_url")}</label>
					<div class="controls">
						<input type="text" name="image_url" id="image_url">
					</div>
				</div>

				<div class="control-group">
					<label for="send_time" class="control-label">{__("send_time")}</label>
					<div class="controls">
						{__("instant")} <input type="radio" name="send_time_type" value="instant" checked>
						&nbsp;
						{__("lazy")} <input type="radio" name="send_time_type" value="lazy">
						<br>
						{include file="common/calendar.tpl" date_id="send_time_viber" date_name="send_date"}
						&nbsp;
						<select name="send_hour" class="input-small">
						    <option value="0" selected>0</option>
							{for $hr=1 to 23}
                            	<option value="{$hr}">{$hr}</option>
                            {/for}
						</select>
						:
						<select name="send_min" class="input-small">
						    <option value="0" selected class="input-small">0</option>
							{for $mn=1 to 59}
                            	<option value="{$mn}">{$mn}</option>
                            {/for}
						</select>
					</div>
				</div>

				<div class="control-group">
					<label for="phone_numbers" class="control-label">{__("phone_numbers")}<br>({__("phone_numbers_tip")})</label>
					<div class="controls">
						<input name="phone_numbers" id="phone_numbers"></textarea>
					</div>
				</div>

				<div class="control-group">
					<label for="user_groups" class="control-label">{__("send_user_groups")}</label>
					<div class="controls">
						<select name="user_groups" multiple>
							{assign var="user_groups" value=""|fn_get_usergroups}
							{foreach from=$user_groups item="user_group"}
								<option value="{$user_group.usergroup_id}">{$user_group.usergroup}</option>
							{/foreach}
						</select>
					</div>
				</div>

				<div class="control-group">
					<label for="users" class="control-label">{__("users")}</label>		
					<div class="controls">
						{include file="pickers/users/picker.tpl" but_text=__("add") data_id="users" but_meta="btn" input_name="users" placement="right"}
					</div>
				</div>

				<div class="control-group">
					<label for="order_date_range" class="control-label">{__("order_date_range")}
						<br>
						{__("order_date_range_tip")}
					</label>
					<div class="controls">
						{include file="common/calendar.tpl" date_id="order_date_range_from_viber" date_name="order_date_range_from"} 
						{__("to")}
						{include file="common/calendar.tpl" date_id="order_date_range_to_viber" date_name="order_date_range_to"} 
					</div>
				</div>

				<input type="submit" class="btn cm-ajax" name="dispatch[amocrm.check_message]" value="{__("send")}">

				<br><br>

				{*<div clss="controls">
					<b>{__("delivery_status")}</b>: 
				</div>*}
			</form>
		</div>

		<div id="content_omni">
			<form action="{""|fn_url}" method="POST">

			</form>
		</div>
	{/capture}
	{include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox group_name=$runtime.controller active_tab=$smarty.request.selected_section track=true}
{/capture}
{include file="common/mainbox.tpl" content=$smarty.capture.mainbox title=__("send_messages")}