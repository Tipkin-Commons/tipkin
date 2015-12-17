{literal}
<script type="text/javascript">
<!--
$(function(){
	jQuery.validator.addMethod("float", function(value, element) {
        return this.optional(element) || /^[0-9\.\,]+$/i.test(value);
    }, "Seul des chiffres, des points ou des virgules sont autorisés");
	
	$('#form-announcement').validate();	
	
	$('#cancel').click(function(){
		location.href = '/announcements-pro';
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

	$('#category').change(function(){
		var idCategory = $(this).val();
		$('#sub-category').find('option').remove();
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

//	{/literal}
//	{if $announce->getTips() != ''}
//	{literal}
		$('#has-tips').click();
//	{/literal}
//	{/if}
//	{literal}
});
//-->
</script>
{/literal}
<form method="post" action="" enctype="multipart/form-data" id="form-announcement">
	<div class="col_12 right" style="margin-top: -65px;">
		<button class="green small valid-form" id="save"><span class="icon small" data-icon="C"></span>Enregister</button>
		<button class="green small valid-form" id="save-close"><span class="icon small" data-icon="D"></span>Enregister & Fermer</button>
		<button class="red small" id="cancel"><span class="icon small" data-icon="x"></span>Fermer</button>
		<input type="hidden" id="action" name="action"/>
	</div>
	<div style="margin: 0px 10px;">
	{$message}
	</div>
	<div class="col_8 visible">
		<h5>Annonce</h5>
		
		<label for="title">Titre de l'annonce : <span class="right"><i>(obligatoire)</i></span></label>
		<input type="text" name="title" id="title" value="{$announce->getTitle()}" class="required" maxlength="100" minlength="6"/>
		
		{if $announce->getStateId() ==  AnnouncementStates::STATE_DRAFT || $announce->getStateId() ==  null}
		<br />
		<input type="checkbox" name="state-validated" id="state-validated"/>
		<label class="inline" for="state-validated">Valider cette annonce</label>
		<br />
		{/if}
		
		{if $announce->getIsPublished()}
		<input type="checkbox" name="is-published" id="is-published" checked="checked"/>
		{else}
		<input type="checkbox" name="is-published" id="is-published"/>
		{/if}
		<label class="inline" for="is-published">Publier cette annonce une fois validée</label>
		
		<br /><br />
		
		<div style="width: 50%; float: left;">
			<label for="category">Sélectionnez une catégorie :</label>
			<select name="category" id="category">
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
		</div>
		
		<div style="width: 50%; float: right;">
			<label for="sub-category">Sélectionnez une sous-catégorie :</label>
			<select name="sub-category" id="sub-category">
			
			</select>
			<div id="div-sub-category">
			{foreach from=$categories item=category}
				{if !$category->getIsRoot()}
					<input type="hidden" class="{$category->getParentCategoryId()}" value="{$category->id()}" name="{$category->getName()}"/>
				{/if}
			{/foreach}
			</div>	
		</div>
		
		<label for="price-public">Prix à partir de <span>(en €)</span> :<span class="right"><i>(obligatoire)</i></span></label>
		<input type="text" class="float" name="price-public" id="price-public" value="{$announce->getPricePublic()}" />
		
		<label for="description">Description :<span class="right"><i>(obligatoire)</i></span></label>
		<textarea style="width: 100%; height: 100px" name="description" id="description" maxlength="800">{$announce->getDescription()}</textarea>
		
		<br /><br />
		
		<label for="raw-material">Consommable/Livraison :</label>
		<textarea style="width: 100%; height: 100px" name="raw-material" id="raw-material" maxlength="255">{$announce->getRawMaterial()}</textarea>
		
		<br /><br />
	</div>
	<div class="col_4 visible">
		<h5>Adresse</h5>
		<label for="addressId">Sélectionnez une adresse :</label>
		<select name="addressId" id="addressId">
			<option value="new">Nouvelle adresse</option>
			{if $announce->id() != null}
			<option value="current" selected="selected">Actuellement enregistrée</option>
			{/if}
			<optgroup label="Mon adresse">
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
		<input type="text" name="address1" id="address1" value="{$announce->getAddress1()}"/>
		
		<label for="address2">Adresse 2 :</label>
		<input type="text" name="address2" id="address2" value="{$announce->getAddress2()}"/>
		
		<label for="zip-code">Code postal : <span class="right"><i>(obligatoire)</i></span></label>
		<input type="text" name="zip-code" id="zip-code" value="{$announce->getZipCode()}"/>
		
		<label for="city">Ville : <span class="right"><i>(obligatoire)</i></span></label>
		<input type="text" name="city" id="city" value="{$announce->getCity()}"/>
		
		<input type="hidden" name="department" id="department"/>
	</div>
	<div class="col_4 visible">
		<h5>Photos</h5>
		<label for="photo-main">Photo principale : </label>
		{if $announce->getPhotoMain() != '' && $announce->getPhotoMain() != AnnouncementPro::IMAGE_DEFAULT}
		<table>
			<tr>
				<td>
					<img alt="image de l'annonce" style="width: 150px;" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"/>
				</td>
				<td>
					<input type="checkbox" name="delete-photo-main" id="delete-photo-main">
					<label class="inline" for="delete-photo-main">Supprimer cette photo</label>
				</td>
			</tr>
		</table>
		{/if}
		<input type="file" name="photo-main" id="photo-main" />
		
		<label for="photo-option-1">Photo optionelle 1 :</label>
		{if $announce->getPhotoOption1() != '' && $announce->getPhotoOption1() != AnnouncementPro::IMAGE_DEFAULT}
		<table>
			<tr>
				<td>
					<img alt="image de l'annonce" style="width: 150px;" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoOption1()}"/>
				</td>
				<td>
					<input type="checkbox" name="delete-photo-option-1" id="delete-photo-option-1">
					<label class="inline" for="delete-photo-option-1">Supprimer cette photo</label>
				</td>
			</tr>
		</table>
		{/if}
		<input type="file" name="photo-option-1" id="photo-option-1"/>
		
		<label for="photo-option-2">Photo optionelle 2 :</label>
		{if $announce->getPhotoOption2() != '' && $announce->getPhotoOption2() != AnnouncementPro::IMAGE_DEFAULT}
		<table>
			<tr>
				<td>
					<img alt="image de l'annonce" style="width: 150px;" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoOption2()}"/>
				</td>
				<td>
					<input type="checkbox" name="delete-photo-option-2" id="delete-photo-option-2">
					<label class="inline" for="delete-photo-option-2">Supprimer cette photo</label>
				</td>
			</tr>
		</table>
		{/if}
		<input type="file" name="photo-option-2" id="photo-option-2"/>
	</div>
</form>