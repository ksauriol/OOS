<?php
/*
 * To see this view in the browser, use:
 * http://hostname/BankingSystem/view/gps
 */
class GPSView {
    
    public static function show($data) {
        ?>
        
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="http://<?=$_GET['base']?>/BankingSystem/css/bootstrap.min.css" type="text/css" />
    <script src="http://<?=$_GET['base']?>/BankingSystem/js/jquery-1.11.3.js"></script>
    <script src="http://<?=$_GET['base']?>/BankingSystem/js/bootstrap.min.js"></script>
    <script src="http://<?=$_GET['base']?>/BankingSystem/js/ViewScripts.js"></script>
    <title>BankingSystem | GPS View</title>
    <style>
        caption {
            font-size: 1.65em;
        }
        
        .rowSelected {
            background-color: #21409A !important;
            color: #D8D8D8;
            outline-style: groove;
            outline-width: 2px;
            outline-color: #213474;
        }
        
        #request_url {
            font-size: 1.1em;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">

<section class="row">
    <div class="col-sm-12"><?php
        if (!isset($data['profiles']) || empty($data['profiles'])):
                ?><p>No profiles to show yet</p><?php
        else: ?>
        <p class="alert alert-info">
            The request url generation stuff works for add/edit/delete, but not getall yet (get all gps data for a particular member).
            You can click on rows to select them for deletion, or to fill in the edit form.  Since these commands require an email
            and password, make sure the profile you are trying to add data for has a password assigned to it.
            The getall command is: http://hostname/BankingSytem/gps/getall?email=EMAIL&password=PASSWORD
        </p>
        <table id="table_profiles" class="table table-striped table-hover table-condensed table-responsive">
            <caption>Profiles</caption>
            <thead>
                <tr>
                    <th>profileID</th>
                    <th>firstName</th>
                    <th>middleName</th>
                    <th>lastName</th>
                    <th>email</th>
                    <th>phone</th>
                    <th>gender</th>
                    <th>address</th>
                    <th>dob</th>
                    <th>temp</th>
                    <th>timeOfTemp</th>
                    <th>password</th>
                    <th>SSN</th>
                </tr>
            </thead>
            <tbody><?php
            foreach ($data['profiles'] as $gps): ?>
                <tr id="profile_<?=$gps->getProfileID()?>" class="profileRow">
                    <td id="profile_profileID_<?=$gps->getProfileID()?>" class="profileID"><?=$gps->getProfileID()?></td>
                    <td id="profile_firstName_<?=$gps->getProfileID()?>" class="firstName"><?=$gps->getFirstName()?></td>
                    <td id="profile_middleName_<?=$gps->getProfileID()?>" class="middleName"><?=$gps->getMiddleName()?></td>
                    <td id="profile_lastName_<?=$gps->getProfileID()?>" class="lastName"><?=$gps->getLastName()?></td>
                    <td id="profile_email_<?=$gps->getProfileID()?>" class="email"><?=$gps->getEmail()?></td>
                    <td id="profile_phone_<?=$gps->getProfileID()?>" class="phone"><?=$gps->getPhoneNumber()?></td>
                    <td id="profile_gender_<?=$gps->getProfileID()?>" class="gender"><?=$gps->getGender()?></td>
                    <td id="profile_address_<?=$gps->getProfileID()?>" class="address"><?=$gps->getAddress()?></td>
                    <td id="profile_dob_<?=$gps->getProfileID()?>" class="dob"><?=$gps->getDOB()?></td>
                    <td id="profile_temp_<?=$gps->getProfileID()?>" class="temp"><?=$gps->getTemp()?></td>
                    <td id="profile_timeOfTemp_<?=$gps->getProfileID()?>" class="timeOfTemp"><?=$gps->getTimeOfTemp()?></td>
                    <td id="profile_password_<?=$gps->getProfileID()?>" class="password"><?=$gps->getPassword()?></td>
                    <td id="profile_SSN_<?=$gps->getProfileID()?>" class="SSN"><?=$gps->getSSN()?></td>
                </tr><?php
            endforeach; ?>
            </tbody>
        </table><?php
        endif; ?>
    </div>
</section>
<section class="row">
    <div class="col-sm-12"><?php
        if (!isset($data['gps']) || empty($data['gps'])):
                ?><p>No GPS data to show yet</p><?php
        else: ?>
        <table id="table_gpsData" class="table table-striped table-hover table-condensed table-responsive">
            <caption>GPSData</caption>
            <thead>
                <tr>
                    <th>gpsID</th>
                    <th>profileID</th>
                    <th>latitude</th>
                    <th>longitude</th>
                    <th>altitude</th>
                    <th>dateAndTime</th>
                </tr>
            </thead>
            <tbody><?php
            foreach ($data['gps'] as $gps): ?>
                <tr id="gps_<?=$gps->getID()?>" class="gpsRow">
                    <td id="gps_gpsID_<?=$gps->getID()?>"><?=$gps->getID()?></td>
                    <td id="gps_profileID_<?=$gps->getID()?>"><?=$gps->getProfileID()?></td>
                    <td id="gps_latitude_<?=$gps->getID()?>"><?=$gps->getLatitude()?></td>
                    <td id="gps_longitude_<?=$gps->getID()?>"><?=$gps->getLongitude()?></td>
                    <td id="gps_altitude_<?=$gps->getID()?>"><?=$gps->getAltitude()?></td>
                    <td id="gps_dateAndTime_<?=$gps->getID()?>"><?=$gps->getDateAndTime()?></td>
                </tr><?php
            endforeach; ?>
            </tbody>
        </table><?php
        endif; ?>
    </div>
</section>

<section class="row">
    <div class="col-sm-12">
        <div class="row">
        	<div class="col-sm-6 col-sm-offset-3">
                <button type="button" id="gps_delete" class="btn btn-default btn-block" disabled="disabled">
                    <span class="glyphicon glyphicon-trash"></span>
                    &nbsp;Generate Delete String
                </button>
            </div>
        </div>
        <hr />
        <form action="" method="get">
        
            <div class="row">
                <div class="form-group col-sm-12">
                    <label for="request_url">request url</label>
                    <input type="text" id="request_url" name="gps_urlstring_add" value="" class="form-control" />
                </div>
            </div>
            
            <div class="row">
                <div class="form-group col-sm-12">
                    <button type="button" id="go" class="btn btn-default btn-block" disabled="disabled">
                        Go
                    </button>
                </div>
            </div>
            
        </form>
    </div>
</section>
<section class="row">
    <div id="gps_add_column" class="col-sm-6">
        <hr />
        <form action="/BankingSystem/gps/add" method="get" class="form-horizontal">
            <fieldset>
                <legend>Add GPS Data</legend>
                <div class="form-group">
                    <label for="gps_profileID_add" class="control-label col-xs-3 col-sm-12 col-md-4">profileID</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_profileID_add" name="profileID" class="form-control" size="10" autofocus="autofocus" required="required" maxlength="10" tabindex="1" pattern="^[0-9]+$" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="gps_latitude_add" class="control-label col-xs-3 col-sm-12 col-md-4">latitude</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_latitude_add" name="latitude" class="form-control" size="10" required="required" maxlength="50" tabindex="2" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="gps_longitude_add" class="control-label col-xs-3 col-sm-12 col-md-4">longitude</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_longitude_add" name="longitude" required="required" size="10" maxlength="50" class="form-control" tabindex="3" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="gps_altitude_add" class="control-label col-xs-3 col-sm-12 col-md-4">altitude</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_altitude_add" name="altitude" required="required" size="10" maxlength="50" class="form-control" tabindex="4" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="gps_dateAndTime_add" class="control-label col-xs-3 col-sm-12 col-md-4">dateAndTime</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_dateAndTime_add" name="notes" required="required" class="form-control" size="30" maxlength="25" tabindex="5" title="yyyy-mm-dd hh:mm:ss" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="gps_email_add" class="control-label col-xs-3 col-sm-12 col-md-4">email</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_email_add" name="email" required="required" class="form-control" size="30" maxlength="25" tabindex="6" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="gps_password_add" class="control-label col-xs-3 col-sm-12 col-md-4">password</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_password_add" name="password" required="required" class="form-control" size="30" maxlength="25" tabindex="7" /><br />
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="btn-group btn-group-justified col-sm-12" role="group">
                        <input type="hidden" id="gps_base" name="gps_base" value="<?=$_GET['base']?>" />
                        
                        <div class="btn-group" role="group">
                            <button type="button" id="gps_generate_add" class="btn btn-primary" tabindex="8">
                                <span class="glyphicon glyphicon-plus"></span>
                                &nbsp;Generate String
                            </button>
                        </div>
                        
                        <div class="btn-group" role="group">
                            <button type="button" id="gps_clear_add" class="btn btn-default" tabindex="9">
                                <span class="glyphicon glyphicon-remove"></span>
                                &nbsp;Clear
                            </button>
                        </div>
                    </div>
                    
                </div>
            </fieldset>
        </form>
    </div>
    
    <div id="gps_edit_column" class="col-sm-6">
        <hr />
        <form action="/BankingSystem/gps/edit" method="get" class="form-horizontal">
            <fieldset>
                <legend>Edit GPS Data</legend>
                <div class="form-group">
                    <label for="gps_gpsID_edit" class="control-label col-xs-3 col-sm-12 col-md-4">gpsID</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_gpsID_edit" name="profileID" class="form-control" size="10" autofocus="autofocus" required="required" maxlength="10" tabindex="1" pattern="^[0-9]+$" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="gps_profileID_edit" class="control-label col-xs-3 col-sm-12 col-md-4">profileID</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_profileID_edit" name="profileID" class="form-control" size="10" autofocus="autofocus" required="required" maxlength="10" tabindex="1" pattern="^[0-9]+$" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="gps_latitude_edit" class="control-label col-xs-3 col-sm-12 col-md-4">latitude</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_latitude_edit" name="latitude" class="form-control" size="10" required="required" maxlength="50" tabindex="2" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="gps_longitude_edit" class="control-label col-xs-3 col-sm-12 col-md-4">longitude</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_longitude_edit" name="longitude" required="required" size="10" maxlength="50" class="form-control" tabindex="3" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="gps_altitude_edit" class="control-label col-xs-3 col-sm-12 col-md-4">altitude</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_altitude_edit" name="altitude" required="required" size="10" maxlength="50" class="form-control" tabindex="4" /><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="gps_dateAndTime_edit" class="control-label col-xs-3 col-sm-12 col-md-4">dateAndTime</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_dateAndTime_edit" name="notes" required="required" class="form-control" size="30" maxlength="25" tabindex="5" title="yyyy-mm-dd hh:mm:ss" /><br />
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="gps_email_edit" class="control-label col-xs-3 col-sm-12 col-md-4">email</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_email_edit" name="email" required="required" class="form-control" size="30" maxlength="25" tabindex="6" /><br />
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="gps_password_edit" class="control-label col-xs-3 col-sm-12 col-md-4">password</label>
                    <div class="col-xs-9 col-sm-12 col-md-8">
                        <input type="text" id="gps_password_edit" name="password" required="required" class="form-control" size="30" maxlength="25" tabindex="7" /><br />
                    </div>
                </div>
                
                <div class="form-group">
                    
                    <div class="btn-group btn-group-justified col-sm-12" role="group">
                        <div class="btn-group" role="group">
                            <button type="button" id="gps_generate_edit" class="btn btn-primary" tabindex="8">
                                <span class="glyphicon glyphicon-plus"></span>
                                &nbsp;Generate String
                            </button>
                        </div>
                        
                        <div class="btn-group" role="group">
                            <button type="button" id="gps_clear_edit" class="btn btn-default" tabindex="9">
                                <span class="glyphicon glyphicon-remove"></span>
                                &nbsp;Clear
                            </button>
                        </div>
                    </div>
                    
                </div>
            </fieldset>
        </form>
    </div>
</section>



<section class="row">
    <div class="col-sm-12">
        
    </div>
</section>

</div>
</body>
</html>
        
        <?php
    }
    
}
?>