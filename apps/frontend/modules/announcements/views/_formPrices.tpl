{foreach from=$announcePriceList item=announcePrice}
	{if $announcePrice->getContactGroupId() == ContactGroups::USERS}
		{$priceUsers = $announcePrice}
	{/if}
	{if $announcePrice->getContactGroupId() == ContactGroups::TIPPEURS}
		{$priceTippeurs = $announcePrice}
	{/if}
	{if $announcePrice->getContactGroupId() == ContactGroups::FRIENDS}
		{$priceFriends = $announcePrice}
	{/if}
	{if $announcePrice->getContactGroupId() == ContactGroups::FAMILY}
		{$priceFamily = $announcePrice}
	{/if}
	{if $announcePrice->getContactGroupId() == ContactGroups::NEIGHBORS}
		{$priceNeighbors = $announcePrice}
	{/if}
{/foreach}
{literal}
<script type="text/javascript">
<!--
$(function(){
	jQuery.validator.addMethod("float-required", function(value, element) {
        return (value > 0);
    }, "Veuillez indiquer un montant non nul");
	
	$('input[name^="price-default-for"]').change(function(){
		if($(this).is(':checked'))
		{
			$(this).next().next().hide();
		}
		else
		{
			$(this).next().next().show();
		}
		
	}).click();

	$('#is-full-day-price').click(function(){
		$('#half-day-price').toggle();
		$('#full-day-price').toggle();
		
		if($(this).is(':checked'))
		{
			$('.div-half-day-price').hide();
		}
		else
		{
			$('.div-half-day-price').show();
		}
	});
	
	
	$('.float').focusout(function(){
		strToFloat(this);		
	}).focusout();
	
	$('#submit-form').click(function(){
		$('#link-tab-users').click();
	});
	$('#form-prices').validate();
	
});

//{/literal}
//{if $announce->getIsFullDayPrice()}
//{literal}
	$('.div-half-day-price').hide();
//{/literal}
//{/if}
//{literal}

//{/literal}
//{if isset($priceTippeurs) && $priceTippeurs->getIsActive()}
//{literal}
	$('#price-default-for-tippeurs').click();
//{/literal}
//{/if}
//{literal}

//{/literal}
//{if isset($priceFamily) && $priceFamily->getIsActive()}
//{literal}
	$('#price-default-for-family').click();
//{/literal}
//{/if}
//{literal}

//{/literal}
//{if isset($priceFriends) && $priceFriends->getIsActive()}
//{literal}
	$('#price-default-for-friends').click();
//{/literal}
//{/if}
//{literal}

//{/literal}
//{if isset($priceNeighbors) && $priceNeighbors->getIsActive()}
//{literal}
	$('#price-default-for-neighbors').click();
//{/literal}
//{/if}
//{literal}

