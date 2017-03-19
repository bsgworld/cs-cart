<div class="control-group">
	<label for="feedback_name" class="control-label">{__("your_name")}</label>
	<div class="controls">
		<input type="text" name="feedback[name]" id="feedback_name">
	</div>
</div>

<div class="control-group">
	<label for="feedback_email" class="control-label">{__("your_email")}</label>
	<div class="controls">
		<input type="text" name="feedback[email]" id="feedback_email">
	</div>
</div>

<div class="control-group">
	<label for="issue_description" class="control-label">{__("your_email")}</label>
	<div class="controls">
		<textarea name="feedback[issue]" id="issue_description"></textarea>
	</div>
</div>

<input type="submit" name="dispatch[amocrm.send_feedback]" value="Отправить" class="btn">