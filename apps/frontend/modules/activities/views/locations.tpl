{extends file="layout.tpl"}

{block name=page_title}Mes prêts{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('.tab-content').css('minHeight','400px');
	
	$('.date').each(function(){
		var date = $(this).text();
		$(this).text(parseToDateFr(date));
	});

	$('.date-end').each(function(){
		if($(this).text() != '')
		{
			var date = $(this).text();
			$(this).text(parseToDateFr(date));
		}
	});
	
	$('.date-option').each(function(){
		var option = $(this).text();
		$(this).text(getOptionLabel(option));
	});

});

function parseToDateFr(date)
{
	var year = date.substring(0,4);
	var month = date.substring(5,7);
	var day = date.substring(8,10);

	return (day + '/' + month + '/' + year);
}

function getOptionLabel(dateOption)
{
	switch (dateOption) {
	case 'morning':
		return 'la matinée';
		break;
	case 'evening':
		return 'la soirée';
		break;
	case 'all-day':
		return 'la journée entière';
		break;

	case 'period':
		return 'la période allant jusqu\'au ';
		break;
		
	default:
		break;
	}
}
//-->
</script>
{/literal}
<div class="col_9">
	<div class="col_12">
		<h4>Mes prêts</h4>
		{$message}
		<div id="tabs">
			<ul class="tabs">
				<li>
					<a href="#current">En attente</a>
				</li>
				<li>
					<a href="#validated">Validée(s)</a>
				</li>
				<li>
					<a href="#canceled">Annulée(s)</a>
				</li>
				<li style="float: right;">
					<a href="#old">Ancienne(s)</a>
				</li>
			</ul>
			<div class="tab-content" id="current">
				{foreach from=$listOfReservations item=reservation}
					 {if $reservation->getUserOwnerId() == $currentUser->id() && strtotime($reservation->getDateEnd()) + (24*60*60) > $smarty.now && $reservation->getStateId() == PaiementStates::WAITING_VALIDATION }
					 	<div class="col_12 visible">
					 		<ul class="button-bar" style="float: right;">
					 			<li>
					 				<a href="/reservations/valid/{$reservation->id()}/{$reservation->getKeyCheck()}">Accepter</a>
					 			</li>
					 			<li>
					 				<a href="/reservations/cancel/{$reservation->id()}/{$reservation->getKeyCheck()}">Refuser</a>
					 			</li>
					 		</ul>
					 		{assign var="announce" 	value=$announcementManager->get($reservation->getAnnouncementId())}
					 		{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
							{assign var="userPhoto" value="{$profilesManager->getByUserId($announce->getUserId())->getAvatar()}"}
							<div class="col_3">
								<div class="center">
									<img alt="image de l'annonce" style="width: 100%; margin-bottom:10px;" src="{$mainPhoto}"/>
								</div>
							</div>
							<div class="col_3">
								<label>{$announce->getTitle()}</label>
								<br />
								<label><span class="icon small red" data-icon="&"></span><span>{$announce->getCity()} {$announce->getDepartmentId()}</span></label>
							</div>
							<div class="col_3">
								<label>Le : <span class="date">{$reservation->getDate()}</span></label>
								Pour <span class="date-option">{$reservation->getDateOption()}</span>
								<span class="date-end">{$reservation->getDateEnd()}</span>
								<label>Prix : <span>{$reservation->getPrice()} €</span></label>
							</div>
							<div class="col_3">
								<a href="/users/member/{$reservation->getUserSubscriberId()}">Profil de {$usersManager->get($reservation->getUserSubscriberId())->getUsername()}</a>
							</div>
					 	</div>
					 {/if}
				{/foreach}
			</div>
			
			<div class="tab-content" id="validated">
				{foreach from=$listOfReservations item=reservation}
					 {if $reservation->getUserOwnerId() == $currentUser->id() && strtotime($reservation->getDateEnd()) + (24*60*60) > $smarty.now && $reservation->getStateId() == PaiementStates::VALIDATED }
					 	<div class="col_12 visible">
					 		{assign var="announce" 	value=$announcementManager->get($reservation->getAnnouncementId())}
					 		{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
							{assign var="userPhoto" value="{$profilesManager->getByUserId($announce->getUserId())->getAvatar()}"}
							<div class="col_3">
								<div class="center">
									<img alt="image de l'annonce" style="width: 100%; margin-bottom:10px;" src="{$mainPhoto}"/>
								</div>
							</div>
							<div class="col_3">
								<label>{$announce->getTitle()}</label>
								<br />
								<label><span class="icon small red" data-icon="&"></span><span>{$announce->getCity()} {$announce->getDepartmentId()}</span></label>
							</div>
							<div class="col_3">
								<label>Le : <span class="date">{$reservation->getDate()}</span></label>
								Pour <span class="date-option">{$reservation->getDateOption()}</span>
								<span class="date-end">{$reservation->getDateEnd()}</span>
								<label>Prix : <span>{$reservation->getPrice()} €</span></label>
							</div>
							<div class="col_3">
								<a href="/users/member/{$reservation->getUserSubscriberId()}">Profil de {$usersManager->get($reservation->getUserSubscriberId())->getUsername()}</a>
							</div>
					 	</div>
					 {/if}
				{/foreach}
			</div>
			
			<div class="tab-content" id="canceled">
				{foreach from=$listOfReservations item=reservation}
					 {if $reservation->getUserOwnerId() == $currentUser->id() && $reservation->getStateId() == PaiementStates::CANCELED }
					 	<div class="col_12 visible">
					 		{assign var="announce" 	value=$announcementManager->get($reservation->getAnnouncementId())}
					 		{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
							{assign var="userPhoto" value="{$profilesManager->getByUserId($announce->getUserId())->getAvatar()}"}
							<div class="col_3">
								<div class="center">
									<img alt="image de l'annonce" style="width: 100%; margin-bottom:10px;" src="{$mainPhoto}"/>
								</div>
							</div>
							<div class="col_3">
								<label>{$announce->getTitle()}</label>
								<br />
								<label><span class="icon small red" data-icon="&"></span><span>{$announce->getCity()} {$announce->getDepartmentId()}</span></label>
							</div>
							<div class="col_3">
								<label>Le : <span class="date">{$reservation->getDate()}</span></label>
								Pour <span class="date-option">{$reservation->getDateOption()}</span>
								<span class="date-end">{$reservation->getDateEnd()}</span>
								<label>Prix : <span>{$reservation->getPrice()} €</span></label>
							</div>
							<div class="col_3">
								<a href="/users/member/{$reservation->getUserSubscriberId()}">Profil de {$usersManager->get($reservation->getUserSubscriberId())->getUsername()}</a>
							</div>
					 	</div>
					 {/if}
				{/foreach}
			</div>
			
			<div class="tab-content" id="old">
				{foreach from=$listOfReservations item=reservation}
					 {if $reservation->getUserOwnerId() == $currentUser->id() && strtotime($reservation->getDateEnd()) + (24*60*60) < $smarty.now && $reservation->getStateId() == PaiementStates::VALIDATED}
					 	<div class="col_12 visible">
					 		{assign var="announce" 	value=$announcementManager->get($reservation->getAnnouncementId())}
					 		{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
							{assign var="userPhoto" value="{$profilesManager->getByUserId($announce->getUserId())->getAvatar()}"}
							<div class="col_3">
								<div class="center">
									<img alt="image de l'annonce" style="width: 100%; margin-bottom:10px;" src="{$mainPhoto}"/>
								</div>
							</div>
							<div class="col_3">
								<label>{$announce->getTitle()}</label>
								<br />
								<label><span class="icon small red" data-icon="&"></span><span>{$announce->getCity()} {$announce->getDepartmentId()}</span></label>
							</div>
							<div class="col_3">
								<label>Le : <span class="date">{$reservation->getDate()}</span></label>
								Pour <span class="date-option">{$reservation->getDateOption()}</span>
								<span class="date-end">{$reservation->getDateEnd()}</span>
								<label>Prix : <span>{$reservation->getPrice()} €</span></label>
							</div>
							<div class="col_3">
								<a href="/users/member/{$reservation->getUserSubscriberId()}">Profil de {$usersManager->get($reservation->getUserSubscriberId())->getUsername()}</a>
							</div>
					 	</div>
					 {/if}
				{/foreach}
			</div>
		</div>
	</div>
</div>
<div class="col_3" style="margin-top: 26px;">
	{include file='_menuActionRight.tpl'}
</div>
{/block}