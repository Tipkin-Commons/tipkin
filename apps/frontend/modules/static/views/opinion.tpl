{extends file="layout.tpl"}

{block name=page_title prepend}Témoignages{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#form-opinion').validate();
	
	$('#link-form-opinion').click(function(){
		$(this).hide();
		$('#form-opinion').show('slow');
		return false;
	});

	$('#link-form-opinion-hide').click(function(){
		$('#form-opinion').hide('slow');
		$('#link-form-opinion').show();
		return false;
	});

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
//-->
</script>
{/literal}
<h1>Vos témoignages</h1>

{if $isAuthenticate == 'true'}
	<div class="col_12 visible">
		{if isset($isMessageSent)}
			<p>
				Votre témoignage a été transmis vers nos service avec succès.
				<br /><br />
				Toute l'équipe TIPKIN vous remercie pour votre implication.
			</p>
		{else}
			<div class="right">
				<a href="" id="link-form-opinion">Laisser un témoignage</a>
			</div>
			<form method="post" id="form-opinion" style="display: none;">
				<label for="username">Pseudo : <strong>{$currentUser->getUsername()}</strong></label>
				<input type="hidden" name="username" id="username" value="{$currentUser->getUsername()}"/>
				<br /><br />
				<label for="comment">Témoignage * :</label>
				<textarea name="comment" id="comment" style="height: 100px;" minlength="20" maxlength="300" class="required"></textarea>
				
				<br /><br />
				<div class="right">
				<button class="small green"  name="submit-form">Envoyer</button>
				<a id="link-form-opinion-hide" class="btn small red">Annuler</a>
				</div>
			</form>
		{/if}
	</div>
{/if}
<div>
{foreach from=$opinionsManager->getListOf() item="opinion"}
	{if $opinion->getIsPublished()}
		
		<div class="col_6 visible" style="height: 165px; overflow: hidden; text-align: justify;">
			<div style="float: right;margin-top: 15px;">
				{date_format(date_create($opinion->getCreationDate()), 'd/m/Y')}
			</div>
			<div class="feedbacks-count">
				<span></span><strong>{$opinion->getUsername()}</strong>
			</div>
			<div class="clearfix"></div>
			<br />
			{nl2br($opinion->getComment())}
		</div>
	{/if}
{/foreach}
</div>
{/block}