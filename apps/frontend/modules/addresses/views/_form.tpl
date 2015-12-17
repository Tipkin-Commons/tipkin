<!-- Ce formulaire est appelé pour tout création/modification d'adresse -->
 
<label for="title"><span>*</span>Titre</label>
<input type="text" name="title" id="title" class="required" value="{$address->getTitle()}"/>

<label for="address-1"><span>*</span> Adresse 1 :</label>
<input type="text" id="address-1" name="address-1" class="required" value="{$address->getAddress1()}"/>

<label for="address-2">Adresse 2 :</label>
<input type="text" id="address-2" name="address-2" class="" value="{$address->getAddress2()}"/>

<label for="zip-code"><span>*</span> Code postal :</label>
<input type="text" id="zip-code" name="zip-code" class="required" maxlength="5" minlength="5" value="{$address->getZipCode()}"/>

<label for="city"><span>*</span> Ville :</label>
<input type="text" id="city" name="city" class="required" value="{$address->getCity()}"/>

<span style="color:#666666; font-style: italic; font-size: x-small;">* Champ obligatoire</span>

<button style="float: right" class="small green" name="save-address">Terminer</button>