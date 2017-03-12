{capture name="mainbox"}
	<table width="100" class="table table-middle">
		<thead>
			<th>ID</th>
			<th>{__("sender_name")}</th>
			<th>{__("recepient")}</th>
			<th>{__("delivery_start")}</th>
			<th>{__("change_date")}</th>
			<th>{__("message_text")}</th>
			<th>{__("order_id")}</th>
			<th>{__("event")}</th>
			<th>{__("result")}</th>
		</thead>
		<tbody>
			
		</tbody>
	</table>
{/capture}
{include file="common/mainbox.tpl" content=$smarty.capture.mainbox title=__("messages_report")}