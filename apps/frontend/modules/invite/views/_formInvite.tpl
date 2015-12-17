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
	if($('#emails_liste')=='')
		{
			return false;
		}
	}
	});

	$('#username').focusout(function(){
		$('#username-exists').load('/username-exists/' + $(this).val());
	});
});
//-->
</script>
{/literal} 



<form method="post" action="/invite/send" id="invite-form" style="display: inline;">
	<h2>Invitez vos amis</h2>
	<h5> Via ce formulaire ou via Facebook</h5>

	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1&appId=391051707631844";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<ul>
	<li>Via Facebook : <div class="fb-send" data-href="http://tipkin.fr/" data-font="tahoma"></div></li>
	<li>
	<hidden name='useremail' id='useremail' value='{$currentUser->getMail()}'/>
	<p> Grâce à ce formulaire, invitez vos contacts à participer à TIPKIN et comme vous, profiter de la consommation collaborative. </p>
	</li>
	</ul>
	
		<table><tr><td>
		<label for="emails_liste">Saisissez les adresses de vos contacts puis cliquez sur "Inviter!"</label>
		</td><td>
		<label for="commentaire">Voulez vous ajouter un commentaire au message qui leur sera transmis?
		<br></label>
		</td></tr>
		<tr><td>
			<textarea class="col_12 " name="emails_liste" id="emails_liste" cols="120" rows="5" placeholder="Saisissez les adresses de vos contacts séparées par une virgule ou un retour à la ligne, puis, cliquez sur 'Inviter!'"/>{$emails_liste}</textarea>
		</td><td>
<!--	
		<q>Si tu ne connais pas encore Tipkin, connecte-toi, et rejoints ma Tipkin-ship !<br/>
		Ensemble nous pourrons partager tous nos objets. <br/>
		N'hésites plus et viens consulter mes annonces sur mon profil </q>
-->
		<textarea class="col_12 " name="commentaire" id="commentaire" cols="120" rows="3" placeholder="Si tu ne connais pas encore Tipkin, connecte-toi, et rejoints ma Tipkin-ship ! Ensemble nous pourrons partager tous nos objets. N'hésites plus et viens consulter mes annonces sur mon profil "/>{$commentaire}</textarea> 
		</td></tr></table>
	
	<div class="col_10 nowrap" >
		<label for="sendback"><input type="checkbox" name="sendback" id="sendback"/>Souhaitez vous recevoir une copie de ce message?</label>
	</div>
	<div class="right">
		<button name="submit-form" id="submit-form">Inviter !</button>
	</div>
</form>
