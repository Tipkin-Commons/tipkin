{literal}
<script type="text/javascript" src="/js/jquery.ui.datepicker-fr.js"></script>
<script type="text/javascript">
<!--
$(function(){
	showDatePicker();

	$('#date-option-end-date').datepicker();
});

$('input[name="date-option"]').change(function(){
	if($(this).val() == '')
		$('#button-date-reserve').removeClass('green').attr('disabled', 'disabled');
	else
		$('#button-date-reserve').addClass('green').removeAttr('disabled');

	if($(this).val() == 'period')
		$('#date-option-end-date').removeAttr('disabled');
	else
		$('#date-option-end-date').attr('disabled', 'disabled');
}).change();

//{/literal}
//{if $isAuthenticate == 'false'}
//{literal}
$('#button-date-reserve').click(function(){
	$('#popup-connect').click();
	return false;
});
//{/literal}
//{else}
//{literal}
$('#button-date-reserve').click(function(){
	//On récupère la date selectionnée
	var selectedDate = $('#selected-date').val();
	//On vérifie qu'il a bien coché une radiobox
	if($('input[name="date-option"]:checked').length > 0)
	{
		var option = $('input[name="date-option"]:checked').val();

		$('#form-reservation #date').val(selectedDate);

		if(option == 'period')
		{
			endDate = $('#date-option-end-date').val(); 
			if(endDate == '')
			{
				return false;
			}

			$('#form-reservation #date-end').val(parseToDateEn(endDate));
		}
		
		$('#form-reservation #date-option').val(option);

		$('#form-reservation').submit();
	}

	$('#calendar').datepicker('refresh');
	
	return false;
});
//{/literal}
//{/if}
//{literal}

function showDatePicker()
{
	var today = new Date('{/literal}{$smarty.now|date_format:"%Y/%m/%d"}{literal}');
	var tomorow = today.setDate(today.getDate() + 1);

	var publicationDate = new Date('{/literal}{$announce->getPublicationDate()|date_format:"%Y/%m/%d"}{literal}');
	var dateMin = new Date(tomorow);

	if(publicationDate > dateMin)
		dateMin = publicationDate;
	
	$('#calendar').datepicker({ 
		minDate: dateMin,
		dateFormat: 'yy/mm/dd',
//		{/literal}
//		{if $announce->getEndPublicationDate() != null}
//		{literal}
		 maxDate: '{/literal}{$announce->getEndPublicationDate()|date_format:"%Y/%m/%d"}{literal}',
//		{/literal}
//		{/if}
//		{literal}
		onChangeMonthYear: function(year, month, inst) {
			$('#calendar-options').hide();
			},
		onSelect: function(dateText, inst) {
			
				$('#button-date-reserve').removeClass('green').attr('disabled','disabled');
				
				$('#calendar-update-date-success').hide();
				
				
				$('#date-option-selected').text(parseToDateFr(dateText));

				$('#selected-date').val(dateText.replace(new RegExp('/','g'), '-'));
				
				$('#calendar-options').show();

				$('[name="date-option"]').removeAttr('disabled');
				$('[name="date-option"]').next().removeClass('disabled');

				$('input[name="date-option"]').prop('checked', false);
				
				if($('#'+dateText.replace(new RegExp('/','g'), '-')).length != 0)
				{
					// On masque les entrées inutiles
					$('#date-option-'+$('#'+dateText).val()).attr('disabled', 'disabled');
					$('#date-option-'+$('#'+dateText).val()).next().addClass('disabled');

					$('#date-option-all-day').attr('disabled', 'disabled');
					$('#date-option-all-day').next().addClass('disabled');
				}
				//{/literal}
				//{foreach from=$listOfReservations item=reservation}
				//{if $reservation->getStateId() != PaiementStates::CANCELED}
				//{literal}
				var testDate = '{/literal}{$reservation->getDate()}{literal}';
				if(testDate == dateText.replace(new RegExp('/','g'), '-'))
				{
					$('#date-option-all-day').attr('disabled', 'disabled');
					$('#date-option-all-day').next().addClass('disabled');
					
					$('#date-option-{/literal}{$reservation->getDateOption()}{literal}').attr('disabled', 'disabled');
					$('#date-option-{/literal}{$reservation->getDateOption()}{literal}').next().addClass('disabled');
				}
				//{/literal}
				//{/if}
				//{/foreach}
				//{literal}

				$('#date-option-end-date').val(null);
				$('[name="date-option"]').change();
				changeManualCalendarLimit(dateText);
			},
		beforeShowDay: closedDay
	});

	$('#end-publication-date').datepicker({ 
		minDate: dateMin,
		dateFormat: 'yy/mm/dd',
		onSelect: function(dateText, inst) {
				$('#calendar').datepicker("option", "maxDate", dateText);
			}
	});
}

