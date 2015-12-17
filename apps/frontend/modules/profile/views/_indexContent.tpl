<h4>{$profile->getFirstname()|capitalize} {$profile->getLastname()|capitalize}</h4>
<p style="text-align: justify;">
	<i>{$profile->getDescription()}</i>
</p>
<p>
	<label>Téléphone : {$profile->getPhone()}</label>
	{if $profile->getMobilePhone() != ''}
	<label>Portable : {$profile->getMobilePhone()}</label>
	{/if}
	{if $profile->getOfficePhone() != ''}
	<label>Bureau : {$profile->getOfficePhone()}</label>
	{/if}
	{if $profile->getWebsite() != ''}
	<label>Site web : <a href="http://{$profile->getWebsite()}" target="_blank">{$profile->getWebsite()}</a></label>
	{/if}
</p>
<hr class="alt2"/>
<h4>Adresse principale</h4>
<address>
	<p>
		{$mainAddress->getAddress1()}
		<br />
		{$mainAddress->getAddress2()}
		<br />
		{$mainAddress->getZipCode()} - {$mainAddress->getCity()|capitalize}
		<br />
		{$mainAddress->getCountry()|upper}
	</p>
</address>
{if $canUseAlternateCurrency}
	<hr class="alt2"/>
	<h4>Monnaies alternatives</h4>
	<p>
		Des monnaies alternatives* sont disponibles dans votre région :
		<br />	
		<a href="/profile/alternate-currency" class="lightbox">Adhérent à des monnaies alternatives</a>
	</p>
	{if count($listCurrencyUsed > 0) && !empty($listCurrencyUsed[0])}
	<fieldset>
		<legend>Monnaie(s) alternative(s) proposée(s) :</legend>
		{foreach from=$listCurrencyUsed item=currencyUse}
			{assign var="alternateCurrency" value=$alternateCurrencyManager->get($currencyUse)}
			<label  class="alternate-currency-label" for="currency-{$alternateCurrency->id()}">
				<span>{$alternateCurrency->getName()}</span> 
				<img src="{AlternateCurrency::$CURRENCY_PATH}{$alternateCurrency->getImageUrl()}"/>
			</label>
		{/foreach}
	</fieldset>
	{/if}
	
	<p>
	<label><span>*monnaies alternatives : <a href="files/lesmonnaiesalternativesetTipkin.pdf" target="_blank">cliquez ici pour en savoir plus</a></span></label>
	</p>
{/if}
