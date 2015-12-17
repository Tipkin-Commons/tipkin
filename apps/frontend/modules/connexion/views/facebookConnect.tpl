{extends file="layout.tpl"}

{block name=page_title}Tipkin Facebook Connect{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	jQuery.validator.addMethod("noSpace", function(value, element) { 
		  return value.indexOf(" ") < 0 && value != ""; 
		}, "Les caractères d\'espacement ne sont pas autorisés");

	jQuery.validator.addMethod("loginRegex", function(value, element) {
		    return this.optional(element) || /^[a-zA-Z0-9\-\.]+$/i.test(value);
		}, "Seul des lettres, des chiffres ou des tirets sont autorisés");

	$('#facebook-connect-form').validate({
		   rules: {
			      name: {
			          noSpace: true,
			          loginRegex: true,
			      }
			   }
			  });
	  
	$('#facebook-connect-form').submit(function(){
		if(!$('#accept-cgu').is(':checked'))
		{
			$('#error-accept-cgu').show();
			return false;
		}
	});

	$('input[name="role"]').click(function(){
		if($(this).attr('id') == 'role-member-pro')
		{
			$('#message-pro').show();
		}
		else
		{
			$('#message-pro').hide();
		}
	});
});
//-->
</script>
{/literal}
	<div class="col_8 visible">
	{$connexionMessage}
		{if !isset($profilDisabled)}
		<form method="post" action="/facebook-connect" id="facebook-connect-form">
			<label class="inline">Vous êtes</label>
			<input type="radio" name="role" id="role-member" checked="checked" value="member" style="margin-left: 20px;"/>
			<label for="role-member" class="inline">Un particulier</label>
			<input type="radio" name="role" id="role-member-pro" value="member-pro" style="margin-left: 20px;"/>
			<label for="role-member-pro" class="inline">Un professionnel</label>
			<br /><br />
			<div id="message-pro" style="display: none;">
				Attention ! l'inscription des professionnels est soumis à conditions, veuillez consulter
				<a href=""> 
					les CGU pour plus d'information.
				</a>
				<br /><br />
			</div>
			{if $usernameExist == 'true'}
			<input type="text" style="width: 300px" placeholder="Pseudo" id="username" name="username" class="required noSpace loginRegex" minlength="6" maxlength="20" />
			<br />
			{else}
			<input type="hidden" id="username" name="username" value="{$username}"/>
			{/if}
			<input type="hidden" id="mailfb" name="mailfb" value="{$mail}"/>
			<input type="checkbox" name="accept-cgu" id="accept-cgu"/>
			<label for="accept-cgu" class="inline">J'ai lu et j'accepte les <a href="">CGU</a></label>
			<input type="submit" value="S'inscrire !" name="register" id="register" style="float: right;"/>
			<br />
			<label class="error hide" id="error-accept-cgu">Veuillez cocher cette case</label>
		</form>
		{else}
		<a href="/">Cliquez sur ce lien pour être redirigé vers la page d'accueil</a>
		{/if}
		<br />
	</div>
{/block}