function changeManualCalendarLimit(dateText)
{
	var dates = new Array();
	//{/literal}
	//{foreach from=$listOfReservations item=reservation}
	//{if $reservation->getStateId() != PaiementStates::CANCELED}
	//{literal}
		dates.push('{/literal}{$reservation->getDate()}{literal}');
	//{/literal}
	//{/if}
	//{/foreach}

	//{foreach from=$unavailabilities item=unavailabily}
	//{literal}
		dates.push('{/literal}{$unavailabily->getDate()}{literal}');
	//{/literal}
	//{/foreach}
	//{literal}

	dates.sort();

	var currentDate = new Date(dateText);
	var endDate;
	
	for(var i = 0; i < dates.length; i++)
	{
		var endDateTest = dates[i].replace(new RegExp('-','g'),'/');
		endDateTest = new Date(endDateTest);

		if(currentDate < endDateTest) 
		{
			endDate = endDateTest;
			break;
		}
	}

	currentDate = new Date(currentDate.setDate(currentDate.getDate() + 1));
	if(endDate == null)
		endDate = new Date('{/literal}{$announce->getEndPublicationDate()|date_format:"%Y/%m/%d"}{literal}');
	else
		endDate = new Date(endDate.setDate(endDate.getDate() - 1));

	if(currentDate >= endDate)
	{
		$('#date-option-period').attr('disabled', 'disabled');
		$('#date-option-period').next().addClass('disabled');
	}
	else
	{
		$('#date-option-period').removeAttr('disabled');
		$('#date-option-period').next().removeClass('disabled');

		var minDate = new Date(currentDate.setDate(currentDate.getDate()));
		
		$('#date-option-end-date').datepicker('option', 'minDate', minDate);
		$('#date-option-end-date').datepicker('option', 'maxDate', endDate);
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
			  	//{/literal}
			  	//{if $announce->getIsFullDayPrice()}
			  		//{literal}
				 	display = false;
			 		//{/literal} 
			  	//{/if}
			  	//{literal}
			  tooltip = 'Indisponible le matin';
			  break;
			case 'evening':
				//{/literal}
				//{if $announce->getIsFullDayPrice()}
					//{literal}
					display = false;
					//{/literal} 
				//{/if}
				//{literal}
				tooltip = 'Indisponible le soir';
			  break;
			case 'all-day':
				display = false;
				tooltip = 'Indisponible la journée entière';
				  break;
			}
		}
	}

	//{/literal}
	//{foreach from=$listOfReservations item=reservation}
		//{if $reservation->getStateId() != PaiementStates::CANCELED}
			//{if $reservation->getDateOption() == 'period'}
				//{literal}
				var testDateBegin = new Date('{/literal}{$reservation->getDate()|date_format:"%Y/%m/%d"}{literal}');
				testDateBegin.setHours(0, 0, 0, 0);
				var testDateEnd = new Date('{/literal}{$reservation->getDateEnd()|date_format:"%Y/%m/%d"}{literal}');
				testDateEnd.setHours(23, 59, 59, 0);
				if(testDateBegin <= currentDate && testDateEnd >= currentDate)
				{
					display = false;
					classToAdd += ' calendar-all-day';

					//{/literal}
					//{if $reservation->getStateId() == PaiementStates::WAITING_VALIDATION || $reservation->getStateId() == PaiementStates::WAITING_PAIEMENT}
						//{literal}
						classToAdd += '-reserved';
						//{/literal}
					//{/if}
					//{literal}
				}
				//{/literal}
			//{else}
				//{literal}	
				var testDate = new Date('{/literal}{$reservation->getDate()|date_format:"%Y/%m/%d"}{literal}');
				if(currentDate > today && currentDate.getDate() == testDate.getDate() && currentDate.getMonth() == testDate.getMonth() && currentDate.getFullYear() == testDate.getFullYear())
				{
					if('{/literal}{$reservation->getDateOption()}{literal}' == 'all-day')
					{
						display = false;
					}
					if('{/literal}{$reservation->getDateOption()}{literal}' == 'evening' && classToAdd.indexOf('calendar-morning') >= 0 )
					{
						display = false;
					}
					if('{/literal}{$reservation->getDateOption()}{literal}' == 'morning' && classToAdd.indexOf('calendar-evening') >= 0 )
					{
						display = false;
					}
			
					classToAdd += ' calendar-{/literal}{$reservation->getDateOption()}{literal}';
					//{/literal}
					//{if $reservation->getStateId() == PaiementStates::WAITING_VALIDATION || $reservation->getStateId() == PaiementStates::WAITING_PAIEMENT}
						//{literal}
						classToAdd += '-reserved';
						//{/literal}
					//{/if}
					//{literal}
				}
				//{/literal}
			//{/if}
		//{/if}
	//{/foreach}
	//{literal}
	
	return [display, classToAdd, tooltip];	
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

