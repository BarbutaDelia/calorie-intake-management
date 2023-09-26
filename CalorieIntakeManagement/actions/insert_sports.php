<?php
session_start();
require_once "../config.php";
require_once "../functions.php";

$sports_err = $duration_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //validare nume
    if (empty(trim($_POST["sports_id"]))) {
        $sports_err = "Selectati ingredientul.";
    }
    //validare prenume
    if (empty(trim($_POST["duration"]))) {
        $duration_err = "Introduceti cantitatea.";
    }

    if(!$duration_err && !$sports_err){
        $session_id = $_SESSION["id"];
        $sports_id = $_POST['sports_id'];
        $duration = $_POST['duration'];
        $query = "INSERT INTO a_s(accounts_id, sports_id, duration) 
	values ('$session_id','$sports_id','$duration')";
        $result = runQueryInsert($query, $conn);
        oci_commit($conn);
        oci_close($conn);
    }
}
header("location: ../pages/user-dashboard.php");
?>
