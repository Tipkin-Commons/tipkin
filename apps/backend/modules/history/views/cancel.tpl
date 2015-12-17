{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#close').click(function(){
		$('#fancybox-close').click();
	});
	$('#confirm-button').click(function(){
		
	});
});
//-->
</script>
{/literal}
<div style="width: 800px">
	<h4>Annulation de réservation n°{$reservation->id()}</h4>
	<div style="float: right;">
		<form method="post" action="/admin/history/cancel-reservation/{$reservation->id()}" style="display: inline;">
			<button class="green" id="confirm-button" name="submit-form">Annuler la reservation</button>
		</form>
		<a id="confirm-command" href=""></a>
		<button class="red" id="close">Retour</button>
	</div>		
</div>