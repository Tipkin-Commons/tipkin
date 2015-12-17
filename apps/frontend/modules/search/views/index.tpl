{extends file="layout.tpl"}

{block name=page_title prepend}
	{assign var="titleEnd" value=""}
	{if $filter->getCategoryId() != 0}
		{assign var="titleEnd" value=$titleEnd|cat:' - '|cat:$categoriesManager->get($filter->getCategoryId())->getName()}
	{/if}
	{if $filter->getSubCategoryId() != 0}
		{assign var="titleEnd" value=$titleEnd|cat:' - '|cat:$categoriesManager->get($filter->getSubCategoryId())->getName()}
	{/if}
	{if $filter->getRegionId() != 0}
		{assign var="titleEnd" value=$titleEnd|cat:' - '|cat:htmlentities($regionsManager->get($filter->getRegionId())->getName())}
	{/if}
	{if $filter->getDepartmentId() != 0}
		{assign var="titleEnd" value=$titleEnd|cat:' - '|cat:htmlentities($departmentsManager->get($filter->getDepartmentId())->getName())}
	{/if}
	
	Location entre particuliers {$titleEnd} 
{/block}

{block name=meta_desc}Location d'objet entre particulier, une plateforme communautaire pour tout louer{/block}

{block name=page_content}
<script type="text/javascript" src="/js/jquery.pagination.js"></script>
<link rel="stylesheet" type="text/css" href="/js/jquery.pagination.css" media="all" />
<link rel="stylesheet" type="text/css" href="/js/jquery.tree.css" media="all" />
<script type="text/javascript" src="/js/jquery.tree.js"></script>
{literal}
<script type="text/javascript">
<!--

$(function(){
	initTreeCategories();

	initLocalizeFilterLabel();
	
	$('#submit-filters').click(function(){
		$('#department').change();
		
		location.href = '/search/' + 
						'page=/' +  
						'region=' + $('#region').val() + '/' + 
						'department=' + $('#department').val() + '/' + 
						'category={/literal}{$filter->getCategoryId()}{literal}/' + 
						'subcategory={/literal}{$filter->getSubCategoryId()}{literal}/' + 
						'zipcode=' + $('#zip-code').val() + '/' + 
						'filter={/literal}{$filter->getFilterText()}{literal}' ;
	});

	$('#region').change(function(){
		var idRegion = $(this).val();
		$('#department').find('option').remove();
		
		$('#department').append('<option value="all" selected="selected">Tous les départements</option>');
		$('#department').append('<option disabled="disabled">-----------------------------------</option>');
		
		$('#departments-list input').each(function(){
			var root = $(this).attr('class');
			if(root == idRegion || idRegion=='all')
			{	
				if($(this).val() == '{/literal}{$filter->getDepartmentId()}{literal}' && idRegion != 'all')
					$('#department').append('<option value="' + $(this).val() + '" selected="selected">' + $(this).attr('name') + '</option>');
				else
					$('#department').append('<option value="' + $(this).val() + '">' + $(this).attr('name') + '</option>');
			}
		});
	}).change();
	
	$('#community-check').change(function(){
		if($(this).is(':checked'))
			$('#community-filter').val('1');
		else
			$('#community-filter').val('');
		
		$('#search').click();
	});

	$('#department').change(function(){
		var regionId = $('#departments-list input[value="' + $(this).val() + '"]');
		if(regionId.length > 0 && $('#region').val() != regionId.attr('class'))
			$('#region').val(regionId.attr('class'));
	}).change();

	$('#announce-member-type').change(function(){
		switch ($(this).val()) {
			case 'all':
			$('.member.announce-carousel').show();
			$('.pro.announce-carousel').show();
			initPagination('');
			break;
			
			case 'pro':
			$('.member.announce-carousel').hide();
			$('.pro.announce-carousel').show();
			initPagination('.pro');
			break;
			
			case 'member':
			$('.member.announce-carousel').show();
			$('.pro.announce-carousel').hide();
			initPagination('.member');
			break;
			
			default: 
			break;
		}
	}).change();
});

