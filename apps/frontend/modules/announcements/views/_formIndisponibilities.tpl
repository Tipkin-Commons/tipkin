{literal}
<script type="text/javascript" src="/js/jquery.ui.datepicker-fr.js"></script>
<script type="text/javascript">
<!--

$(function(){
	showDatePicker();	
});


$('#submit-form-draft').click(function(){
	$('#save-as-draft').val('yes');
	$('#submit-form').click();
});


var end = $('#label-end-publication-date').text();
$('#label-end-publication-date').text(parseToDateFr(end));

$('#button-date-add').click(function(){
	//On récupère la date selectionnée
	var selectedDate = $('#selected-date').val();
	//On vérifie qu'il a bien coché une radiobox
	if($('input[name="date-option"]:checked').length > 0)
	{
		$('#date-option-error').hide();
		var option = $('input[name="date-option"]:checked').val();
		//On crée un input hidden
		var inputHidden = '<input type="hidden" id="' + selectedDate + '" name="' + selectedDate + '" value="' + option + '"/>';
		//On ajoute le champs à la div
		$('#calendar-dates').append(inputHidden);
				
		//On ajoute notre date au un champs hidden les listant
		var appendDate = selectedDate.replace(new RegExp('/','g'), '-');
		var listDate = $('#date-list').val();

		if(listDate != '')
			appendDate = ',' + appendDate;

		listDate += appendDate;

		$('#date-list').val(listDate);

		//On masque la div de creation
		$('#create-date').hide();
		//On affiche la div de creation
		$('#edit-date').show();

		$('#calendar-options fieldset').hide();
	}
	else
	{
		$('#date-option-error').show();
	}

	$('#calendar').datepicker('refresh');
	
	return false;
});

$('#button-date-update').click(function(){
	//On récupère le bon champs hidden correspondant
	var selectedDate = $('#selected-date').val();
	$('#'+selectedDate).val($('input[name="date-option"]:checked').val());
	//On supprime le champs

	$('#calendar').datepicker('refresh');
	$('#calendar-options fieldset').hide();

	return false;
});

$('#button-date-delete').click(function(){
	
	var selectedDate = $('#selected-date').val();
	var listDate = $('#date-list').val();

	var arrayDate = listDate.split(',');

	var index = arrayDate.indexOf(selectedDate);
	if(arrayDate.indexOf(selectedDate) != -1)
		arrayDate.splice(index, 1);

	listDate = arrayDate.toString();
	
	$('#date-list').val(listDate);
	
	//On supprime le champs
	$('#'+selectedDate).remove();

	$('#calendar').datepicker('refresh');

	$('input[name="date-option"]').prop('checked', false);
	
	$('#create-date').show();
	//On affiche la div de creation
	$('#edit-date').hide();

	$('#calendar-options fieldset').hide();

	return false;
});

function showDatePicker()
{
	var today = new Date('{/literal}{$smarty.now|date_format:"%Y/%m/%d"}{literal}');
	var tomorow = new Date(today.setDate(today.getDate() + 1));
	var publicationDate = new Date('{/literal}{$announce->getPublicationDate()|date_format:"%Y/%m/%d"}{literal}');
	if(publicationDate < tomorow)
	{
		publicationDate = tomorow;
	}

	displayOptions(publicationDate.dateFormat('Y/m/d'), null);
	
	var dateMin = new Date(publicationDate);
	
	$('#calendar').datepicker({ 
		minDate: dateMin,
		dateFormat: 'yy/mm/dd',
		maxDate: '{/literal}{$announce->getEndPublicationDate()|date_format:"%Y/%m/%d"}{literal}',
		onSelect: displayOptions,
		beforeShowDay: closedDay
	});
}

function displayOptions(dateText, inst)
{
	$('#date-option-selected').text('Indisponibilité du ' + parseToDateFr(dateText) + ' :');

	$('#selected-date').val(dateText.replace(new RegExp('/','g'), '-'));
	
	$('#calendar-options fieldset').show();
	
	if($('#'+dateText.replace(new RegExp('/','g'), '-')).length != 0)
	{
		$('#date-option-'+$('#'+dateText.replace(new RegExp('/','g'), '-')).val()).click();
		
		$('#create-date').hide();
		$('#edit-date').show();
	}
	else
	{
		$('input[name="date-option"]').prop('checked', false);
		
		$('#create-date').show();
		$('#edit-date').hide();
	}
}

