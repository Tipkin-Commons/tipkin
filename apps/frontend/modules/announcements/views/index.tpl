{extends file="layout.tpl"}

{block name=page_title}Mes annonces{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('.tab-content').css('minHeight','400px');
	
	$('#add-announce').click(function(){
		location.href = '/announcements/new';
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
<div class="col_9">
	<div class="col_12">
		<button class="small" id="add-announce" style="float: right;">
			<span class="icon small" data-icon="+"></span>
			Nouvelle annonce
		</button>
		<h4>Mes annonces</h4>
		{$message}
		<ul class="tabs">
			<li>
				<a href="#drafts" id="tab-drafts">Brouillon(s)</a>
			</li>
			<li>
				<a href="#pending" id="tab-pending">En attente(s)</a>
			</li>
			<li>
				<a href="#validated" id="tab-validated">Validée(s)</a>
			</li>
			<li>
				<a href="#archived" id="tab-archived">Archive(s)</a>
			</li>
			<li style="float: right;">
				<a href="#refused" id="tab-refused">Refusé(s)</a>
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
							<a href="/view/member/announce-{$announce->getLink($announce->id())}" class="tooltip window" title="Apperçu <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="a"></span>
							</a>
						</li>
						<li>
							<a href="/announcements/edit/{$announce->id()}" class="tooltip" title="Modifier <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="7"></span>
							</a>
						</li>
						<li>
							<a id="" href="/announcements/delete/{$announce->id()}" class="lightbox tooltip" title="Supprimer <em>{$announce->getTitle()}</em>">
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
									{if $mainPhoto != Announcement::IMAGE_DEFAULT}
										{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
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
			{if $countDraft == 0}
				Aucune annonce disponible
			{/if}
		</div>
		
		<div id="pending" class="tab-content">
			{assign var="countPending" value=0}
			{foreach from=$announces item=announce}
				{if $announce->getStateId()==AnnouncementStates::STATE_PENDING}
				{assign var="countPending" value=$countPending+1}
				<div class="col_6 visible announce-card">
					<ul class="button-bar"">
						<li>
							<a class="tooltip" title="En attente de validation">
								<span class="icon small blue" data-icon="t"></span>
							</a>
						</li>
					</ul>
					
					<ul class="button-bar" style="float: right;">
						<li>
							<a href="/view/member/announce-{$announce->getLink($announce->id())}" class="tooltip window" title="Apperçu <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="a"></span>
							</a>
						</li>
						<li>
							<a href="/announcements/edit/{$announce->id()}" class="tooltip" title="Modifier <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="7"></span>
							</a>
						</li>
						<li>
							<a id="" href="/announcements/delete/{$announce->id()}" class="lightbox tooltip" title="Supprimer <em>{$announce->getTitle()}</em>">
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
									{if $mainPhoto != Announcement::IMAGE_DEFAULT}
										{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
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
			{if $countPending == 0}
				Aucune annonce disponible
			{/if}
		</div>
		
		<div id="validated" class="tab-content">
			{assign var="countValidated" value=0}
			{assign var="countValidatedOnline" value=0}
			{assign var="countValidatedOffline" value=0}
			{foreach from=$announces item=announce}
				{if $announce->getStateId()==AnnouncementStates::STATE_VALIDATED && $announce->getRefAnnouncementId() == null}
				{assign var="countValidated" value=$countValidated+1}
					
				{if $announce->getIsPublished()}
					{assign var="countValidatedOnline" value=$countValidatedOnline+1}
				{else}
					{assign var="countValidatedOffline" value=$countValidatedOffline+1}
				{/if}
				
				<div class="col_6 visible  announce-card">
					<div class="publication-info">
						Publié du {date($announce->getPublicationDate())|date_format:"%d/%m/%Y"} au {date($announce->getEndPublicationDate())|date_format:"%d/%m/%Y"}
					</div>
				
					<ul class="button-bar" style="float: right;">
						<li>
							<a href="/announcements/edit/{$announce->id()}" class="tooltip" title="Modifier <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="7"></span>
							</a>
						</li>
						<li>
							<a href="/view/member/announce-{$announce->getLink($announce->id())}" class="tooltip" title="Apperçu <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="a"></span>
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
									{if $mainPhoto != Announcement::IMAGE_DEFAULT}
										{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
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
			{if $countValidated == 0}
				Aucune annonce disponible
			{/if}
		</div>
		
		<div id="archived" class="tab-content">
			{assign var="countArchived" value=0}
			{foreach from=$announces item=announce}
				{if $announce->getStateId()==AnnouncementStates::STATE_ARCHIVED && $announce->getRefAnnouncementId() == null}
				{assign var="countArchived" value=$countArchived+1}
				<div class="col_6 visible announce-card">
					<ul class="button-bar" style="float: right;">
						<li>
							<a href="/view/member/announce-{$announce->getLink($announce->id())}" class="tooltip window" title="Apperçu <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="a"></span>
							</a>
						</li>
						<li>
							<a href="/announcements/edit/{$announce->id()}" class="tooltip" title="Modifier <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="7"></span>
							</a>
						</li>
						<li>
							<a id="" href="/announcements/unarchive/{$announce->id()}" class="tooltip" title="Republier <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon=")"></span>
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
									{if $mainPhoto != Announcement::IMAGE_DEFAULT}
										{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
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
		
		<div id="refused" class="tab-content">
			{assign var="countRefused" value=0}
			{foreach from=$announces item=announce}
				{if $announce->getStateId()==AnnouncementStates::STATE_REFUSED}
				{assign var="countRefused" value=$countRefused+1}
				<div class="col_6 visible announce-card">
					<ul class="button-bar">
						<li>
							<a href="/announcements/admin-comment/{$announce->id()}" class="tooltip lightbox" title="Lire le commentaire de l'administrateur">
								<span class="icon small red" data-icon="i"></span>
							</a>
						</li>
					</ul>
					<ul class="button-bar" style="float: right;">
						<li>
							<a href="/view/member/announce-{$announce->getLink($announce->id())}" class="tooltip window" title="Apperçu <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="a"></span>
							</a>
						</li>
						<li>
							<a href="/announcements/edit/{$announce->id()}" class="tooltip" title="Modifier <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="7"></span>
							</a>
						</li>
						<li>
							<a id="" href="/announcements/delete/{$announce->id()}" class="lightbox tooltip" title="Supprimer <em>{$announce->getTitle()}</em>">
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
									{if $mainPhoto != Announcement::IMAGE_DEFAULT}
										{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
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
			{if $countRefused == 0}
				Aucune annonce disponible
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
						Brouillon(s)
					</td>
					<td class="right">
						{$countDraft}
					</td>
				</tr>
				<tr>
					<td>
						En attente(s)
					</td>
					<td class="right">
						{$countPending}
					</td>
				</tr>
				<tr>
					<td>
						Validée(s)
					</td>
					<td class="right">
						{$countValidated}
					</td>
				</tr>
<!--				<tr style="font-size: 12px;">-->
<!--					<td>-->
<!--						<span class="icon small gray" data-icon="}"></span> Publié(s)-->
<!--						<br />-->
<!--						<span class="icon small gray" data-icon="}"></span> Hors ligne-->
<!--					</td>-->
<!--					<td class="right">-->
<!--						{$countValidatedOnline}<br />-->
<!--						{$countValidatedOffline}-->
<!--					</td>-->
<!--				</tr>-->
				<tr>
					<td>
						Refusé(s)
					</td>
					<td class="right">
						{$countRefused}
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
						{$countArchived + $countValidated + $countDraft + $countPending + $countRefused}
					</th>
				</tr>
			</tbody>
		</table>
	</div>
</div>
{/block}