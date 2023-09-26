<?php
require_once "../config.php";
require_once "../functions.php";
session_start();

$current_date = "'" . strtoupper(date('d-M-y')) . "'";
$session_id = $_SESSION["id"];
$ingredient_id = $_GET["iid"]; //id_ul ingredientului pe care l-am selectat pt stergere


$query = 'DELETE FROM a_i a WHERE a.ingredients_id = ' . $ingredient_id . ' and a.accounts_id = ' . $session_id . ' and TO_CHAR(a."date") = ' . $current_date;

$result = runQueryInsert($query, $conn);
oci_commit($conn);
oci_close($conn);
header("location: ../pages/user-dashboard.php");
?>