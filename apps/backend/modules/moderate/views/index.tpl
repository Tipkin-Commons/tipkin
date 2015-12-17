{extends file="layout.tpl"}

{block name=page_title}Modération{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$(".filterable tr:has(td)").each(function(){
   		var t = $(this).text().toLowerCase(); //all row text
   		$("<td class='indexColumn'></td>")
    	.hide().text(t).appendTo(this);
 	});

	$("#input-filter").keyup(function(){
   		var s = $(this).val().toLowerCase().split(" ");
   		//show all rows.
   		$(".filterable tr:hidden").show();
   		$.each(s, function(){
    		$(".filterable tr:visible .indexColumn:not(:contains('"
        		+ this + "'))").parent().hide();
   		});//each
 	});//key up.

	$('#search').click(function(){
		$('#input-filter').keyup();
	});

	$('.read-message').click(function(){
		var message = $(this).attr('href');
		$(message).toggle();
	});
});

//-->
</script>
{/literal}
<div class="col_12">
	<h4>Modération</h4>
	{$message}
	<ul class="tabs">
		<li>
			<a href="#announce">Annonce</a>
		</li>
		<li>
			<a href="#feedback">Feedback</a>
		</li>
	</ul>
	<div id="announce" class="tab-content">
		<table class="sortable filterable">
			<thead>
				<tr>
					<th>Annonce</th>
					<th>Utilisateur</th>
					<th>Modérateur</th>
					<th class="center" style="width: 120px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$moderatesManager->getListOf(Moderate::TYPE_ANNOUNCEMENT) item=moderate}
					<tr>
						<td>
							<a href="/view/member/announce-{$moderate->getTypeId()}" target="_blank">
								{$announcementsManager->get($moderate->getTypeId())->getTitle()}
							</a>
						</td>
						<td>
							{$usersManager->get($announcementsManager->get($moderate->getTypeId())->getUserId())->getUsername()}
						</td>
						<td>
							{$usersManager->get($moderate->getUserAuthorId())->getUsername()}
						</td>
						<td>
							<ul class="button-bar">
								<li>
									<a class="read-message tooltip" title="Lire/Masquer son message" href="#announce-message-{$moderate->id()}">
										<span class="icon small" data-icon="a"></span>
									</a>
								</li>
								<li>
									<a class="tooltip lightbox" href="/admin/moderate/announce/delete/{$moderate->id()}" title="Supprimer son message">
										<span class="icon small" data-icon="T"></span>
									</a>
								</li>
								<li>
									<a href="/admin/announcements/refuse/{$moderate->getTypeId()}" class="lightbox tooltip" title="Refuser <i>{$announcementsManager->get($moderate->getTypeId())->getTitle()}</i>">
										<span class="icon small red" data-icon="x"></span>
									</a>
								</li>
							</ul>
						</td>
					</tr>
					<tr id="announce-message-{$moderate->id()}" style="display: none;">
						<td colspan="4">
							{$moderate->getMessage()}
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
	<div id="feedback" class="tab-content">
		<table class="sortable filterable">
			<thead>
				<tr>
					<th>Feedback</th>
					<th>Autheur du feedback</th>
					<th>Utilisateur concerné</th>
					<th class="center" style="width: 120px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$moderatesManager->getListOf(Moderate::TYPE_FEEDBACK) item=moderate}
					<tr>
						<td>
							<a href="/feedback/read/{$moderate->getTypeId()}" class="lightbox">
							Voir le feedback
							</a>
						</td>
						<td>
							{$usersManager->get($feedbacksManager->get($moderate->getTypeId())->getUserAuthorId())->getUsername()}
						</td>
						<td>
							{$usersManager->get($moderate->getUserAuthorId())->getUsername()}
						</td>
						<td>
							<ul class="button-bar">
								<li>
									<a class="read-message tooltip" title="Lire/Masquer son message" href="#feedback-message-{$moderate->id()}">
										<span class="icon small" data-icon="a"></span>
									</a>
								</li>
								<li>
									<a class="tooltip lightbox" href="/admin/moderate/feedback/delete/{$moderate->id()}" title="Supprimer son message">
										<span class="icon small" data-icon="T"></span>
									</a>
								</li>
								<li>
									<a class="tooltip lightbox" href="/admin/feedback/delete/{$moderate->getTypeId()}" title="Supprimer ce feedback">
										<span class="icon small" data-icon="X"></span>
									</a>
								</li>
							</ul>
						</td>
					</tr>
					<tr id="feedback-message-{$moderate->id()}" style="display: none;">
						<td colspan="4">
							{$moderate->getMessage()}
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
</div>
{/block}