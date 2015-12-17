{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#form-update-password').validate();
});
//-->
</script>
{/literal}
<div style="width: 400px;">
	<h4>Modifier mon mot de passe</h4>
	<div class="col_12 visible">
		<div class="col_12">
			<form method="post" action="/profile-pro/update-password" id="form-update-password">
					
				<label for="old-password"><span>*</span> Ancien mot de passe :</label>
				<input type="password" id="old-password" name="old-password" class="required" maxlength="255"/>
				
				<label for="new-password"><span>*</span> Nouveau mot de passe :</label>
				<input type="password" id="new-password" name="new-password" class="required" maxlength="255" minlength="6"/>
				
				<label for="confirm-new-password"> Confirmez le nouveau mot de passe :</label>
				<input type="password" id="confirm-new-password" name="confirm-new-password" equalTo="#new-password" maxlength="255"/>
				
				<span style="color:#666666; font-style: italic; font-size: x-small;">* Champ obligatoire</span>
				
				<button style="float: right" class="small green" name="save-new-password">Terminer</button>
			</form>
		</div>
	</div>
</div>