function strToFloat(element)
{
	var value = $(element).val();
	if(/^[0-9\.\,]+$/.test(value))
	{
		if(value.indexOf(',') != -1 && value.indexOf('.') != -1)
		{
			value = value.replace(',','');
		}
		if(value.indexOf(',') != -1)
		{
			value = value.replace(',','.');
		}
		$(element).val(value);
	}
	else
	{
		$(element).val('0');
	}
}
//-->
</script>
{/literal}
<form action="" id="form-prices" name="form-prices" method="post">
	<label for="caution">Caution <span>(en €)</span> : <span class="right"><i>(facultatif)</i></span></label>
	<input type="text" class="float" name="caution" id="caution" value="{$announce->getCaution()}"/>
	<fieldset style="margin-top: 0">	
		<input type="checkbox" name="is-full-day-price" id="is-full-day-price" {if $announce->getIsFullDayPrice()} checked="checked" {/if}/>
		<label class="inline" for="is-full-day-price">Ne pas afficher les prix à la demi-journée</label>
		<label><span>En choisissant cet option, les Tippeurs ne pourront effectuer que des réservation à la journée ou plus (semaine, week-end, etc.).</span></label>
	</fieldset>
	
	<div id="btn-calculate-prices">
		<a href="#calculate-prices" class="btn small lightbox">Cacul de prix...</a>
	</div>
	
	<ul class="tabs">
		<li>
			<a id="link-tab-users" href="#tab-users"><label class="inline" style="line-height: 20px;">Public <i>(par défaut)</i></label></a>
		</li>
		<li>
			<a href="#tab-family"><img alt="" src="/images/family.png" width="20px;" align="absmiddle" /> Famille</a>
		</li>
		<li>
			<a href="#tab-friends"><img alt="" src="/images/friends.png" width="20px;" align="absmiddle" /> Amis</a>
		</li>
		<li>
			<a href="#tab-neighbors"><img alt="" src="/images/neighbors.png" width="20px;" align="absmiddle" /> Voisin</a>
		</li>
		<li>
			<a href="#tab-tippeurs"><img alt="" src="/images/tippeurs.png" width="20px;" align="absmiddle" /> Tippeurs</a>
		</li>
	</ul>
	
	<div class="tab-content" id="tab-users">
		Veuillez définir vos tarifs publics :
		<div class="col_12 visible">
			<div class="div-half-day-price">
				<label for="price-half-day-users">Prix à la demi-journée <span>(en €)</span> :<span class="right"><i>(obligatoire)</i></span></label>
				<input type="text" class="float-required float" id="price-half-day-users" name="price-half-day-users" value="{$priceUsers->getHalfDay()}"/>
			</div>
			
			<label for="price-day-users">Prix à la journée <span>(en €)</span> :<span class="right"><i>(obligatoire)</i></span></label>
			<input type="text" class="float-required float" id="price-day-users" name="price-day-users" value="{$priceUsers->getDay()}"/>
			
			<label for="price-week-end-users">Prix au week-end <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-week-end-users" name="price-week-end-users" value="{$priceUsers->getWeekEnd()}"/>
			
			<label for="price-week-users">Prix à la semaine <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-week-users" name="price-week-users" value="{$priceUsers->getWeek()}"/>
			
			<label for="price-fortnight-users">Prix à la quinzaine <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-fortnight-users" name="price-fortnight-users" value="{$priceUsers->getFortnight()}"/>
		</div>
	</div>
	
	<div class="tab-content" id="tab-family">
		Veuillez définir vos tarifs pour vos contacts Famille :
		
		<br /><br />
		
		<input type="checkbox" id="price-default-for-family" name="price-default-for-family" checked="checked"/>
		<label class="inline" for="price-default-for-family">Identique aux prix publics</label>
		
		<div class="col_12 visible">
			<div class="div-half-day-price">
				<label for="price-half-day-family">Prix à la demi-journée <span>(en €)</span> :</label>
				<input type="text" class="float" id="price-half-day-family" name="price-half-day-family" value="{$priceFamily->getHalfDay()}"/>
			</div>
			
			<label for="price-day-family">Prix à la journée <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-day-family" name="price-day-family" value="{$priceFamily->getDay()}"/>
			
			<label for="price-week-end-family">Prix au week-end <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-week-end-family" name="price-week-end-family" value="{$priceFamily->getWeekEnd()}"/>
			
			<label for="price-week-family">Prix à la semaine <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-week-family" name="price-week-family" value="{$priceFamily->getWeek()}"/>
			
			<label for="price-fortnight-family">Prix à la quinzaine <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-fortnight-family" name="price-fortnight-family" value="{$priceFamily->getFortnight()}"/>
		</div>
	</div>
	
	<div class="tab-content" id="tab-friends">
		Veuillez définir vos tarifs pour vos contacts Amis :
		
		<br /><br />
		
		<input type="checkbox" id="price-default-for-friends" name="price-default-for-friends" checked="checked"/>
		<label class="inline" for="price-default-for-friends">Identique aux prix publics</label>
		
		<div class="col_12 visible">
			<div class="div-half-day-price">
				<label for="price-half-day-friends">Prix à la demi-journée <span>(en €)</span> :</label>
				<input type="text" class="float" id="price-half-day-friends" name="price-half-day-friends" value="{$priceFriends->getHalfDay()}"/>
			</div>
			
			<label for="price-day-friends">Prix à la journée <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-day-friends" name="price-day-friends" value="{$priceFriends->getDay()}"/>
			
			<label for="price-week-end-friends">Prix au week-end <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-week-end-friends" name="price-week-end-friends" value="{$priceFriends->getWeekEnd()}"/>
			
			<label for="price-week-friends">Prix à la semaine <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-week-friends" name="price-week-friends" value="{$priceFriends->getWeek()}"/>
			
			<label for="price-fortnight-friends">Prix à la quinzaine <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-fortnight-friends" name="price-fortnight-friends" value="{$priceFriends->getFortnight()}"/>
		</div>
	</div>
	
	<div class="tab-content" id="tab-neighbors">
		Veuillez définir vos tarifs pour vos contacts Voisins :
		
		<br /><br />
		
		<input type="checkbox" id="price-default-for-neighbors" name="price-default-for-neighbors" checked="checked"/>
		<label class="inline" for="price-default-for-neighbors">Identique aux prix publics</label>
		
		<div class="col_12 visible">
			<div class="div-half-day-price">
				<label for="price-half-day-neighbors">Prix à la demi-journée <span>(en €)</span> :</label>
				<input type="text" class="float" id="price-half-day-neighbors" name="price-half-day-neighbors" value="{$priceNeighbors->getHalfDay()}"/>
			</div>
			
			<label for="price-day-neighbors">Prix à la journée <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-day-neighbors" name="price-day-neighbors" value="{$priceNeighbors->getDay()}"/>
			
			<label for="price-week-end-neighbors">Prix au week-end <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-week-end-neighbors" name="price-week-end-neighbors" value="{$priceNeighbors->getWeekEnd()}"/>
			
			<label for="price-week-neighbors">Prix à la semaine <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-week-neighbors" name="price-week-neighbors" value="{$priceNeighbors->getWeek()}"/>
			
			<label for="price-fortnight-neighbors">Prix à la quinzaine <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-fortnight-neighbors" name="price-fortnight-neighbors" value="{$priceNeighbors->getFortnight()}"/>
		</div>
	</div>
	
	<div class="tab-content" id="tab-tippeurs">
		Veuillez définir vos tarifs pour vos contacts Tippeurs :
		
		<br /><br />
		
		<input type="checkbox" id="price-default-for-tippeurs" name="price-default-for-tippeurs" checked="checked"/>
		<label class="inline" for="price-default-for-tippeurs">Identique aux prix publics</label>
		
		<div class="col_12 visible">
			<div class="div-half-day-price">
				<label for="price-half-day-tippeurs">Prix à la demi-journée <span>(en €)</span> :</label>
				<input type="text" class="required float" id="price-half-day-tippeurs" name="price-half-day-tippeurs" value="{$priceTippeurs->getHalfDay()}"/>
			</div>
			
			<label for="price-day-tippeurs">Prix à la journée <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-day-tippeurs" name="price-day-tippeurs" value="{$priceTippeurs->getDay()}"/>
			
			<label for="price-week-end-tippeurs">Prix au week-end <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-week-end-tippeurs" name="price-week-end-tippeurs" value="{$priceTippeurs->getWeekEnd()}"/>
			
			<label for="price-week-tippeurs">Prix à la semaine <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-week-tippeurs" name="price-week-tippeurs" value="{$priceTippeurs->getWeek()}"/>
			
			<label for="price-fortnight-tippeurs">Prix à la quinzaine <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-fortnight-tippeurs" name="price-fortnight-tippeurs" value="{$priceTippeurs->getFortnight()}"/>
		</div>
	</div>
	<div class="right">
		<button id="submit-form" name="submit-form" class="small">Suivant</button>
		<a class="btn red small" href="/announcements">Annuler</a>
	</div>
