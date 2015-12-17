{extends file="layout.tpl"}

{block name=page_title prepend}{$user->getUsername()}{/block}

{block name=meta_desc}{$user->getUsername()} est inscrit(e) sur TIPKIN, la plateforme plateforme communautaire pour tout louer{/block}

{block name=page_content}
<script type="text/javascript" src="/js/jquery.jcarousel.min.js"></script>
{literal}
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
});
//-->
</script>
{/literal}
<a id="popup-connect" class="lightbox" href="/popup-connect/return-url={$smarty.server.REQUEST_URI}"></a>
<div class="col_8">
	<img alt="{$usersManager->get($user->id())->getUsername()}" src="{$profile->getAvatar()}" class="align-left"/>
	<h1 style="line-height: 5px; margin-top: 5px;">
	{if $isAuthenticate == 'true' && $currentUser->getRoleId() != Role::ROLE_MEMBER_PRO && $contactsManager->isContactExistById($currentUser->id(), $user->id())}
		{$profile->getLastname()|upper} {$profile->getFirstname()|capitalize}
	{else}
		{$usersManager->get($user->id())->getUsername()}
	{/if}
	</h1>
	<div class="localization-carousel" style="margin-left: 15px;">
		{$mainAddress->getCity()}
	</div>
	<br />
	<div style="margin-top: -15px;">
		<div style="float: right; margin-left: 10px">
			<a href="http://maps.google.com/maps?q={$mainAddress->getZipCode()},FRANCE" target="_blank">
				<img alt="" style="border: solid 1px #CCCCCC;" src="http://maps.googleapis.com/maps/api/staticmap?markers={$mainAddress->getZipCode()},FRANCE&zoom=10&size=100x100&maptype=roadmap&sensor=false"/>
			</a>
		</div>
		<div style="text-align: justify;">
			{$profile->getDescription()}
		</div>
		<div class="clearfix"></div>
		<div class="mark-large">
			{assign var="userMark" value=$feedbacksManager->getMarkByUserId($user->id())}
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
		<div>
			{if count($listCurrencyUsed > 0) && !empty($listCurrencyUsed[0])}
			<fieldset>
			<legend>Je partage aussi avec une ou plusieurs monnaies locales : </legend>
				{foreach from=$listCurrencyUsed item=currencyUse}
					{assign var="alternateCurrency" value=$alternateCurrencyManager->get($currencyUse)}
					<label  class="alternate-currency-label" for="currency-{$alternateCurrency->id()}"> 
						<span>{$alternateCurrency->getName()}</span>
						<img src="{AlternateCurrency::$CURRENCY_PATH}{$alternateCurrency->getImageUrl()}" alt="{$alternateCurrency->getName()}" title="{$alternateCurrency->getName()}"/>
					</label>
				{/foreach}
			</fieldset>
			{/if}
		</div>
	</div>
	<br />
	<div class="clearfix"></div>
	<div class="feedbacks-count">
		{assign var="listOfFeedbacks" value=$feedbacksManager->getByUserId($user->id())}
		<span>{count($listOfFeedbacks)}</span> Commentaire(s)
	</div>
	<div class="tips-count">
		{assign var="countTips" value=0}
		{foreach from=$reservationsManager->getByUserId($user->id()) item=reservation}
			{if $reservation->getStateId() == PaiementStates::VALIDATED}
				{assign var="countTips" value=$countTips+1}
			{/if}
		{/foreach}
		<span>{$countTips}</span> Tip(s)
	</div>
	<div class="clearfix"></div>
	{if  $isAuthenticate == 'true' && $currentUser->getRoleId() != Role::ROLE_MEMBER_PRO && $currentUser->id() != $user->id() || $isAuthenticate == 'false'}
		<div class="view-user-relation">
			<div class="first">
				Vous et 
				{if $isAuthenticate == 'true' && $currentUser->getRoleId() != Role::ROLE_MEMBER_PRO && $contactsManager->isContactExistById($currentUser->id(), $user->id())}
					{$profile->getLastname()|upper} {$profile->getFirstname()|capitalize}
				{else}
					{$usersManager->get($user->id())->getUsername()}
				{/if}
			</div>
			<div class="info">
				{if $isAuthenticate == 'true' && $currentUser->getRoleId() != Role::ROLE_MEMBER_PRO && $currentUser->id() != $user->id()}
					{if $isContactRequestExist}
						<button style="color: #777" class="small" disabled="disabled">Demande d'ajout en attente...</button>
					{elseif $isContactExist}
						{assign var="contact" value="{$contactsManager->getByCouple($currentUser->id(), $user->id())}"}
						Ce tippeur fait parti de vos contacts : 
						<img alt="" src="{ContactGroups::getImageSrc($contact->getContactGroupId())}" align="absmiddle"/>
						{ContactGroups::getLabel($contact->getContactGroupId())}
						<br /><br />
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
	{elseif $isAuthenticate == 'true' && $currentUser->getRoleId() == Role::ROLE_MEMBER_PRO}
		<div class="view-user-relation">
			<div class="first">Vous et {$usersManager->get($user->id())->getUsername()}</div>
			<div class="info">Section indisponible pour les membres pro.</div>
		</div>
	{else}
		<div class="view-user-relation">
			<div class="first">
				Mes opérations en cours
			</div>
			<div>
				<div class="tips-icon">&nbsp;</div>
				<div class="tips-info">
					Demandes d'emprunt d'objet en attente : <span style="color:red;">{$nbReservations}</span>
					<br /><br />
					Demandes de prêt d'objet en attente : <span style="color:red;">{$nbLocations}</span>
				</div>
				<div class="contact-icon"></div>
				<div class="contact-info">
					<br />
					Demandes d'entrée dans votre tipkinship en attente : <span style="color:red;">{$nbWait}</span>
				</div>
			</div>
		</div>
	{/if}
	<div class="view-user-contact-stat">
		{assign var="countFamily" value="0"}
		{assign var="countFriends" value="0"}
		{assign var="countNeighbors" value="0"}
		{assign var="countTippeurs" value="0"}
		{foreach from=$contactsManager->getListOf($user->id()) item=contact}
			{if $contact->getContactGroupId() == ContactGroups::FAMILY}
				{assign var="countFamily" value=$countFamily+1}
			{/if}
			{if $contact->getContactGroupId() == ContactGroups::FRIENDS}
				{assign var="countFriends" value=$countFriends+1}
			{/if}
			{if $contact->getContactGroupId() == ContactGroups::NEIGHBORS}
				{assign var="countNeighbors" value=$countNeighbors+1}
			{/if}
			{if $contact->getContactGroupId() == ContactGroups::TIPPEURS}
				{assign var="countTippeurs" value=$countTippeurs+1}
			{/if}
		{/foreach}
		<table>
			<tr>
				<td>
					<img alt="" src="{ContactGroups::getImageSrc(ContactGroups::FAMILY)}" align="absmiddle" />
					<span class="contact-group">Famille</span>
				</td>
				<td>
					<span class="percent">{$countFamily}</span>
				</td>
			</tr>
			<tr>
				<td>
					<img alt="" src="{ContactGroups::getImageSrc(ContactGroups::FRIENDS)}" align="absmiddle" />
					<span class="contact-group">Amis</span>
				</td>
				<td>
					<span class="percent">{$countFriends}</span>
				</td>
			</tr>
			<tr>
				<td>
					<img alt="" src="{ContactGroups::getImageSrc(ContactGroups::NEIGHBORS)}" align="absmiddle" />
					<span class="contact-group">Voisins</span>
				</td>
				<td>
					<span class="percent">{$countNeighbors}</span>
				</td>
			</tr>
			<tr>
				<td>
					<img alt="" src="{ContactGroups::getImageSrc(ContactGroups::TIPPEURS)}" align="absmiddle" />
					<span class="contact-group">Tippeurs</span>
				</td>
				<td>
					<span class="percent">{$countTippeurs}</span>
				</td>
			</tr>
		</table>
	</div>
	<div class="clearfix"></div>
	<h5 style="font-size: 1.5em;">Tipkin-ship</h5>
	
	<div class="contact-count">
		<span>{count($contactsManager->getListOf($user->id()))}</span> Contacts
	</div>
	<div class="clearfix"></div>
	<div class="list-of-contact">
		{foreach from=$contactsManager->getListOf($user->id()) item=contact}
			{if $contact->getUserId1() == $user->id()}
				{assign var="contactId" value=$contact->getUserId2()}
			{else}
				{assign var="contactId" value=$contact->getUserId1()}
			{/if}
			<div class="contact">
				<div class="user-photo">
					<a href="/users/member/{$contactId}">
						<img alt="" class="tooltip" src="{$profilesManager->getByUserId($contactId)->getAvatar()}" title="Voir son profil"/>
					</a>
				</div>
				<div class="contact-username">{$usersManager->get($contactId)->getUsername()}</div>
				{assign var="contactAddress" value=$addressesManager->get($profilesManager->getByUserId($contactId)->getMainAddressId())}
				<div class="localize">
					{if strlen({$contactAddress->getCity()}) > 15 }
						{substr({$contactAddress->getCity()} , 0, 15)}...
					{else}
						{$contactAddress->getCity()} 
					{/if}
					{substr($contactAddress->getZipCode(), 0, 2)}
				</div>
				<div class="contact-group">
					<img alt="" src="{ContactGroups::getImageSrc($contact->getContactGroupId())}"/>
					<a title="Voir son profil" class="plus" href="/users/member/{$contactId}"></a>
				</div>
			</div>
		{/foreach}
	</div>
