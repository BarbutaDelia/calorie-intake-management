<?php
require_once "../config.php";
require_once "../functions.php";
session_start();

$current_date = "'" . strtoupper(date('d-M-y')) . "'";
$session_id = $_SESSION["id"];
$sport_id = $_GET["sid"]; //id_ul ingredientului pe care l-am selectat pt stergere


$query = 'DELETE FROM a_s a WHERE a.sports_id = ' . $sport_id . ' and a.accounts_id = ' . $session_id . ' and TO_CHAR(a."date") = ' . $current_date;

$result = runQueryInsert($query, $conn);
oci_commit($conn);
oci_close($conn);
header("location: ../pages/user-dashboard.php");
?>