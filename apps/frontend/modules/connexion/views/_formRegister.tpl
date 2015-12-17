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


	$('#registration-form').validate({
		   rules: {
			      name: {
			          //noSpace: true,
			          loginRegex: true
			      }
			   }
			  });

	$('#error-accept-cgu').hide();
	
	$('#registration-form').submit(function(){
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

	$('#username').focusout(function(){
		$('#username-exists').load('/username-exists/' + $(this).val());
	});
});
//-->
</script>
{/literal}
<form method="post" id="registration-form" style="display: inline;">
	<h2>Inscription</h2>
	<label>Vous êtes :</label>
	<div class="col_5 center">
		{if $user->getRoleId() == 0 || $user->getRoleId() == Role::ROLE_MEMBER}
			<input type="radio" name="role" id="role-member" checked="checked" value="member"/>
		{else}
			<input type="radio" name="role" id="role-member" value="member"/>
		{/if}
		<label for="role-member" class="inline">Un particulier <br /> <img alt="image de membre" width="75px" src="/images/member.png"/> </label>
	</div>
	<div class="col_6 center">
		{if $user->getRoleId() == Role::ROLE_MEMBER_PRO}
			<input type="radio" name="role" id="role-member-pro" checked="checked" value="member-pro"/>
		{else}
			<input type="radio" name="role" id="role-member-pro" value="member-pro"/>
		{/if}
		<label for="role-member-pro" class="inline">Un professionnel <br /> <img alt="image de pro" width="75px" src="/images/pro.png"/></label>
	</div>
	<div class="clearfix"></div>
	
	<div id="message-pro" style="display: none;">
		Attention ! l'inscription des professionnels est soumis à conditions, veuillez consulter
		<a href="/files/CGU-Tipkin.pdf" target="_blank"> 
			les CGU pour plus d'information.
		</a>
		<br /><br />
	</div>
	<div class="center">
		<div class="blockinput">
			<input type="text" id="username" name="username" placeholder="Pseudo" class="required noSpace loginRegex" minlength="6" maxlength="20" value="{$user->getUsername()}" />
		</div>
		
		<div class="blockinput" id="username-exists">
			
		</div>
		
		<div class="clear"></div>
		
		<div class="blockinput">
			<input type="text" placeholder="Adresse mail" name="mail" id="mail" class="required email" value="{$user->getMail()}" />
		</div>
		
		<div class="blockinput">
			<input type="text" placeholder="Confirmer l'adresse mail" name="mail-confirmation" id="mail-confirmation" equalto="#mail"/>
		</div>
		
		<div class="blockinput">
			<input type="password" placeholder="Mot de passe" name="password" id="password" class="required noSpace" minlength="6" value="{$user->getPassword()}"/>
		</div>
		
		<div class="blockinput">
			<input type="password" placeholder="Confirmer le mot de passe" name="password-confirmation" id="password-confirmation" equalto="#password"/>
		</div>
		<br />
		<div class="blockinput">
                    <input type="checkbox" name="mailingState" id="mailingState" value='1' checked/>
			<label for="mailingState" class="inline">Je souhaite recevoir régulièrement les news Tipkin</label>
			<br /><br />
		</div>	
		<div class="blockinput" style="text-align: left; margin-left: 25px;">
			<input type="checkbox" name="accept-cgu" id="accept-cgu"/>
			<label for="accept-cgu" class="inline">J'ai lu et j'accepte les <a href="/files/CGU-Tipkin.pdf" target="_blank">CGU</a></label>
			<br /><br />
			<label class="error" id="error-accept-cgu" style="margin-top: -17px;">Veuillez cocher cette case</label>
		</div>
	</div>
	
		<input type="submit" value="S'inscrire !" name="register" id="register" style="float: left;"/>
</form>
	<div>
		{include file='_formFacebookConnect.tpl'}
	</div>
	<br /><br />
<br /><br />