<?php
session_start();

$email=$_SESSION['email'];

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://myselfhealthcalendly.herokuapp.com/calendly/checkStatus'.$email,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);

curl_close($curl);

if($response!=='true')
{
    session_destroy();
    session_commit();
    header('Location: calendlyIntegration.php');
    exit;
} else {
    session_commit();
    header('Location: meeting.php');
    exit;
}