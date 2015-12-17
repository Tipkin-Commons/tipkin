{literal}
<script type="text/javascript" src="/js/jquery.ui.datepicker-fr.js"></script>
<script type="text/javascript">
<!--
$('#form-calendar').validate();

today = new Date('{/literal}{$smarty.now|date_format:"%Y-%m-%d"}{literal}');
tomorow = new Date(today.setDate(today.getDate() + 1));

$('#publication-date').datepicker({
	minDate : today,
	onSelect: setMinDateCalendarEndPublication
});

$('#end-publication-date').datepicker({
	minDate: tomorow		
});

$('[name="publication-date-radio"]').change(function(){
	if($('#publication-date-manual').is(':checked'))
	{
		$('#fieldset-publication-date').show('fast');
		setMinDateCalendarEndPublication($('#publication-date').val(), null);
	}
	else
	{
		$('#fieldset-publication-date').hide('fast');
		$('#end-publication-date').datepicker("option", "minDate", tomorow);
	}
}).change();

$('[name="end-publication-date-radio"]').change(function(){
	if($('#end-publication-date-manual').is(':checked'))
		$('#fieldset-end-publication-date').show('fast');
	else
		$('#fieldset-end-publication-date').hide('fast');
}).change();

$('.no-text').keyup(function(){
	$(this).val('');
});

function setMinDateCalendarEndPublication (dateText, inst)
{
	minEndDate = new Date(parseToDateEn(dateText));
	minEndDate = new Date(minEndDate.setDate(minEndDate.getDate()+1));
	$('#end-publication-date').datepicker("option", "minDate", minEndDate);	
}

function parseToDateFr(date)
{
	var year = date.substring(0,4);
	var month = date.substring(5,7);
	var day = date.substring(8,10);

	return (day + '/' + month + '/' + year);
}

function parseToDateEn(date)
{
	var year = date.substring(6,10);
	var month = date.substring(3,5);
	var day = date.substring(0,2);

	return (year + '-' + month + '-' + day);
}

//-->
</script>
{/literal}
<form method="post" id="form-calendar">
	<div style="float: left; width: 48%; margin-right: 2%;">
		<h5>Début de publication</h5>
		<input type="radio" name="publication-date-radio" value="default" id="publication-date-default" checked="checked" /> 
		<label class="inline" for="publication-date-default">
			Publier mon annonce dès sa validation
		</label> 
		<br />
		<input type="radio" name="publication-date-radio" value="manual" id="publication-date-manual" /> 
		<label class="inline" for="publication-date-manual">
			Choisir une date manuellement...
		</label>
		<fieldset id="fieldset-publication-date">
			Sélectionnez une date de début de publication pour votre annonce : 
			<input type="text"	name="publication-date" id="publication-date" class="required no-text"/> 
			<label>
				<span>Votre annonce sera en ligne à partir de la date que vous indiquerez ci-dessus.</span>
			</label>
		</fieldset>
	</div>
	<div style="float: left; width: 48%">
		<h5>Fin de publication</h5>
		<input type="radio" name="end-publication-date-radio" value="default" id="end-publication-date-default" checked="checked" /> 
		<label class="inline" for="end-publication-date-default">
			2 ans après publication de l'annonce
		</label> 
		<br />
		<input type="radio" name="end-publication-date-radio" value="manual" id="end-publication-date-manual" /> 
		<label class="inline" for="end-publication-date-manual">
			Choisir une date manuellement...
		</label>
		<fieldset id="fieldset-end-publication-date">
			Sélectionnez une date de fin de publication pour votre annonce : 
			<input type="text" name="end-publication-date" id="end-publication-date" class="required no-text"/> 
			<label>
				<span>Votre annonce restera en ligne jusqu'à la date que vous indiquerez ci-dessus.</span>
			</label>
		</fieldset>
	</div>
	<div class="clearfix"></div>
	<br />
	<br />
	<div class="right">
		<button id="submit-form" name="submit-form" class="small">Suivant</button>
		<a class="btn red small" href="/announcements">Annuler</a>
</div>
</form>