{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#form-add-address').validate();
});
//-->
</script>
{/literal}
<div style="width: 450px;">
	<div class="col_12">
		<h4>Ajouter une nouvelle adresse</h4>
		<div class="col_12 visible">
			<div class="col_12">
				<form method="post" id="form-add-address" action="/addresses/new">
				{include file="_form.tpl"}
				</form>
			</div>
		</div>
	</div>
</div>