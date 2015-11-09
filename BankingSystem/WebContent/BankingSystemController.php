<?php
if (ob_get_contents() === false)
    ob_start();
include_once("includer.php");
if (session_status() == PHP_SESSION_NONE)
    session_start();

$base = null;
$control = 'none';
$arguments = array();

// parse the request URL
$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$urlPieces = preg_split("/\//", $url, null, PREG_SPLIT_NO_EMPTY);
$numPieces = count($urlPieces);
if ($numPieces > 0)
    $base = $urlPieces[0];
if ($numPieces > 1) 
    $control = $urlPieces[1];
if ($numPieces > 2)
    $arguments = array_slice($urlPieces, 2);

// run the requested controller
switch ($control) {
    case 'account' : ProfileController::run($arguments); break;
   // case 'signup' : SignupController::run($arguments); break;
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