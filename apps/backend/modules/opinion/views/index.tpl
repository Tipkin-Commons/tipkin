{extends file="layout.tpl"}

{block name=page_title}Témoignages{/block}

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
	<h4>Gestion des Témoignages</h4>
	{$message}
	<ul class="tabs">
		<li>
			<a href="#new">Nouveaux témoignages</a>
		</li>
		<li>
			<a href="#published">Témoignages publiés</a>
		</li>
	</ul>
	<div id="new" class="tab-content">
		<table class="sortable filterable">
			<thead>
				<tr>
					<th>Pseudo</th>
					<th>Date de création</th>
					<th class="center" style="width: 120px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$opinionsManager->getListOf() item=opinion}
					{if !$opinion->getIsPublished()}
					<tr>
						<td>
							{$opinion->getUsername()}
						</td>
						<td>
							{$opinion->getCreationDate()}
						</td>
						<td>
							<ul class="button-bar">
								<li>
									<a class="read-message tooltip" title="Lire/Masquer ce témoignage" href="#opinion-{$opinion->id()}">
										<span class="icon small" data-icon="a"></span>
									</a>
								</li>
								<li>
									<a class="tooltip lightbox" href="/admin/opinion/delete/{$opinion->id()}" title="Supprimer ce témoignage">
										<span class="icon small" data-icon="T"></span>
									</a>
								</li>
								<li>
									<a class="tooltip lightbox" href="/admin/opinion/publish/{$opinion->id()}" title="Publier ce témoignage">
										<span class="icon small" data-icon=")"></span>
									</a>
								</li>
							</ul>
						</td>
					</tr>
					<tr id="opinion-{$opinion->id()}" style="display: none;">
						<td colspan="4">
							{$opinion->getComment()}
						</td>
					</tr>
					{/if}
				{/foreach}
			</tbody>
		</table>
	</div>
	<div id="published" class="tab-content">
		<table class="sortable filterable">
			<thead>
				<tr>
					<th>Pseudo</th>
					<th>Date de création</th>
					<th class="center" style="width: 100px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$opinionsManager->getListOf() item=opinion}
					{if $opinion->getIsPublished()}
					<tr>
						<td>
							{$opinion->getUsername()}
						</td>
						<td>
							{$opinion->getCreationDate()}
						</td>
						<td>
							<ul class="button-bar">
								<li>
									<a class="read-message tooltip" title="Lire/Masquer ce témoignage" href="#opinion-{$opinion->id()}">
										<span class="icon small" data-icon="a"></span>
									</a>
								</li>
								<li>
									<a class="tooltip lightbox" href="/admin/opinion/delete/{$opinion->id()}" title="Supprimer ce témoignage">
										<span class="icon small" data-icon="T"></span>
									</a>
								</li>
							</ul>
						</td>
					</tr>
					<tr id="opinion-{$opinion->id()}" style="display: none;">
						<td colspan="4">
							{$opinion->getComment()}
						</td>
					</tr>
					{/if}
				{/foreach}
			</tbody>
		</table>
	</div>
</div>
{/block}