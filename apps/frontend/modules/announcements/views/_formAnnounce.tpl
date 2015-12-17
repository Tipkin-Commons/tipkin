{literal}
<script type="text/javascript">
<!--
$(function(){
	jQuery.validator.addMethod("select-required", function(value, element) {
		return value != 'null';
    }, "Veuillez sectionner un élément");
	
	$('#form-announcement').validate();	
	
	$('#cancel').click(function(){
		location.href = '/announcements';
		return false;
	});

	$('.valid-form').click(function(){
		$('#action').val($(this).attr('id'));
	});

	$('#addressId').change(function(){
		var id = $(this).val();
		$('#address1').val($('#address-'+id+'-address1').val());
		$('#address2').val($('#address-'+id+'-address2').val());
		$('#zip-code').val($('#address-'+id+'-zip-code').val());
		$('#city').val($('#address-'+id+'-city').val());

		$('#zip-code').change();
	}).change();

	$('#zip-code').change(function(){
		var dpt = $(this).val().substring(0,2);
		if(dpt == '20' || dpt == '97')
			dpt = $(this).val().substring(0,3);
			
		$('#department').val(dpt);
	}).change();
	
	$('#has-tips').change(function(){
		if($('#has-tips').is(':checked'))
			$('#div-tips').show('fast');
		else
			$('#div-tips').hide('fast');
	}).change();

	$('#category').change(function(){
		var idCategory = $(this).val();
		$('#sub-category').find('option').remove();
		$('#sub-category').append('<option value="null">-- Choisissez une sous-catégorie --</option>');
		$('#div-sub-category input').each(function(){
			var root = $(this).attr('class');
			if(root == idCategory)
			{	
				if($(this).val() == '{/literal}{$announce->getSubCategoryId()}{literal}')
					$('#sub-category').append('<option value="' + $(this).val() + '" selected="selected">' + $(this).attr('name') + '</option>');
				else
					$('#sub-category').append('<option value="' + $(this).val() + '">' + $(this).attr('name') + '</option>');
			}
		});
	}).change();
	
	$('[maxlength]').keyup(function(){
		var charsMax = $(this).attr('maxlength');
		var charsVal = $(this).val().length;
		var charsLeft = charsMax - charsVal;

		var id = $(this).attr('id');
		
		var labelInfo = '<label class="count-char"><span class="right">' + charsLeft + ' caractères restant </span></label>';

		if($(this).next().attr('class') == 'count-char')
			$(this).next().remove();
		if($(this).next().attr('class') == 'error' && $(this).next().next().attr('class') == 'count-char')
			$(this).next().next().remove();
		
		$(this).after(labelInfo);
	}).keyup();
});

//{/literal}
//{if $announce->getTips() != ''}
//{literal}
$('#has-tips').click();
//{/literal}
//{/if}
//{literal}

