{capture name="mainbox"}
	{include file="common/pagination.tpl"}
	<table width="100" class="table table-middle">
		<thead>
			<tr>
				<th>ID</th>
				<th>{__("sender_name")}</th>
				<th>{__("send_method")}</th>
				<th>{__("recepient")}</th>
				<th>{__("delivery_start")}</th>
				<th>{__("message_text")}</th>
				<th>{__("order_id")}</th>
				<th>{__("event")}</th>
				<th>{__("result")}</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$messages item="message"}
				<tr>
					<td>{$message.message_id}</td>
					<td>{$message.sender}</td>
					<td>{$message.send_method}</td>
					<td>{$message.phone}</td>
					<td>{"d.m.Y H:i:s"|date:$message.send_time}</td>
					<td>{$message.body}</td>
					<td>{$message.order_id}</td>
					<td>{__($message.event)}</td>
					<td>{$message.result}</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
	{include file="common/pagination.tpl"}
{/capture}
{include file="common/mainbox.tpl" content=$smarty.capture.mainbox title=__("messages_report")}
