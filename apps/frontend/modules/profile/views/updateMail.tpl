{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#form-update-mail').validate();
});
//-->
</script>
{/literal}
<div style="width: 400px;">
	<h4>Modifier mon email</h4>
	<div class="col_12 visible">
		<div class="col_12">
			<form method="post" action="/profile/update-mail" id="form-update-mail">
					
				<label for="old-mail"> Votre email actuel : <strong>{$currentUser->getMail()}</strong></label>
				<br />
				<div class="notice warning" style="text-align: justify;">
					Attention ! Si vous modifiez votre email et que vous utilisez la fonction Facebook Connect, 
					vous perdrez la possibilité de vous connectez simplement via cet outil.
				</div>
				<br />
				<label for="new-mail"><span>*</span> Nouvel email :</label>
				<input type="text" id="new-mail" name="new-mail" class="required email"/>
				
				<label for="confirm-new-mail"> Confirmez le nouvel email :</label>
				<input type="text" id="confirm-new-mail" name="confirm-new-mail" equalTo="#new-mail"/>
				
				<span style="color:#666666; font-style: italic; font-size: x-small;">* Champ obligatoire</span>
				
				<button style="float: right" class="small green" name="save-new-mail">Terminer</button>
			</form>
		</div>
	</div>
</div>