function pageselectCallback(page_index, jq){
	var items_per_page = 12;
	
	filter = $('#announce-member-type').val();
	if(filter == 'all')
		filter = '';
	else
		filter = '.'+filter;
	
    var contentList = new Array();
    for(i = 0; i < items_per_page; i++)
    {
    	var index = page_index * items_per_page + i;
        if(index < $('#hidden-results div.announce-carousel' + filter).length)
        {
	     	new_content = $('#hidden-results div.announce-carousel'+filter+':eq('+index+')').clone();
	     	contentList.push(new_content);
        }
    }
    $('#results').empty();

    for(i = 0; i < contentList.length; i++)
    {
    	$('#results').append(contentList[i]);
    }

    $('#results div[title]').addClass('tooltip');

    refreshTooltips();
    
    return false;
}

function refreshTooltips()
{
	// Standard tooltip
	$('.tooltip, .tooltip-top, .tooltip-bottom, .tooltip-right, .tooltip-left').each(function(){
		// variables 
		var tpos = 'top';
		var content = $(this).attr('title');
		var dataContent = $(this).attr('data-content');
		var keepAlive = false;
		var action = 'hover';
		
		// position
		if($(this).hasClass('tooltip-top')) 	{ tpos = 'top'; 	}
		if($(this).hasClass('tooltip-right')) 	{ tpos = 'right'; 	}
		if($(this).hasClass('tooltip-bottom')) 	{ tpos = 'bottom'; 	}
		if($(this).hasClass('tooltip-left')) 	{ tpos = 'left'; 	}
		
		// content
		$('.tooltip-content').removeClass('hide').wrap('<div class="hide"></div>');
		if(dataContent) { content = $(dataContent).html(); keepAlive = true; }
		
		// action (hover or click) defaults to hover
		if($(this).attr('data-action') == 'click') { action = 'click'; }
		
		// tooltip
		$(this).attr('title','')
		.tipTip({defaultPosition: tpos, content: content, keepAlive: keepAlive, activation: action});
	});
}

function initPagination(filter) {
    // count entries inside the hidden content
    var num_entries = $('#hidden-results div.announce-carousel' + filter).length;
    // Create content inside pagination element
    $("#pagination").pagination(num_entries, {
        callback: pageselectCallback,
        items_per_page:12,
        prev_text:'précédent',
        next_text:'suivant'
    });
 }

function initLocalizeFilterLabel()
{
	var filterRegion = $('#region option[value="' + $('#region').val() + '"]').text();
	var filterDepartment = $('#department option[value="' + $('#department').val() + '"]').text();
	var filterZipCode = $('#zip-code').val();
	if(filterZipCode != '')
		filterZipCode = ', Code postal : ' + filterZipCode ;
	if(filterDepartment != 'Tous les départements')
		filterDepartment = filterDepartment.substring(5, filterDepartment.length);
	$('#localize-filter').text(filterRegion + ', ' + filterDepartment + filterZipCode);
}

