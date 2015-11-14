$(document).ready(function() {
	$('#gps_generate_add').click(addGPS_generate);
	$('#gps_generate_edit').click(editGPS_generate);
	$('#gps_clear_add').click(clearForm);
	$('#gps_clear_edit').click(clearForm);
	$('#gps_delete').click(deleteGPS_generate);
	$('#go').click(go);
	$('#request_url_clear').click(function() { $('#request_url').val(''); } );
	$('#gps_getall').click(getallGPS_generate);
	$('#request_url').keydown(checkGoButtonState);
	
	// add hover and click listeners for measurement rows
	$('table > tbody > tr').each(function(index, element) {
		var tableType;
		$(element).hover(
			function() { $(element).addClass('rowHover');    },
			function() { $(element).removeClass('rowHover'); }
		);
		tableType = $(element).attr('id').split('_')[0];
		if (tableType == 'gps')
			$(element).click(rowClicked_gps);
		else if (tableType == 'profile')
			$(element).click(rowClicked_profile);
	});
	
	checkGoButtonState();
});

function getallGPS_generate() {
	if ($('#profiles_table .rowSelected').length == 0) {
		$('#gps_getall').prop('disabled', true);
		return;
	}
	
	var urlstring = 'http://' + $('#base').val() + '/BankingSystem/gps/getall?';
	var profileID = $('#profiles_table .rowSelected').attr('id').split('_')[1];
	var email = $('#profile_email_' + profileID).text();
	var password = $('#profile_password_' + profileID).text();
	
	urlstring = urlstring + 'email=' + email;
	urlstring = urlstring + '&password=' + password;
	
	$('#request_url').val(urlstring).select();
	checkGoButtonState();
}

function rowClicked_profile() {
	var selected_row;
	
	// activate getall button
	$('#gps_getall').prop('disabled', false).removeClass('btn-default').addClass('btn-primary');
	
	// deselect all rows
	$('#profiles_table > tbody > tr').each(function(index, element) {
		$(element).removeClass('rowSelected');
	});

	// select clicked row
	$(this).addClass('rowSelected');
}

function deleteGPS_generate(event) {
	if ($('#gps_table .rowSelected').length == 0) {
		$('#gps_delete').prop('disabled', true);
		return;
	}
	
	var urlstring = 'http://' + $('#base').val() + '/BankingSystem/gps/delete?';
	var gpsID = $('#gps_table .rowSelected').attr('id').split('_')[1];
	var profileID = $('#gps_profileID_' + gpsID).text();
	var email = $('#profile_email_' + profileID).text();
	var password = $('#profile_password_' + profileID).text();
	
	urlstring = urlstring + 'email=' + email;
	urlstring = urlstring + '&password=' + password;
	urlstring = urlstring + '&gpsID=' + gpsID;
	
	$('#request_url').val(urlstring).select();
	checkGoButtonState();
}

// selects a row when clicked, and fills in the edit form
function rowClicked_gps(event) {
	var selected_row;
	
	// activate delete button
	$('#gps_delete').prop('disabled', false).removeClass('btn-default').addClass('btn-danger');
	
	// deselect all rows
	$('#gps_table > tbody > tr').each(function(index, element) {
		$(element).removeClass('rowSelected');
	});

	// select clicked row
	$(this).addClass('rowSelected');
	
	// fill in edit form (w/values from GPSData table)
	$('#gps_table .rowSelected').children().each(function(index, element) {
		var col_name = $(element).attr('id').split('_')[1];
		$('#gps_' + col_name + '_edit').val($(element).text());
	});
	
	// fill in edit form (w/values from Profiles table)
	var gpsID = $('#gps_table .rowSelected').attr('id').split('_')[1];
	var profileID = $('#gps_profileID_' + gpsID).text();
	var email = $('#profile_email_' + profileID).text();
	var password = $('#profile_password_' + profileID).text();
	$('#gps_email_edit').val(email);
	$('#gps_password_edit').val(password);
}

function checkGoButtonState() {
	if ($('#request_url').val().trim().length > 0)
		$('#go').prop('disabled', false).removeClass('btn-default').addClass('btn-success');
	else
		$('#go').prop('disabled', true).removeClass('btn-success').addClass('btn-default');
}

function clearForm() {
	var formType = $(this).attr('id').split('_')[2];
	$('#gps_' + formType + '_column input[type=text]').val('');
}

function go(event) {
	checkGoButtonState();
	if ($('#request_url').val().trim().length > 0)
		window.location.assign($('#request_url').val());
}

function editGPS_generate(event) {
	var urlstring = 'http://' + $('#base').val() + '/BankingSystem/gps/edit?';
	
	urlstring = urlstring + '&email=' + $('#gps_email_edit').val();
	urlstring = urlstring + '&password=' + $('#gps_password_edit').val();
	urlstring = urlstring + '&gpsID=' + $('#gps_gpsID_edit').val();
	urlstring = urlstring + '&profileID=' + $('#gps_profileID_edit').val();
	urlstring = urlstring + '&latitude=' + $('#gps_latitude_edit').val();
	urlstring = urlstring + '&longitude=' + $('#gps_longitude_edit').val();
	urlstring = urlstring + '&altitude=' + $('#gps_altitude_edit').val();
	urlstring = urlstring + '&dateAndTime=' + $('#gps_dateAndTime_edit').val();
	
	$('#request_url').val(urlstring).select();
	checkGoButtonState();
}

function addGPS_generate(event) {
	var urlstring = 'http://' + $('#base').val() + '/BankingSystem/gps/add?';
	
	urlstring = urlstring + '&email=' + $('#gps_email_add').val();
	urlstring = urlstring + '&password=' + $('#gps_password_add').val();
	urlstring = urlstring + '&profileID=' + $('#gps_profileID_add').val();
	urlstring = urlstring + '&latitude=' + $('#gps_latitude_add').val();
	urlstring = urlstring + '&longitude=' + $('#gps_longitude_add').val();
	urlstring = urlstring + '&altitude=' + $('#gps_altitude_add').val();
	urlstring = urlstring + '&dateAndTime=' + $('#gps_dateAndTime_add').val();
	
	$('#request_url').val(urlstring).select();
	checkGoButtonState();
}