</form>
<!-- MODAL POPUP -->
<div style="display: none;">
	<div id="calculate-prices" style="width: 400px">
	{literal}
	<script type="text/javascript">
	<!--
	$('#calculate-prices-button').click(function(){
		var contactGroup = $('#contact-groups').val();
		var priceRef = $('#price-to-calculate').val();

		$('#price-half-day-'+contactGroup).val(priceRef);
		$('#price-day-'+contactGroup).val(priceRef*2);
		$('#price-week-end-'+contactGroup).val(priceRef*4);
		$('#price-week-'+contactGroup).val(priceRef*14);
		$('#price-fortnight-'+contactGroup).val(priceRef*28);

		$('#fancybox-close').click();
	});		
	//-->
	</script>
	{/literal}
		<h5>Calcul automatisé</h5>
		<div class="col_12 visible">
			<label for="contact-groups">Sélectionnez un groupe de contact :</label>
			<select id="contact-groups">
				<option value="users">Public</option>
				<option value="family">Famille</option>
				<option value="friends">Amis</option>
				<option value="neighbors">Voisins</option>
				<option value="tippeurs">Tippeurs</option>
			</select>
			<label>Définissez un montant pour la demi-journée <span>(en €)</span> :</label>
			<input type="text" class="float" id="price-to-calculate" style="width: 80px;"/>
			<br />
			<div class="right">
			<button id="calculate-prices-button">Calculer</button>
			</div>
		</div>
	</div>
</div>