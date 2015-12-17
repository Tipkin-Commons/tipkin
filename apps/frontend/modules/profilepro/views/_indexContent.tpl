<h4>{$profilePro->getCompanyName()|capitalize}</h4>

<p style="text-align: justify;">
	<i>{$profilePro->getDescription()}</i>
</p>

<p>
	<label style="font-weight: bold;">{$profilePro->getFirstname()} {$profilePro->getLastname()|upper}</label>
	<br />
	<label>Téléphone : {$profilePro->getPhone()}</label>
	{if $profilePro->getMobilePhone() != ''}
	<label>Portable : {$profilePro->getMobilePhone()}</label>
	{/if}
	{if $profilePro->getOfficePhone() != ''}
	<label>Bureau : {$profilePro->getMobilePhone()}</label>
	{/if}
	{if $profilePro->getWebsite() != ''}
	<label>Site web : <a href="http://{$profilePro->getWebsite()}" target="_blank">{$profilePro->getWebsite()}</a></label>
	{/if}
</p>
<hr class="alt2"/>
<h4>Adresse</h4>
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