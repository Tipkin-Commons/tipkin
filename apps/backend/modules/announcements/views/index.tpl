{extends file="layout.tpl"}

{block name=page_title}Gestion des annonces{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
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
	<h4>Gestion des annonces</h4>
	{$message}
	<ul class="tabs left">
		<li>
			<a href="#tab-pending">En attente de validation</a>
		</li>
	</ul>
	
	<div id="tab-pending" class="tab-content">
	<table class="sortable">
		<thead>
			<tr>
				<th>Titre</th>
				<th>Catégorie - Sous catégorie</th>
				<th>Utilisateur</th>
				<th class="center" style="min-width: 120px">Actions</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$announces item=announce}
			{if $announce->getStateId() == AnnouncementStates::STATE_PENDING}
			<tr>
				<td>{$announce->getTitle()}</td>
				<td>{$categoriesManager->get($announce->getCategoryId())->getName()} - {$categoriesManager->get($announce->getSubCategoryId())->getName()}</td>
				<td>{$usersManager->get($announce->getUserId())->getUsername()}</td>
				<td class="center">
					<ul class="button-bar">
						<li>
							<a href="/view/member/announce-{$announce->id()}" class="tooltip window" title="Apperçu <em>{$announce->getTitle()}</em>">
								<span class="icon small" data-icon="a"></span>
							</a>
						</li>
						<li>
							<a href="/admin/announcements/validate/{$announce->id()}" class="lightbox tooltip" title="Valider <i>{$announce->getTitle()}</i>">
								<span class="icon small green" data-icon="C"></span>
							</a>
						</li>
						<li>
							<a href="/admin/announcements/refuse/{$announce->id()}" class="lightbox tooltip" title="Refuser <i>{$announce->getTitle()}</i>">
								<span class="icon small red" data-icon="x"></span>
							</a>
						</li>
					</ul>
				</td>
			</tr>
			{/if}
		{/foreach}
		</tbody>
	</table>
	</div>
</div>
{/block}