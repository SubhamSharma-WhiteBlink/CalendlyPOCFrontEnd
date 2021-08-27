<?php

$post = file_get_contents('php://input');
header('Content-Type: application/json');

session_start();

$_SESSION["email"]=$post;
/*$_SESSION["name"]=$userName;*/
session_commit();

echo "true";
