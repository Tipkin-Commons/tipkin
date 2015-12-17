{extends file="layout.tpl"}

{block name=page_title}Ma Tipkin-ship{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	//{if isset($contactGroup)}
	$('#tab-{/literal}{$contactGroup}{literal}').click();
	//{/if}

	$('.tab-content').css('minHeight','400px');
});

//-->
</script>
{/literal}
<div class="col_9">
	<div class="col_12">
		<h4>Ma Tipkin-ship</h4>
		{$message}
		<ul class="tabs">
			<li>
				<a href="#family" id="tab-family"><img alt="" src="/images/family.png" width="20px;" align="absmiddle" /> Famille</a>
			</li>
			<li>
				<a href="#friends" id="tab-friends"><img alt="" src="/images/friends.png" width="20px;" align="absmiddle" /> Amis</a>
			</li>
			<li>
				<a href="#neighbors" id="tab-neighbors"><img alt="" src="/images/neighbors.png" width="20px;" align="absmiddle" />Voisins</a>
			</li>
			<li style="float: right;">
				<a href="#wait" id="tab-wait"><label style="line-height: 20px;" class="inline">{$nbWait} demande(s) en attente</label></a>
			</li>
			<li>
				<a href="#tippeurs" id="tab-tippeurs"><img alt="" src="/images/tippeurs.png" width="20px;" align="absmiddle" /> Tippeurs</a>
			</li>
		</ul>
		
		<div id="family" class="tab-content">
			{assign var="countContact" value=0}
			{foreach from=$contacts item=contact}
				{if $contact->getContactGroupId() == ContactGroups::FAMILY}
					{assign var="countContact" value=$countContact+1}
					{if $contact->getUserId1() == $currentUser->id()}
						{assign var="profile" value="{$profilesManager->getByUserId($contact->getUserId2())}"}
					{else}
						{assign var="profile" value="{$profilesManager->getByUserId($contact->getUserId1())}"}
					{/if}
					{assign var="mainAddress" value="{$addressesManager->get($profile->getMainAddressId())}"}
					<div class="col_3 visible contact">
						<div class="user-contact">
							<a href="/users/member/{$profile->getUserId()}">
								<img alt="image de profil" src="{$profile->getAvatar()}"/>
							</a>
						</div>
						<a href="/users/member/{$profile->getUserId()}">{$profile->getLastname()|upper} {$profile->getFirstname()|capitalize}</a>
					</div>
				{/if}
			{/foreach}
			{if $countContact == 0}
				Aucun contact dans ce groupe.
			{/if}
		</div>
		
		<div id="friends" class="tab-content">
			{assign var="countContact" value=0}
			{foreach from=$contacts item=contact}
				{if $contact->getContactGroupId() == ContactGroups::FRIENDS}
					{assign var="countContact" value=$countContact+1}
					{if $contact->getUserId1() == $currentUser->id()}
						{assign var="profile" value="{$profilesManager->getByUserId($contact->getUserId2())}"}
					{else}
						{assign var="profile" value="{$profilesManager->getByUserId($contact->getUserId1())}"}
					{/if}
					{assign var="mainAddress" value="{$addressesManager->get($profile->getMainAddressId())}"}
					<div class="col_3 visible contact">
						<div class="user-contact">
							<a href="/users/member/{$profile->getUserId()}">
								<img alt="image de profil" src="{$profile->getAvatar()}"/>
							</a>
						</div>
						<a href="/users/member/{$profile->getUserId()}">{$profile->getLastname()|upper} {$profile->getFirstname()|capitalize}</a>
					</div>
				{/if}
			{/foreach}
			{if $countContact == 0}
				Aucun contact dans ce groupe.
			{/if}
		</div>
		
		<div id="neighbors" class="tab-content">
			{assign var="countContact" value=0}
			{foreach from=$contacts item=contact}
				{if $contact->getContactGroupId() == ContactGroups::NEIGHBORS}
					{assign var="countContact" value=$countContact+1}
					{if $contact->getUserId1() == $currentUser->id()}
						{assign var="profile" value="{$profilesManager->getByUserId($contact->getUserId2())}"}
					{else}
						{assign var="profile" value="{$profilesManager->getByUserId($contact->getUserId1())}"}
					{/if}
					{assign var="mainAddress" value="{$addressesManager->get($profile->getMainAddressId())}"}
					<div class="col_3 visible contact">
						<div class="user-contact">
							<a href="/users/member/{$profile->getUserId()}">
								<img alt="image de profil" src="{$profile->getAvatar()}"/>
							</a>
						</div>
						<a href="/users/member/{$profile->getUserId()}">{$profile->getLastname()|upper} {$profile->getFirstname()|capitalize}</a>
					</div>
				{/if}
			{/foreach}
			{if $countContact == 0}
				Aucun contact dans ce groupe.
			{/if}
		</div>
		
		<div id="tippeurs" class="tab-content">
			{assign var="countContact" value=0}
			{foreach from=$contacts item=contact}
				{if $contact->getContactGroupId() == ContactGroups::TIPPEURS}
					{assign var="countContact" value=$countContact+1}
					{if $contact->getUserId1() == $currentUser->id()}
						{assign var="profile" value="{$profilesManager->getByUserId($contact->getUserId2())}"}
					{else}
						{assign var="profile" value="{$profilesManager->getByUserId($contact->getUserId1())}"}
					{/if}
					{assign var="mainAddress" value="{$addressesManager->get($profile->getMainAddressId())}"}
					<div class="col_3 visible contact">
						<div class="user-contact">
							<a href="/users/member/{$profile->getUserId()}">
								<img alt="image de profil" src="{$profile->getAvatar()}"/>
							</a>
						</div>
						<a href="/users/member/{$profile->getUserId()}">{$profile->getLastname()|upper} {$profile->getFirstname()|capitalize}</a>
					</div>
				{/if}
			{/foreach}
			{if $countContact == 0}
				Aucun contact dans ce groupe.
			{/if}
		</div>
		
		<div id="wait" class="tab-content">
			{assign var="countContact" value=0}
			{foreach from=$contactRequests item=contactRequest}
				{assign var="countContact" value=$countContact+1}
				{assign var="profile" value="{$profilesManager->getByUserId($contactRequest->getUserIdFrom())}"}
					<div class="col_12 visible">
						<img alt="image de profil" src="{$profile->getAvatar()}" style="height: 100px;" class="align-left"/>
				
						<a href="/users/member/{$profile->getUserId()}">{$usersManager->get($contactRequest->getUserIdFrom())->getUsername()}</a>
						souhaite faire parti de vos contact en tant que <b><img alt="" src="{ContactGroups::getImageSrc({$contactRequest->getContactGroupId()})}" align="absmiddle"/> {ContactGroups::getLabel({$contactRequest->getContactGroupId()})}</b>.
						Que décidez vous ?
						<br /><br />
						<ul class="button-bar" style="float: right;">
							<li><a class="lightbox" href="/contacts/accept/{$contactRequest->id()}">Accepter</a></li>
							<li><a class="lightbox" href="/contacts/refuse/{$contactRequest->id()}">Refuser</a></li>
						</ul>
					</div>
			{/foreach}
			{if $countContact == 0}
				Aucune requète en attente.
			{/if}
		</div>
	</div>
</div>
<div class="col_3" style="margin-top: 26px;">
	{include file='_menuActionRight.tpl'}
</div>
<div class="col_3">
	<div class="col_12 visible">
		<table>
			<thead>
				<tr>
					<th colspan="2" class="center">
						STATISTIQUES
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						Tippeurs
					</td>
					<td class="right">
						{$nbTippeurs}
					</td>
				</tr>
				<tr>
					<td>
						Amis
					</td>
					<td class="right">
						{$nbFriends}
					</td>
				</tr>
				<tr>
					<td>
						Famille
					</td>
					<td class="right">
						{$nbFamily}
					</td>
				</tr>
				<tr>
					<td>
						Voisins
					</td>
					<td class="right">
						{$nbNeighbors}
					</td>
				</tr>
				<tr>
					<th>
						TOTAL
					</th>
					<th class="right">
						{$nbTippeurs + $nbFriends + $nbFamily + $nbNeighbors}
					</th>
				</tr>
			</tbody>
		</table>
	</div>
</div>
{/block}