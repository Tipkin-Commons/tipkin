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
					Supprimer votre annonce : <i>{$announce->getTitle()}</i>
				</li>
			</ul>
		</div>
		<div style="float: right;">
			<form method="post" action="/announcements-pro/delete/{$announce->id()}" style="display: inline;">
				<button class="green" id="confirm-button" name="confirm">Oui</button>
			</form>
			<a id="confirm-command" href=""></a>
			<button class="red" id="close">Non</button>
		</div>
	</div>
</div>