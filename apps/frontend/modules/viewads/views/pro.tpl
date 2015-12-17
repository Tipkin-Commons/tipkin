{extends file="layout.tpl"}

{block name=page_title prepend}
	{$announce->getTitle()} 
	a partir de
	{$announce->getPricePublic()}&euro;
	
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
	if($('#carousel li').length > 4)
	{
		$('#carousel').jcarousel({
			vertical: true,
			visible: 3,
			scroll: 3
		});
	}
	
	$('.other-user-announce').click(function(){
		location.href = '/view/pro/' + $(this).attr('id');
	});
});
//-->
</script>
{/literal}
<div  class="breadcrumb">
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
<div class="col_8 visible announce-left">
	<div style="float: right;">
		<div style="float: left;">
			<img alt="" width="20" src="{ProfilePro::AVATAR_DEFAULT_PRO}" align="top">
		</div>
		<div style="float: left; margin-left: 3px; margin-top: 4px;">Professionnel</div>
	</div>
	<h1>{$announce->getTitle()}</h1>
	<div style="margin-left: 15px; margin-top: -10px; height: 25px;">
		<a name="fb_share" type="button" share_url="http://{$smarty.server.SERVER_NAME}{$smarty.server.REQUEST_URI}"></a>
	</div>
	<div class="localization-carousel" style="margin-left: 15px;">
		{$announce->getCity()} {$announce->getDepartmentId()}
	</div>
	<br />
	{if $announce->getPhotoMain() != AnnouncementPro::IMAGE_DEFAULT}
	<div class="col_9">
		<div class="gallery-main">
			<img alt="{$announce->getTitle()}" style="width: 400px" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{$announce->getPhotoMain()}"/>
		</div>
	</div>
	{/if}
	<div class="col_3">
		<div class="gallery">
			{if $announce->getPhotoMain() != AnnouncementPro::IMAGE_DEFAULT}
			<a href="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{$announce->getPhotoMain()}">
				<img alt="{$announce->getTitle()}" class="v-card" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"/>
			</a>
			{/if}
			{if $announce->getPhotoOption1() != null}
			<a href="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{$announce->getPhotoOption1()}">
				<img alt="{$announce->getTitle()}" class="v-card" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoOption1()}"/>
			</a>
			{/if}
			{if $announce->getPhotoOption2() != null}
			<a href="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{$announce->getPhotoOption2()}">
				<img alt="{$announce->getTitle()}" class="v-card" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoOption2()}"/>
			</a>
			{/if}
		</div>
	</div>
	<div class="col_12 tell-me">
		<h5>Raconte-moi ton objet</h5>
		<hr/>
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
		<h5  class="consommable">Consommable/Livraison :</h5>
		<hr />
		<p>
			{$announce->getRawMaterial()}
		</p>
		{/if}
	</div>
</div>
<div class="col_4 announce-right">
	<div class="col_12 profile">
		<img alt="{$usersManager->get($announce->getUserId())->getUsername()}" src="{$profile->getAvatar()}" class="announce-profile" />
		<div class="announce-profile-name">
			{if $isAuthenticate == 'true'}
				{$profile->getCompanyName()}
			{else}
				{$usersManager->get($announce->getUserId())->getUsername()}
			{/if}
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
		{if $isAuthenticate == 'true'}
			<a class="watch-profile" href="/users/pro/{$profile->getUserId()}">Voir son profil</a>
			{if $profile->getWebsite() != ''}
				<a class="watch-website" href="http://{$profile->getWebsite()}" target="_blank">Voir son site web</a>
			{/if}
		{else}
			<a class="watch-profile lightbox" href="/popup-connect/return-url={$smarty.server.REQUEST_URI}">Voir son profil</a>
		{/if}
		</div>
	</div>
	<hr />
	<div class="col_12 prices">
		<table>
			<tr>
				<td>
					<label class="inline">Prix à partir de :</label>
				</td>
				<td>
					{$announce->getPricePublic()} €
				</td>
			</tr>
		</table>
	</div>
	<hr />
	<div class="col_12 ">
		<h5 class="announce-other-products">Les autres produits de {$profile->getCompanyName()}</h5>
		<hr/>
		<div id="carousel" class="jcarousel-skin-tango">
			<ul>
				{assign var="countOtherAnnounces" value=0}
				{foreach from=$listOfUserAnnonces item=userAnnounce}
					{if $userAnnounce->id() != $announce->id() && $userAnnounce->getStateId() == AnnouncementStates::STATE_VALIDATED && $userAnnounce->getIsPublished()}
						{assign var="countOtherAnnounces" value=countOtherAnnounces+1}
						<li>
							<div id="announce-{$userAnnounce->id()}" class="other-user-announce">
								<div class="other-user-announce-div-image">
								{if $userAnnounce->getPhotoMain() == AnnouncementPro::IMAGE_DEFAULT}
									<img alt="{$userAnnounce->getTitle()}" class="other-user-announce-image" src="{$userAnnounce->getPhotoMain()}"/>
								{else}
									<img alt="{$userAnnounce->getTitle()}" class="other-user-announce-image" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$userAnnounce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$userAnnounce->getPhotoMain()}"/>
								{/if}
								</div>
								<span class="other-user-announce-title">
									{$userAnnounce->getTitle()}
								</span>
								<a class="plus-other-products" href="/view/pro/announce-{$userAnnounce->id()}"><span>+</span></a>
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