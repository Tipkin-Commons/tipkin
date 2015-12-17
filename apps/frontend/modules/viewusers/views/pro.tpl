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
		location.href = '/view/pro/' + $(this).attr('id');
	});

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
<div class="col_8">
	<img alt="{$profile->getCompanyName()}" src="{$profile->getAvatar()}" class="align-left"/>
	<h1 style="line-height: 5px; margin-top: 5px; text-decoration: underline;">
	{if $profile->getWebsite() != ''}
		<a href="http://{$profile->getWebsite()}" target="_blank">{$profile->getCompanyName()}</a>
	{else}
		{$profile->getCompanyName()}
	{/if}
	</h1>
	<br />
	<h1 style="line-height: 5px; margin-top: 5px;">
	{$profile->getLastname()|upper} {$profile->getFirstname()|capitalize}
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
					{if $userAnnounce->getStateId() == AnnouncementStates::STATE_VALIDATED && $userAnnounce->getIsPublished()}
						{assign var="countOtherAnnounces" value=countOtherAnnounces+1}
						<li>
							<div id="announce-{$userAnnounce->id()}" class="other-user-announce">
								<div class="other-user-announce-div-image">
								{if $userAnnounce->getPhotoMain() == Announcement::IMAGE_DEFAULT}
									<img alt="{$userAnnounce->getTitle()}" class="other-user-announce-image" src="{$userAnnounce->getPhotoMain()}"/>
								{else}
									<img alt="{$userAnnounce->getTitle()}" class="other-user-announce-image" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$userAnnounce->id()}/{Announcement::THUMBNAILS_PREFIX}{$userAnnounce->getPhotoMain()}"/>
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
				Aucun produit.
			{/if}	
		</div>
	</div>
</div>
{/block}