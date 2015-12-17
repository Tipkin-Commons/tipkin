<div style="width: 450px;">
	<div class="col_12">
		<h4>
			Modifier une
			{if $categoryType == 'category'}
				 catégorie
			{else}
				sous-catégorie
			{/if}
		</h4>
		<div class="col_12 visible">
			<div class="col_12">
				{include file='_form.tpl'}
			</div>
		</div>
	</div>
</div>
