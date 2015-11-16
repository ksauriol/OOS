<?php
//if (ob_get_contents() === false)
    ob_start();
include_once("includer.php");
//if (session_status() == PHP_SESSION_NONE)
  //  session_start();

$base = null;
$control = 'none';
$arguments = array();

// parse the request URL
//$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$url =$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
/*$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
list($fill, $base, $control, $action, $arguments) =
explode('/', $url, 5) + array("", "", "", "", null); 
     $_SESSION['base'] = $base;
	 $_SESSION['control'] = $control; 
	 $_SESSION['action'] = $action;
	 $_SESSION['arguments'] = $arguments;*/
//print_r($url.'<br>');
$part = preg_split("/\/BankingSystem\//", $url, null, PREG_SPLIT_NO_EMPTY);
$urlPieces = preg_split("/\//", $part[1], null, PREG_SPLIT_NO_EMPTY);
$base = $part[0];
$_GET['base'] = $base;
$numPieces = count($urlPieces);
//print_r($numPieces.'<br>');
if ($numPieces > 0) 
    $control = $urlPieces[0];
if ($numPieces > 1)
    $arguments = array_slice($urlPieces, 1);

// run the requested controller
switch ($control) {
    case 'account' : ProfileController::run($arguments); break;
    case 'bank'    : BankController::run($arguments); break;
    case 'login'   : LoginController::run(array_merge(array($control), $arguments));
    case 'logout'  : LoginController::run(array_merge(array($control), $arguments));
    default: View::run();break;
      /*  $replyMsg = new ReplyMessage();
        $replyMsg->status = 'failed';
        $replyMsg->message = "$control is not recognized command";
        $json = json_encode($replyMsg);
        echo $json;*/
}

function sendMessage($status, $message) {
    $replyMsg = new ReplyMessage();
    $replyMsg->status = $status;
    $replyMsg->message = $message;
    
    $json = json_encode($replyMsg);
    echo $json;
}

ob_end_flush();
?>