{extends file="layout.tpl"}

{block name=page_title prepend}Contactez-nous{/block}
{block name=meta_desc}Vous avez une question ? Un problème à nous faire remonter ? Remplissez le formulaire dédié à cet effet{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#form-contact').validate();
});
//-->
</script>
{/literal}
<div class="col_2">
</div>
<div class="col_8 visible">
	<h2>Contactez-nous</h2>
	{if isset($isMessageSent)}
		<p>
			Votre message a été transmis vers nos service avec succès.
			<br /><br />
			Nous vous répondrons dans les plus bref délais.
			<br /><br />
			Cordialement,
			<br />
			L'équipe TIPKIN.
		</p>
	{else}
		<form method="post" id="form-contact">
			<label for="email">Email * :</label>
			<input type="text" name="email" id="email" class="required  email"/>
			
			<label for="subject">Sujet * :</label>
			<input type="text" name="subject" id="subject" class="required"/>
			
			<label for="message">Message * :</label>
			<textarea name="message" id="message" rows="" cols="" minlength="20" class="required"></textarea>
			
			<input type="checkbox" name="send-copy" id="send-copy">
			<label class="inline" for="send-copy">Je souhaite recevoir une copie de ce message</label>
			
			<br /><br />
			<button class="small green" style="float: right;">Envoyer</button>
			<br /><br />
		</form>
	{/if}
</div>
{/block}