{extends file="layout.tpl"}

{block name=page_title}Annulation de réservations{/block}

{block name=page_content}
<div class="col_12">
	{if $paiementState == PaiementStates::CANCELED}
	<h1>Cette réservation a déjà été annulé !</h1>
	<p>
		Une action d'annulation pour la demande d'emprunt a déjà été prise en compte.
		<br /><br />
		A très bientôt !
		<br /><br />
		L'équipe Tipkin.
	</p>
	{else}
	<h1>Cette réservation a déjà été validée !</h1>
	<p>
		Une action de validation pour la demande d'emprunt a déjà été prise en compte.
		<br /><br />
		Nous vous souhaitons un très bon Tip !
		<br /><br />
		L'équipe Tipkin.
	</p>
	{/if}
</div>

{/block}