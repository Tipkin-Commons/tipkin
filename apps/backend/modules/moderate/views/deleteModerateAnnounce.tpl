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
					<table>
						<tr>
							<td>
								<a href="/view/member/announce-{$moderate->getTypeId()}" target="_blank">
									{$announcementsManager->get($moderate->getTypeId())->getTitle()}
								</a>
							</td>
							<td>
								{$usersManager->get($announcementsManager->get($moderate->getTypeId())->getUserId())->getUsername()}
							</td>
							<td>
								{$usersManager->get($moderate->getUserAuthorId())->getUsername()}
							</td>
						</tr>
						<tr>
							<td colspan="3">
								{$moderate->getMessage()}
							</td>
						</tr>
					</table>
				</li>
			</ul>
		</div>
		<div style="float: right;">
			<form method="post" action="/admin/moderate/announce/delete/{$moderate->id()}" style="display: inline;">
				<button class="green" id="confirm-button" name="submit-form">Oui</button>
			</form>
			<a id="confirm-command" href=""></a>
			<button class="red" id="close">Non</button>
		</div>
	</div>
</div>