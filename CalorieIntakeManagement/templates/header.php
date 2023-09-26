<?php
// Initialize the session
session_start();
require_once "../config.php";

// Check if the user is already logged in, if yes then redirect him to dashboard page
if(!isset($_SESSION["id"]) || !$_SESSION["id"]) {
    header("location: ../login.php");
    exit;
}
//require_once "../functions.php";
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>BD App | Delia Barbuta</title>
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand">BD APP</a>
            <div>
                <a href="/bd-proiect/logout.php">Log out</a>
            </div>
        </div>
    </nav>