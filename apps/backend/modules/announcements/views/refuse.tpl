{literal}
<script type="text/javascript">
	$(function(){
		$('#form-refuse').validate();
		
		$('#close').click(function(){
			$('#fancybox-close').click();
			return false;
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
	<form method="post" id="form-refuse" action="/admin/announcements/refuse/{$announce->id()}" style="display: inline;">
	<div class="col_12">
		<div id="description-command">
			<ul class="alt">
				<li>
					Refuser cette annonce : <i>{$announce->getTitle()}</i>
				</li>
			</ul>
			<label for="admin-comment">Laissez un commentaire :<span class="right"><i>(champ obligatoire)</i></span></label>
			<textarea style="width: 100%; height: 100px" class="required" name="admin-comment" id="admin-comment" placeholder="Pourquoi refusez-vous cette annonce ?"></textarea>
			{if $announce->getAdminComment() != ''}
			<div class="notice warning">
				Cette annonce a déjà été refusée précédemment.
				<br /><br />
				Raison de refus :
				<br />
				<i>{$announce->getAdminComment()}</i>
			</div>
			{/if}
			<br />
		</div>
		<div style="float: right;">
			
			<button class="green" id="confirm-button" name="confirm">Oui</button>
			<a id="confirm-command" href=""></a>
			<button class="red" id="close">Non</button>
		</div>
	</div>
	</form>
</div>