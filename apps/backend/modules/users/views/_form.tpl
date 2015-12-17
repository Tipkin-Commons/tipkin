{literal}
<script type="text/javascript">
<!--
$(function(){
	$(function(){
		jQuery.validator.addMethod("noSpace", function(value, element) { 
			  return value.indexOf(" ") < 0 && value != ""; 
			}, "Les caractères d\'espacement ne sont pas autorisés");

		$('#form-{/literal}{$url}{literal}').validate({
			   rules: {
				      name: {
				          noSpace: true
				      }
				   }
				  });
		$('#generate-password').click(function(){
			if($(this).is(":checked"))
			{
				$('#password').attr('disabled','disabled').removeClass('error').next('label.error').css('display','none');
				$('label[for="password"]').addClass('disabled');
				$('#password-confirmation').attr('disabled','disabled').removeClass('error').next('label.error').css('display','none');
				$('label[for="password-confirmation"]').addClass('disabled');
			}
			else
			{
				$('#password').removeAttr('disabled');
				$('label[for="password"]').removeClass('disabled');
				$('#password-confirmation').removeAttr('disabled');
				$('label[for="password-confirmation"]').removeClass('disabled');
			}
		});
	});	
});
//-->
</script>
{/literal}
<div style="width: 450px;">
	<div class="col_12">
		<h4>Ajouter un {$roleName}</h4>
		<div class="col_12 visible">
			<div class="col_12">
				<form method="post" id="form-{$url}" action="/admin/users/{$url}">
					<label for="username">Nom d'utilisateur :</label>
					<input type="text" id="username" name="username" class="required noSpace" minlength="6" maxlength="20" />
					
					<label>Adresse mail :</label>
					<input type="text" name="mail" id="mail" class="required email" />
					
					<label>Confirmer l'adresse mail :</label>
					<input type="text" name="mail-confirmation" id="mail-confirmation" equalTo="#mail"/>
					
					<input type="checkbox" name="generate-password" id="generate-password" style="width: 20px"/>
					<label for="generate-password" style="width: 300px">Générer automatiquement le mot de passe</label>
					
					<br /><br />
					
					<label for="password">Mot de passe :</label>
					<input type="password" name="password" id="password" class="required noSpace" minlength="6"/>
					
					<label for="password-confirmation">Confirmer le mot de passe :</label>
					<input type="password" name="password-confirmation" id="password-confirmation" equalTo="#password"/>
					
					<input type="hidden" name="role" value="{$role}"/>
					
					<button name="{$url}" style="float: right;" class="small green">Terminer</button>
				</form>
			</div>
		</div>
	</div>
</div>
