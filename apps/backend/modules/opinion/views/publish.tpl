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
<div style="width: 800px;">
	<div class="col_12 visible" style="font-weight: bolder; font-size: large;">
		Veuillez confirmer votre action :
	</div>
	<div class="col_12">
		<div id="description-command">
			<ul class="alt">
				<li>
					Publier ce témoignage : 
					<table class="sortable filterable">
						<thead>
							<tr>
								<th>Pseudo</th>
								<th>Date de création</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									{$opinion->getUsername()}
								</td>
								<td>
									{$opinion->getCreationDate()}
								</td>
							</tr>
							<tr>
								<td colspan="2">
									{$opinion->getComment()}
								</td>
							</tr>
						</tbody>
					</table>
				</li>
			</ul>
		</div>
		<div style="float: right;">
			<form method="post" action="/admin/opinion/publish/{$opinion->id()}" style="display: inline;">
				<button class="green" id="confirm-button" name="submit-form">Publier</button>
			</form>
			<a id="confirm-command" href=""></a>
			<button class="red" id="close">Annuler</button>
		</div>
	</div>
</div>