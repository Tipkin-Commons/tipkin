{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#form-profile').validate();
});
//-->
</script>
{/literal}
<form method="post" id="form-profile">
	<h4>Informations</h4>

	Je suis : <br />
	<div class="col_6 center">
	<input type="radio" name="gender" id="gender-male" value="M" style=""/>
		<label for="gender-male" class="inline">un homme <br /> <img alt="image d'homme" width="100px" src="{Profile::AVATAR_DEFAULT_MALE}" /></label>
		</div>
	<div class="col_6 center">
		<input type="radio" name="gender" id="gender-female" value="F" style=""/>
		<label for="gender-female" class="inline">une femme <br /> <img alt="image de femme" width="100px" src="{Profile::AVATAR_DEFAULT_FEMALE}" /></label>
	</div>
	
	<br /><br />
	
	<label for="lastname"><span>*</span> Nom :</label>
	<input type="text" id="lastname" name="lastname" class="required" value="{$profile->getLastname()}" maxlength="255"/>
	
	<label for="firstname"><span>*</span> Prénom :</label>
	<input type="text" id="firstname" name="firstname" class="required" value="{$profile->getFirstname()}" maxlength="255"/>
	
	<label for="description">Description :</label>
	<textarea style="width: 100%; height: 100px" name="description" id="description">{$profile->getDescription()}</textarea>
	<br /><br />
	
	<label for="phone"><span>*</span> Téléphone : <span>[0-9]</span></label>
	<input type="text" id="phone" name="phone" class="required digits" maxlength="10" value="{$profile->getPhone()}"/>
	
	<label for="mobile-phone">Téléphone portable : <span>[0-9]</span></label>
	<input type="text" id="mobile-phone" name="mobile-phone" value="{$profile->getMobilePhone()}" maxlength="10" class="digits"/>
	
	<label for="office-phone">Téléphone de bureau : <span>[0-9]</span></label>
	<input type="text" id="office-phone" name="office-phone" value="{$profile->getOfficePhone()}" maxlength="10" class="digits"/>
	
	<span style="color:#666666; font-style: italic; font-size: x-small;">* Champ obligatoire</span>
	
	<hr class="alt2"/>
	
	<h4>Adresse principale</h4>
	
	<label for="address-1"><span>*</span> Adresse 1 :</label>
	<input type="text" id="address-1" name="address-1" class="required" value="{$mainAddress->getAddress1()}"/>
	
	<label for="address-2">Adresse 2 :</label>
	<input type="text" id="address-2" name="address-2" class="" value="{$mainAddress->getAddress2()}"/>
	
	<label for="zip-code"><span>*</span> Code postal :</label>
	<input type="text" id="zip-code" name="zip-code" class="required" maxLength="5" minLength="5" value="{$mainAddress->getZipCode()}"/>
	
	<label for="city"><span>*</span> Ville :</label>
	<input type="text" id="city" name="city" class="required" value="{$mainAddress->getCity()}"/>
	
	<span style="color:#666666; font-style: italic; font-size: x-small;">* Champ obligatoire</span>
	
	<button style="float: right" class="small green" name="save-profile">Terminer</button>
</form>