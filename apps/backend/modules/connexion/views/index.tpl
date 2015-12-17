{extends file="layout.tpl"}

{block name=page_title append} - Connexion{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#connexion-form').validate();
});
//-->
</script>
{/literal}
<div class="col_6 column">
	<h2>Connexion</h2>
	{if isset($connexionMessage)}
		{$connexionMessage}
	{/if}
	<form action="" method="post" id="connexion-form">
	    <label>Nom d'utilisateur :</label>
	    <input type="text" name="login" class="required" />
	    
	    <label>Mot de passe :</label>
	    <input type="password" name="password" class="required"/>
	    
	    <input type="submit" value="Connexion" name="connect" style="float: right;" />
	</form>
</div>
{/block}