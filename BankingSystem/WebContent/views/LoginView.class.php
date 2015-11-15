<?php

class LoginView {
    
    public static function show($data = null) {
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
    <script src="http://<?=$_GET['base']?>/BankingSystem/js/LoginViewScripts.js"></script>
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
        
        #request_url_label {
            font-size: 1.3em;
        }
        
        .rowHover {
            background-color: #A8A8A8 !important;
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
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non quo est eius illo ut id esse debitis alias iure quod deserunt optio fuga incidunt omnis sed facilis accusamus recusandae? Esse.
        </p>
        <table id="profiles_table" class="table table-striped table-hover table-condensed table-responsive">
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
                    <td id="profile_profileID_<?=$gps->getProfileID()?>"><?=$gps->getProfileID()?></td>
                    <td id="profile_firstName_<?=$gps->getProfileID()?>"><?=$gps->getFirstName()?></td>
                    <td id="profile_middleName_<?=$gps->getProfileID()?>"><?=$gps->getMiddleName()?></td>
                    <td id="profile_lastName_<?=$gps->getProfileID()?>"><?=$gps->getLastName()?></td>
                    <td id="profile_email_<?=$gps->getProfileID()?>"><?=$gps->getEmail()?></td>
                    <td id="profile_phone_<?=$gps->getProfileID()?>"><?=$gps->getPhoneNumber()?></td>
                    <td id="profile_gender_<?=$gps->getProfileID()?>"><?=$gps->getGender()?></td>
                    <td id="profile_address_<?=$gps->getProfileID()?>"><?=$gps->getAddress()?></td>
                    <td id="profile_dob_<?=$gps->getProfileID()?>"><?=$gps->getDOB()?></td>
                    <td id="profile_temp_<?=$gps->getProfileID()?>"><?=$gps->getTemp()?></td>
                    <td id="profile_timeOfTemp_<?=$gps->getProfileID()?>"><?=$gps->getTimeOfTemp()?></td>
                    <td id="profile_password_<?=$gps->getProfileID()?>"><?=$gps->getPassword()?></td>
                    <td id="profile_SSN_<?=$gps->getProfileID()?>"><?=$gps->getSSN()?></td>
                </tr><?php
            endforeach; ?>
            </tbody>
        </table><?php
        endif; ?>
    </div>
</section>
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
    
        <div class="btn-group btn-group-justified col-sm-12" role="group">
            <div class="btn-group" role="group">
                <button type="button" id="login_btn" class="btn btn-default generator-btn" disabled="disabled">
                    <span class="glyphicon glyphicon-ok"></span>
                    &nbsp;Generate Login String
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" id="logout_btn" class="btn btn-default generator-btn" disabled="disabled">
                    <span class="glyphicon glyphicon-ok"></span>
                    &nbsp;Generate Logout String
                </button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <hr />
        <form action="" method="get">
            <input type="hidden" id="base" name="base" value="<?=$_GET['base']?>" />
            
            <div class="row">
                <div class="col-xs-4 col-sm-3">
                    <label id="request_url_label" for="request_url">Request URL</label>
                </div>
                <div class="col-xs-3 col-xs-offset-5 col-sm-1 col-sm-offset-8">
                    <button type="button" id="request_url_clear" class="btn btn-default">Clear</button>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12">
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
</div>

</div>
</body>
</html>
        
        <?php
    }
    
}

?>