</div>
<div class="col_4">
	<div>
		<h5 class="announce-other-products">Les produits de {$user->getUsername()} :</h5>
		<hr />
		<div id="carousel" class="jcarousel-skin-tango">
			<ul>
				{assign var="countOtherAnnounces" value=0}
				{foreach from=$listOfUserAnnonces item=userAnnounce}
					{if $userAnnounce->getStateId() == AnnouncementStates::STATE_VALIDATED}
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
				Aucun produit.
			{/if}
		</div>
	</div>
	<div class="clearfix" style="margin-top: 25px;"></div>
	<div class="feedbacks">
		<div class="count-items">
				<div class="number">{count($listOfFeedbacks)}</div>
				<div class="text">Commentaire(s) sur ce tippeur</div>
		</div>
		<div class="clearfix"></div>
		{assign var="countFeedbacks" value=0}
		{foreach from=$listOfFeedbacks item=feedback}
			{if $countFeedbacks < 4}
				<div class="item">
					<div class="user">
						<div class="avatar">
							<img alt="" src="{$profilesManager->getByUserId($feedback->getUserAuthorId())->getAvatar()}" width="50"/>
						</div>
						<div class="username">
							{assign var="username" value=$usersManager->get($feedback->getUserAuthorId())->getUsername()}
							{if strlen($username) > 11 }
								{substr($username , 0, 9)}...
							{else}
								{$username}
							{/if}
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
							{if strlen({$feedback->getComment()}) > 75 }
								{substr({$feedback->getComment()} , 0, 72)}...
							{else}
								{$feedback->getComment()}
							{/if}
						</div>
					</div>
				</div>
			{/if}
			{assign var="countFeedbacks" value=$countFeedbacks+1}	
		{/foreach}
		{if $countFeedbacks == 0}
			<div class="right">
			Aucun feedback disponible
			</div>
		{/if}
	</div>
	{if $countFeedbacks > 0}
		<div class="all-comments">
			<a href="/feedback/user/{$user->id()}" class="lightbox">Voir tous les commentaires</a>
		</div>
	{/if}
</div>
{/block}