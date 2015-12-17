{literal}
<script type="text/javascript">
	$(function(){
		
		$('#close').click(function(){
			$('#fancybox-close').click();
		});
	});
</script>
{/literal}
<div style="width: 400px;">
	<div class="col_12 visible" style="font-weight: bolder; font-size: large;">
		Commentaire de l'administrateur :
	</div>
	<div class="col_12">
		<div id="description-command">
			<ul class="alt">
				<li>
					Votre annonce a été refusée pour la raison suivante :
					<br /> 
					<i>{nl2br($announce->getAdminComment())}</i>
				</li>
			</ul>
		</div>
		<div style="float: right;">
			<button class="" id="close">Fermer</button>
		</div>
	</div>
</div>