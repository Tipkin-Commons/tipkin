{extends file="layout.tpl"}

{block name=page_title}Historique{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$(".filterable tr:has(td)").each(function(){
   		var t = $(this).text().toLowerCase(); //all row text
   		$("<td class='indexColumn'></td>")
    	.hide().text(t).appendTo(this);
 	});

	$("#input-filter").keyup(function(){
   		var s = $(this).val().toLowerCase().split(" ");
   		//show all rows.
   		$(".filterable tr:hidden").show();
   		$.each(s, function(){
    		$(".filterable tr:visible .indexColumn:not(:contains('"
        		+ this + "'))").parent().hide();
   		});//each
 	});//key up.

	$('#search').click(function(){
		$('#input-filter').keyup();
	});

	$('.read-message').click(function(){
		var message = $(this).attr('href');
		$(message).toggle();
	});
});

//-->
</script>
{/literal}
<div class="col_12">
	<h4>Gestion des historiques de réservation</h4>
	{$message}
	<ul class="tabs">
		<li>
			<a href="#canceled">Annulé</a>
		</li>
		<li>
			<a href="#validated">Validé</a>
		</li>
		<li style="float: right;">
			<a href="#proceed">Traité</a>
		</li>
	</ul>
	<div id="canceled" class="tab-content">
		<table class="sortable filterable">
			<thead>
				<tr>
					<th>Annonce</th>
					<th>Créé le</th>
					<th>Mis à jour le</th>
					<th>Montant</th>
					<th>Acompte</th>
					<th>Transaction Réf.</th>
					<th class="center">Actions</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$reservationsManager->getListOf() item=reservation}
					{if !$reservation->getAdminProceed() && $reservation->getStateId() == PaiementStates::CANCELED}
					<tr>
						<td>
							<a href="/view/member/announce-{$reservation->getAnnouncementId()}" target="_blank">
								{$announcementsManager->get($reservation->getAnnouncementId())->getTitle()}
							</a>
						</td>
						<td>
							{$reservation->getCreatedTime()}
						</td>
						<td>
							{$reservation->getUpdatedTime()}
						</td>
						<td>
							{$reservation->getPrice()} €
						</td>
						<td>
							{round($reservation->getPrice() * $platform_fee_ratio, 2)} €
						</td>
						<td>
							{$reservation->getTransactionRef()}
						</td>
						<td>
							<a href="/admin/history/proceed-reservation/{$reservation->id()}">Traité</a>
						</td>
					</tr>
					{/if}
				{/foreach}
			</tbody>
		</table>
	</div>
	<div id="validated" class="tab-content">
		<table class="sortable filterable">
			<thead>
				<tr>
					<th>Annonce</th>
					<th>Créé le</th>
					<th>Mis à jour le</th>
					<th>Montant</th>
					<th>Acompte</th>
					<th>Transaction Réf.</th>
					<th class="center">Actions</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$reservationsManager->getListOf() item=reservation}
					{if !$reservation->getAdminProceed() && $reservation->getStateId() == PaiementStates::VALIDATED}
					<tr>
						<td>
							<a href="/view/member/announce-{$reservation->getAnnouncementId()}" target="_blank">
								{$announcementsManager->get($reservation->getAnnouncementId())->getTitle()}
							</a>
						</td>
						<td>
							{$reservation->getCreatedTime()}
						</td>
						<td>
							{$reservation->getUpdatedTime()}
						</td>
						<td>
							{$reservation->getPrice()} €
						</td>
						<td>
							{round($reservation->getPrice() * $platform_fee_ratio, 2)} €
						</td>
						<td>
							{$reservation->getTransactionRef()}
						</td>
						<td>
							<a href="/admin/history/proceed-reservation/{$reservation->id()}">Traité</a>
							|
							<a class="lightbox" href="/admin/history/cancel-reservation/{$reservation->id()}">Annuler cette réservation</a>
						</td>
					</tr>
					{/if}
				{/foreach}
			</tbody>
		</table>
	</div>
	<div id="proceed" class="tab-content">
		<table class="sortable filterable">
			<thead>
				<tr>
					<th>Annonce</th>
					<th>Créé le</th>
					<th>Mis à jour le</th>
					<th>Montant</th>
					<th>Acompte</th>
					<th>Transaction Réf.</th>
					<th>Statut</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$reservationsManager->getListOf() item=reservation}
					{if $reservation->getAdminProceed()}
					<tr>
						<td>
							<a href="/view/member/announce-{$reservation->getAnnouncementId()}" target="_blank">
								{$announcementsManager->get($reservation->getAnnouncementId())->getTitle()}
							</a>
						</td>
						<td>
							{$reservation->getCreatedTime()}
						</td>
						<td>
							{$reservation->getUpdatedTime()}
						</td>
						<td>
							{$reservation->getPrice()} €
						</td>
						<td>
							{round($reservation->getPrice() * $platform_fee_ratio, 2)} €
						</td>
						<td>
							{$reservation->getTransactionRef()}
						</td>
						<td>
							{if $reservation->getStateId() == PaiementStates::VALIDATED}
								Validé
							{else}
								Annulé
							{/if}
							<br />
							<a class="lightbox" href="/admin/history/cancel-reservation/{$reservation->id()}">Annuler cette réservation</a>
						</td>
					</tr>
					{/if}
				{/foreach}
			</tbody>
		</table>
	</div>
</div>
{/block}
