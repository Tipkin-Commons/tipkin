{literal}
<script type="text/javascript">
<!--
$(function(){
	$(function(){
		$('#form-category').validate();
	});	
});
//-->
</script>
{/literal}
<form method="post" id="form-category" action="{$actionPost}">
	<label for="name">Nom :</label>
	<input type="text" id="name" name="name" class="required" minlength="6" maxlength="50" value="{$category->getName()}" />
	
	{if $categoryType == "sub-category"}
		<label for="parent-category">Catégorie parente :</label>
		<select name="parent-category" id="parent-category">
			{foreach from=$categoriesManager->getListOf() item=categoryEntry}
				{if $categoryEntry->getIsRoot()}
					{if $category->getParentCategoryId() == $categoryEntry->id()}
						<option value="{$categoryEntry->id()}" selected="selected">
							{$categoryEntry->getName()}
						</option>
					{else}
						<option value="{$categoryEntry->id()}">
							{$categoryEntry->getName()}
						</option>
					{/if}
				{/if}
			{/foreach}
		</select>
	{/if}
	<label for="description">Description :</label>
	<textarea style="height: 100px" name="description" id="description">{$category->getDescription()}</textarea>
	<button name="submit-form" style="float: right;" class="small green">Terminer</button>
</form>