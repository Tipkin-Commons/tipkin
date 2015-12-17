{extends file="layout.tpl"}

{block name=page_title}Mes annonces{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('.tab-content').css('minHeight','400px');
	
	$('#add-announce').click(function(){
		location.href = '/announcements-pro/new';
	});

	$('#tab-{/literal}{$state}{literal}').click();

	$('.window').click(function(){
		var urlToOpen = $(this).attr('href');
		var attributes = 'width=1000,height=800,reziable=no,toolbar=no,location=no,menubar=no,scrollbars=yes';
		
		window.open(urlToOpen,'Apperçu',attributes);
		return false;
	});
});
//-->
</script>
{/literal}
<div class="col_12">
	<h4>Mes annonces</h4>
	<div class="col_9">
		{$message}
		<ul class="tabs">
			<li>
				<a href="#drafts" id="tab-drafts">Brouillon(s)</a>
			</li>
			<li>
				<a href="#validated" id="tab-validated">Annonce(s)</a>
			</li>
			<li>
				<a href="#archived" id="tab-archived">Archive(s)</a>
			</li>
		</ul>
		
		<div id="drafts" class="tab-content">
			{assign var="countDraft" value=0}
			{foreach from=$announces item=announce}
				{if $announce->getStateId()==AnnouncementStates::STATE_DRAFT}
				{assign var="countDraft" value=$countDraft+1}
				<div class="col_6 visible announce-card">
					<ul class="button-bar" style="float: right;">
						<li>
							<a href="/announcements-pro/preview/{$announce->id()}" class="tooltip window" title="Apperçu <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="a"></span>
							</a>
						</li>
						<li>
							<a href="/announcements-pro/edit/{$announce->id()}" class="tooltip" title="Modifier <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="7"></span>
							</a>
						</li>
						<li>
							<a id="" href="/announcements-pro/delete/{$announce->id()}" class="lightbox tooltip" title="Supprimer <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="T"></span>
							</a>
						</li>
					</ul>
					<table>
						<thead>
							<tr>
								<th colspan="2">
									{$announce->getTitle()}
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									{assign var="mainPhoto" value="{$announce->getPhotoMain()}"}
									{if $mainPhoto != AnnouncementPro::IMAGE_DEFAULT}
										{assign var="mainPhoto" value="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
									{/if}
									<div class="v-card">
										<img alt="image de l'annonce" src="{$mainPhoto}"/>
									</div>
								</td>
								<td>
									<label style="margin-bottom: 8px;"><span>{$categoriesManager->get($announce->getSubCategoryId())->getName()}</span></label>
									<br />
									<label style="margin-bottom: 8px;">Prix : <span>{$announce->getPricePublic()} €</span></label>
									<br />
									<label style="margin-bottom: 8px;"><span class="icon small red" data-icon="&"></span>{$announce->getCity()}</label>
								</td>
							</tr>
						</tbody>
					</table>
					
				</div>
				{/if}
			{/foreach}
			{if $countDraft == 0}
				Aucune annonce disponible
			{/if}
		</div>
		
		<div id="validated" class="tab-content">
			{assign var="countValidated" value=0}
			{assign var="countValidatedOnline" value=0}
			{assign var="countValidatedOffline" value=0}
			{foreach from=$announces item=announce}
				{if $announce->getStateId()==AnnouncementStates::STATE_VALIDATED}
				{assign var="countValidated" value=$countValidated+1}
					
				{if $announce->getIsPublished()}
					{assign var="countValidatedOnline" value=$countValidatedOnline+1}
				{else}
					{assign var="countValidatedOffline" value=$countValidatedOffline+1}
				{/if}
				
				<div class="col_6 visible announce-card">
					<ul class="button-bar">
						{if $announce->getIsPublished()}
						<li>
							<a href="/announcements-pro/unpublish/{$announce->id()}" class="tooltip" title="Dépublier <em>{$announce->getTitle()}</em>">
								<span class="icon small green" data-icon="V"></span>
							</a>
						</li>
						{else}
						<li>
							<a href="/announcements-pro/publish/{$announce->id()}" class="tooltip" title="Publier <em>{$announce->getTitle()}</em>">
								<span class="icon small red" data-icon="V"></span>
							</a>
						</li>
						{/if}
					</ul>
				
					<ul class="button-bar" style="float: right;">
						<li>
							<a href="/announcements-pro/preview/{$announce->id()}" class="tooltip window" title="Apperçu <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="a"></span>
							</a>
						</li>
						<li>
							<a href="/announcements-pro/edit/{$announce->id()}" class="tooltip" title="Modifier <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="7"></span>
							</a>
						</li>
						<li>
							<a id="" href="/announcements-pro/archive/{$announce->id()}" class="lightbox tooltip" title="Archiver <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="("></span>
							</a>
						</li>
						<li>
							<a id="" href="/announcements-pro/delete/{$announce->id()}" class="lightbox tooltip" title="Supprimer <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="T"></span>
							</a>
						</li>
					</ul>
					<table>
						<thead>
							<tr>
								<th colspan="2">
									{$announce->getTitle()}
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									{assign var="mainPhoto" value="{$announce->getPhotoMain()}"}
									{if $mainPhoto != AnnouncementPro::IMAGE_DEFAULT}
										{assign var="mainPhoto" value="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
									{/if}
									<div class="v-card">
										<img alt="image de l'annonce" src="{$mainPhoto}"/>
									</div>
								</td>
								<td>
									<label style="margin-bottom: 8px;"><span>{$categoriesManager->get($announce->getCategoryId())->getName()}</span></label>
									<br />
									<label style="margin-bottom: 8px;">Prix : <span>{$announce->getPricePublic()} €</span></label>
									<br />
									<label style="margin-bottom: 8px;"><span class="icon small red" data-icon="&"></span>{$announce->getCity()}</label>
								</td>
							</tr>
						</tbody>
					</table>
					
				</div>
				{/if}
			{/foreach}
			{if $countValidated == 0}
				Aucune annonce disponible
			{/if}
		</div>
		
		<div id="archived" class="tab-content">
			{assign var="countArchived" value=0}
			{foreach from=$announces item=announce}
				{if $announce->getStateId()==AnnouncementStates::STATE_ARCHIVED}
				{assign var="countArchived" value=$countArchived+1}
				<div class="col_6 visible announce-card">
					<ul class="button-bar" style="float: right;">
						<li>
							<a href="/announcements-pro/preview/{$announce->id()}" class="tooltip window" title="Apperçu <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="a"></span>
							</a>
						</li>
						<li>
							<a href="/announcements-pro/edit/{$announce->id()}" class="tooltip" title="Modifier <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="7"></span>
							</a>
						</li>
						<li>
							<a id="" href="/announcements-pro/unarchive/{$announce->id()}" class="lightbox tooltip" title="Desarchiver <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon=")"></span>
							</a>
						</li>
						<li>
							<a id="" href="/announcements-pro/delete/{$announce->id()}" class="lightbox tooltip" title="Supprimer <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="T"></span>
							</a>
						</li>
					</ul>
					<table>
						<thead>
							<tr>
								<th colspan="2">
									{$announce->getTitle()}
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									{assign var="mainPhoto" value="{$announce->getPhotoMain()}"}
									{if $mainPhoto != AnnouncementPro::IMAGE_DEFAULT}
										{assign var="mainPhoto" value="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
									{/if}
									<div class="v-card">
										<img alt="image de l'annonce" src="{$mainPhoto}"/>
									</div>
								</td>
								<td>
									<label style="margin-bottom: 8px;"><span>{$categoriesManager->get($announce->getCategoryId())->getName()}</span></label>
									<br />
									<label style="margin-bottom: 8px;">Prix : <span>{$announce->getPricePublic()} €</span></label>
									<br />
									<label style="margin-bottom: 8px;"><span class="icon small red" data-icon="&"></span>{$announce->getCity()}</label>
								</td>
							</tr>
						</tbody>
					</table>
					
				</div>
				{/if}
			{/foreach}
			{if $countArchived == 0}
				Aucune annonce disponible
			{/if}
		</div>
	</div>
	<div class="col_3">
		<div class="col_12 center" style="margin-top: -20px;">
			<button class="small" id="add-announce">
				<span class="icon small" data-icon="+"></span>
				Nouvelle annonce
			</button>
		</div>
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
							Brouillon(s)
						</td>
						<td class="right">
							{$countDraft}
						</td>
					</tr>
					<tr>
						<td>
							Annonce(s)
						</td>
						<td class="right">
							{$countValidated}
						</td>
					</tr>
					<tr style="font-size: 12px;">
						<td>
							<span class="icon small gray" data-icon="}"></span> Publié(s)
							<br />
							<span class="icon small gray" data-icon="}"></span> Hors ligne
						</td>
						<td class="right">
							{$countValidatedOnline}
							<br />
							{$countValidatedOffline}
						</td>
					</tr>
					<tr>
						<td>
							Archive(s)
						</td>
						<td class="right">
							{$countArchived}
						</td>
					</tr>
					<tr>
						<th>
							TOTAL
						</th>
						<th class="right">
							{$countArchived + $countValidated + $countDraft}
						</th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
{/block}