{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#cancel-avatar').click(function(){
		$('#fancybox-close').click();
	});

	$('#save-avatar').click(function(){
		var file = $('input[type=file]').val();
		if(file == '')
		{
			$('#label-error').css('display', 'block');
		}
		else
		{
			$('#label-error').css('display', 'none');
			$('#action-buttons').css('display', 'none');
			$('#ajax-loading').css('display', 'block');
			$('#form-change-avatar').submit();
		}
	});
});
//-->
</script>
{/literal}
<div style="width: 500px;">
	<div class="col_12 visible center" style="">
		<br />
		<form method="post" style="display: inline;" enctype="multipart/form-data" id="form-change-avatar" action="/profile/photo">
			<label>Votre photo de profil actuelle :</label>
			<br /><br />
			<img src="{$avatar}" class="avatar">
			<br /><br />
			Choisissez une nouvelle photo : <i>(8Mo max)</i>
			<br /><br />
			<div style="margin: auto; width : 350px; text-align: left;">
				<input type="hidden" name="MAX_FILE_SIZE" value="8000000" />
				<input type="file" style="width: 350px" name="avatar" id="avatar" class="required"/>
				<label class="error" style="margin-top: -25px;display: none;" id="label-error">Veuillez choisir une image</label>
			</div>
			<br />
			<div id="ajax-loading" style="display: none;">
				Veuillez patienter pendant le chargement...
				<br/>
				<img src="/images/ajax-loader.gif"/>
				<br />
			</div>
		</form>
		<div id="action-buttons">
			<button class="small green" name="save-avatar" id="save-avatar">
				Valider
			</button>
			<button class="small red" name="cancel-avatar" id="cancel-avatar">
				Annuler
			</button>
		</div>
		<br />
	</div>
</div>