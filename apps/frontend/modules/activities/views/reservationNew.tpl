{extends file="layout.tpl"}

{block name=page_title}Nouvelle réservation{/block}
{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#date').text(parseToDateFr('{/literal}{$reservation->getDate()}{literal}'));
	$('#date-option').text(getOptionLabel('{/literal}{$reservation->getDateOption()}{literal}'));

	//{/literal}
	//{if !is_null($reservation->getDateEnd())}
	//{literal}
	$('#date-end').text(parseToDateFr('{/literal}{$reservation->getDateEnd()}{literal}'));
	//{/literal}
	//{/if}
	//{literal}
	$('#confirm-reservation').click(function(){
		$('#form-reservation').submit();
	});

	//$('#beta-link').click();
	
	//{/literal}
	//{if !is_null($currency)}
	//{literal}
		var rate = $('#currencyObj').attr('data-rate');
		var imgPath = $('#currencyObj').attr('data-img');
		var title = $('#currencyObj').val();
			
		$('span.currency').html('<img src="' + imgPath + '" title="' + title + '" />')
		
		$('.default-price').each(function(){
			var targetClass = $(this).attr('target-class');
			var value = $(this).val() * rate;
			
			$('.' + targetClass).html(value);
		});
	//{/literal}
	//{/if}
	//{literal}
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
<div class="col_12">
	<h3>Nouvelle réservation</h3>
	<div style="border: solid 1px; overflow: hidden;">
		<div class="col_4 visible" id="announce-{$announce->id()}">
			{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{$announce->getPhotoMain()}"}
			{assign var="userPhoto" value="{$profilesManager->getByUserId($announce->getUserId())->getAvatar()}"}
			<div class="center">
				<img alt="image de l'annonce" style="width: 100%; margin-bottom:10px;" src="{$mainPhoto}"/>
			</div>
			<div class="clearfix"></div>
			<div style="width: 25%; height: 100%; float: left; margin-bottom:10px;" class="center">
				<img alt="image de l'utilisateur" style="width: 100%" src="{$userPhoto}"/>
			</div>
			<div style="width: 73%; float: right; margin-left: 2%">
				<label>{$announce->getTitle()}</label>
				<br />
				<label><span class="icon small red" data-icon="&"></span><span>{$announce->getCity()} {$announce->getDepartmentId()}</span></label>
				<label>				
					{$announce->getPricePublic()} <span class="currency">€</span> 
					<span>/j</span>
				</label>
			</div>
		</div>
		<div class="col_8">
			<h4>Veuillez confirmer votre réservation</h4>
			Le <span id="date"></span>, réservation pour <span id="date-option"></span>
			{if !is_null($nbDays)}
				<span id="date-end"></span>
			{/if}
			{if $nbDays > 0}
				(inclus) - {$nbDays} jours
			{/if}
			<br /><br />
			{foreach from=$listOfContacts item=contact}
				{if $currentUser->id() != $announce->getUserId() 
						&& ($contact->getUserId1() == $currentUser->id() || $contact->getUserId2() == $currentUser->id())}
					Ce tippeur fait partie de vos contact :
					<b>
						<img alt="image du tipkinship" src="{ContactGroups::getImageSrc($contact->getContactGroupId())}" align="absmiddle"/>
						{ContactGroups::getLabel($contact->getContactGroupId())} 
					</b>
					<br /><br />
				{/if}
			{/foreach}
			TARIFS :
			{assign var="isContactPrice" value=0}
			{foreach from=$listOfContacts item=contact}
				{if $currentUser->id() != $announce->getUserId() 
						&& ($contact->getUserId1() == $currentUser->id() || $contact->getUserId2() == $currentUser->id())}
					{foreach from=$listOfPrices item=price}
						{if $contact->getContactGroupId() == $price->getContactGroupId() && $price->getIsActive()}
							{assign var="isContactPrice" value=1}
							
							{if $reservation->getDateOption() == 'morning' || $reservation->getDateOption() == 'evening'}
								{assign var="priceToPayed" value=$price->getHalfDay()}
							{elseif $reservation->getDateOption() == 'all-day'}
								{assign var="priceToPayed" value=$price->getDay()}
							{else $reservation->getDateOption() == 'period'}
								{assign var="priceToPayed" value=$price->calculatePriceByPeriod($nbDays)}
							{/if}
							
							<input type="hidden" class="default-price" target-class="halfday-price" value="{$price->getHalfDay()}" />
							<input type="hidden" class="default-price" target-class="caution-price" value="{$announce->getCaution()}" />
							<input type="hidden" class="default-price" target-class="day-price" value="{$price->getDay()}" />
							<input type="hidden" class="default-price" target-class="weekend-price" value="{$price->getWeekEnd()}" />
							<input type="hidden" class="default-price" target-class="week-price" value="{$price->getWeek()}" />
							<input type="hidden" class="default-price" target-class="fortnight-price" value="{$price->getFortnight()}" />
							
							<table>
							{if !$announce->getIsFullDayPrice()}
								<tr>
									<td>
										<span class="halfday-price">{$price->getHalfDay()}</span> <label class="inline"><span><span class="currency">€</span> par 1/2 journée</span></label>
									</td>
									<td>
										<span class="day-price">{$price->getDay()}</span> <label class="inline"><span><span class="currency">€</span> par journée</span></label>
									</td>
								</tr>
							{else}
								<tr>
									<td colspan="2">
										<span class="day-price">{$price->getDay()}</span> <label class="inline"><span><span class="currency">€</span> par journée</span></label>
									</td>
								</tr>
							{/if}
								<tr>
									<td>
										<span class="weekend-price">{$price->getWeekEnd()}</span> <label class="inline"><span><span class="currency">€</span> par week-end</span></label>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>
										<span class="week-price">{$price->getWeek()}</span> <label class="inline"><span><span class="currency">€</span> par semaine</span></label>
									</td>
									<td>
										<span class="fortnight-price">{$price->getFortnight()}</span> <label class="inline"><span><span class="currency">€</span> par quinzaine</span></label>
									</td>
								</tr>
							</table>
						{/if}
					{/foreach}
				{/if} 
			{/foreach}
			{if $isContactPrice == 0}
				{foreach from=$listOfPrices item=price}
					{if $price->getContactGroupId() == ContactGroups::USERS}
					
						{if $reservation->getDateOption() == 'morning' || $reservation->getDateOption() == 'evening'}
							{assign var="priceToPayed" value=$price->getHalfDay()}
						{elseif $reservation->getDateOption() == 'all-day'}
							{assign var="priceToPayed" value=$price->getDay()}
						{else $reservation->getDateOption() == 'period'}
							{assign var="priceToPayed" value=$price->calculatePriceByPeriod($nbDays)}
						{/if}
						
						<input type="hidden" class="default-price" target-class="halfday-price" value="{$price->getHalfDay()}" />
						<input type="hidden" class="default-price" target-class="caution-price" value="{$announce->getCaution()}" />
						<input type="hidden" class="default-price" target-class="day-price" value="{$price->getDay()}" />
						<input type="hidden" class="default-price" target-class="weekend-price" value="{$price->getWeekEnd()}" />
						<input type="hidden" class="default-price" target-class="week-price" value="{$price->getWeek()}" />
						<input type="hidden" class="default-price" target-class="fortnight-price" value="{$price->getFortnight()}" />
						
						<table>
						{if !$announce->getIsFullDayPrice()}
							<tr>
								<td>
									<span class="halfday-price">{$price->getHalfDay()}</span> <label class="inline"><span><span class="currency">€</span> par 1/2 journée</span></label>
								</td>
								<td>
									<span class="day-price">{$price->getDay()}</span> <label class="inline"><span><span class="currency">€</span> par journée</span></label>
								</td>
							</tr>
						{else}
							<tr>
								<td colspan="2">
									<span class="day-price">{$price->getDay()}</span> <label class="inline"><span><span class="currency">€</span> par journée</span></label>
								</td>
							</tr>
						{/if}
							<tr>
								<td>
									<span class="weekend-price">{$price->getWeekEnd()}</span> <label class="inline"><span><span class="currency">€</span> par week-end</span></label>
								</td>
								<td></td>
							</tr>
							<tr>
								<td>
									<span class="week-price">{$price->getWeek()}</span> <label class="inline"><span><span class="currency">€</span> par semaine</span></label>
								</td>
								<td>
									<span class="fortnight-price">{$price->getFortnight()}</span> <label class="inline"><span><span class="currency">€</span> par quinzaine</span></label>
								</td>
							</tr>
						</table>
					{/if}
				{/foreach}
			{/if}
			{if $announce->getCaution() > 0}
				<label>Caution : <span><span class="caution-price">{$announce->getCaution()}</span> <span class="currency">€</span></span></label> 
			{/if}
			{if is_null($currency) and $platform_fee_ratio gt 0}
				<label>Montant à prévoir avec le Tippeur : <span><span class="total-price">{$priceToPayed - round($priceToPayed * $platform_fee_ratio, 2)}</span> <span class="currency">€</span></span></label>
				<label id="account-to-pay">Acompte à payer pour la réservation : <span><span class="account-price">{round($priceToPayed * $platform_fee_ratio, 2)}</span> <span class="currency">€</span></span></label>
			{else}
				<label>Montant à prévoir avec le Tippeur : <span><span class="total-price">{$priceToPayed}</span> <span class="currency">€</span></span></label>
			{/if}
			
			
		</div>
	</div>
	<div class="col_12 right">
		<button class="green small" id="confirm-reservation">Confirmer</button>
		<a class="button red small" href="/view/member/announce-{$announce->getLink($announce->id())}">Annuler</a>
	</div>
</div>
{if !is_null($currency)}
	<input type="hidden" id="currencyObj" data-rate="{$currency->getRate()}" data-img="{AlternateCurrency::$CURRENCY_PATH}{$currency->getImageUrl()}" value="{$currency->getName()}" />
{/if}
<div id="reservation">
	<!-- SIMULATION DE RESERVATION POUR LES TESTS -->
	<form action="/activities/reservations/landing" id="form-reservation" method="post">
		<input type="hidden" name="date" id="date" value="{$reservation->getDate()}" />
		<input type="hidden" name="date-end" id="date-end" value="{$reservation->getDateEnd()}" />
		<input type="hidden" name="date-option" id="date-option" value="{$reservation->getDateOption()}" />
		{if $isAuthenticate == 'true'}
			<input type="hidden" name="user-subscriber-id" id="user-subscriber-id" value="{$currentUser->id()}"/>
		{/if}
		<input type="hidden" name="contact-group-id" id="contact-group-id" value="{$reservation->getContactGroupId()}"/>
		<input type="hidden" name="user-owner-id" id="user-owner-id" value="{$announce->getUserId()}"/>
		<input type="hidden" name="announcement-id" id="announcement-id" value="{$announce->id()}"/>
		<input type="hidden" name="price" id="price" value="{$priceToPayed}"/>
		{if is_null($currency)}
			<input type="hidden" name="currency-id" id="currency-id" value="default"/>
		{else}
			<input type="hidden" name="currency-id" id="currency-id" value="{$currency->id()}"/>
		{/if}
	</form>
</div>
<!-- MODAL POPUP -->
<a href="#beta" id="beta-link" class="lightbox"></a>
<div style="display: none;">
	<div id="beta">
		<h1>INFORMATION</h1>
		Cher Tippeur,
		<br /><br />
		Cette version Béta de Tipkin est entièrement gratuite.<br />
		La page paiement en ligne sera accessible très prochainement. 
		<br /><br />
		L'équipe TIPKIN.
		<br /><br />
	</div>
</div>
{/block}
