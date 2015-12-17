{extends file="layout.tpl"}

{block name=page_title}Mot de passe oublié{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#recover-password-form').validate();
});
//-->
</script>
{/literal}
<div class="col_2">
</div>
<div class="col_8 visible">
	<h3>
		Vous avez oublié votre mot de passe ?
	</h3>
	{if $message != ''}
		{$message}
		<a href="/login">Cliquez ici pour revenir à la page de connexion</a>
	{else}
	<p>
		Entrez votre adresse mail pour que nous vous envoyons un nouveau mot de passe pour vous connecter.
	</p>
	<form method="post" id="recover-password-form">
		<label>Votre email :</label>
		<input type="text" name="mail" style="width: 300px;" class="required email"/>
		<br />
		<input type="submit" name="recover-password" value="Envoyer un nouveau mot de passe"/>
	</form>
	{/if}
</div>
{/block}