{literal}
<script type="text/javascript">
	$(function(){
		
		$('#close').click(function(){
			$('#fancybox-close').click();
		});
		$('#confirm-button').click(function(){
			
		});
	});
</script>
{/literal}
<div style="width: 400px;">
	<div class="col_12 visible" style="font-weight: bolder; font-size: large;">
		Veuillez confirmer votre action :
	</div>
	<div class="col_12">
		<div id="description-command">
			<ul class="alt">
				<li>
					Désinscription :
					<br /><br />
					<i>
						Un email sera envoyé à l'administrateur lui notifiant votre demande de désinscription du site.
					</i>								
				</li>
			</ul>
		</div>
		<div style="float: right;">
			<form method="post" action="/profile/delete" style="display: inline;">
				<button class="green" id="confirm-button" name="confirm">Oui</button>
			</form>
			<a id="confirm-command" href=""></a>
			<button class="red" id="close">Non</button>
		</div>
	</div>
</div>