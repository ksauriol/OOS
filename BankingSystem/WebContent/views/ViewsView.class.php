<?php
class ViewsView {
    
    public static function show() {
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

<h2><a href="/BankingSystem/view/login">Login</a></h2><br />
<h2><a href="/BankingSystem/view/gps">GPS</a></h2><br />

</div>
</body>
</html>
        
        <?php
    }
    
}
?>