{extends file="layout.tpl"}

{block name=page_title prepend}
	A louer {$announce->getTitle()} 
	
	{$announce->getPricePublic()}&euro;
	 
	{if $announce->getIsFullDayPrice()}
		par jour
	{else}
		par demi-journée
	{/if}
	
	{if $announce->getDepartmentId() != 0}
		- {$announce->getCity()} {htmlentities($departmentsManager->get($announce->getDepartmentId())->getName())} {$announce->getDepartmentId()}
	{/if}
{/block}

{block name=meta_desc}{strip_tags($announce->getDescription())}{/block}

{block name=page_content}
{literal}
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
        type="text/javascript">
</script>
<script type="text/javascript" src="/js/jquery.jcarousel.min.js"></script>
<script type="text/javascript">
<!--
$(function(){
	$('.other-user-announce').click(function(){
		location.href = '/view/member/' + $(this).attr('id') + '/' + $(this).attr('smarturl');
	});

	//{/literal}
	//{if $isAuthenticate == 'false'}
	//{literal}
	$('#contacts').click(function(){
		$('#popup-connect').click();
		return false;
	});
	//{/literal}
	//{else}
	//{literal}
	$('#contacts').click(function(){
		$('#link-contacts').click();
	});
	//{/literal}
	//{/if}
	//{literal}

	if($('#carousel li').length > 4)
	{
		$('#carousel').jcarousel({
			vertical: true,
			visible: 3,
			scroll: 3
		});
	}
	
	$('#currency-selection a.currency-selection').click(function(){

		var isDefault = $(this).attr('data-value') == 'default';
		var rate = $(this).attr('data-rate');
		var currencyId = $(this).attr('data-value');
		
		$(this).siblings().removeClass('selected');
		$(this).addClass('selected');
		
		$('#currency-id').val(currencyId);
		
		if(!isDefault) {
			var imgPath = $(this).attr('data-img');
			var title = $(this).attr('title');
			
			$('span.currency').html('<img src="' + imgPath + '" title="' + title + '" />')
			
			$('#alternate-currency-card').css('display','block');
		}
		else {
			$('span.currency').html('&euro;');
			$('#alternate-currency-card').css('display','none');
		}
		
		$('.default-price').each(function(){
			var targetClass = $(this).attr('target-class');
			var value = $(this).val() * rate;
			
			$('.' + targetClass).html(value);
		});
		
		return false;
	});
});
//-->
</script>
{/literal}
<div class="breadcrumb">
	<ul class="breadcrumbs alt1">
		<li><a href="/">Accueil</a></li>
		{foreach from=$categories item=category}
			{if $category->id() == $announce->getCategoryId()}
				<li><a href="/search/page=/region=/department=/category={$announce->getCategoryId()}/subcategory=/zipcode=/filter=">{$category->getName()}</a></li>
			{/if}
		{/foreach}
		{foreach from=$categories item=category}
			{if $category->id() == $announce->getSubCategoryId()}
				<li><a href="/search/page=/region=/department=/category={$announce->getCategoryId()}/subcategory={$announce->getSubCategoryId()}/zipcode=/filter=">{$category->getName()}</a></li>
			{/if}
		{/foreach}	
	</ul>
</div>
<div style="margin: 0px 10px;">
	{$message}
