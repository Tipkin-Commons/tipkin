{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#feedback-form').validate();

	$('#submit-form').click(function(){
		var isValid = true;
		var value = 0;
		for(var i = 1 ; i <= 4 ; i++)
		{
			if($('input[name="mark-'+i+'"]:checked').val() == null)
			{
				isValid = false;
			}
			else
			{
				value += parseInt($('input[name="mark-'+i+'"]:checked').val());
			}
		}
		if(!isValid)
		{
			$('#mark-error').show();
			return false;
		}
		else
		{
			$('#mark-error').hide();
			value = parseInt(value/4);
			$('#mark').val(value);
		}
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

	$('table tr td').css('width', '14%');
	$('table tr td').css('text-align', 'center');
	$('label').css('font-weight', 'bold');
});
//-->
</script>
{/literal}
<form method="post" action="/feedback/{$feedbackRequest->id()}" id="feedback-form">
	<br />
	<label class="error" id="mark-error" style="display: none;">Vous n'avez pas donné de note à toutes les questions</label>
	{if $feedbackRequest->getUserSubscriberId() == $feedbackRequest->getUserAuthorId()}
		<label>Les explications du propriétaire concernant son objet étaient :</label>
		<table>
			<tr>
				<td></td>
				<td>1</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
				<td>5</td>
				<td></td>
			</tr>
			<tr>
				<td>Insuffisantes</td>
				<td><input type="radio" name="mark-1" value="1"/></td>
				<td><input type="radio" name="mark-1" value="2"/></td>
				<td><input type="radio" name="mark-1" value="3"/></td>
				<td><input type="radio" name="mark-1" value="4"/></td>
				<td><input type="radio" name="mark-1" value="5"/></td>
				<td>parfaites</td>
			</tr>
		</table>
		
		<label>Le propriétaire fût-il serviable :</label>
		<table>
			<tr>
				<td></td>
				<td>1</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
				<td>5</td>
				<td></td>
			</tr>
			<tr>
				<td>Pas du tout</td>
				<td><input type="radio" name="mark-2" value="1"/></td>
				<td><input type="radio" name="mark-2" value="2"/></td>
				<td><input type="radio" name="mark-2" value="3"/></td>
				<td><input type="radio" name="mark-2" value="4"/></td>
				<td><input type="radio" name="mark-2" value="5"/></td>
				<td>Totalement</td>
			</tr>
		</table>
		
		<label>Le propriétaire fût-il sympathique :</label>
		<table>
			<tr>
				<td></td>
				<td>1</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
				<td>5</td>
				<td></td>
			</tr>
			<tr>
				<td>Peu enthousiaste</td>
				<td><input type="radio" name="mark-3" value="1"/></td>
				<td><input type="radio" name="mark-3" value="2"/></td>
				<td><input type="radio" name="mark-3" value="3"/></td>
				<td><input type="radio" name="mark-3" value="4"/></td>
				<td><input type="radio" name="mark-3" value="5"/></td>
				<td>Super génial</td>
			</tr>
		</table>
		
		<label>Le produit correspondait-il à l'annonce :</label>
		<table>
			<tr>
				<td></td>
				<td>1</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
				<td>5</td>
				<td></td>
			</tr>
			<tr>
				<td>Pas du tout</td>
				<td><input type="radio" name="mark-4" value="1"/></td>
				<td><input type="radio" name="mark-4" value="2"/></td>
				<td><input type="radio" name="mark-4" value="3"/></td>
				<td><input type="radio" name="mark-4" value="4"/></td>
				<td><input type="radio" name="mark-4" value="5"/></td>
				<td>Totalement</td>
			</tr>
		</table>
	{else}
		<label>Le tippeur était-il ponctuel pour rendre l'objet :</label>
		<table>
			<tr>
				<td></td>
				<td>1</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
				<td>5</td>
				<td></td>
			</tr>
			<tr>
				<td>Insuffisantes</td>
				<td><input type="radio" name="mark-1" value="1"/></td>
				<td><input type="radio" name="mark-1" value="2"/></td>
				<td><input type="radio" name="mark-1" value="3"/></td>
				<td><input type="radio" name="mark-1" value="4"/></td>
				<td><input type="radio" name="mark-1" value="5"/></td>
				<td>parfaites</td>
			</tr>
		</table>
		
		<label>Le tippeur vous a-t-il rendu le produit en bon étât :</label>
		<table>
			<tr>
				<td></td>
				<td>1</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
				<td>5</td>
				<td></td>
			</tr>
			<tr>
				<td>Pas du tout</td>
				<td><input type="radio" name="mark-2" value="1"/></td>
				<td><input type="radio" name="mark-2" value="2"/></td>
				<td><input type="radio" name="mark-2" value="3"/></td>
				<td><input type="radio" name="mark-2" value="4"/></td>
				<td><input type="radio" name="mark-2" value="5"/></td>
				<td>Totalement</td>
			</tr>
		</table>
		
		<label>Le tippeur était-il sympathique :</label>
		<table>
			<tr>
				<td></td>
				<td>1</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
				<td>5</td>
				<td></td>
			</tr>
			<tr>
				<td>Peu enthousiaste</td>
				<td><input type="radio" name="mark-3" value="1"/></td>
				<td><input type="radio" name="mark-3" value="2"/></td>
				<td><input type="radio" name="mark-3" value="3"/></td>
				<td><input type="radio" name="mark-3" value="4"/></td>
				<td><input type="radio" name="mark-3" value="5"/></td>
				<td>Super génial</td>
			</tr>
		</table>
		
		<label>Vous recommanderiez ce tippeur :</label>
		<table>
			<tr>
				<td></td>
				<td>1</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
				<td>5</td>
				<td></td>
			</tr>
			<tr>
				<td>Pas du tout</td>
				<td><input type="radio" name="mark-4" value="1"/></td>
				<td><input type="radio" name="mark-4" value="2"/></td>
				<td><input type="radio" name="mark-4" value="3"/></td>
				<td><input type="radio" name="mark-4" value="4"/></td>
				<td><input type="radio" name="mark-4" value="5"/></td>
				<td>Totalement</td>
			</tr>
		</table>
	{/if}
	<input type="hidden" name="mark" id="mark" />
	<label for="comment">Commentaire :</label>
	<textarea name="comment" id="comment" class="required" style="height: 125px" maxlength="300"></textarea>
	<br /><br />
	<div class="right">
		<button name="submit-form" id="submit-form">Envoyer</button>
	</div>
</form>