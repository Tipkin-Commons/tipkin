{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#connexion-form').validate();
});
//-->
</script>
{/literal}
<form action="" method="post" id="connexion-form" style="display: inline;">
	<h2>Connexion</h2>
    <div class="blockinput">
		<input type="text" placeholder="Pseudo ou adresse mail" name="login" class="required"/>
	</div>
	<div class="clearfix"></div>
	<br /><br />
	<div class="blockinput">
		<input type="password" placeholder="Mot de passe" name="password" class="required"/>
    </div>
    <div class="clearfix"></div>
    <br /><br />
    <input type="checkbox" name="create-cookie" id="create-cookie"/>
    <label for="create-cookie" class="inline">Mémoriser</label>
	 | <a href="/recover-password">Mot de passe oublié ?</a>
	
	<br /><br />
	
		<input type="submit" value="Connexion" name="connect" style="float: left;" />
</form>
<div style="float: left; margin-right: 10px;">
	{include file='_formFacebookConnect.tpl'}
</div>

<br />

<br />