</div>
<div class="col_8 announce-left">
	{if $isAdminAuthenticate == 'true'}
		<div>
			<form class="admin" action="" method="post">
				<input type="hidden" name="announce-id" value="{$announce->id()}">
				<input type="hidden" name="user-id" value="{$announce->getUserId()}">
				<button name="admin-edit">Modifier cette annonce</button>
			</form>
			<div style="margin: 13px;">
				{if $carrouselsManager->isAnnounceExist($announce->id())}
					{assign var="carrousel" value=$carrouselsManager->getByAnnounceId($announce->id())}
					<a class="btn red" style="padding: 3px;" href="/admin/carrousel/delete/{$carrousel->id()}">Supprimer du carrousel</a>
				{else}
					<a class="btn orange" style="padding: 3px;" href="/admin/carrousel/add/{$announce->id()}">Ajouter au carrousel</a>
				{/if}
			</div>
		</div>
	{/if}
	<h1>
		{$announce->getTitle()} 
	</h1>
	{if $announce->getStateId() == AnnouncementStates::STATE_VALIDATED} 
		<div style="margin-left: 15px; margin-top: -10px; height: 25px;">
			<a name="fb_share" type="button" share_url="http://{$smarty.server.SERVER_NAME}{$smarty.server.REQUEST_URI}"></a>
		</div>
	{/if}
	<div class="localization-carousel" style="margin-left: 15px;">
		{$announce->getCity()} {$announce->getDepartmentId()}
	</div>
	<br />
	<div class="announce-gallery">
		<div class="col_9">
			{if $announce->getPhotoMain() != Announcement::IMAGE_DEFAULT}
			<div class="gallery-main">
				<img alt="{$announce->getTitle()}" style="width: 400px" src="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{$announce->getPhotoMain()}"/>
			</div>
			{/if}
		</div>
		<div class="col_3">
			<div class="gallery">
				{if $announce->getPhotoMain() != Announcement::IMAGE_DEFAULT}
				<a href="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{$announce->getPhotoMain()}">
					<img alt="{$announce->getTitle()}" class="v-card" src="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"/>
				</a>
				{/if}
				{if $announce->getPhotoOption1() != null}
				<a href="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{$announce->getPhotoOption1()}">
					<img alt="{$announce->getTitle()}" class="v-card" src="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoOption1()}"/>
				</a>
				{/if}
				{if $announce->getPhotoOption2() != null}
				<a href="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{$announce->getPhotoOption2()}">
					<img alt="{$announce->getTitle()}" class="v-card" src="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoOption2()}"/>
				</a>
				{/if}
			</div>
		</div>
	</div>
	<div class="col_12 tell-me">
		<h5>Raconte-moi ton objet</h5>
		<hr />
		<div style="float: right; text-align: right; width: 165px;">
			<a href="http://maps.google.com/maps?q={$announce->getZipCode()},FRANCE" target="_blank">
				<img alt="" style="border: solid 1px #CCCCCC;" src="http://maps.googleapis.com/maps/api/staticmap?markers={$announce->getZipCode()},FRANCE&zoom=10&size=150x150&maptype=roadmap&sensor=false"/>
			</a>
		</div>
		<div style="text-align: justify;">
			{nl2br($announce->getDescription())}
		</div>
		
	</div>
	<div class="col_12 tell-me">
		{if $announce->getRawMaterial() != ''}
			<h5 class="consommable">Consommable / Livraison :</h5>
			<hr />
			<p>
				{$announce->getRawMaterial()}
			</p>
		{/if}
		{if $announce->getTips() != ''}
			<p class="tip">
				{$announce->getTips()}
			</p>
		{/if}
	</div>
	<div class="clearfix"></div>
	<div class="feedbacks col_12">
		{assign var="listOfFeedbacks" value=$feedbacksManager->getByAnnounceId($announce->id())}
		<div class="count-items">
				<div class="number">{count($listOfFeedbacks)}</div>
				<div class="text">Commentaire(s) sur ce produit</div>
		</div>
		{assign var="countFeedbacks" value=0}
		{foreach from=$listOfFeedbacks item=feedback}
			{if $countFeedbacks < 2}
				<div class="item">
					<div class="user">
						<div class="avatar">
							<img alt="" src="{$profilesManager->getByUserId($feedback->getUserAuthorId())->getAvatar()}" width="75"/>
						</div>
						<div class="username">
							{assign var="username" value=$usersManager->get($feedback->getUserAuthorId())->getUsername()}
							{$username}
						</div>
					</div>
					<div class="feedback-item">
						<div class="mark">
							{assign var="mark" value=$feedback->getMark()}
							{while $mark > 0}
								<img alt="" src="/images/star-on.png"/>
								{assign var="mark" value=$mark-1}	
							{/while}
							{assign var="unmark" value=5-$feedback->getMark()}
							{while $unmark > 0}
								<img alt="" src="/images/star-off.png"/>
								{assign var="unmark" value=$unmark-1}
							{/while}
							<div class="creation-date">
								{date_format(date_create($feedback->getCreationDate()),'d/m/Y')}
							</div>
						</div>
						<div class="comment">
							{$feedback->getComment()} 
						</div>
					</div>
				</div>
			{/if}
			{assign var="countFeedbacks" value=$countFeedbacks+1}	
		{/foreach}
	</div>
	{if $countFeedbacks > 0}
		<div class="all-comments-announce">
			<a href="/feedback/announce/{$announce->id()}" class="lightbox">Voir tous les commentaires</a>
		</div>
	{/if}
	{if $countFeedbacks == 0}
		<div class="all-comments-announce">
			Aucun feedback disponible
		</div>
	{/if}
	<div class="clearfix"></div>
	{if $isAuthenticate == 'true' && $currentUser->getRoleId() == Role::ROLE_MEMBER  && $currentUser->id() != $announce->getUserId()}
		<div class="right col_12">
			<a href="/moderate/announce/{$announce->id()}">Signaler cette annonce</a>
		</div>
	{/if}
