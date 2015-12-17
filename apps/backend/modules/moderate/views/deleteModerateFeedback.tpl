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
					Supprimer cette demande de modération : 
					<table class="sortable filterable">
						<thead>
							<tr>
								<th>Autheur du feedback</th>
								<th>Utilisateur concerné</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									{$usersManager->get($feedbacksManager->get($moderate->getTypeId())->getUserAuthorId())->getUsername()}
								</td>
								<td>
									{$usersManager->get($moderate->getUserAuthorId())->getUsername()}
								</td>
							</tr>
							<tr>
								<td colspan="2">
									{$moderate->getMessage()}
								</td>
							</tr>
						</tbody>
					</table>
				</li>
			</ul>
		</div>
		<div style="float: right;">
			<form method="post" action="/admin/moderate/feedback/delete/{$moderate->id()}" style="display: inline;">
				<button class="green" id="confirm-button" name="submit-form">Oui</button>
			</form>
			<a id="confirm-command" href=""></a>
			<button class="red" id="close">Non</button>
		</div>
	</div>
</div>