function closedDay(date)
{
	var today = new Date('{/literal}{$smarty.now|date_format:"%Y/%m/%d"}{literal}');
	var currentDate = new Date(date);
	var listDateToShow = $('#date-list').val().split(',');

	var display = true;
	var classToAdd = '';
	var tooltip = '';
	
	for(var i = 0; i < listDateToShow.length ; i++ )
	{
		var testDate = new Date(listDateToShow[i].replace(new RegExp('-','g'),'/'));
		if(currentDate > today && currentDate.getDate() == testDate.getDate() && currentDate.getMonth() == testDate.getMonth() && currentDate.getFullYear() == testDate.getFullYear())
		{
			value = $('#'+listDateToShow[i]).val();
			classToAdd = 'calendar-' + value;
			switch(value)
			{
			case 'morning':
			  tooltip = 'Indisponible le matin';
			  break;
			case 'evening':
				tooltip = 'Indisponible le soir';
			  break;
			case 'all-day':
				tooltip = 'Indisponible la journée entière';
				  break;
			}
		}
	}
	return [display, classToAdd, tooltip];	
}

function parseToDateFr(date)
{
	var year = date.substring(0,4);
	var month = date.substring(5,7);
	var day = date.substring(8,10);

	return (day + '/' + month + '/' + year);
}
//-->
</script>
{/literal}
<form method="post" id="form-indisponibilities">
	<h5>Récapitulatif</h5>
	Votre annonce sera publiée à partir du {date_format(date_create($announce->getPublicationDate()),'d/m/Y')}, en fonction de la validation d'un administrateur.
	<br /><br />
	Elle cessera d'être active et passera dans vos annonces archivées le {date_format(date_create($announce->getEndPublicationDate()),'d/m/Y')}.
	<br /><br />
	<hr class="alt2"/>

	<h5>Indisponibilités</h5>
	Si vous le souhaitez, vous pouvez ajouter des dates d'indisponibilités. Ces jours  ne seront plus sélectionnables par les Tippeurs lorsqu'ils consulteront votre annonce.
	<br /><br />
	<label>
		Choisissez une date dans le calendrier ci-dessous et gérez vos indisponibilités grâce au formulaire ci-contre
		<span>(Répétez cette action pour chaque indisponibilité)</span> 
		:
	</label>
	<br /><br />
	
	<div id="calendar" class="calendar-datepicker" style="border: 1px solid #CCCCCC; background-color:#F5F5F5; width: 38%;float: left; margin-left: 18px;"></div>
	
	<div id="calendar-options" class="edit">
		<fieldset>
			<label id="date-option-selected"></label>
			
			<br />
			
			<input type="hidden" id="selected-date"/>
			<br />
			
			<input type="radio" name="date-option" id="date-option-morning" value="morning"/>
			<label class="inline" for="date-option-morning">Matin</label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" name="date-option" id="date-option-evening" value="evening"/>
			<label class="inline" for="date-option-evening">Soir</label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" name="date-option" id="date-option-all-day" value="all-day"/>
			<label class="inline" for="date-option-all-day">Journée entière</label>
			<br />
			<div id="date-option-error" class="hide">
				<br />
				<label class="error" style="margin-top: -20px;">Veuillez cocher un élément</label>
			</div>
			<br />
			<div class="right" id="create-date">
				<button id="button-date-add" class="small green">Ajouter</button>
			</div>
			<div class="right hide" id="edit-date">
				<button id="button-date-update" class="small green">Modifier</button>
				<button id="button-date-delete" class="small red">Supprimer</button>
			</div>
		</fieldset>
	</div>
	<div id="calendar-dates">
		{if isset($dateList)}
			<input type="hidden" name="date-list" id="date-list" value="{$dateList}" />
		{else}
			<input type="hidden" name="date-list" id="date-list" />
		{/if}
		{if isset($unavailabilities)}
			{foreach from=$unavailabilities item=unavailability}
			<input type="hidden" name="{$unavailability->getDate()}" id="{$unavailability->getDate()}" value="{$unavailability->getDateOption()}"/>
			{/foreach}
		{/if}
	</div>
	<div class="clearfix"></div>
	<br /><br />
	<div class="right">
		{if $announce->getStateId() ==  AnnouncementStates::STATE_DRAFT || $announce->getStateId() ==  AnnouncementStates::STATE_REFUSED}
			<input type="hidden" name="save-as-draft" id="save-as-draft" value="no"/>
			
			<a id="submit-form-draft" name="submit-form-draft" class="btn green small">Enregistrer dans mes brouillons</a>
		{/if}
		<button id="submit-form" name="submit-form" class="small green">Terminer</button>
		<a class="btn red small" href="/announcements">Annuler</a>
	</div>
</form>