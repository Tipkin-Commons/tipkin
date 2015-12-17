{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#connexion-form').validate();
});
//-->
</script>
{/literal}
<div style="width: 400px;">
	<h5>Veuillez vous connecter :</h5>
	<div class="col_12 visible">
		<div class="col_12">
			<form action="/popup-connect/return-url={$returnUrl}" method="post" id="connexion-form">
			    <label>Nom d'utilisateur ou adresse mail :</label>
			    <input type="text" name="login" class="required"/>
			
			    <label>Mot de passe :</label>
			    <input type="password" name="password" class="required"/>
			    
			    <br />
			    
			    <input type="checkbox" name="create-cookie" id="create-cookie"/>
			    <label for="create-cookie" class="inline">Mémoriser</label>
				
				<br /><br />
				
			    <input type="submit" value="Connexion" name="connect" style="float: right;" />
			    <div style="margin-top: 10px;">
			    	<a href="/recover-password">Mot de passe oublié ?</a>
			    </div>
			</form>
		</div>
	</div>
</div>