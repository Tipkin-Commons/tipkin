{extends file="layout.tpl"}

{block name=page_title}{$currentUser->getUsername()} - Mes adresses{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#add-address').click(function(){
		$('#new-address').click();
	});
});

//-->
</script>
{/literal}
<div class="col_9">
	<div class="col_12">
		<button class="small" id="add-address" style="float: right;">
			<span class="icon small" data-icon="+"></span>
			Nouvelle adresse
		</button>
		<a id="new-address" href="/addresses/new" class="lightbox"></a>
		<h4>Adresse principale</h4>
		<div class="col_12 visible">
			<ul class="button-bar" style="float: right;">
				<li>
					<a href="/addresses/edit/{$mainAddress->id()}" class="lightbox tooltip" title="Modifier <em>{$mainAddress->getTitle()}</em>">
						<span class="icon small" data-icon="7"></span>
					</a>
				</li>
			</ul>
			<h5>{$mainAddress->getTitle()|capitalize}</h5>
			<address>
				{$mainAddress->getAddress1()}
				<br/>
				{$mainAddress->getAddress2()}
				<br/>
				{$mainAddress->getZipCode()} - {$mainAddress->getCity()|capitalize}
				<br />
				{$mainAddress->getCountry()|upper}
			</address>
		</div>
		<hr class="alt2"/>
		<h4>Autres adresses</h4>
		{foreach from=$addresses item=address}
		{if $mainAddress->id() != $address->id()}
		<div class="col_4 visible">
			<ul class="button-bar" style="float: right;">
				<li>
					<a href="/addresses/edit/{$address->id()}" class="lightbox tooltip" title="Modifier <em>{$address->getTitle()}</em>">
						<span class="icon small" data-icon="7"></span>
					</a>
				</li>
				<li>
					<a id="delete/{$address->id()}" href="#confirm-action" class="tooltip command lightbox" title="Supprimer <em>{$address->getTitle()}</em>">
						<span class="icon small" data-icon="T"></span>
					</a>
				</li>
			</ul>
			<br />
			<h5>{$address->getTitle()}</h5>
			<address>
				{$address->getAddress1()}
				<br/>
				{$address->getAddress2()}
				<br/>
				{$address->getZipCode()} - {$address->getCity()|capitalize}
				<br />
				{$address->getCountry()|upper}
			</address>
		</div>
		{/if}
		{/foreach} 
	</div>
</div>
<div class="col_3" style="margin-top: 26px;">
	{include file='_menuActionRight.tpl'}
</div>
<!-- MODAL POPUP -->
<div style="display: none">
	<div id="confirm-action" style="width: 400px;">
		{literal}
		<script type="text/javascript">
			$(function(){
				$('.command').click(function(){
					$('#confirm-command').attr('href', '{/literal}/addresses/{literal}' + $(this).attr('id'));
					$('#description-command').html('<ul class="alt"><li>' + $('#tiptip_content').html() + '</li></ul>');
				});
				
				$('#close').click(function(){
					$('#fancybox-close').click();
				});
				$('#confirm-button').click(function(){
					location.href = $('#confirm-command').attr('href');
				});
			});
		</script>
		{/literal}
		<div class="col_12 visible" style="font-weight: bolder; font-size: large;">
			Veuillez confirmer votre action :
		</div>
		<div class="col_12">
			<div id="description-command"></div>
			<div style="float: right;">
				<button class="green" id="confirm-button">Oui</button>
				<a id="confirm-command" href=""></a>
				<button class="red" id="close">Non</button>
			</div>
		</div>
	</div>
</div>
{/block}