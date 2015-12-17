{literal}
<script type="text/javascript">
	$(function(){
		
		$('#close').click(function(){
			$('#fancybox-close').click();
			return false;
		});
	});
</script>
{/literal}
<div style="width: 400px;">
	<div class="col_12">
		<h5>Ajouter à ma Tipkin-ship</h5>
		<form action="/contacts/add/{$user->id()}" method="post" style="display: inline;">
			<div class="col_12 visible">
				<div style="margin: 10px;">
					<label>Sélectionner un groupe :</label>
					<select id="contact-group" name="contact-group">
						<option value="{ContactGroups::FAMILY}">Famille</option>
						<option value="{ContactGroups::FRIENDS}">Amis</option>
						<option value="{ContactGroups::NEIGHBORS}">Voisins</option>
						<option value="{ContactGroups::TIPPEURS}">Tippeurs</option>
					</select>
					<input type="hidden" name="user-id-to" value="{$user->id()}"/>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class=" col_12 right">
				<button class="small green">Envoyer une demande à ce membre</button>
				<button class="small red" id="close">Annuler</button>
			</div>
		</form>
	</div>
</div>