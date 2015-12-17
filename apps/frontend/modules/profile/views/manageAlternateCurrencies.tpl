{literal}
<script type="text/javascript">
<!--
$(function(){
	
});
//-->
</script>
{/literal}
<div style="width: 600px;">
	<h4>Membres de monnaies alternatives</h4>
	<div class="col_12 visible">
		<div class="col_12">
			<form method="post" action="/profile/alternate-currency" id="form-manage-alternate-currencies">
				{foreach from=$listAlternateCurrenciesAvailable item=alternateCurrency}	
					<input type="checkbox" 
						   name="alternateCurrency[]" 
						   id="currency-{$alternateCurrency->id()}" 
						   value="{$alternateCurrency->id()}"
						   {if in_array($alternateCurrency->id(), $listCurrencyUsed)}
						   		checked="checked"
						   {/if}
					/>
					<label  class="alternate-currency-label" for="currency-{$alternateCurrency->id()}">
						<span>{$alternateCurrency->getName()}</span> 
						<img src="{AlternateCurrency::$CURRENCY_PATH}{$alternateCurrency->getImageUrl()}"/>
					</label>
					<br />
				{/foreach}
				<button style="float: right" class="small green" name="save-currencies">Terminer</button>
			</form>
		</div>
	</div>
</div>