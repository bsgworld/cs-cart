<div class="control-group">
    <label for="amocrm_msg_{$status_data.status_id}" class="control-label">{__("message")}:</label>
    <div class="controls">
        <textarea name="status_data[amocrm_msg]" id="amocrm_msg_{$status_data.status_id}">{$amocrm_msg}</textarea>
    </div>
</div>

{__("amocrm_vars")}:
<ul>
	<li>%ORDER_ID% {__("equals_order_id")};</li>
	<li>%AMOUNT% {__("equals_order_total")};</li>
	<li>%NAME% {__("equals_user_name")};</li>
	<li>%LAST_NAME% {__("equals_user_last_name")};</li>
	<li>%USER_EMAIL% {__("equals_user_email")};</li>
	<li>%COUNTRY% {__("equals_shipping_country")};</li>
	<li>%ADDRESS% {__("equals_shipping_address")};</li>
	<li>%CITY% {__("equals_shipping_city")};</li>
	<li>%STATE% {__("equals_shipping_state")}.</li>
</ul>