//-->
</script>
{/literal}
<form method="post" action="" id="form-announcement">
	<div>
		{if $isAdminAuthenticate != 'true' && ($announce->getStateId() !=  AnnouncementStates::STATE_DRAFT && $announce->getStateId() !=  AnnouncementStates::STATE_REFUSED)}
			<label>Titre de l'annonce : <u>{$announce->getTitle()}</u></label>
			<input type="hidden" name="title" id="title" value="{$announce->getTitle()}"/>
			<br /><br />
		{else}
			<label for="title">Titre de l'annonce : <span class="right"><i>(obligatoire)</i></span></label>
			<input type="text" name="title" id="title" value="{$announce->getTitle()}" class="required" maxlength="100" minlength="6"/>
		{/if}
		<div style="width: 49%; float: left; margin-right: 2%">
			{if  $isAdminAuthenticate != 'true' && $announce->getStateId() !=  AnnouncementStates::STATE_DRAFT && $announce->getStateId() !=  AnnouncementStates::STATE_REFUSED}
				{foreach from=$categories item=category}
					{if $category->getIsRoot()}
						{if $category->id() == $announce->getCategoryId()}
							Catégorie : {$category->getName()}
							<br /><br />
						{/if}
					{/if}
				{/foreach}
				<input type="hidden" name="category" id="category" value="{$announce->getCategoryId()}"/>
			{else}
				<label for="category">Sélectionnez une catégorie :</label>
				<select name="category" id="category" class="select-required" style="width: 100%">
					<option value="null" >-- Choisissez une catégorie --</option>
				{foreach from=$categories item=category}
					{if $category->getIsRoot()}
						{if $category->id() == $announce->getCategoryId()}
						<option value="{$category->id()}" selected="selected">{$category->getName()}</option>
						{else}
						<option value="{$category->id()}">{$category->getName()}</option>
						{/if}
					{/if}
				{/foreach}
				</select>
			{/if}
		</div>
		
		<div style="width: 49%; float: right;">
			{if $isAdminAuthenticate != 'true' && $announce->getStateId() !=  AnnouncementStates::STATE_DRAFT && $announce->getStateId() !=  AnnouncementStates::STATE_REFUSED}
				{foreach from=$categories item=category}
					{if !$category->getIsRoot()}
						{if $category->id() == $announce->getSubCategoryId()}
							Sous-catégorie : {$category->getName()}
							<br /><br />
						{/if}
					{/if}
				{/foreach}
				<input type="hidden" name="sub-category" id="sub-category" value="{$announce->getSubCategoryId()}"/>
			{else}
				<label for="sub-category">Sélectionnez une sous-catégorie :</label>
				<select name="sub-category" id="sub-category" class="select-required" style="width: 100%">
				</select>
				<div id="div-sub-category">
				{foreach from=$categories item=category}
					{if !$category->getIsRoot()}
						<input type="hidden" class="{$category->getParentCategoryId()}" value="{$category->id()}" name="{$category->getName()}"/>
					{/if}
				{/foreach}
				</div>
			{/if}
		</div>
		
		<label for="description">Description :<span class="right"><i>(obligatoire)</i></span></label>
		<textarea style="width: 100%; height: 100px" name="description" id="description" maxlength="800" class="required">{$announce->getDescription()}</textarea>
		
		<br /><br />
		
		<input type="checkbox" name="has-tips" id="has-tips"/>
		<label class="inline" for="has-tips">Proposer une astuce</label>
		<br />
		<div style="display: none;" id="div-tips">
			<label for="tips">Astuce :</label>
			<input type="text" name="tips" id="tips" maxlength="100" value="{$announce->getTips()}"/>
		</div>
		
		<br /><br />
		
		<label for="raw-material">Consommable/Livraison :</label>
		<textarea style="width: 100%; height: 100px" name="raw-material" id="raw-material" maxlength="255">{$announce->getRawMaterial()}</textarea>
	</div>
	<br />
	<hr class="alt2"/>
	<br />
	<div>
		<label for="addressId">Sélectionnez une adresse pour votre annonce :</label>
		<select name="addressId" id="addressId">
			<option value="new">Nouvelle adresse</option>
			{if $announce->id() != null}
			<option value="current" selected="selected">Actuellement enregistrée</option>
			{/if}
			<optgroup label="Mes adresses">
		{foreach from=$addresses item=address}
			{if $announce->id() == null && $address->id() == $profile->getMainAddressId()}
			<option value="{$address->id()}" selected="selected">{$address->getTitle()}</option>
			{else}
			<option value="{$address->id()}">{$address->getTitle()}</option>
			{/if}
		{/foreach}
			</optgroup>
		</select>
		<div id="addresses" style="display: none;">
			<div id="address-current}">
				<input type="hidden" id="address-current-address1" value="{$announce->getAddress1()}">
				<input type="hidden" id="address-current-address2" value="{$announce->getAddress2()}">
				<input type="hidden" id="address-current-zip-code" value="{$announce->getZipCode()}">
				<input type="hidden" id="address-current-city" value="{$announce->getCity()}">
			</div>
		{foreach from=$addresses item=address}
			<div id="address-{$address->id()}">
				<input type="hidden" id="address-{$address->id()}-address1" value="{$address->getAddress1()}">
				<input type="hidden" id="address-{$address->id()}-address2" value="{$address->getAddress2()}">
				<input type="hidden" id="address-{$address->id()}-zip-code" value="{$address->getZipCode()}">
				<input type="hidden" id="address-{$address->id()}-city" value="{$address->getCity()}">
			</div>
		{/foreach}
		</div>
		
		<label for="address1">Adresse 1 : <span class="right"><i>(obligatoire)</i></span></label>
		<input type="text" class="required" name="address1" id="address1" value="{$announce->getAddress1()}"/>
		
		<label for="address2">Adresse 2 :</label>
		<input type="text" name="address2" id="address2" value="{$announce->getAddress2()}"/>
		
		<label for="zip-code">Code postal : <span class="right"><i>(obligatoire)</i></span></label>
		<input type="text" class="required" name="zip-code" id="zip-code" value="{$announce->getZipCode()}"/>
		
		<label for="city">Ville : <span class="right"><i>(obligatoire)</i></span></label>
		<input type="text" class="required" name="city" id="city" value="{$announce->getCity()}"/>
		
		<input type="hidden" name="department" id="department"/>
	</div>
	<br /><br />
	<div class="right">
		{if $announce->id() == null}
			<button id="submit-form" class="small">Créer et continuer</button>
		{else}
			<button id="submit-form" class="small">Modifier et continuer</button>
		{/if}
		<a class="btn red small" href="/announcements">Annuler</a>
	</div>
</form>