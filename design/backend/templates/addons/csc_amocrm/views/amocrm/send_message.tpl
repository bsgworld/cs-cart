{capture name="mainbox"}
	Была запланирована рассылка на 10000 клиентов.
	Отправлено на Viber: 5000. Не отправлено: 5000.<br><br>
	Отправить через SMS? <a href="{"amocrm.send&selected_section=sms"|fn_url}" class="btn">{__("yes")}</a>
{/capture}
{include file="common/mainbox.tpl" content=$smarty.capture.mainbox title=__("result")}