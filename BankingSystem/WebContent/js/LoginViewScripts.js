$(document).ready(function() {
	$('#go').click(go);
	$('#request_url_clear').click(function() { $('#request_url').val(''); } );
	$('#login_btn').click(login_generate);
	$('#logout_btn').click(logout_generate);
	$('#request_url').keydown(checkGoButtonState);
	
	// add hover and click listeners for table rows
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

function login_generate() {
	if ($('#profiles_table .rowSelected').length == 0) {
		$('.generator-btn').prop('disabled', true);
		return;
	}
	
	var profileID = $('#profiles_table .rowSelected').attr('id').split('_')[1];
	var email = $('#profile_email_' + profileID).text();
	var password = $('#profile_password_' + profileID).text();
	
	var urlstring = 'http://' + $('#base').val() + '/BankingSystem/login?';
	urlstring = urlstring + 'email=' + email;
	urlstring = urlstring + '&password=' + password;
	
	$('#request_url').val(urlstring).select();
	checkGoButtonState();
}

function logout_generate() {
	if ($('#profiles_table .rowSelected').length == 0) {
		$('.generator-btn').prop('disabled', true);
		return;
	}
	
	var profileID = $('#profiles_table .rowSelected').attr('id').split('_')[1];
	var email = $('#profile_email_' + profileID).text();
	var password = $('#profile_password_' + profileID).text();
	
	var urlstring = 'http://' + $('#base').val() + '/BankingSystem/logout?';
	urlstring = urlstring + 'email=' + email;
	urlstring = urlstring + '&password=' + password;
	
	$('#request_url').val(urlstring).select();
	checkGoButtonState();
}

function rowClicked_profile() {
	// activate generator buttons
	$('.generator-btn').prop('disabled', false).removeClass('btn-default').addClass('btn-primary');
	
	// deselect currently selected row
	$('#profiles_table .rowSelected').removeClass('rowSelected');

	// select clicked row
	$(this).addClass('rowSelected');
}

function checkGoButtonState() {
	if ($('#request_url').val().trim().length > 0)
		$('#go').prop('disabled', false).removeClass('btn-default').addClass('btn-success');
	else
		$('#go').prop('disabled', true).removeClass('btn-success').addClass('btn-default');
}

function go(event) {
	checkGoButtonState();
	if ($('#request_url').val().trim().length > 0)
		window.location.assign($('#request_url').val());
}