function initTreeCategories()
{
	var dataCategories = [
		{
			label: '<a id="category-all" href="/search/' +
					'page=/' +   
					'region={/literal}{$filter->getRegionId()}{literal}/' + 
					'department={/literal}{$filter->getDepartmentId()}{literal}/' + 
					'category=all/' + 
					'subcategory=/' + 
					'zipcode={/literal}{$filter->getZipCode()}{literal}/' +
					// {/literal}
					// {if $filter->getInCommunity() != null}
					// {literal}
					'community=/' +
					// {/literal}
					// {/if}
					// {literal}
					'filter=' + 
					'">' +
					'Toutes' +
					'</a>'
		},
//	    {/literal}
//		{foreach from=$categories item=category}
//		{if $category->getIsRoot()}
//		{literal}
		{
			label: '<a id="category-{/literal}{$category->id()}{literal}" href="/search/' +
							'page=/' +   
							'region={/literal}{$filter->getRegionId()}{literal}/' + 
							'department={/literal}{$filter->getDepartmentId()}{literal}/' + 
							'category={/literal}{$category->id()}{literal}/' + 
							'subcategory=/' + 
							'zipcode={/literal}{$filter->getZipCode()}{literal}/' +
							// {/literal}
							// {if $filter->getInCommunity() != null}
							// {literal}
							'community=/' +
							// {/literal}
							// {/if}
							// {literal}
							'filter=' + 
							'">' +
						'{/literal}{$category->getName()}{literal}' +
					'</a>',
			children : [
//			{/literal}
//			{foreach from=$categories item=subCategory}
//				{if $subCategory->getParentCategoryId() == $category->id()}
//	    		{literal}
				{ label: '<a id="subcategory-{/literal}{$subCategory->id()}{literal}" href="/search/' +
							'page=/' +  
							'region={/literal}{$filter->getRegionId()}{literal}/' + 
							'department={/literal}{$filter->getDepartmentId()}{literal}/' + 
							'category={/literal}{$category->id()}{literal}/' + 
							'subcategory={/literal}{$subCategory->id()}{literal}/' + 
							'zipcode={/literal}{$filter->getZipCode()}{literal}/' +
							// {/literal}
							// {if $filter->getInCommunity() != null}
							// {literal}
							'community=/' +
							// {/literal}
							// {/if}
							// {literal}
							'filter=' + 
							'">' + 
								'{/literal}{$subCategory->getName()}{literal}' +
							'</a>'
				},
//	    		{/literal}
//				{/if}
//			{/foreach}
//			{literal}
			]
		},
//	    {/literal}
//		{/if}
//		{/foreach}
//		{literal}        	
	];

	$('#tree-categories').tree({
			data: dataCategories,
			saveState: 'tree-categories'
	});

	if($('#category-{/literal}{$filter->getCategoryId()}{literal}').length > 0)
	{
		$('#category-{/literal}{$filter->getCategoryId()}{literal}').parent().parent().find('a').click();
		$('#category-{/literal}{$filter->getCategoryId()}{literal}').parent().addClass('selected');
	}
	if($('#subcategory-{/literal}{$filter->getSubCategoryId()}{literal}').length > 0)
	{
		$('#subcategory-{/literal}{$filter->getSubCategoryId()}{literal}').parent().addClass('selected');
	}
}

//-->
</script>
{/literal}
<div class="col_3" style="float: left;">
	<div class="col_12 visible">
		<h5>FILTRER LES ANNONCES</h5>
		<label for="announce-member-type">Afficher :</label> 
		<select id="announce-member-type">
			<option value="all">Toutes les annonces</option>
			<option value="pro">Pro uniquement</option>
			<option value="member">Particulier uniquement</option>
		</select>
		{if $isAuthenticate == 'true'}
			<div class="clearfix"></div>
			{if $filter->getInCommunity() != null}
				<input checked="checked" type="checkbox" value="1" id="community-check" style="display: block; float: left;"/>
			{else}
				<input type="checkbox" value="1" id="community-check" style="display: block; float: left;"/>
			{/if}
			<label for="community-check" style="display:block; float:left; width: auto; max-width: 120px; margin-top: -3px; margin-left: 5px;">Ma communauté seulement</label>
		{/if}
	</div>
	<div class="col_12 visible">
		<h5>CATEGORIES</h5>
		<div id="tree-categories"></div>
	</div>
</div>

