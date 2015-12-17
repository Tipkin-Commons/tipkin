{extends file="layout.tpl"}

{block name=page_title}TIPKIN -  Location d'objet entre particulier, une plateforme communautaire pour tout emprunter{/block}
{block name=meta_desc}Je possède. Tu empruntes. Nous partageons !{/block}

{block name=page_content}

<script type="text/javascript" src="/js/jquery.jcarousel.min.js"></script>
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#form-search').submit(function(){
		location.href = '/search/' + 
						'page=/' +  
						'region=/' + 
						'department=/' + 
						'category=/' + 
						'subcategory=/' + 
						'zipcode=/' +
						'filter=' + $('#search-text').val() ;
		return false;
	});

	$('#carousel').jcarousel({
				visible: 4
			});
});
//-->
</script>
{/literal}
<h1 class="center home-title">Nous mettons nos biens en commun</h1>
<p class="center home-sous-titre">Empruntez n'importe quel objet à un tippeur contre un pourboire. <a href="/faq">En savoir plus</a></p>

<div class="col_1">
</div>
<div class="col_10 visible center">
	<div class="col_4">
		<ul class="menu">
			<li class="home">
				<a id="home-localize">Localisez-vous</a>
				<ul style="height: 300px; overflow: auto; width: 240px;">
					{assign var="countRegions" value=0}
					{assign var="countRegionsCols" value=0}
					{foreach from=$regions item=region}
						{assign var="countRegions" value=$countRegions+1}
						{if $countRegions > 8}
						{assign var="countRegionsCols" value=$countRegionsCols+1}
						{assign var="countRegions" value=0}
						{/if}
						<li class="home" style="">
							<a href="/search/page=/region={$region->id()}/department=/category=/subcategory=/zipcode=/filter=">
								{htmlentities($region->getName())}
							</a>
							
						</li>
					{/foreach}
				</ul>
			</li>
		</ul>
	</div>
	<div class="col_4">
		<ul class="menu">
			<li class="home">
				<a id="home-categories" href="/search/page=/region=/department=/category=all/subcategory=/zipcode=/filter=">Catégories</a>
				<ul>
				{foreach from=$categories item=category}
					{if $category->getIsRoot()}
						<li class="home">
							<a href="/search/page=/region=/department=/category={$category->id()}/subcategory=/zipcode=/filter=">
								{$category->getName()}
							</a>
							
						</li>
					{/if}
				{/foreach}
				</ul>
			</li>
		</ul>
	</div>
	<div>
		<form method="post" action="" name="form-search" id="form-search">
			<input style="margin:0" class="inline" type="text" name="search-text" id="search-text" placeholder="Recherchez un objet"/>
			<button id="search" name="search" class="green"><span class="icon" data-icon="s"></span></button>
		</form>
	</div>
</div>
<div class="col_1">
</div>
<div class="col_12 center" style="margin-top: -25px;">
	<div id="carousel" class="jcarousel-skin-tango col_12">
		<ul>
			{foreach from=$listOfAnnouncements item=announce}
			{if $announce->getPhotoMain() == Announcement::IMAGE_DEFAULT}
				{assign var="mainPhoto" value="{$announce->getPhotoMain()}"}
			{else}
				{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
			{/if}
			{assign var="userPhoto" value="{$profilesManager->getByUserId($announce->getUserId())->getAvatar()}"}
				<li>
					<div class="col_12 member announce-carousel" id="announce-{$announce->id()}">
						<div class="center photo-carousel">
							{if $isAdminAuthenticate == 'true'}
								{assign var="carrousel" value=$carrouselsManager->getByAnnounceId($announce->id())}
								<a href="/admin/carrousel/delete/{$carrousel->id()}" style="float: right;">Supprimer du carrousel</a>	
							{/if}
							<a href="/view/member/announce-{$announce->getLink($announce->id())}">
								<img alt="{$announce->getTitle()}" src="{$mainPhoto}"/>
							</a>
						</div>
						<div class="clearfix"></div>
						<div class="photo-user-carousel">
							<div class="user-photo">
								<img alt="{$userManager->get($announce->getUserId())->getUsername()}" src="{$userPhoto}" class="align-left"/>
							</div>
							<div class="nom-produit-carousel tooltip" title="{if strlen($announce->getTitle()) > 50 }{$announce->getTitle()}{/if}" >
								{$announce->getTitle()}
							</div>
						</div>
						<div class="localization-carousel tooltip" title="{if strlen($announce->getCity()) > 15 }{$announce->getCity()} {$announce->getDepartmentId()}{/if}">
							{if strlen({$announce->getCity()}) > 15 }
								{substr({$announce->getCity()} , 0, 15)|upper}...
							{else}
								{$announce->getCity()|upper} 
							{/if} 
							{$announce->getDepartmentId()}
						</div>
						<div class="clearfix"></div>
						<div class="left price-carousel">
							<div class="price">
								{$announce->getPricePublic()} € /j
							</div>
							{if $isAuthenticate == 'true'}
								{if $contactsManager->isContactExistById($currentUser->id(), $announce->getUserId())}
									{assign var="contact" value=$contactsManager->getByCouple($currentUser->id(), $announce->getUserId())}
									<div class="contact-picto {ContactGroups::getClass($contact->getContactGroupId())}"></div>
								{else}
									<div class="contact-picto none"></div>
								{/if}
							{else}
								<div class="contact-picto all"></div>
							{/if}
							{if $announce->getTips() != ''}
								<div class="carrousel-tip" title="Astuce disponible"></div>
							{/if}
							<a class="plus-carousel" title="Détail de l'annonce..." href="/view/member/announce-{$announce->getLink($announce->id())}"><span>+</span></a>	
						</div>
					</div>
				</li>
			{/foreach}
		</ul>
	</div>
</div>
<div class="col_12" style="margin-top: -25px;">
<h4 class="center">TIP = Astuce, pourboire <span style="margin-left:20px"></span> KIN = Nos proches</h4>
</div>
<div class="col_2">
</div>
<div class="col_8 visible center home-message">
	<span>Besoin d'un objet particulier, mettez une alerte auprès des tippeurs. <a href="/contact">Contactez-nous</a></span>
</div>
{/block}