</div>
<div class="col_4 announce-right">
	<div class="col_12 profile">
		
		<img alt="{$usersManager->get($announce->getUserId())->getUsername()}" src="{$profile->getAvatar()}" class="announce-profile" alt="" />
		<div class="announce-profile-name">
			{if $isAuthenticate == 'true' && $currentUser->getRoleId() != Role::ROLE_MEMBER_PRO && $contactsManager->isContactExistById($currentUser->id(), $announce->getUserId())}
				{$profile->getLastname()|upper} {$profile->getFirstname()|capitalize}
			{else}
				{$usersManager->get($announce->getUserId())->getUsername()}
			{/if}
		</div>
		<div class="mark">
			{assign var="userMark" value=$feedbacksManager->getMarkByUserId($announce->getUserId())}
			{assign var="mark" value=$userMark}
			{while $mark > 0}
				<img alt="" src="/images/star-on.png"/>
				{assign var="mark" value=$mark-1}	
			{/while}
			{assign var="unmark" value=5-$userMark}
			{while $unmark > 0}
				<img alt="" src="/images/star-off.png"/>
				{assign var="unmark" value=$unmark-1}
			{/while}
		</div>
		<div class="announce-profile-localization">
			{$mainAddress->getCity()}
		</div>
		<div style="text-align: justify;">
			{if strlen($profile->getDescription()) > 100 }
				{substr($profile->getDescription(), 0, 100)}...
			{else}
				{$profile->getDescription()}
			{/if}
		</div>
		<br />
		<div class="announce-profile-links">
			{if $isAuthenticate == 'true' && $currentUser->id() == $profile->getUserId()}
				<a class="watch-profile" href="/users/member/{$profile->getUserId()}">Voir mon profil</a>
			{else}
				<a class="watch-profile" href="/users/member/{$profile->getUserId()}">Voir son profil</a>
			{/if}
			{if $isAuthenticate == 'true' && $currentUser->getRoleId() != Role::ROLE_MEMBER_PRO && $currentUser->id() != $user->id()}
				{if $isContactRequestExist}
					<button style="color: #777" class="small" disabled="disabled">Demande d'ajout en attente...</button>
				{elseif $isContactExist}
					{assign var="contact" value="{$contactsManager->getByCouple($currentUser->id(), $user->id())}"}
					<button class="small" id="contacts">Supprimer de ma Tipkin-ship</button>
					<a id="link-contacts" href="/contacts/delete/{$contact->id()}" class="lightbox"></a>
				{else}
					<button class="add-to-contact" id="contacts">Ajouter à ma Tipkin-ship</button>
					<a id="link-contacts" href="/contacts/add/{$user->id()}" class="lightbox"></a>
				{/if}
			{else}
				{if  $isAuthenticate == 'false' || $currentUser->id() != $user->id()}
					<button class="add-to-contact" id="contacts">Ajouter à ma Tipkin-ship</button>
				{/if}
			{/if}
		</div>
	</div>
	{if $canUseAlternateCurrency && count($listCurrencyUsed > 0) && !empty($listCurrencyUsed[0])}
		<div class="col_12 currency-select">
			<span>Monnaie(s) disponible(s) :</span>
			<div id="currency-selection">
				<a href="#" data-value="default" data-rate="1" class="currency-selection selected">&euro;</a>
				{foreach from=$listCurrencyUsed item=currencyUse}
					{assign var="alternateCurrency" value=$alternateCurrencyManager->get($currencyUse)}
					<a href="#" data-value="{$alternateCurrency->id()}" data-rate="{$alternateCurrency->getRate()}" title="{$alternateCurrency->getName()}" data-img="{AlternateCurrency::$CURRENCY_PATH}{$alternateCurrency->getImageUrl()}" class="currency-selection tooltip"><img src="{AlternateCurrency::$CURRENCY_PATH}{$alternateCurrency->getImageUrl()}" /></a>
				{/foreach}
			</div>
			<h5 id="alternate-currency-card" style="display:none;">N'oubliez pas de présenter votre carte d'adhérent à la monnaie alternative</h5>
		</div>
	{/if}
	<hr />
	{if $isAuthenticate == 'true' && $currentUser->getRoleId() != Role::ROLE_MEMBER_PRO}
		{foreach from=$listOfContacts item=contact}
			{if $currentUser->id() != $announce->getUserId() 
					&& ($contact->getUserId1() == $currentUser->id() || $contact->getUserId2() == $currentUser->id())}
				<div class="col_12 tipkinship">
					Ce tippeur fait parti de vos contacts :
					<br /> 
					<img alt="" src="{ContactGroups::getImageSrc($contact->getContactGroupId())}" align="absmiddle"/> {ContactGroups::getLabel($contact->getContactGroupId())}
				</div>
				<hr />
			{/if}
		{/foreach}
	{/if}
	<div class="col_12 prices">
		{assign var="isContactPrice" value=0}
		
		{if $isAuthenticate == 'true' && $currentUser->getRoleId() != Role::ROLE_MEMBER_PRO}
			{foreach from=$listOfContacts item=contact}
				{if $currentUser->id() != $announce->getUserId() 
						&& ($contact->getUserId1() == $currentUser->id() || $contact->getUserId2() == $currentUser->id())}
					{foreach from=$listOfPrices item=price}
						{if $contact->getContactGroupId() == $price->getContactGroupId() && $price->getIsActive()}
							{assign var="isContactPrice" value=1}
							{assign var="contactGroupId" value=$contact->getContactGroupId()}
							
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
										<span class="halfday-price">{$price->getHalfDay()}</span> <span class="currency">€</span> <br /><label class="inline"><span>par 1/2 journée</span></label>
									</td>
							{else}
								<tr>
									<td>
										<span class="day-price">{$price->getDay()}</span> <span class="currency">€</span> <br /><label class="inline"><span>par journée</span></label>
									</td>
							{/if}
							{if $announce->getCaution() > 0}
									<td>
										<span class="caution-price">{$announce->getCaution()}</span> <span class="currency">€</span> <br /><label class="inline">Caution</label>
									</td>
								 </tr>
							{else}
									<td>
									</td>
								</tr>
							{/if}
							
							{if !$announce->getIsFullDayPrice()}
									<tr>
										<td>
											<span class="day-price">{$price->getDay()}</span> <span class="currency">€</span> <br /><label class="inline"><span>par journée</span></label>
										</td>
										<td>
											<span class="weekend-price">{$price->getWeekEnd()}</span> <span class="currency">€</span> <br /><label class="inline"><span>par week-end</span></label>
										</td>
									</tr>
								{else}
									<tr>
										<td>
											<span class="weekend-price">{$price->getWeekEnd()}</span> <span class="currency">€</span> <br /><label class="inline"><span>par week-end</span></label>
										</td>
										<td></td>
									</tr>
								{/if}
								<tr>
									<td>
										<span class="week-price">{$price->getWeek()}</span> <span class="currency">€</span> <br /><label class="inline"><span>par semaine</span></label>
									</td>
									<td>
										<span class="fortnight-price">{$price->getFortnight()}</span> <span class="currency">€</span> <br /><label class="inline"><span><span class="currency">€</span> par quinzaine</span></label>
									</td>
								</tr>
							</table>
						{/if}
					{/foreach}
				{/if} 
			{/foreach}
		{/if}
		{if $isContactPrice == 0}
			{foreach from=$listOfPrices item=price}
				{if $price->getContactGroupId() == ContactGroups::USERS}
					{assign var="contactGroupId" value=ContactGroups::USERS}
					
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
								<span class="halfday-price">{$price->getHalfDay()}</span> <span class="currency">€</span> <br /><label class="inline">par 1/2 journée</label>
							</td>
					{else}
						<tr>
							<td>
								<span class="day-price">{$price->getDay()}</span> <span class="currency">€</span> <br /><label class="inline">par journée</label>
							</td>
					{/if}
					{if $announce->getCaution() > 0}
							<td>
								<span class="caution-price">{$announce->getCaution()}</span> <span class="currency">€</span> <br /><label class="inline">Caution</label>
							</td>
						 </tr>
					{else}
							<td></td>
						</tr>
					{/if}
					
					{if !$announce->getIsFullDayPrice()}
						<tr>
							<td>
								<span class="day-price">{$price->getDay()}</span> <span class="currency">€</span> <br /><label class="inline"><span>par journée</span></label>
							</td>
							<td>
								<span class="weekend-price">{$price->getWeekEnd()}</span> <span class="currency">€</span> <br /><label class="inline"><span>par week-end</span></label>
							</td>
						</tr>
					{else}
						<tr>
							<td>
								<span class="weekend-price">{$price->getWeekEnd()}</span> <span class="currency">€</span> <br /><label class="inline"><span>par week-end</span></label>
							</td>
							<td></td>
						</tr>
					{/if}
						
						<tr>
							<td>
								<span class="week-price">{$price->getWeek()}</span> <span class="currency">€</span> <br /><label class="inline"><span>par semaine</span></label>
							</td>
							<td>
								<span class="fortnight-price">{$price->getFortnight()}</span> <span class="currency">€</span> <br /><label class="inline"><span>par quinzaine</span></label>
							</td>
						</tr>
					</table>
				{/if}
			{/foreach}
		{/if}
	</div>
	<hr />
	{if $announce->getStateId() == AnnouncementStates::STATE_VALIDATED} 
	<div class="col_12 visible announce-calendar">
		{include file='_formCalendar.tpl'}
	</div>
	{/if}
	<div>
		<h5 class="announce-other-products">Les autres produits de {$user->getUsername()}</h5>
		<hr/>
		<div id="carousel" class="jcarousel-skin-tango">
			<ul>
				{assign var="countOtherAnnounces" value=0}
				{foreach from=$listOfUserAnnonces item=userAnnounce}
					{if $userAnnounce->id() != $announce->id() && $userAnnounce->getStateId() == AnnouncementStates::STATE_VALIDATED}
						{assign var="countOtherAnnounces" value=countOtherAnnounces+1}
						<li>
							<div id="announce-{$userAnnounce->id()}" smarturl="{$userAnnounce->getLink($userAnnounce->id())}" class="other-user-announce">
								<div class="other-user-announce-div-image">
								{if $userAnnounce->getPhotoMain() == Announcement::IMAGE_DEFAULT}
									<img alt="{$userAnnounce->getTitle()}" class="other-user-announce-image" src="{$userAnnounce->getPhotoMain()}"/>
								{else}
									<img alt="{$userAnnounce->getTitle()}" class="other-user-announce-image" src="{Announcement::ANNOUNCEMENT_DIRECTORY}{$userAnnounce->id()}/{Announcement::THUMBNAILS_PREFIX}{$userAnnounce->getPhotoMain()}"/>
								{/if}
								</div>
								<span class="other-user-announce-title">
									{$userAnnounce->getTitle()}
								</span>
								<a class="plus-other-products" href="/view/member/announce-{$userAnnounce->getLink($userAnnounce->id())}"><span>+</span></a>
							</div>
						</li>
					{/if}
				{/foreach}
			</ul>
			{if $countOtherAnnounces == 0}
				Aucun autre produit.
			{/if}		
		</div>
	</div>
</div>
{/block}