<h5>Réservations</h5>
<label><span>Cliquez sur une date pour effectuer une réservation</span></label>
<br /><br />
<div id="calendar" class="calendar-datepicker"></div>
<div id="calendar-options" class="hide">
	<fieldset>
		<legend style="font-weight: bold;">Réservation à partir du <label class="inline" id="date-option-selected"></label></legend>
		
		<input type="hidden" id="selected-date"/>
		Choisissez une disponibilité :
		<br />
		{if !$announce->getIsFullDayPrice()}
		<input type="radio" name="date-option" id="date-option-morning" value="morning"/>
		<label class="inline" for="date-option-morning">Matin</label>
		&nbsp;&nbsp;&nbsp;
		<input type="radio" name="date-option" id="date-option-evening" value="evening"/>
		<label class="inline" for="date-option-evening">Soir</label>
		&nbsp;&nbsp;&nbsp;
		{/if}
		<input type="radio" name="date-option" id="date-option-all-day" value="all-day"/>
		<label class="inline" for="date-option-all-day">Journée</label>
		<br />
		<input type="radio" name="date-option" id="date-option-period" value="period"/>
		<label class="inline" for="date-option-period">Jusqu'au :</label>
		<br />
		<input type="text" id="date-option-end-date" name="date-option-end-date" placeholder="Choisir une date..." />
		
		<div class="right" id="create-date">
			{if $isAuthenticate == 'true' && $currentUser->getRoleId() == Role::ROLE_MEMBER_PRO}
				<label><span>Réservations indisponibles pour les Membres Pro</span></label>
			{elseif $isAuthenticate == 'true' && $currentUser->id() == $announce->getUserId()}
				<label><span>Réservations indisponibles sur vos propres annonces</span></label>
			{else}
				<button id="button-date-reserve" disabled="disabled" class="small">Réserver</button>
			{/if}
			<a id="popup-connect" class="lightbox" href="/popup-connect/return-url={$smarty.server.REQUEST_URI}"></a>
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
<div id="reservation" style="height: 0px;">
	<form action="/activities/reservations/new" id="form-reservation" method="post">
		<input type="hidden" name="date" id="date" />
		<input type="hidden" name="date-end" id="date-end" />
		<input type="hidden" name="date-option" id="date-option" />
		{if $isAuthenticate == 'true'}
			<input type="hidden" name="user-subscriber-id" id="user-subscriber-id" value="{$currentUser->id()}"/>
		{/if}
		<input type="hidden" name="contact-group-id" id="contact-group-id" value="{$contactGroupId}"/>
		<input type="hidden" name="user-owner-id" id="user-owner-id" value="{$announce->getUserId()}"/>
		<input type="hidden" name="announcement-id" id="announcement-id" value="{$announce->id()}"/>
		<input type="hidden" name="currency-id" id="currency-id" value="default" />
	</form>
</div>