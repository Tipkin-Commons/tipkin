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
					Supprimer cette catégorie : <i>{$category->getName()}</i>
					{if $category->getIsRoot()}
						<br /><br />
						<span style="color: red">
							Attention ! Vous êtes sur le point de supprimer une catégorie parente.
							<br />
							Toutes les sous-catégories de cette catégorie seront également supprimées.
						</span>
					{/if}
				</li>
			</ul>
		</div>
		<div style="float: right;">
			<form method="post" action="/admin/categories/delete/{$category->id()}" style="display: inline;">
				<button class="green" id="confirm-button" name="submit-form">Oui</button>
			</form>
			<a id="confirm-command" href=""></a>
			<button class="red" id="close">Non</button>
		</div>
	</div>
</div>