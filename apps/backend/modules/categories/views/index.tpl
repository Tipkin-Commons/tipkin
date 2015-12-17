{extends file="layout.tpl"}

{block name=page_title}Gestion des catégories{/block}

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
});

//-->
</script>
{/literal}
<div class="col_9">
	{$message}
	<h4>Gestion des catégories</h4>
	<div style="float: right; margin-top:-33px;">
		<input type="text" id="input-filter"  placeholder="Recherche..." style="width: 220px; margin-bottom: 0px"/>
		<button id="search" class="tooltip" title="Lancer la recherche"><span class="icon" data-icon="s"></span></button>
	</div>
	<table class="sortable filterable">
		<thead>
			<tr>
				<th>Nom de catégorie</th>
				<th>Catégorie parente</th>
				<th class="center" style="width: 100px;">Actions</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$categoriesManager->getListOf() item=category}
				<tr>
					<td>
						{$category->getName()}
						<br />
						<label><span>{$category->getDescription()}</span></label>
					</td>
					<td>
						{if !$category->getIsRoot()}
							{$categoriesManager->get($category->getParentCategoryId())->getName()}
						{/if}
					</td>
					<td>
						<ul class="button-bar">
							<li>
								<a class="lightbox" href="/admin/categories/edit/{$category->id()}">
									<span class="icon small" data-icon="7"></span>
								</a>
							</li>
							{if !$categoriesManager->hasAnnouncementsLinked($category->id())}
								<li>
									<a class="lightbox" href="/admin/categories/delete/{$category->id()}">
										<span class="icon small" data-icon="T"></span>
									</a>
								</li>
							{/if}
						</ul>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>
<div class="col_3">
	<ul class="menu vertical right">
		<li>
			<a>
				Ajouter une catégorie
			</a>
			<ul>
				<li>
					<a class="lightbox" href="/admin/categories/add/category">
						Catégorie
					</a>
				</li>
				<li>
					<a class="lightbox" href="/admin/categories/add/sub-category">
						Sous-catégorie
					</a>
				</li>
			</ul>
		</li>
	</ul>
</div>
{/block}