<div class="col_9" style="float: right;">
	<form id="form-search" method="post" style="display: inline;">
		<div class="col_8">
			<input class="border" name="filter" id="filter" type="text" maxlength="80" placeholder='Exemple : "appareil à fondue"' value="{urldecode($filter->getFilterText())}"/>
			<div class="localization-carousel" style="display: inline;"></div><label class="inline"><span id="localize-filter">Toulouse</span> <a href="#filters" class="lightbox">Modifier...</a></label>
		</div>
		<div class="col_4">
				<button name="search" id="search" class="small search-button"><span class="icon small" data-icon="s"></span></button>
				<input type="hidden" name="region" value="{$filter->getRegionId()}"/>
				<input type="hidden" name="department" value="{$filter->getDepartmentId()}"/>
				<input type="hidden" name="category" value="{$filter->getCategoryId()}"/>
				<input type="hidden" name="subcategory" value="{$filter->getSubCategoryId()}"/>
				<input type="hidden" name="previous-filter-text" value="{urldecode($filter->getFilterText())}"/>
				{if $isAuthenticate == 'true'}
					<input type="hidden" name="community-filter" id="community-filter" value="{$filter->getInCommunity()}" />
				{/if}
			 &nbsp;
			
		</div>
	</form>
	<hr />
	<div id="results"></div>
	<div class="clearfix"></div>
	<div id="pagination" style="float: right;">
		This content will be replaced when pagination inits.
	</div>
	<div id="hidden-results" style="display: none;">
	{foreach from=$announcementsPro item=announce}
	{if $announce->getPhotoMain() == AnnouncementPro::IMAGE_DEFAULT}
		{assign var="mainPhoto" value="{$announce->getPhotoMain()}"}
	{else}
		{assign var="mainPhoto" value="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}	
	{/if}
	{assign var="userPhoto" value="{$profilesProManager->getByUserId($announce->getUserId())->getAvatar()}"}
		<div class="col_3 visible pro announce-carousel" id="announce-{$announce->id()}">
			<div title="Voir cette annonce"  class="center photo-search">
				<a href="/view/pro/announce-{$announce->id()}">
					<img alt="{$announce->getTitle()}" src="{$mainPhoto}"/>
				</a>
			</div>
			<div class="clearfix"></div>
			<div class="photo-user-search">
				{if $isAuthenticate == 'true'}
					<div class="user-photo" title="Voir son profil">
						<a href="/users/pro/{$announce->getUserId()}">
							<img alt="{$usersManager->get($announce->getUserId())->getUsername()}" src="{$userPhoto}" class="align-left"/>
						</a>
					</div>
				{else}
					<div class="user-photo" title="Voir son profil">
						<a href="/login">
							<img alt="{$usersManager->get($announce->getUserId())->getUsername()}" src="{$userPhoto}" class="align-left"/>
						</a>
					</div>
				{/if}
				<div class="nom-produit-carousel" title="{if strlen($announce->getTitle()) > 20 }{$announce->getTitle()}{/if}" >
					{if strlen($announce->getTitle()) > 20 }
						{substr($announce->getTitle() , 0, 20)}...
					{else}
						{$announce->getTitle()}
					{/if}
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="localization-search" title="{if strlen($announce->getCity()) > 15 }{$announce->getCity()} {$announce->getDepartmentId()}{/if}">
				{if strlen({$announce->getCity()}) > 15 }
					{substr({$announce->getCity()} , 0, 15)}...
				{else}
					{$announce->getCity()} 
				{/if}
				{$announce->getDepartmentId()}
			</div>
			<div class="clearfix"></div>
			<div class="left price-carousel">
				<div class="price">
					{$announce->getPricePublic()} € /j
				</div>
				<a title="Détail de l'annonce..." class="plus-search" href="/view/pro/announce-{$announce->id()}"><span>+</span></a>
			</div>
		</div>
		{/foreach}
	
	{foreach from=$announcements item=announce}
		<!-- Obsolète -->
		{if $announce->getRefAnnouncementId() == null}
			{if $announce->getPhotoMain() == Announcement::IMAGE_DEFAULT}
				{assign var="mainPhoto" value="{$announce->getPhotoMain()}"}
			{else}
				{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
			{/if}
			{assign var="userPhoto" value="{$profilesManager->getByUserId($announce->getUserId())->getAvatar()}"}
			<div class="col_3 member announce-carousel" id="announce-{$announce->id()}">
				<div title="Voir cette annonce" class="center  photo-search">
					<a href="/view/member/announce-{$announce->getLink($announce->id())}">
						<img alt="{$announce->getTitle()}" src="{$mainPhoto}"/>
					</a>
				</div>
				<div class="clearfix"></div>
				<div class="photo-user-search">
					<div title="Voir son profil" class="user-photo">
						<a href="/users/member/{$announce->getUserId()}">
							<img alt="{$usersManager->get($announce->getUserId())->getUsername()}" src="{$userPhoto}" class="align-left"/>
						</a>
					</div>
					<div class="nom-produit-carousel" title="{if strlen($announce->getTitle()) > 20 }{$announce->getTitle()}{/if}" >
						{if strlen($announce->getTitle()) > 20 }
							{substr($announce->getTitle() , 0, 20)}...
						{else}
							{$announce->getTitle()}
						{/if}
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="localization-search" title="{if strlen($announce->getCity()) > 15 }{$announce->getCity()} {$announce->getDepartmentId()}{/if}">
					{if strlen({$announce->getCity()}) > 15 }
						{substr({$announce->getCity()} , 0, 15)}...
					{else}
						{$announce->getCity()} 
					{/if}
					{$announce->getDepartmentId()}
				</div>
				<div class="clearfix"></div>
				<div class="left price-carousel">
					<div class="price">
						{$announce->getPricePublic()} € /j
					</div>
					<a title="Détail de l'annonce..." class="plus-search" href="/view/member/announce-{$announce->getLink($announce->id())}"><span>+</span></a>
				</div>
			</div>
		{/if}
	{/foreach}
	</div>
</div>
<div class="hide" id="modal-popup">
	<div id="filters" style="width: 600px;">
		<div class="col_12 visible" >
			<div>
				<div id="tab-localize">
					<label>Choisissez une localisation pour votre recherche.</label>
					<div class="col_6">
						<label for="region">Sélectionnez une région :</label>
						<select name="region" id="region">
							<option value="all">Toutes les régions</option>
							<option disabled="disabled">-----------------------------------</option>
							{foreach from=$regions item=region}
							{if $region->id() == $filter->getRegionId()}
								<option value="{$region->id()}" selected="selected">{htmlentities($region->getName())}</option>
							{else}
								<option value="{$region->id()}">{htmlentities($region->getName())}</option>
							{/if}
							{/foreach}
						</select>
					</div>
					<div class="col_6">
						<label for="department">Sélectionnez un département :</label>
						<select name="department" id="department">
							<option value="all">Tous les départements</option>
							<option disabled="disabled">-----------------------------------</option>
							{foreach from=$departments item=department}
								{if $department->id() == $filter->getDepartmentId()}
								<option value="{$department->idChar()}" selected="selected">{$department->idChar()} - {htmlentities($department->getName())}</option>
								{else}
								<option value="{$department->idChar()}">{$department->idChar()} - {htmlentities($department->getName())}</option>
								{/if}
							{/foreach}
						</select>
						<div id="departments-list">
							{foreach from=$departments item=department}
							<input type="hidden" class="{$department->getRegionId()}" name="{$department->idChar()} - {htmlentities($department->getName())}" value="{$department->idChar()}"/>
							{/foreach}
						</div>
					</div>
					<div class="col_6">
					<label>Code Postal : </label>
					<input type="text" placeholder="Tous" name="zip-code" id="zip-code" value="{$filter->getZipCode()}" maxlength="5"/>
					</div>
				</div>
			</div>
			<div class="right">
				<button class="small" id="submit-filters">Terminer</button>
			</div>
		</div>
	</div>
</div>
{/block}