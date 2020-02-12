<?php
/**
 * Created by PhpStorm.
 * User: quand
 * Date: 4/13/2019
 * Time: 8:39 PM
 */
//Get the full request url
$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//Check if the post data contain token or not
try {
    if (!isset($_POST['csrf_token'])) {
        addLog("return magic error: No magic key due to no token", $url);
        echo json_encode(["success" => "false", "magic_key" => "No magic key due to no token"]);
    } else {

        //the token is the timestamp when sending request, minimal difference allowed is 1 second
        if ( time() - $_POST['csrf_token'] < 2) {
            $salt = isset($_POST['salt']) ? $_POST['salt'] : "";
            $magicKey = generateMagicKey($salt);
            if (!$magicKey) {
                echo json_encode(["success" => "false", "magic_key" => "No magic key due to no salt value"]);
                addLog("return magic error: No magic key due to no salt value", $url);
            }
            addLog("return magic key: $magicKey", $url);
            echo json_encode(["success" => "true", "magic_key" => $magicKey]);
        } else{
            echo json_encode(["success" => "true", "magic_key" => "No magic key due to wrong token"]);
        }
    }
} catch (Exception $e) {
    addLog($e->getMessage(), $url);
}



function generateMagicKey($salt)
{
    if (empty($salt)) {
        return false;
    }
    return base64_encode($salt);
}

//create and add log for current app
function addLog($message, $url = null) {
    $date = date('Y-m-d H:i:s');
    file_put_contents("app.log", "[".$date."] INFO: receiving request from ". $url .", result:  " . $message."\n", FILE_APPEND);
}

